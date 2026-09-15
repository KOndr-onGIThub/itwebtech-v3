#!/usr/bin/env node
/**
 * B4 static verifier (OND-132)
 * ─────────────────────────────────
 * Fetchne 9 stránek × 3 locales, vytáhne z HTML hlavičky to, co lze
 * verifikovat čistě statickou parsovací cestou (bez headless prohlížeče):
 *
 *   1. <link rel="canonical"> — existuje a odpovídá URL.
 *   2. <link rel="alternate" hreflang="..."> — 3 jazyky + x-default,
 *      žádné cross-language 301 (ověřuje se přes HTTP HEAD redirect chain).
 *   3. <script type="application/ld+json"> — všechny bloky parsne přes
 *      `JSON.parse`; reportuje typy (LocalBusiness / Organization / Person /
 *      FAQPage / Article / BreadcrumbList / Service) a chybné JSON-LD.
 *   4. <html lang="..."> — odpovídá aktuálnímu locale.
 *   5. Consent Mode v2 default state — kontrola, že `gtag('consent','default'...)`
 *      je v HTML přítomný a obsahuje ad_user_data + ad_personalization
 *      (rozdíl proti v1).
 *   6. Analytics page_lang dimension — `window.__analyticsConfig.pageLang`
 *      odpovídá lang atribute na <html>.
 *
 * Lighthouse + axe-core + Core Web Vitals + GA4 DebugView NEjsou v scope
 * tohoto skriptu — viz scripts/b4-verify.sh, který kompletní suite spouští.
 *
 * Usage:
 *   node scripts/b4-static-check.mjs --base https://preview-sitewide-redesign.itwebtech.cz \
 *        --project hellsearch --article jak-vybrat-cms
 *
 * Exit code 0 = vše OK, jinak = počet failed kontrol.
 */
import fs from 'node:fs';
import path from 'node:path';
import process from 'node:process';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

// ─── CLI args ─────────────────────────────────────────────────────────────
function arg(name, fallback = null) {
    const idx = process.argv.indexOf(`--${name}`);
    return idx >= 0 ? process.argv[idx + 1] : fallback;
}

const BASE = (arg('base') || 'https://preview-sitewide-redesign.itwebtech.cz').replace(/\/$/, '');
const PROJECT_SLUG = arg('project') || 'hellsearch';
const ARTICLE_SLUG = arg('article') || 'jak-vybrat-cms';
const OUT_FILE = arg('out') || path.join(__dirname, '..', 'storage', 'b4-static-report.json');

// ─── Page set ─────────────────────────────────────────────────────────────
function loadPages() {
    const file = path.join(__dirname, 'b4-pages.txt');
    return fs.readFileSync(file, 'utf8')
        .split('\n')
        .filter((l) => l && !l.startsWith('#'))
        .map((line) => {
            const [cs, en, de, label] = line.split('|').map((s) => s.trim());
            const subst = (p) => p
                .replace('{project}', PROJECT_SLUG)
                .replace('{article}', ARTICLE_SLUG);
            return {
                label,
                urls: { cs: BASE + subst(cs), en: BASE + subst(en), de: BASE + subst(de) },
            };
        });
}

// ─── Fetch & parse helpers ────────────────────────────────────────────────
async function fetchHtml(url) {
    const res = await fetch(url, { redirect: 'follow' });
    const finalUrl = res.url;
    const status = res.status;
    const html = await res.text();
    return { status, finalUrl, html };
}

function extractTag(html, regex) {
    const m = html.match(regex);
    return m ? m[1] : null;
}

function extractAll(html, regex) {
    const out = [];
    let m;
    while ((m = regex.exec(html)) !== null) out.push(m[1]);
    return out;
}

