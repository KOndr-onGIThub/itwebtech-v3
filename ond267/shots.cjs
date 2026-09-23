// OND-267 — před/po na sestaveném bundlu, 1440 i 390 px.
//
// BEFORE = živá produkce (stav před tímhle balíkem, vlna 1 už na ní je).
// AFTER  = lokální instance s produkčními daty, na kterých proběhly migrace
//          z tohohle balíku, a s lokálně sestaveným CSS.
//
// Screenshot se bere přes `page.screenshot({clip})`, ne `el.screenshot()` —
// ten na tomhle webu zamrzá na „waiting for element to be stable".
const { chromium } = require('playwright');
const fs = require('fs');
const path = require('path');

const MODE = process.env.MODE || 'after';            // before | after
const BASE = MODE === 'before'
  ? 'https://itwebtech.ondrejkriska.cz'
  : (process.env.BASE || 'http://127.0.0.1:8199');
const OUTDIR = process.env.OUTDIR || '.screenshots';

const SHOTS = [
  { page: '/en',            sel: '#section-testimonials', name: 'en-recenze' },
  { page: '/de',            sel: '#section-testimonials', name: 'de-recenze' },
  { page: '/de',            sel: '#wie-ich-arbeite',      name: 'de-beratung' },
  { page: '/de/preisliste', sel: '.pricing-addons, .pricing-guarantees, main', name: 'de-cenik' },
  { page: '/de/kontakt',    sel: '.contact-info',         name: 'de-kontakt-udaje' },
  { page: '/de/datenschutz', sel: 'main',                 name: 'de-zasady' },
  { page: '/en/projects/pitarena', sel: '.portfolio-detail-meta', name: 'en-projekt-meta' },
  { page: '/de/projekte/hcms',     sel: '.portfolio-detail-meta', name: 'de-projekt-meta' },
  { page: '/projekty/excel-tools', sel: '.portfolio-detail-meta', name: 'cs-excel-meta' },
  { page: '/projekty/yolk',        sel: '.portfolio-detail-body, main', name: 'cs-yolk-texty' },
  { page: '/projekty/cyklocentrum', sel: '.portfolio-detail-body, main', name: 'cs-cyklo-uvozovky' },
  { page: '/en/blog/how-much-does-a-website-cost', sel: 'main', name: 'en-clanek-cena' },
];

const VPS = [{ n: 'd', w: 1440, h: 900 }, { n: 'm', w: 390, h: 844 }];

(async () => {
  fs.mkdirSync(OUTDIR, { recursive: true });
  const browser = await chromium.launch({ args: ['--no-sandbox', '--force-prefers-reduced-motion'] });

  for (const vp of VPS) {
    for (const s of SHOTS) {
      const ctx = await browser.newContext({
        viewport: { width: vp.w, height: vp.h },
        locale: 'cs-CZ', reducedMotion: 'reduce',
      });
      const page = await ctx.newPage();
      try {
        await page.goto(BASE + s.page, { waitUntil: 'domcontentloaded', timeout: 45000 });
        await page.waitForTimeout(1200);
        // dojeď reveal animace — jinak je sekce v čase 0 průhledná
        await page.evaluate(() => {
          document.querySelectorAll('[x-intersect],[data-reveal],[data-reveal-group],.reveal,.pd-reveal')
            .forEach((e) => { e.classList.add('is-visible', 'revealed', 'in-view'); e.style.opacity = '1'; e.style.transform = 'none'; });
          document.querySelectorAll('.cookie-bar, [data-cookie-bar], .cookiebar').forEach((e) => e.remove());
        });
        await page.waitForTimeout(500);

        const el = await page.$(s.sel);
        if (!el) { console.error(`  ✗ nenalezeno ${s.sel} na ${s.page} @${vp.n}`); await ctx.close(); continue; }
        await el.scrollIntoViewIfNeeded({ timeout: 4000 }).catch(() => {});
        await page.waitForTimeout(400);
        const box = await el.boundingBox();
        if (!box) { console.error(`  ✗ bez boxu ${s.name} @${vp.n}`); await ctx.close(); continue; }

        const file = path.join(OUTDIR, `ond267-${MODE}-${s.name}-${vp.n}.png`);
        await page.screenshot({
          path: file, animations: 'disabled', timeout: 20000,
          clip: {
            x: Math.max(0, box.x), y: Math.max(0, box.y),
            width: Math.min(box.width, vp.w), height: Math.min(box.height, 2400),
          },
        });
        console.error('  ✓ ' + file);
      } catch (e) {
        console.error(`  ✗ ${s.name} @${vp.n}: ${e.message.split('\n')[0]}`);
      }
      await ctx.close();
    }
  }
  await browser.close();
})();
