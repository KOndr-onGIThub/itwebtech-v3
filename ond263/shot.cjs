// ond263/shot.cjs — live page capture (desktop 1440x900) with reveal animations forced on
// usage: node ond263/shot.cjs <slug> [outname]
const { chromium } = require('/home/paperclip/node_modules/playwright-core');

const BASE = 'https://itwebtech.ondrejkriska.cz';

(async () => {
  const targets = process.argv.slice(2);
  const browser = await chromium.launch({ executablePath: process.env.PW_CHROME });
  const ctx = await browser.newContext({ viewport: { width: 1440, height: 900 }, deviceScaleFactor: 1 });
  const page = await ctx.newPage();
  for (const t of targets) {
    const [url, out] = t.split('=>');
    await page.goto(BASE + url, { waitUntil: 'networkidle', timeout: 60000 });
    // force all reveal states visible + stop animations mid-flight
    await page.addStyleTag({ content: `*{animation-play-state:paused!important}
      [data-reveal],[data-reveal-group],[data-cue]{opacity:1!important;transform:none!important;visibility:visible!important}` });
    await page.evaluate(() => window.scrollTo(0, document.body.scrollHeight));
    await page.waitForTimeout(2500);
    await page.evaluate(() => window.scrollTo(0, 0));
    await page.waitForTimeout(1200);
    await page.screenshot({ path: out, fullPage: true });
    console.log('shot', url, '->', out);
  }
  await browser.close();
})();
