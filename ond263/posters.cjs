// OND-263 – poster framy + 1:1 miniatury karet ze zdrojových videí (1080×1920)
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const P = __dirname + '/out/poster';

const jobs = [
  // [zdroj, výřez y, výstup]
  // y volené tak, aby subjekt přežil i druhý ořez na dlaždici 385×240 (ta bere střed čtverce)
  ['video-pitbike-akademie-poster.jpg', 760, 'video-pitbike-akademie-card.jpg'],
  ['animace-delejme-poster.jpg', 500, 'animace-delejme-card.jpg'],
];

(async () => {
  for (const [src, top, out] of jobs) {
    await sharp(`${P}/${src}`)
      .extract({ left: 0, top, width: 1080, height: 1080 })
      .jpeg({ quality: 90, chromaSubsampling: '4:4:4' })
      .toFile(`${P}/${out}`);
    console.log('card', out, 'z y=' + top);
  }

  // kontrolní list: poster v 9:16 + karta v reálné velikosti mřížky (598) a dlaždice (385×240)
  const cells = [];
  const W = 2200, cellH = 700;
  for (const [src, , card] of jobs) {
    cells.push([src, card]);
  }
  let y = 10;
  const comps = [];
  for (const [src, card] of cells) {
    const poster = await sharp(`${P}/${src}`).resize({ height: cellH - 20 }).png().toBuffer();
    const pm = await sharp(poster).metadata();
    const c598 = await sharp(`${P}/${card}`).resize(598, 598, { fit: 'cover' }).png().toBuffer();
    const t385 = await sharp(`${P}/${card}`).resize(385, 240, { fit: 'cover' }).png().toBuffer();
    comps.push({ input: poster, left: 10, top: y });
    comps.push({ input: c598, left: 30 + pm.width, top: y });
    comps.push({ input: t385, left: 50 + pm.width + 598, top: y });
    y += cellH;
  }
  await sharp({ create: { width: W, height: y + 10, channels: 3, background: { r: 18, g: 18, b: 18 } } })
    .composite(comps).jpeg({ quality: 86 }).toFile(__dirname + '/work/poster-check.jpg');
  console.log('wrote work/poster-check.jpg');
})();
