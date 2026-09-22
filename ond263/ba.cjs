// ond263/ba.cjs — před/po list: každý řádek = starý slot vs nový soubor,
// vykreslené v geometrii, ve které je stránka opravdu zobrazuje
// (wide → pás 1100 px, card → čtverec 598 cover/top, thumbnail → 16:10 cover)
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');

const SRC = 'ond263/src/projects';
const OUT = 'ond263/out/projects';

// [label, starý soubor, nový soubor, role]
const ROWS = [
  ['barana / lead', `${SRC}/barana/hero-1.png`, `${OUT}/barana/hero-1.png`, 'wide'],
  ['barana / galerie 1', `${SRC}/barana/gallery-1.png`, `${OUT}/barana/gallery-1.png`, 'wide'],
  ['cyklocentrum / lead', `${SRC}/cyklocentrum/gallery-4.png`, `${OUT}/cyklocentrum/gallery-4.png`, 'wide'],
  ['nove-interiery / lead', `${SRC}/nove-interiery/hero-1.png`, `${OUT}/nove-interiery/hero-1.png`, 'wide'],
  ['zubni-provazek / lead', `${SRC}/zubni-provazek/gallery-1.png`, `${OUT}/zubni-provazek/gallery-1.png`, 'wide'],
  ['josefopa / lead', `${SRC}/josefopa/hero-1.jpg`, `${OUT}/josefopa/hero-1.jpg`, 'wide'],
  ['vanspedition / karta „před"', `${SRC}/vanspedition/gallery-2.webp`, `${OUT}/vanspedition/gallery-2.webp`, 'card'],
  ['picker / main_page_h', `${SRC}/picker/gallery-2.jpg`, `${OUT}/picker/gallery-2.jpg`, 'card'],
  ['picker / call_abn', `${SRC}/picker/gallery-4.jpg`, `${OUT}/picker/gallery-4.jpg`, 'card'],
  ['frl-creator / main_page', `${SRC}/frl-creator/hero-1.jpg`, `${OUT}/frl-creator/hero-1.jpg`, 'card'],
  ['frl-creator / pdf_list', `${SRC}/frl-creator/gallery-2.jpg`, `${OUT}/frl-creator/gallery-2.jpg`, 'card'],
  ['excel-tools / backup_case2', `${SRC}/excel-tools/gallery-1.jpg`, `${OUT}/excel-tools/gallery-1.jpg`, 'card'],
  ['video-pitbike / lead', `${SRC}/video-pitbike-akademie/hero-1.jpg`, `${OUT}/video-pitbike-akademie/hero-1.jpg`, 'wide'],
  ['animace-delejme / lead', `${SRC}/animace-delejme/hero-1.jpg`, `${OUT}/animace-delejme/hero-1.jpg`, 'wide'],
  ['logo-realitacky / lead', `${SRC}/logo-realitacky/hero-1.png`, `${OUT}/logo-realitacky/hero-1.png`, 'wide'],
  ['clanek-motorkari / lead', `${SRC}/clanek-motorkari-cz/hero-1.jpg`, `${OUT}/clanek-motorkari-cz/hero-1.jpg`, 'wide'],
  ['clanek-motorkari / galerie 1', `${SRC}/clanek-motorkari-cz/gallery-1.jpg`, `${OUT}/clanek-motorkari-cz/gallery-1.jpg`, 'wide'],
  ['hcms / miniatura karty', `${SRC}/hcms/gallery-4.jpg`, `${OUT}/hcms/thumbnail-1.jpg`, 'thumb'],
  ['článek: raketa → vlastní aplikace', 'ond263/src/articles/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony.webp', 'ond263/out/articles/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony.webp', 'art'],
  ['článek: DIGITAL MARKETING → BARANA', 'ond263/src/articles/digi_marketing.webp', 'ond263/out/articles/digi_marketing.webp', 'art'],
  ['článek: BEST WEB DESIGN → Nové interiéry', 'ond263/src/articles/best_web.webp', 'ond263/out/articles/best_web.webp', 'art'],
  ['/kontakt (webp zdroj)', 'ond263/src/portraits/public/img/about/ondrej_kriska_preview.webp', 'ond263/out/public/img/about/ondrej_kriska_preview.webp', 'portrait'],
];

const GEOM = {
  wide: { w: 520, h: null },
  card: { w: 300, h: 300, fit: 'cover', pos: 'top' },
  thumb: { w: 400, h: 250, fit: 'cover' },
  art: { w: 440, h: null },
  portrait: { w: 200, h: 250, fit: 'cover', pos: 'top' },
};

(async () => {
  const CELL = 540, PAD = 14, LAB = 26;
  const comps = [];
  let y = PAD;
  const rowsMeta = [];
  for (const [label, before, after, role] of ROWS) {
    const g = GEOM[role];
    const mk = async (f) => {
      let p = sharp(f);
      if (g.h) p = p.resize(g.w, g.h, { fit: g.fit, position: g.pos || 'centre' });
      else p = p.resize(g.w);
      return p.png().toBuffer();
    };
    const [b, a] = await Promise.all([mk(before), mk(after)]);
    const mb = await sharp(b).metadata(), ma = await sharp(a).metadata();
    const rowH = Math.max(mb.height, ma.height);
    comps.push({ input: Buffer.from(`<svg width="1120" height="${LAB}"><rect width="100%" height="100%" fill="#000"/><text x="6" y="18" font-family="monospace" font-size="14" fill="#E9E27A">${label}</text><text x="560" y="18" font-family="monospace" font-size="12" fill="#888">PO →</text></svg>`), left: PAD, top: y });
    comps.push({ input: b, left: PAD, top: y + LAB });
    comps.push({ input: a, left: PAD + CELL + 20, top: y + LAB });
    rowsMeta.push(rowH);
    y += LAB + rowH + PAD;
  }
  const H = y;
  await sharp({ create: { width: 1120 + 2 * PAD, height: H, channels: 3, background: { r: 18, g: 20, b: 24 } } })
    .composite(comps).jpeg({ quality: 84 }).toFile('ond263/work/before-after.jpg');
  console.log('wrote ond263/work/before-after.jpg', (1120 + 2 * PAD) + 'x' + H);
})();
