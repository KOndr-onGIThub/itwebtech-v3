// ond263/sheet.cjs — contact sheet builder
// usage: node ond263/sheet.cjs <out.jpg> <cols> <cellW> <file1> [file2...]
const sharp = require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs = require('fs');
const path = require('path');

(async () => {
  const [out, colsS, cellWS, ...files] = process.argv.slice(2);
  const cols = parseInt(colsS, 10);
  const cellW = parseInt(cellWS, 10);
  const cellH = Math.round(cellW * 0.75);
  const pad = 8;
  const labelH = 22;
  const rows = Math.ceil(files.length / cols);
  const W = cols * (cellW + pad) + pad;
  const H = rows * (cellH + labelH + pad) + pad;

  const comps = [];
  for (let i = 0; i < files.length; i++) {
    const c = i % cols, r = Math.floor(i / cols);
    const x = pad + c * (cellW + pad);
    const y = pad + r * (cellH + labelH + pad);
    const buf = await sharp(files[i])
      .resize(cellW, cellH, { fit: 'contain', background: { r: 30, g: 30, b: 34 } })
      .png().toBuffer();
    comps.push({ input: buf, left: x, top: y });
    const label = path.basename(path.dirname(files[i])) + '/' + path.basename(files[i]);
    const meta = await sharp(files[i]).metadata();
    const txt = `<svg width="${cellW}" height="${labelH}"><rect width="100%" height="100%" fill="#111"/>` +
      `<text x="4" y="15" font-family="monospace" font-size="12" fill="#ddd">${label} ${meta.width}x${meta.height}</text></svg>`;
    comps.push({ input: Buffer.from(txt), left: x, top: y + cellH });
  }

  await sharp({ create: { width: W, height: H, channels: 3, background: { r: 20, g: 20, b: 22 } } })
    .composite(comps).jpeg({ quality: 88 }).toFile(out);
  console.log('wrote', out, W + 'x' + H, files.length + ' cells');
})();