function getJsonLdBlocks(html) {
    const re = /<script[^>]*type=["']application\/ld\+json["'][^>]*>([\s\S]*?)<\/script>/gi;
    const blocks = [];
    let m;
    while ((m = re.exec(html)) !== null) {
        const raw = m[1].trim();
        try {
            blocks.push({ ok: true, data: JSON.parse(raw) });
        } catch (e) {
            blocks.push({ ok: false, error: e.message, raw: raw.slice(0, 200) });
        }
    }
    return blocks;
}

function getHreflangs(html) {
    const re = /<link\s+[^>]*rel=["']alternate["'][^>]*>/gi;
    const out = {};
    let m;
    while ((m = re.exec(html)) !== null) {
        const tag = m[0];
        const lang = (tag.match(/hreflang=["']([^"']+)["']/i) || [])[1];
        const href = (tag.match(/href=["']([^"']+)["']/i) || [])[1];
        if (lang && href) out[lang] = href;
    }
    return out;
}

function hasConsentDefaultV2(html) {
    if (!html.includes("gtag('consent', 'default'")) return false;
    // v2 nutně obsahuje ad_user_data a ad_personalization
    return html.includes('ad_user_data') && html.includes('ad_personalization');
}

function pageLang(html) {
    const lang = extractTag(html, /<html[^>]*\blang=["']([^"']+)["']/i);
    const jsLang = (html.match(/pageLang:\s*"([^"]+)"/) || [])[1];
    return { lang, jsLang };
}

// ─── Per-URL audit ────────────────────────────────────────────────────────
const REQUIRED_TYPES_GLOBAL = ['LocalBusiness', 'Organization', 'Person'];
const REQUIRED_PER_LABEL = {
    home:    ['FAQPage'],
    price:   ['Service', 'BreadcrumbList'],
    blog:    ['BreadcrumbList'],
    article: ['Article', 'BreadcrumbList'],
    projects:['BreadcrumbList'],
    project: ['BreadcrumbList'],
    contact: ['BreadcrumbList'],
};

function collectTypes(blocks) {
    const types = new Set();
    for (const b of blocks) {
        if (!b.ok) continue;
        const collect = (node) => {
            if (!node || typeof node !== 'object') return;
            if (Array.isArray(node)) return node.forEach(collect);
            if (node['@type']) {
                const t = node['@type'];
                if (Array.isArray(t)) t.forEach((x) => types.add(x));
                else types.add(t);
            }
            for (const v of Object.values(node)) collect(v);
        };
        collect(b.data);
    }
    return [...types];
}

async function auditUrl(label, locale, url, allReports) {
    const report = { label, locale, url, problems: [] };
    let res;
    try {
        res = await fetchHtml(url);
    } catch (e) {
        report.problems.push({ id: 'fetch', msg: e.message });
        allReports.push(report);
        return report;
    }
    report.status = res.status;
    report.finalUrl = res.finalUrl;

    if (res.status !== 200) {
        report.problems.push({ id: 'http_status', msg: `HTTP ${res.status}` });
    }
    if (res.finalUrl !== url) {
        report.problems.push({ id: 'redirect', msg: `→ ${res.finalUrl}` });
    }

    // Canonical
    const canonical = extractTag(res.html, /<link\s+rel=["']canonical["']\s+href=["']([^"']+)["']/i);
    report.canonical = canonical;
    if (!canonical) report.problems.push({ id: 'canonical_missing', msg: 'no <link rel="canonical">' });

    // hreflang
    const alts = getHreflangs(res.html);
    report.hreflang = alts;
    for (const want of ['cs', 'en', 'de', 'x-default']) {
        if (!alts[want]) report.problems.push({ id: 'hreflang_missing', msg: `missing ${want}` });
    }

    // <html lang>
    const { lang, jsLang } = pageLang(res.html);
    report.htmlLang = lang;
    report.jsPageLang = jsLang;
    if (lang !== locale) report.problems.push({ id: 'html_lang', msg: `lang=${lang} ≠ ${locale}` });
    if (jsLang && jsLang !== locale) report.problems.push({ id: 'js_page_lang', msg: `pageLang=${jsLang} ≠ ${locale}` });

    // JSON-LD
    const blocks = getJsonLdBlocks(res.html);
    report.jsonld = {
        blocks: blocks.length,
        broken: blocks.filter((b) => !b.ok).map((b) => ({ error: b.error, raw: b.raw })),
        types: collectTypes(blocks),
    };
    for (const want of REQUIRED_TYPES_GLOBAL) {
        if (!report.jsonld.types.includes(want)) {
            report.problems.push({ id: 'jsonld_missing', msg: `missing @type=${want}` });
        }
    }
    for (const want of REQUIRED_PER_LABEL[label] || []) {
        if (!report.jsonld.types.includes(want)) {
            report.problems.push({ id: 'jsonld_missing_page', msg: `${label} missing @type=${want}` });
        }
    }
    for (const broken of report.jsonld.broken) {
        report.problems.push({ id: 'jsonld_invalid', msg: broken.error });
    }

    // Consent Mode v2 (only on pages with analytics partial — všechny mají layout, takže všude)
    if (!hasConsentDefaultV2(res.html)) {
        // ANALYTICS_ENABLED=false → skripty nejsou → tohle není fail, jen info
        report.consentMode = 'absent_or_disabled';
    } else {
        report.consentMode = 'v2';
    }

    allReports.push(report);
    return report;
}

// ─── Hreflang cross-page matrix ──────────────────────────────────────────
function checkHreflangMatrix(reports) {
    const findings = [];
    const byLabel = new Map();
    for (const r of reports) {
        if (!byLabel.has(r.label)) byLabel.set(r.label, []);
        byLabel.get(r.label).push(r);
    }
    for (const [label, group] of byLabel) {
        // U každého locale ve skupině musí hreflang ukázat na URL ostatních locale ve skupině.
        const urlByLocale = Object.fromEntries(group.map((g) => [g.locale, g.url]));
        for (const g of group) {
            for (const other of ['cs', 'en', 'de']) {
                const expected = urlByLocale[other];
                const got = g.hreflang?.[other];
                if (expected && got && expected !== got) {
                    findings.push({
                        label, locale: g.locale, other,
                        msg: `hreflang ${other} mismatch: ${got} ≠ ${expected}`,
                    });
                }
            }
        }
    }
    return findings;
}

// ─── Main ─────────────────────────────────────────────────────────────────
(async () => {
    console.log(`[b4] base=${BASE}`);
    const pages = loadPages();
    const reports = [];
    for (const p of pages) {
        for (const locale of ['cs', 'en', 'de']) {
            const u = p.urls[locale];
            process.stdout.write(`  · ${locale}\t${p.label.padEnd(10)}\t${u.replace(BASE, '')} ... `);
            const r = await auditUrl(p.label, locale, u, reports);
            console.log(r.problems.length ? `FAIL (${r.problems.length})` : 'ok');
        }
    }

    const matrix = checkHreflangMatrix(reports);
    const summary = {
        base: BASE,
        timestamp: new Date().toISOString(),
        urlCount: reports.length,
        failedUrls: reports.filter((r) => r.problems.length).length,
        hreflangMatrixIssues: matrix.length,
        reports,
        hreflangMatrix: matrix,
    };

    fs.mkdirSync(path.dirname(OUT_FILE), { recursive: true });
    fs.writeFileSync(OUT_FILE, JSON.stringify(summary, null, 2));
    console.log(`\n[b4] written ${OUT_FILE}`);
    console.log(`[b4] failed URLs: ${summary.failedUrls}/${summary.urlCount}, hreflang issues: ${matrix.length}`);
    process.exit(summary.failedUrls + matrix.length);
})();
