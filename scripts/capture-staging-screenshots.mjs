import { chromium } from 'playwright';
import fs from 'node:fs';
import path from 'node:path';

const baseUrl = process.env.SCREENSHOT_BASE_URL || 'https://itwebtech.ondrejkriska.cz';
const outDir = process.env.SCREENSHOT_OUTPUT_DIR || '/home/paperclip/workspaces/itwebtech/screenshots';
const prefix = process.env.SCREENSHOT_PREFIX || 'OND-11';

const viewports = [
  { name: 'mobile', width: 375, height: 812 },
  { name: 'tablet', width: 768, height: 1024 },
  { name: 'desktop', width: 1440, height: 900 },
  { name: '4k', width: 2560, height: 1440 },
];

const ensureDir = () => fs.mkdirSync(outDir, { recursive: true });

async function forceAnimatedElementsVisible(page) {
  await page.addStyleTag({
    content: `
      [data-aos], .aos-init, .aos-animate, [class*="reveal"], [class*="animate"] {
        opacity: 1 !important;
        visibility: visible !important;
        transform: none !important;
        filter: none !important;
        animation: none !important;
        transition: none !important;
      }

      main *, [role="main"] * {
        opacity: 1 !important;
        visibility: visible !important;
      }
    `,
  });

  await page.evaluate(() => {
    const nodes = document.querySelectorAll('main *, [role="main"] *');
    for (const node of nodes) {
      if (!(node instanceof HTMLElement)) continue;
      if (node.style.display === 'none') node.style.display = '';
      if (node.style.opacity === '0') node.style.opacity = '1';
      if (node.style.visibility === 'hidden') node.style.visibility = 'visible';
      if (node.style.transform && node.style.transform !== 'none') node.style.transform = 'none';
    }
    window.dispatchEvent(new Event('scroll'));
    window.dispatchEvent(new Event('resize'));
  });
}

async function runScrollPass(page) {
  await page.evaluate(async () => {
    const sleep = (ms) => new Promise((resolve) => setTimeout(resolve, ms));
    const maxY = () => Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
    const step = Math.max(240, Math.floor(window.innerHeight * 0.8));

    let y = 0;
    while (y < maxY()) {
      y = Math.min(y + step, maxY());
      window.scrollTo(0, y);
      window.dispatchEvent(new Event('scroll'));
      await sleep(180);
    }

    await sleep(500);
    window.scrollTo(0, 0);
    window.dispatchEvent(new Event('scroll'));
    await sleep(500);
  });
}

async function waitForImages(page) {
  await page.evaluate(async () => {
    const pending = Array.from(document.images)
      .filter((img) => !img.complete)
      .map(
        (img) =>
          new Promise((resolve) => {
            img.addEventListener('load', resolve, { once: true });
            img.addEventListener('error', resolve, { once: true });
            setTimeout(resolve, 3000);
          })
      );
    await Promise.all(pending);
  });
}

async function capture(page, viewport) {
  await page.setViewportSize({ width: viewport.width, height: viewport.height });
  await page.goto(baseUrl, { waitUntil: 'domcontentloaded', timeout: 90000 });
  await page.waitForLoadState('networkidle');

  await forceAnimatedElementsVisible(page);
  await runScrollPass(page);
  await waitForImages(page);
  await page.waitForTimeout(700);

  const outPath = path.join(outDir, `${prefix}-${viewport.name}-${viewport.width}x${viewport.height}.png`);
  await page.screenshot({ path: outPath, fullPage: true, animations: 'disabled' });
  console.log(outPath);
}

(async () => {
  ensureDir();

  const browser = await chromium.launch({
    headless: true,
    args: ['--disable-gpu', '--disable-dev-shm-usage'],
  });

  try {
    const context = await browser.newContext({
      ignoreHTTPSErrors: true,
      locale: 'cs-CZ',
    });

    const page = await context.newPage();
    for (const viewport of viewports) {
      await capture(page, viewport);
    }

    await context.close();
  } finally {
    await browser.close();
  }
})();
