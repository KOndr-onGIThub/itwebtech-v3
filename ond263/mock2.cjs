// ond263/mock2.cjs — článek na Motorkáři.cz (čerstvý živý screenshot) + náhledovka HCMS
const { chromium } = require('/home/paperclip/node_modules/playwright-core');
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');
const path = require('path');

const b64 = (b) => 'data:image/png;base64,' + b.toString('base64');

async function shot(page, html, out, w, h) {
  await page.setViewportSize({ width: w, height: h });
  await page.setContent(html, { waitUntil: 'load' });
  await page.waitForTimeout(250);
  fs.mkdirSync(path.dirname(out), { recursive: true });
  await page.screenshot({ path: out });
  console.log('  ->', out, w + 'x' + h, (fs.statSync(out).size / 1024 | 0) + 'KB');
}

(async () => {
  const b = await chromium.launch({ executablePath: process.env.PW_CHROME });
  const page = await (await b.newContext({ deviceScaleFactor: 1 })).newPage();
  const T = 'ond263/work/mock';

  // — 1) hero: okno prohlížeče s živým článkem (zdroj DPR2 → ořez pod reklamní pruh)
  const deskBuf = await sharp('ond263/work/motorkari-desktop.png')
    .extract({ left: 400, top: 150, width: 2080, height: 1200 }).png().toBuffer();

  const CSS = `*{margin:0;padding:0;box-sizing:border-box}
    body{background:#F4F4F2;font-family:system-ui,sans-serif}
    .stage{width:100vw;height:100vh;display:flex;align-items:center;justify-content:center}
    .win{width:1240px;border-radius:14px;overflow:hidden;background:#fff;
         box-shadow:0 28px 60px rgba(0,0,0,.16),0 2px 8px rgba(0,0,0,.10)}
    .bar{height:40px;background:#ECECEA;display:flex;align-items:center;gap:8px;padding:0 14px;
         border-bottom:1px solid #DDDDDA}
    .dot{width:11px;height:11px;border-radius:50%;background:#CFCFCC}
    .url{margin-left:14px;height:22px;flex:1;max-width:420px;border-radius:11px;background:#fff;
         border:1px solid #DDDDDA;display:flex;align-items:center;padding:0 12px;
         font-size:11px;color:#77776F;letter-spacing:.2px}
    .view{height:620px;overflow:hidden}
    .view img{width:100%;display:block}
    /* telefon */
    .phone{width:320px;height:660px;border-radius:40px;background:#15171B;padding:10px;position:relative;
           box-shadow:0 24px 48px rgba(0,0,0,.18),0 2px 6px rgba(0,0,0,.10)}
    .phone::after{content:'';position:absolute;top:18px;left:50%;transform:translateX(-50%);
           width:78px;height:6px;border-radius:3px;background:#2A2D33}
    .screen{width:300px;height:640px;border-radius:31px;overflow:hidden;background:#fff}
    .screen img{width:100%;display:block}`;

  console.log('clanek-motorkari-cz hero');
  await shot(page, `<!doctype html><meta charset="utf-8"><style>${CSS}</style>
    <div class="stage"><div class="win">
      <div class="bar"><i class="dot"></i><i class="dot"></i><i class="dot"></i>
        <div class="url">motorkari.cz/motosport/motocross/…/ycf-cup-motokrosovych-pitbiku…</div></div>
      <div class="view"><img src="${b64(deskBuf)}"></div>
    </div></div>`, `${T}/clanek-hero.png`, 1500, 750);

  // — 2) galerie 1: rovný telefon s mobilní verzí článku
  const mobBuf = await sharp('ond263/work/motorkari-mobile.png')
    .extract({ left: 0, top: 60, width: 780, height: 1600 }).png().toBuffer();
  console.log('clanek-motorkari-cz gallery-1');
  await shot(page, `<!doctype html><meta charset="utf-8"><style>${CSS}</style>
    <div class="stage"><div class="phone"><div class="screen"><img src="${b64(mobBuf)}"></div></div></div>`,
    `${T}/clanek-gallery1.png`, 1500, 750);

  await b.close();

  // — 3) HCMS náhledovka do karty (16:10, cover) —————————————————————————
  console.log('hcms thumbnail');
  fs.mkdirSync('ond263/out/projects/hcms', { recursive: true });
  await sharp('ond263/src/projects/hcms/hero-1.jpg')          // = hcms_preview
    .resize(1600, 1000, { fit: 'cover', position: 'centre' })
    .jpeg({ quality: 90, chromaSubsampling: '4:4:4' })
    .toFile('ond263/out/projects/hcms/thumbnail-1.jpg');
  console.log('  -> ond263/out/projects/hcms/thumbnail-1.jpg 1600x1000');
})();
