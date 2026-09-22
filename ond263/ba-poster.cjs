// OND-263 – před/po pro poster framy (bod 3 po odpovědi boardu k videím)
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const D = __dirname;

const rows = [
  ['video-pitbike-akademie', `${D}/src/projects/video-pitbike-akademie/hero-1.jpg`,
    `${D}/out/poster/video-pitbike-akademie-poster.jpg`, `${D}/out/poster/video-pitbike-akademie-card.jpg`],
  ['animace-delejme', `${D}/src/projects/animace-delejme/hero-1.jpg`,
    `${D}/out/poster/animace-delejme-poster.jpg`, `${D}/out/poster/animace-delejme-card.jpg`],
];

const H = 560, PAD = 14;
const label = (t, w) => Buffer.from(
  `<svg width="${w}" height="24"><rect width="100%" height="100%" fill="#000"/><text x="6" y="17" font-family="monospace" font-size="14" fill="#9fe">${t}</text></svg>`);

(async () => {
  const comps = [];
  let y = PAD, maxX = 0;
  for (const [name, before, poster, card] of rows) {
    let x = PAD;
    for (const [cap, file] of [[`${name} — DNES (hero)`, before], ['PO — poster 1080×1920', poster], ['PO — karta 1080×1080', card]]) {
      const buf = await sharp(file).resize({ height: H }).png().toBuffer();
      const m = await sharp(buf).metadata();
      comps.push({ input: buf, left: x, top: y + 24 });
      comps.push({ input: label(cap, m.width), left: x, top: y });
      x += m.width + PAD;
    }
    maxX = Math.max(maxX, x);
    y += H + 24 + PAD;
  }
  await sharp({ create: { width: maxX, height: y, channels: 3, background: { r: 16, g: 16, b: 16 } } })
    .composite(comps).jpeg({ quality: 86 }).toFile(`${D}/work/poster-before-after.jpg`);
  console.log('wrote work/poster-before-after.jpg', maxX, y);
})();
