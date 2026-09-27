#!/usr/bin/env node
/**
 * Orphan (sirotek) detektor pro copy změny — OND-381
 * ──────────────────────────────────────────────────
 * Proč existuje: OND-371 proměřila perex nad kontaktním formulářem na 1440
 * a 390 px. Na obou to sedělo. Na 900 a 540 px — tedy MEZI nimi — dělal ten
 * samý text sirotka o šířce 11–20 % sloupce, což musela dohánět OND-377.
 * Zlom řádku se nemění spojitě, mění se skokem, a ten skok umí padnout
 * přesně do mezery mezi dvěma měřenými body. Dvoubodová sada tedy o tom,
 * co je mezi krajními šířkami, neřekne nic.
 *
 * Co skript dělá: načte nasazenou stránku (žádná DOM injekce vlastního
 * markupu, žádné vlastní CSS — měří se skutečný render nasazeného buildu),
 * počká na `document.fonts.ready`, obalí každé slovo elementu spanem a
 * z jejich `getBoundingClientRect()` přečte řádkové boxy, které prohlížeč
 * doopravdy vysázel. Vypíše počet řádků, jejich šířky a šířku posledního
 * řádku jako procento šířky sloupce. Nejhorší hodnota napříč celým průchodem
 * se porovná s prahem `--min`; pod prahem skript skončí nenulovým exit kódem.
 *
 * Spouští se RUČNĚ, když se mění text. Není to CI gate — viz
 * POLICY-shared-host-builds.md, stroj hostí i produkci. Průchod je sekvenční
 * (jedna stránka v jednom okamžiku), 21 page loadů ≈ 1 minuta.
 *
 * Usage:
 *   node scripts/typo-orphans.mjs
 *   node scripts/typo-orphans.mjs --sel '.hero__lead' --path '/,/en/,/de/' --min 25
 *   node scripts/typo-orphans.mjs --base http://127.0.0.1:8000 --path /kontakt
 *
 * Args:
 *   --base   origin nasazeného buildu (default staging)
 *   --path   jedna cesta, nebo několik oddělených čárkou (default CS/EN/DE /kontakt)
 *   --sel    CSS selektor měřeného elementu (default perex kontaktního formuláře)
 *   --min    minimální akceptovatelná šířka posledního řádku v % sloupce (default 20)
 *
 * Exit code 0 = všechny šířky nad prahem. 1 = něco práh podlezlo, nebo se
 * element na některé šířce nenašel (aby překlep v selektoru nehlásil zeleno).
 */
import process from 'node:process';
import { chromium } from 'playwright';

// ─── CLI args ─────────────────────────────────────────────────────────────
function arg(name, fallback = null) {
    const idx = process.argv.indexOf(`--${name}`);
    return idx >= 0 ? process.argv[idx + 1] : fallback;
}

const BASE = (arg('base') || 'https://itwebtech.ondrejkriska.cz').replace(/\/$/, '');
const PATHS = (arg('path') || '/kontakt,/en/contact,/de/kontakt')
    .split(',')
    .map((p) => p.trim())
    .filter(Boolean);
const SEL = arg('sel') || '.contact-form__subtitle[x-show="!submitted"]';
const MIN = Number(arg('min') || 20);

if (!Number.isFinite(MIN) || MIN < 0 || MIN > 100) {
    console.error(`--min musí být číslo 0–100, dostal jsem "${arg('min')}"`);
    process.exit(1);
}

/* Sedm šířek, ne dvě. 1024 / 900 / 640 / 540 jsou ty mezilehlé body, na
   kterých OND-371 sirotka neviděla. */
const WIDTHS = [1440, 1024, 900, 640, 540, 390, 320];

// ─── Probe (běží v kontextu stránky) ──────────────────────────────────────
const probe = (sel) => {
    const el = document.querySelector(sel);
    if (!el) return { err: 'no element' };
    const cs = getComputedStyle(el);
    const text = el.textContent.trim();
    const prev = el.innerHTML;
    el.innerHTML = text.split(' ').map((w) => `<span data-w>${w}</span>`).join(' ');
    const rows = new Map();
    for (const s of el.querySelectorAll('[data-w]')) {
        const top = Math.round(s.getBoundingClientRect().top);
        if (!rows.has(top)) rows.set(top, []);
        rows.get(top).push(s);
    }
    const lines = [...rows.values()].map((ws) => ({
        text: ws.map((s) => s.textContent).join(' '),
        w: Math.round(
            ws[ws.length - 1].getBoundingClientRect().right - ws[0].getBoundingClientRect().left,
        ),
    }));
    el.innerHTML = prev;
    return {
        col: Math.round(el.getBoundingClientRect().width),
        wrap: `${cs.textWrap || ''}|${cs.textWrapStyle || ''}`,
        text,
        lines,
    };
};

// ─── Sweep ────────────────────────────────────────────────────────────────
console.log(`sel=${SEL}\nbase=${BASE}\nmin=${MIN}%  widths=${WIDTHS.join('/')}`);

const browser = await chromium.launch();
let worst = { ratio: 1 };
const errors = [];

for (const path of PATHS) {
    console.log(`\n=== ${path} ===`);
    for (const width of WIDTHS) {
        const page = await browser.newPage({ viewport: { width, height: 900 } });
        let r;
        try {
            await page.goto(BASE + path, { waitUntil: 'networkidle', timeout: 60000 });
            await page.evaluate(() => document.fonts.ready);
            r = await page.evaluate(
                ([sel, src]) => new Function('sel', `return (${src})(sel)`)(sel),
                [SEL, probe.toString()],
            );
        } catch (e) {
            r = { err: e.message.split('\n')[0] };
        } finally {
            await page.close();
        }
        if (r.err) {
            console.log(`vw${String(width).padStart(4)} -> ${r.err}`);
            errors.push(`${path} vw${width}: ${r.err}`);
            continue;
        }
        const last = r.lines[r.lines.length - 1];
        const ratio = r.lines.length > 1 ? last.w / r.col : 1;
        if (ratio < worst.ratio) {
            worst = { ratio, path, width, last: last.text, w: last.w, col: r.col };
        }
        console.log(
            `vw${String(width).padStart(4)} col=${r.col} wrap=${r.wrap} lines=${r.lines.length} ` +
            `[${r.lines.map((l) => l.w).join('/')}]px last=${Math.round(ratio * 100)}% "${last.text}"`,
        );
    }
}
await browser.close();

// ─── Verdikt ──────────────────────────────────────────────────────────────
const pct = Math.round(worst.ratio * 100);
console.log(
    `\nWORST last line: ${worst.path || '(n/a)'} vw${worst.width || '-'} — ${worst.w || 0}px ` +
    `of ${worst.col || 0}px = ${pct}%  "${worst.last || '(single line)'}"`,
);

if (errors.length) {
    console.log(`\nFAIL — ${errors.length} měření neproběhlo:\n  ${errors.join('\n  ')}`);
    process.exit(1);
}
if (pct < MIN) {
    console.log(`\nFAIL — poslední řádek ${pct}% < práh ${MIN}% = sirotek. Přeformuluj copy.`);
    process.exit(1);
}
console.log(`\nPASS — nejhorší poslední řádek ${pct}% ≥ práh ${MIN}%.`);
