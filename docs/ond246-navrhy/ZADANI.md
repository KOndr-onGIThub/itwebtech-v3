# OND-246 — „hloubka a hra světel" na ondraweb (homepage)

## Co se řeší

Board (Ondřej Kriška, majitel) chce webu dodat **hloubku a decentní „wau" efekt — hru světel**.
Podmínky, které zadal doslova:

- „Nic přehnaného, jenom decentně a profesionálně."
- „Určitě nechci přidávat další speciální odlišnou barvu." → **žádná nová barva**. K dispozici je
  jen stávající akcent **acid `#D8FF3A`** (+ hover `#E6FF70`, press `#C2E62E`) a neutrály.
- „Všechno to musí zapadnout do současného konceptu."
- „Nechci jenom 1 prvek, který se bude opakovat v naprosto stejné podobě ve všech sekcích.
  Mělo by to bejt nějakým způsobem živé a trošku odlišné v různých sekcích. Aby to podpořilo
  ten obsah."

Poslední bod je nejdůležitější a nejčastěji se poserou právě na něm: **nestačí jeden efekt
nalepený na `.pd-section`**. Chceme *systém* (pravidlo, jak se světlo na webu chová), který má
v každé sekci jiné konkrétní provedení, odvozené od toho, co ta sekce říká.

## Současný stav webu (důležité — ctít, ne přepisovat)

Homepage má vlastní vizuální jazyk „ACID" (namespace `.pd-`, styly v `resources/css/podpis.css`).
Jeho deklarovaná gramatika:

- barva je **signální inkoust**: existuje jen tam, kde je akce nebo důraz
- **vlasové linky** místo karet a boxů, hodně negativního prostoru
- **žádné pill tvary, zaoblené karty ani glow blury**
- obsah **nikdy nestartuje v `opacity: 0`** (žádné vstupní fade-in celých bloků)
- pohyb je **přesný a účelový, ne dekorativní**

Tokeny (už existují, používej je):

```
--color-bg-base:   #0A0A0B    --color-fg-primary: #F2F0EA
--color-bg-elev-1: #131316    --color-fg-muted:   #9B9AA0
--color-bg-elev-2: #1B1B20    --color-fg-faint:   #5C5C63
--color-border-soft: #26262B  --color-border-mid: #3A3A41
--color-accent: #D8FF3A  --color-accent-hover: #E6FF70  --color-accent-press: #C2E62E
--ease-out: cubic-bezier(.2,.8,.2,1)  --duration-micro: 120ms  --duration-macro: 320ms
--space-1..11 = 4,8,12,16,24,32,48,64,96,128,192 px
```

**Vizuální realita:** stránka je dnes prakticky plochá — každá sekce je stejná černá plocha
`#0A0A0B`, oddělená `border-top: 1px solid #26262B`, vpravo nahoře acid pořadové číslo
(`.pd-head__index`, 02–15). Obsah „plave" v prázdnu. To je přesně ten nedostatek hloubky.

## Pořadí a obsah sekcí (co která sekce říká — podle toho se efekt liší)

| # | selektor / id | obsah |
|---|---|---|
| — | `.pd-hero` | fotka Ondry přes pravých 54 %, text vlevo v negativním prostoru, podpis (acid klikyhák), acid CTA tlačítko |
| 02 | 1. `.pd-section` | **Weby, které běží v praxi** — 3 `.pd-work` dlaždice se screenshoty živých webů (`.pd-work__plate` = 1px rámeček + obrázek) |
| — | `.pd-strip` | jednořádkový social proof (hodnocení) |
| — | `.pd-clients` | tichá řada jmen klientů `.pd-clients__name` |
| 03 | | **Jak weby stavím** — `.pd-lead`, `.pd-avoid`, 2× `.pd-issue` s velkým `.pd-issue__num` |
| 04 | | **Co stavím** — 3 sloupce `.pd-service` oddělené svislými vlasovými linkami, acid `.pd-service__num` (01/02/03), odrážky |
| 05 | | **Realizované projekty** — 3× `.pd-case` (velký vizuál `.pd-case__visual` + text) |
| 06 | `#section-price` | **Kolik to bude stát** — 3 `.pd-price__col`, jeden `.pd-price__col--featured` |
| 07 | | **Proč já** — `.pd-why__video` (video) + tichá mřížka `.pd-why__item` |
| 08 | | **Pod kapotou** — 3 `.pd-hood__fact` + naměřený čas načtení |
| 09 | | **Od první zprávy ke spuštěnému webu — 4 jasné kroky** — `ol.pd-steps`, 4× `.pd-step` s velkým acid `.pd-step__num`, oddělené vodorovnými linkami |
| 10 | `#section-testimonials` | **Reference** — 6× `.pd-testi__item` |
| 11 | | **Generátor versus váš byznys** — 2 sloupce `.pd-versus__col`, jeden `--mine` |
| 12 | | **18 let v Toyotě** — `.pd-origin__quote` |
| 13 | | **Záruky** — 2× `.pd-promise__item` |
| 14 | `#faq` | **FAQ** — `details.pd-faq__item` + `.pd-faq__mark` (acid křížek) |
| 15 | | **Poptávka** — `.pd-form__panel`, `.pd-form__submit` |

