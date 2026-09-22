// ond263/build-a.cjs — A) prohození obsahu slotů (hero ukazuje homepage klienta)
// Role slotu (wide >=1.5 => band/lead, jinak 1:1 karta) se NEMĚNÍ — mění se jen obsah.
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');
const path = require('path');

const SRC = 'ond263/src/projects';
const OUT = 'ond263/out/projects';

function out(p) { const f = path.join(OUT, p); fs.mkdirSync(path.dirname(f), { recursive: true }); return f; }

// zapíše do cílového slotu ve formátu podle přípony slotu
async function write(pipeline, slot) {
  const ext = path.extname(slot).toLowerCase();
  const f = out(slot);
  if (ext === '.png') await pipeline.png({ compressionLevel: 9 }).toFile(f);
  else if (ext === '.webp') await pipeline.webp({ quality: 90 }).toFile(f);
  else await pipeline.jpeg({ quality: 90, chromaSubsampling: '4:4:4' }).toFile(f);
  const m = await sharp(f).metadata();
  console.log('  ->', slot, m.width + 'x' + m.height, (fs.statSync(f).size / 1024 | 0) + 'KB');
}

(async () => {
  // 1) BARANA — lead ukazuje tablet vzhůru nohama, homepage je v galerii 1
  console.log('barana');
  await write(sharp(`${SRC}/barana/gallery-1.png`), 'barana/hero-1.png');            // homepage do leadu
  await write(sharp(`${SRC}/barana/hero-1.png`).rotate(180), 'barana/gallery-1.png'); // tablet otočit na čitelno

  // 2) CYKLOCENTRUM — lead je gallery-4 (tři telefony s ceníkem), homepage je gallery-5
  console.log('cyklocentrum');
  await write(sharp(`${SRC}/cyklocentrum/gallery-5.webp`), 'cyklocentrum/gallery-4.png');
  await write(sharp(`${SRC}/cyklocentrum/gallery-4.png`), 'cyklocentrum/gallery-5.webp');

  // 3) NOVÉ INTERIÉRY — lead je "Přijďte si sáhnout", homepage je gallery-4
  console.log('nove-interiery');
  await write(sharp(`${SRC}/nove-interiery/gallery-4.png`), 'nove-interiery/hero-1.png');
  await write(sharp(`${SRC}/nove-interiery/hero-1.png`), 'nove-interiery/gallery-4.png');

  // 4) ZUBNÍ PROVÁZEK — lead je blogový článek (gallery-1), homepage je gallery-4
  console.log('zubni-provazek');
  await write(sharp(`${SRC}/zubni-provazek/gallery-4.png`), 'zubni-provazek/gallery-1.png');
  await write(sharp(`${SRC}/zubni-provazek/gallery-1.png`), 'zubni-provazek/gallery-4.png');

  // 5) JOSEF OPA — hero má AI sci-fi pozadí; gallery-2 je stejný mockup na čisté bílé
  console.log('josefopa');
  await write(sharp(`${SRC}/josefopa/gallery-2.webp`), 'josefopa/hero-1.jpg');
})();
