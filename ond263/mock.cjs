// ond263/mock.cjs — mockupy překreslené v DOM (Playwright), bez zapečených play tlačítek
const { chromium } = require('/home/paperclip/node_modules/playwright-core');
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');
const path = require('path');

const b64 = (f) => 'data:image/png;base64,' + fs.readFileSync(f).toString('base64');

const CSS = `
  *{margin:0;padding:0;box-sizing:border-box}
  body{background:#F4F4F2;font-family:system-ui,sans-serif;overflow:hidden}
  .stage{width:100vw;height:100vh;display:flex;align-items:center;justify-content:center;gap:70px}
  .phone{width:320px;height:553px;border-radius:38px;background:#15171B;padding:10px;
         box-shadow:0 24px 48px rgba(0,0,0,.18),0 2px 6px rgba(0,0,0,.10);position:relative}
  .phone::after{content:'';position:absolute;top:18px;left:50%;transform:translateX(-50%);
         width:78px;height:6px;border-radius:3px;background:#2A2D33}
  .screen{width:300px;height:533px;border-radius:29px;overflow:hidden;background:#000}
  .screen img{width:100%;height:100%;object-fit:cover;display:block}
  /* logo swatches */
  .sw{width:480px;height:242px;border-radius:12px;overflow:hidden;
      box-shadow:0 18px 40px rgba(0,0,0,.14),0 1px 3px rgba(0,0,0,.10)}
  .sw img{width:100%;height:100%;object-fit:cover;display:block}
`;

function phonesHtml(imgs) {
  return `<!doctype html><meta charset="utf-8"><style>${CSS}</style>
  <div class="stage">${imgs.map(i => `<div class="phone"><div class="screen"><img src="${b64(i)}"></div></div>`).join('')}</div>`;
}

function swatchHtml(imgs) {
  return `<!doctype html><meta charset="utf-8"><style>${CSS}</style>
  <div class="stage" style="gap:90px">${imgs.map(i => `<div class="sw"><img src="${b64(i)}"></div>`).join('')}</div>`;
}

async function render(page, html, out, { width = 1500, height = 750 } = {}) {
  await page.setViewportSize({ width, height });
  await page.setContent(html, { waitUntil: 'load' });
  await page.waitForTimeout(250);
  fs.mkdirSync(path.dirname(out), { recursive: true });
  await page.screenshot({ path: out });
  const m = await sharp(out).metadata();
  console.log('  ->', out, m.width + 'x' + m.height, (fs.statSync(out).size / 1024 | 0) + 'KB');
}

(async () => {
  const b = await chromium.launch({ executablePath: process.env.PW_CHROME });
  const page = await (await b.newContext({ deviceScaleFactor: 1 })).newPage();
  const F = 'ond263/frames';
  const T = 'ond263/work/mock';

  console.log('video-pitbike-akademie');
  await render(page, phonesHtml([`${F}/pitbike-1.png`, `${F}/pitbike-2.png`, `${F}/pitbike-3.png`]),
    `${T}/video-pitbike.png`);

  console.log('animace-delejme');
  await render(page, phonesHtml([`${F}/delejte-1.png`, `${F}/delejte-2.png`, `${F}/delejte-3.png`]),
    `${T}/animace-delejme.png`);

  console.log('logo-realitacky');
  await render(page, swatchHtml(['ond263/work/rlt-sw-dark.png', 'ond263/work/rlt-sw-navy.png']),
    `${T}/logo-realitacky.png`, { width: 1200, height: 480 });

  await b.close();
})();