Kompletní inventář tříd: `/tmp/ond246/classes.txt`. Živé HTML: `/tmp/ond246/prod.html`.

## Jak pracovat a jak si to ověřit

Nepracuje se v repu. Efekt piš jako **overlay, který se injektuje do reálné produkční stránky**:

- CSS → `/tmp/ond246/var<N>.css`
- volitelně JS → `/tmp/ond246/var<N>.js` (čistý ES5/ES2015, žádné importy, spustí se po načtení
  stránky; máš k dispozici `IntersectionObserver`, `requestAnimationFrame`)

Render + screenshoty (chromium je nainstalovaný, skript funguje, neupravuj ho):

```bash
cd /home/paperclip
SHOT_DIR=/tmp/ond246/shots node ond246-shot.js var<N> /tmp/ond246/var<N>.css /tmp/ond246/var<N>.js
```

Vyrobí `/tmp/ond246/shots/var<N>__*.png` pro všechny sekce. **Musíš si je prohlédnout
nástrojem Read** (jsou to obrázky) a iterovat, dokud výsledek nesedí. Baseline „před" snímky
jsou tamtéž jako `base__*.png` — porovnej se s nimi.

Pozn.: `prod.html` je uložená kopie produkční stránky; obrázky/CSS se tahají z
`https://itwebtech.ondrejkriska.cz` (síť funguje). Nesmíš měnit `prod.html`.

## Tvrdá kritéria (nesplněné = práce k ničemu)

1. **Žádná nová barva.** Jen `#D8FF3A` / `#E6FF70` / `#C2E62E` a neutrální bílá/černá/šedá.
   Zakázané: modrá, fialová, tyrkys, duhové gradienty.
2. **Decentní.** Když si snímek „před" a „po" položíš vedle sebe, rozdíl musí být znát, ale
   nikdo nesmí říct „to bliká". Žádné pulzující blury přes půl obrazovky.
3. **Odlišné po sekcích.** Minimálně **5 různých konkrétních provedení** napříč stránkou,
   každé odvozené z obsahu sekce. Jeden efekt × 14 sekcí = nesplněno.
4. **Čitelnost a kontrast.** Text nesmí ztratit kontrast; AA zůstává.
5. **Respekt ke gramatice.** Žádné zaoblené karty, žádné velké rozmazané glow koule,
   nic nestartuje v `opacity: 0`.
6. **`prefers-reduced-motion: reduce`** — pohyb vypnout, statická část efektu smí zůstat.
7. **Výkon.** Animuj jen `transform` / `opacity` / `background-position`. Žádné
   `box-shadow` animace, žádný `filter: blur()` v nekonečné smyčce přes velké plochy.

## Co odevzdat

1. `/tmp/ond246/var<N>.css` (+ `var<N>.js`), okomentované česky — u každé sekce napiš,
   **proč** má právě tohle provedení.
2. `/tmp/ond246/var<N>-README.md`: název varianty (2–3 slova, česky, bez žargonu),
   jedna věta „co člověk uvidí", pak tabulka sekce → provedení → proč.
3. Ověřené screenshoty v `/tmp/ond246/shots/var<N>__*.png`.

Ve finálním textu odpovědi vrať: název varianty, jednu větu popisu, seznam sekce→provedení
a cestu ke snímkům, které jsi sám prohlédl.
