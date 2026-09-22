// simuluje kartu galerie: aspect-ratio 1/1, object-fit cover, object-position top, 598px
const sharp=require('/home/paperclip/workspaces/itwebtech/repo/node_modules/sharp');
const fs=require('fs'),path=require('path');
(async()=>{
 const [out,...files]=process.argv.slice(2);
 const cols=Math.min(files.length,4), cellW=380, labelH=22, pad=10;
 const rows=Math.ceil(files.length/cols);
 const W=cols*(cellW+pad)+pad, H=rows*(cellW+labelH+pad)+pad, comps=[];
 for(let i=0;i<files.length;i++){
  const c=i%cols,r=Math.floor(i/cols);
  const x=pad+c*(cellW+pad), y=pad+r*(cellW+labelH+pad);
  const buf=await sharp(files[i]).resize(cellW,cellW,{fit:'cover',position:'top'}).png().toBuffer();
  comps.push({input:buf,left:x,top:y});
  const m=await sharp(files[i]).metadata();
  const lbl=path.basename(path.dirname(files[i]))+'/'+path.basename(files[i])+' '+m.width+'x'+m.height;
  comps.push({input:Buffer.from(`<svg width="${cellW}" height="${labelH}"><rect width="100%" height="100%" fill="#000"/><text x="4" y="15" font-family="monospace" font-size="12" fill="#bbb">${lbl}</text></svg>`),left:x,top:y+cellW});
 }
 await sharp({create:{width:W,height:H,channels:3,background:{r:16,g:19,b:24}}}).composite(comps).jpeg({quality:88}).toFile(out);
 console.log('wrote',out);
})();
