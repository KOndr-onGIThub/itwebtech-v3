// ond263/build-articles.cjs — náhrady AI/stock ilustrací v článcích (bod 7)
// Princip: místo cizího stocku reálné záběry z portfolia. Žádný text v obrázku.
const { chromium } = require('/home/paperclip/node_modules/playwright-core');
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');
const path = require('path');

const b64 = (f) => 'data:image/png;base64,' + fs.readFileSync(f).toString('base64');
const OUT = 'ond263/out/articles';

const CSS = `*{margin:0;padding:0;box-sizing:border-box}
  body{background:#0E1116;font-family:system-ui,sans-serif}
  .stage{width:100vw;height:100vh;position:relative;overflow:hidden}
  .win{position:absolute;left:40px;top:150px;width:800px;border-radius:12px;overflow:hidden;background:#fff;
       box-shadow:0 30px 70px rgba(0,0,0,.55)}
  .bar{height:32px;background:#23272E;display:flex;align-items:center;gap:7px;padding:0 12px}
  .dot{width:9px;height:9px;border-radius:50%;background:#464C56}
  .view{height:376px;overflow:hidden;background:#fff}
  .view img{width:100%;display:block}
  .ph{position:absolute;right:60px;bottom:50px;width:250px;height:510px;border-radius:32px;background:#15171B;
      padding:8px;box-shadow:0 30px 70px rgba(0,0,0,.6)}
  .ph .scr{width:234px;height:494px;border-radius:26px;overflow:hidden;background:#012060;display:flex;align-items:center}
  .ph .scr img{width:100%;height:auto;display:block}
  .solo{width:100vw;height:100vh;display:flex;align-items:center;justify-content:center}
  .solo .ph{position:static;width:176px;height:352px;border-radius:26px;padding:7px}
  .solo .ph .scr{width:162px;height:338px;border-radius:20px;display:flex;align-items:center}`;

async function shot(page, html, out, w, h) {
  await page.setViewportSize({ width: w, height: h });
  await page.setContent(html, { waitUntil: 'load' });
  await page.waitForTimeout(250);
  fs.mkdirSync(path.dirname(out), { recursive: true });
  await page.screenshot({ path: out });
  console.log('  ->', out, w + 'x' + h);
}

(async () => {
  fs.mkdirSync(OUT, { recursive: true });
  const b = await chromium.launch({ executablePath: process.env.PW_CHROME });
  const page = await (await b.newContext({ deviceScaleFactor: 1 })).newPage();

  // zdroje: reálné obrazovky aplikací na míru
  const hcms = await sharp('ond263/src/projects/hcms/gallery-1.jpg').png().toBuffer();
  fs.writeFileSync('ond263/work/_hcms.png', hcms);
  const picker = await sharp('ond263/src/projects/picker/gallery-2.jpg')
    .extract({ left: 0, top: 0, width: 465, height: 700 }).png().toBuffer();
  fs.writeFileSync('ond263/work/_picker.png', picker);

  // 1) „Kdy se vyplatí aplikace na míru" — hero (nahrazuje AI raketu s logem itwebtech)
  console.log('kdy-se-vyplati / hero');
  await shot(page, `<!doctype html><meta charset="utf-8"><style>${CSS}</style>
    <div class="stage">
      <div class="win"><div class="bar"><i class="dot"></i><i class="dot"></i><i class="dot"></i></div>
        <div class="view"><img src="${b64('ond263/work/_hcms.png')}"></div></div>
      <div class="ph"><div class="scr"><img src="${b64('ond263/work/_picker.png')}"></div></div>
    </div>`, 'ond263/work/mock/art-app-hero.png', 1140, 760);

  // 2) tentýž článek — čtvercová náhledovka do výpisu /jak-na-to
  console.log('kdy-se-vyplati / preview');
  await shot(page, `<!doctype html><meta charset="utf-8"><style>${CSS}</style>
    <div class="solo"><div class="ph"><div class="scr"><img src="${b64('ond263/work/_picker.png')}"></div></div></div>`,
    'ond263/work/mock/art-app-preview.png', 377, 378);

  await b.close();

  // 3) „Potřebuje vaše firma webovou stránku" — dvě anglické stock koláže → reálné weby klientů
  const wide = async (src, out) => {
    const inner = await sharp(src).resize(1080, null).png().toBuffer();
    const m = await sharp(inner).metadata();
    const canvas = await sharp({ create: { width: 1140, height: 760, channels: 3, background: { r: 14, g: 17, b: 22 } } })
      .composite([{ input: inner, left: 30, top: Math.round((760 - m.height) / 2) }])
      .webp({ quality: 88 }).toBuffer();
    fs.writeFileSync(out, canvas);
    console.log('  ->', out, '1140x760', (fs.statSync(out).size / 1024 | 0) + 'KB');
  };
  console.log('potrebuje-vase-firma / dva obrázky');
  await wide('ond263/out/projects/barana/hero-1.png', `${OUT}/digi_marketing.webp`);
  await wide('ond263/out/projects/nove-interiery/hero-1.png', `${OUT}/best_web.webp`);

  // 4) finalizace article hero + preview do cílových formátů
  await sharp('ond263/work/mock/art-app-hero.png').resize(1140, 760)
    .webp({ quality: 88 })
    .toFile(`${OUT}/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony.webp`);
  await sharp('ond263/work/mock/art-app-preview.png').resize(377, 378)
    .webp({ quality: 90 })
    .toFile(`${OUT}/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony_preview.webp`);
  for (const f of fs.readdirSync(OUT)) {
    const m = await sharp(path.join(OUT, f)).metadata();
    console.log('  ==', f, m.width + 'x' + m.height, (fs.statSync(path.join(OUT, f)).size / 1024 | 0) + 'KB');
  }
})();
