# OND-246 — tři návrhy hloubky homepage

> **STAV: tyhle tři soubory jsou PŘEKONANÉ.** Board si vyžádal všechny tři
> dohromady, ale s ořezáním — výsledek žije v `resources/css/hloubka.css`
> a `resources/js/hloubka.js` a zapíná se třídou `pd--depth` na domovské
> stránce. Prototypy tu zůstávají jen do schválení finální podoby; pak se
> celá složka smaže.
>
> Co se do finální vrstvy **nedostalo** a proč:
>
> | z návrhu | co vypadlo | důvod |
> |---|---|---|
> | 2 | přejezd náboje po dělítku každé sekce | rušilo čtenáře; náboj dostávají jen konkrétní prvky |
> | 2 | jiskra sjíždějící celou sekcí 09 | předbíhala čtenáře, seznam kroků je vyšší než okno |
> | 2 | svislá dělítka v sekcích 03 a 04 | nic nevyprávějí, jen přidávala pohyb |
> | 3 | hladiny desek po sekcích (`--pd-plate-0..8`) | celé sekce proti sobě vyzdvižené board nechtěl |
> | 3 | povrchový efekt (maska sledující kurzor + feTurbulence zrno) | nebylo zadané a bylo to nejdražší místo ze všech tří prototypů: při pohybu myši 1305 ms rasterizace proti 10 ms bez něj |
> | 3 | vyzdvižená deska pod doporučeným sloupcem ceníku | sloupec je označený už třikrát; deska rozbíjela levou hranu mřížky |
> | 1 | posun zdroje světla při scrollu (`--pd-py`) | **jednotlivě úplně nejdražší konstrukce ze všech tří návrhů**: +801 ms rasterizace a +154 ms přepočtu stylů na jeden průjezd stránkou, za 7 px pohybu světla |

## Poznámka k tomu, co bylo drahé — měřeno, ne odhadnuto

Původní domněnka byla, že za cenu návrhu 3 může zrno (`feTurbulence`).
**Měření to vyvrátilo.** Zrno samo o sobě stojí +18 ms — statická maska se
rasterizuje jednou. Drahá byla kombinace: `pointermove` zapisuje `--mx/--my`,
radiální maska je čte, takže se při každém pohybu myši překreslí maskovaný
`::after` na všech 17 sekcích. Samotná maska bez obsluhy myši (+4 ms) ani
obsluha bez masky (+5 ms) nestojí nic — teprve obě dohromady dají +1295 ms.
Zrno je zesilovač (+401 ms navíc), ne spouštěč.

A hlavně: nejdražší jednotlivá věc nebyla v návrhu 3, ale v návrhu 1 —
posun zdroje světla při scrollu. Proto ve finální vrstvě není.

Prototypy ke třem návrhům, které dostal board. **Nic z toho není produkční
kód** — jsou to vrstvy, které se za běhu přikládaly na tehdejší homepage,
aby šlo rozhodnout, kterým směrem jít.

## Proč to není rovnou v repu

Rozhodovalo se nad reálnou produkční stránkou, ne nad mockupem: stažená
kopie `/` z `itwebtech.ondrejkriska.cz` + injektovaná CSS/JS vrstva.
Díky tomu snímky ukazují skutečný obsah, skutečné fotky a skutečnou
typografii, ne přiblížení.

## Soubory

| soubor | návrh |
|---|---|
| `var1.css` + `var1.js` | **1 — Světlo shora.** Stránka má jeden světelný zdroj; plochy a hrany na něj reagují, úhel dopadu se mění sekci od sekce. |
| `var2.css` + `var2.js` | **2 — Proud v lince.** Akcentní barva se chová jako proud, který jednou proběhne linkou sekce. Dráha je v každé sekci jiná. |
| `var3.css` + `var3.js` | **3 — Vrstvy.** Stránka je stoh desek; výška desky odpovídá tomu, kolik pozornosti si sekce zaslouží. Akcentní barvu nepoužívá vůbec. |
| `ZADANI.md` | brief, podle kterého varianty vznikly — včetně tvrdých kritérií |

Odůvodnění jednotlivých sekcí je v komentářích přímo v CSS.

## Společná pravidla, která všechny tři dodržují

- žádná nová barva — jen stávající `#D8FF3A` / `#E6FF70` / `#C2E62E` a neutrály
- žádné zaoblené karty ani rozmazané světelné koule
- nic nestartuje v `opacity: 0`
- `prefers-reduced-motion: reduce` vypíná pohyb, statická část zůstává
- animuje se jen `transform` / `opacity` / `background-position`

## Jak si to pustit

```bash
node ond246-shot.js  var1 docs/ond246-navrhy/var1.css docs/ond246-navrhy/var1.js
```

Pomocné skripty (render, srovnávací listy, video) leží mimo repo
v `/home/paperclip/ond246-*.js` — jsou to jednorázové nástroje k rozhodnutí,
ne součást buildu.

## Známé omezení snímků

U návrhu 2 jde o pohyb — na statickém snímku je vidět jen okamžik, kdy
náboj prochází. Proto k němu vzniklo i video.
