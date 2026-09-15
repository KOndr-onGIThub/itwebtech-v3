#!/usr/bin/env node
/**
 * B4 summary aggregator (OND-132)
 * ────────────────────────────────────
 * Po `b4-verify.sh` přečte:
 *   storage/b4-static-report.json
 *   storage/b4/lighthouse/*.json
 *   storage/b4/axe/*.json
 * a sestaví Markdown report do storage/b4/summary.md, který odpovídá
 * Definition of done v OND-132:
 *   - Lighthouse ≥95 mobile na všech 4 osách
 *   - LCP ≤2.5s, INP ≤200ms, CLS ≤0.05
 *   - axe-core 0 violations
 *   - JSON-LD + hreflang zelené
 */
import fs from 'node:fs';
import path from 'node:path';
import process from 'node:process';

function arg(name, fallback = null) {
    const idx = process.argv.indexOf(`--${name}`);
    return idx >= 0 ? process.argv[idx + 1] : fallback;
}

const OUT_DIR = arg('out-dir') || path.resolve('storage/b4');
const STATIC_REPORT = path.resolve(OUT_DIR, '..', 'b4-static-report.json');

function safeRead(file) {
    try { return JSON.parse(fs.readFileSync(file, 'utf8')); } catch { return null; }
}

const THRESHOLDS = {
    performance: 0.95,
    accessibility: 0.95,
    'best-practices': 0.95,
    seo: 0.95,
    lcp: 2500,    // ms
    inp: 200,     // ms (Lighthouse 12+ approximates INP via responsiveness audit)
    cls: 0.05,
};

function lhMetric(audit) {
    if (!audit) return null;
    return audit.numericValue ?? null;
}

function summarizeLighthouse(file) {
    const lh = safeRead(file);
    if (!lh) return null;
    const cats = lh.categories || {};
    const audits = lh.audits || {};
    return {
        scores: {
            performance:      cats.performance?.score ?? null,
            accessibility:    cats.accessibility?.score ?? null,
            'best-practices': cats['best-practices']?.score ?? null,
            seo:              cats.seo?.score ?? null,
        },
        cwv: {
            lcp: lhMetric(audits['largest-contentful-paint']),
            cls: audits['cumulative-layout-shift']?.numericValue ?? null,
            inp: lhMetric(audits['interaction-to-next-paint']) ?? lhMetric(audits['interactive']) ?? null,
        },
    };
}

function summarizeAxe(file) {
    const data = safeRead(file);
    if (!data) return null;
    // @axe-core/cli --save → array of results per URL
    const arr = Array.isArray(data) ? data : [data];
    const violations = arr.flatMap((d) => d.violations ?? []);
    return { violations: violations.length, ids: violations.map((v) => v.id) };
}

const stat = safeRead(STATIC_REPORT);
const lhDir = path.join(OUT_DIR, 'lighthouse');
const axDir = path.join(OUT_DIR, 'axe');
const lhFiles = fs.existsSync(lhDir) ? fs.readdirSync(lhDir).filter((f) => f.endsWith('.json')) : [];
const axFiles = fs.existsSync(axDir) ? fs.readdirSync(axDir).filter((f) => f.endsWith('.json')) : [];

const rows = [];
const labels = new Set();
for (const f of lhFiles) labels.add(f.replace(/\.json$/, ''));
for (const f of axFiles) labels.add(f.replace(/\.json$/, ''));

for (const key of [...labels].sort()) {
    const lh = summarizeLighthouse(path.join(lhDir, key + '.json'));
    const ax = summarizeAxe(path.join(axDir, key + '.json'));
    rows.push({ key, lh, ax });
}

function fmtScore(s) {
    if (s === null || s === undefined) return '—';
    const pct = Math.round(s * 100);
    return pct >= 95 ? `✅ ${pct}` : `❌ ${pct}`;
}

function fmtMs(v, max) {
    if (v === null || v === undefined) return '—';
    return v <= max ? `✅ ${Math.round(v)}` : `❌ ${Math.round(v)}`;
}

function fmtCls(v) {
    if (v === null || v === undefined) return '—';
    return v <= THRESHOLDS.cls ? `✅ ${v.toFixed(3)}` : `❌ ${v.toFixed(3)}`;
}

let md = `# B4 Verification Summary (OND-132)\n\n`;
md += `Base URL: \`${stat?.base ?? 'n/a'}\`\n`;
md += `Run at: \`${stat?.timestamp ?? 'n/a'}\`\n\n`;

md += `## Static check\n`;
if (stat) {
    md += `- URL count: ${stat.urlCount}\n`;
    md += `- Failed URLs: ${stat.failedUrls}\n`;
    md += `- Hreflang matrix issues: ${stat.hreflangMatrixIssues}\n\n`;
    if (stat.failedUrls || stat.hreflangMatrixIssues) {
        md += `### Findings\n\n`;
        for (const r of stat.reports) {
            if (!r.problems.length) continue;
            md += `- **${r.locale} / ${r.label}** \`${r.url}\`\n`;
            for (const p of r.problems) md += `    - ${p.id}: ${p.msg}\n`;
        }
        md += '\n';
    }
} else {
    md += `_static report not found_\n\n`;
}

md += `## Lighthouse + axe per URL\n\n`;
md += `| URL key | Perf | A11y | BP | SEO | LCP (ms ≤2500) | CLS (≤0.05) | INP/INT (ms ≤200) | axe violations |\n`;
md += `|---|---|---|---|---|---|---|---|---|\n`;
for (const r of rows) {
    const lh = r.lh?.scores ?? {};
    const cwv = r.lh?.cwv ?? {};
    const ax = r.ax?.violations ?? '—';
    md += `| \`${r.key}\` | ${fmtScore(lh.performance)} | ${fmtScore(lh.accessibility)} | ${fmtScore(lh['best-practices'])} | ${fmtScore(lh.seo)} | ${fmtMs(cwv.lcp, THRESHOLDS.lcp)} | ${fmtCls(cwv.cls)} | ${fmtMs(cwv.inp, THRESHOLDS.inp)} | ${ax === 0 ? '✅ 0' : (ax === '—' ? '—' : `❌ ${ax}`)} |\n`;
}

const outFile = path.join(OUT_DIR, 'summary.md');
fs.mkdirSync(OUT_DIR, { recursive: true });
fs.writeFileSync(outFile, md);
console.log(`[b4-summary] written ${outFile}`);
