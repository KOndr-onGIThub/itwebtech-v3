const { chromium } = require('/home/paperclip/node_modules/playwright-core');
(async () => {
  const b = await chromium.launch({ executablePath: process.env.PW_CHROME });
  const p = await (await b.newContext({viewport:{width:1440,height:900}})).newPage();
  for (const slug of process.argv.slice(2)) {
    await p.goto('https://itwebtech.ondrejkriska.cz/projekty/'+slug, {waitUntil:'networkidle'});
    const r = await p.evaluate(() => [...document.querySelectorAll('.portfolio-detail-gallery__item img')].map(img=>{
      const cs=getComputedStyle(img), b=img.getBoundingClientRect();
      return {src:(img.currentSrc||img.src).split('/').pop(), nat:img.naturalWidth+'x'+img.naturalHeight,
        box:Math.round(b.width)+'x'+Math.round(b.height), fit:cs.objectFit};
    }));
    console.log(slug, JSON.stringify(r));
  }
  await b.close();
})();
