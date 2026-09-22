// ond263/build-b.cjs — B) karty do 1:1
//
// Proč: .portfolio-detail-gallery__item img má `aspect-ratio:1/1; object-fit:cover;
// object-position:top` (ověřeno v prohlížeči na produkci). Každý snímek, který není
// 1:1, tedy PŘIJDE o obsah — vysoké o spodek, široké o boky. Řešení je systémové:
// karty dodávat jako 1:1, obsah vepsat (contain) na podklad v barvě karty
// (#1C1F24 = --bg-surface #101318 + bílá 7,2 %), takže rám splyne se stránkou.
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');
const path = require('path');

const PAD = { r: 0x1C, g: 0x1F, b: 0x24 };
const SRC = 'ond263/src/projects';
const LEG = 'ond263/legacy/projects';
const OUT = 'ond263/out/projects';

function outPath(p) { const f = path.join(OUT, p); fs.mkdirSync(path.dirname(f), { recursive: true }); return f; }

async function encode(buf, slot) {
  const ext = path.extname(slot).toLowerCase();
  const f = outPath(slot);
  let p = sharp(buf);
  if (ext === '.png') p = p.png({ compressionLevel: 9 });
  else if (ext === '.webp') p = p.webp({ quality: 92 });
  else p = p.jpeg({ quality: 92, chromaSubsampling: '4:4:4' });
  await p.toFile(f);
  const m = await sharp(f).metadata();
  console.log('  ->', slot, m.width + 'x' + m.height, (fs.statSync(f).size / 1024 | 0) + 'KB');
}

/** vepíše zdroj (volitelně po ořezu) doprostřed čtverce; nikdy neupscaluje */
async function square(src, slot, { crop = null, minCanvas = 0 } = {}) {
  let p = sharp(src);
  if (crop) p = p.extract(crop);
  const buf = await p.png().toBuffer();
  const m = await sharp(buf).metadata();
  const side = Math.max(m.width, m.height, minCanvas);
  const out = await sharp({ create: { width: side, height: side, channels: 3, background: PAD } })
    .composite([{ input: buf, left: Math.round((side - m.width) / 2), top: Math.round((side - m.height) / 2) }])
    .png().toBuffer();
  await encode(out, slot);
}

(async () => {
  // — VAN spedition: „před" (1200×900) ztrácel ve čtverci ~25 % šířky ————————
  console.log('vanspedition');
  await square(`${SRC}/vanspedition/gallery-2.webp`, 'vanspedition/gallery-2.webp');
  // nový protějšek „po" — dvojice před/po zaplní řádek mřížky (dnes tam zeje díra)
  await square('reference/itwebtech/public/images/projects/vanspedition/after.webp',
    'vanspedition/gallery-3.webp');

  // — Picker: svislé obrazovky mizely od ~40 % výšky —————————————————————————
  console.log('picker');
  await square(`${SRC}/picker/gallery-2.jpg`, 'picker/gallery-2.jpg');   // picker_main_page_h
  await square(`${SRC}/picker/gallery-4.jpg`, 'picker/gallery-4.jpg');   // picker_call_abn

  // — FRL Creator ————————————————————————————————————————————————————————————
  console.log('frl-creator');
  await square(`${SRC}/frl-creator/hero-1.jpg`, 'frl-creator/hero-1.jpg'); // formulář se usekával z boků
  // pdf_list: 25 z 30 řádků prázdných → výřez vyplněné části (hlavička + 5 řádků)
  await square(`${SRC}/frl-creator/gallery-2.jpg`, 'frl-creator/gallery-2.jpg',
    { crop: { left: 0, top: 0, width: 1257, height: 330 } });

  // — Excel Tools: postup 0–4 se sekal za krokem 2 ————————————————————————————
  console.log('excel-tools');
  await square(`${SRC}/excel-tools/gallery-1.jpg`, 'excel-tools/gallery-1.jpg');
})();
