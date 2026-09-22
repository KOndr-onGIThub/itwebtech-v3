const { chromium } = require('/home/paperclip/node_modules/playwright-core');
const URL='https://www.motorkari.cz/motosport/motocross/ostatni/ycf-cup-motokrosovych-pitbiku-si-ziskava-srdce-jezdcu-i-fanousku-50728.html';
(async()=>{
 const b=await chromium.launch({executablePath:process.env.PW_CHROME});
 for (const [name,vp] of [['desktop',{width:1440,height:1000}],['mobile',{width:390,height:844}]]) {
   const ctx=await b.newContext({viewport:vp,deviceScaleFactor:2,locale:'cs-CZ',
     userAgent: name==='mobile' ? 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1' : undefined,
     isMobile: name==='mobile', hasTouch: name==='mobile'});
   const p=await ctx.newPage();
   try{ await p.goto(URL,{waitUntil:'domcontentloaded',timeout:60000}); }catch(e){ console.log('goto',e.message); }
   await p.waitForTimeout(4000);
   // consent
   for (const sel of ['button:has-text("Souhlasím")','button:has-text("Přijmout")','button:has-text("Rozumím")','#didomi-notice-agree-button','.fc-cta-consent','button[aria-label*="Souhlas"]']) {
     try{ const l=p.locator(sel).first(); if(await l.count() && await l.isVisible()){ await l.click({timeout:2000}); console.log('consent',sel); await p.waitForTimeout(1500); break; } }catch(e){}
   }
   await p.evaluate(()=>{document.querySelectorAll('iframe,[id*="ad"],[class*="advert"],[class*="banner"]').forEach(e=>{if(e.getBoundingClientRect().height>40)e.style.visibility='hidden'})});
   await p.waitForTimeout(1000);
   await p.screenshot({path:`ond263/work/motorkari-${name}.png`});
   console.log('shot',name);
   await ctx.close();
 }
 await b.close();
})();
