/**
 * OND-300 — měření chování <video class="video-intro"> ve fullscreenu.
 *
 * Postaví minimální stránku se stejným markupem jako slot na /o-mne,
 * načte do ní vybuildovaný app.css, přepne video do fullscreenu
 * (přes skutečný klik = user gesture) a změří, kam se element vejde.
 *
 * Běh:  node scripts/ond300-fullscreen-probe.cjs <base-url> [--no-fix]
 *       --no-fix = za běhu smaže pravidlo `.video-intro:fullscreen`
 *                  ze stylesheetu → stav PŘED opravou.
 */
const { chromium } = require('playwright');

const BASE = process.argv[2] || 'http://127.0.0.1:8899';
const DROP_FIX = process.argv.includes('--no-fix');

/* Oba sloty, ve kterých video na webu žije. Wrappery i class na obalu
   drží přesně to, co je v home.blade.php / about.blade.php — kvůli
   nevrstveným pravidlům z podpis.css a hloubka.css. */
const SLOTS = {
    home: '<div class="pd--depth"><div class="pd-why"><div class="pd-why__media"><div class="pd-why__video">@V@</div></div></div></div>',
    about: '<div class="pd--depth pd--depth-sub"><div class="about-intro"><div class="about-intro__photo about-intro__video">@V@</div></div></div>',
};

const page_html = (cssHref, videoSrc) => {
    const v = (id) => `<video id="${id}" class="video-intro" controls preload="metadata" playsinline width="1080" height="1920"><source src="${videoSrc}" type="video/mp4"></video>`;
    const slots = Object.entries(SLOTS)
        .map(([name, tpl]) => tpl.replace('@V@', v(`v-${name}`)))
        .join('\n');
    return `<!doctype html>
<html lang="cs"><head><meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="${cssHref}">
<style>body{margin:0;background:#111}.wrap{padding:40px;display:flex;gap:40px}</style>
</head><body>
<div class="wrap">${slots}</div>
<button id="go" style="position:fixed;left:0;top:0;width:60px;height:24px">fs</button>
</body></html>`;
};

(async () => {
    const browser = await chromium.launch({ args: ['--autoplay-policy=no-user-gesture-required'] });
    const ctx = await browser.newContext({ viewport: { width: 1920, height: 1080 } });
    const page = await ctx.newPage();

    const manifest = await (await fetch(`${BASE}/build/manifest.json`)).json();
    const cssFile = manifest['resources/css/app.css'].file;

    await page.route('**/ond300-probe.html', route => route.fulfill({
        contentType: 'text/html; charset=utf-8',
        body: page_html(`${BASE}/build/${cssFile}`, `${BASE}/video/ondra-uvod.mp4`),
    }));
    await page.goto(`${BASE}/ond300-probe.html`);

    const dropped = await page.evaluate((drop) => {
        if (!drop) return 0;
        let n = 0;
        for (const sheet of document.styleSheets) {
            let rules;
            try { rules = sheet.cssRules; } catch { continue; }
            for (let i = rules.length - 1; i >= 0; i--) {
                const t = rules[i].cssText || '';
                if (/:fullscreen/.test(t) && /video-intro/.test(t)) { sheet.deleteRule(i); n++; }
            }
        }
        return n;
    }, DROP_FIX);

    await page.evaluate(() => {
        window.__fs = (id) => document.getElementById(id).requestFullscreen();
        document.getElementById('go').addEventListener('click', () => window.__fs(window.__target));
    });

    const suffix = DROP_FIX ? 'before' : 'after';
    const out = { mode: DROP_FIX ? 'PŘED opravou' : 'PO opravě', droppedRules: dropped, sloty: {} };

    for (const name of Object.keys(SLOTS)) {
        await page.evaluate((n) => { window.__target = `v-${n}`; }, name);
        await page.click('#go');
        await page.waitForFunction(() => !!document.fullscreenElement, null, { timeout: 5000 });
        await page.waitForTimeout(400);

        out.sloty[name] = await page.evaluate((n) => {
            const v = document.getElementById(`v-${n}`);
            const r = v.getBoundingClientRect();
            const cs = getComputedStyle(v);
            // Jak velký je SKUTEČNĚ vykreslený obraz uvnitř elementu.
            const iw = v.videoWidth || 1080, ih = v.videoHeight || 1920;
            const s = cs.objectFit === 'contain'
                ? Math.min(r.width / iw, r.height / ih)
                : Math.max(r.width / iw, r.height / ih);
            const cw = iw * s, ch = ih * s;
            return {
                viewport: { w: window.innerWidth, h: window.innerHeight },
                boxRect: { top: Math.round(r.top), w: Math.round(r.width), h: Math.round(r.height) },
                objectFit: cs.objectFit, aspectRatio: cs.aspectRatio, maxWidth: cs.maxWidth,
                obraz: { w: Math.round(cw), h: Math.round(ch) },
                orezanoNahore_px: Math.round(Math.max(0, (ch - r.height) / 2)),
                orezanoPoStranach_px: Math.round(Math.max(0, (cw - r.width) / 2)),
            };
        }, name);

        await page.screenshot({ path: `storage/app/ond300-fs-${name}-${suffix}.png` });
        await page.evaluate(() => document.exitFullscreen());
        await page.waitForFunction(() => !document.fullscreenElement, null, { timeout: 5000 });
    }

    console.log(JSON.stringify(out, null, 2));
    await browser.close();
})();
