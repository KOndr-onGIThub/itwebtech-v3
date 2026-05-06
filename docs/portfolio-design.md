# Portfolio — UI/UX návrh listingu a detailu

Dokument je zdroj pravdy pro implementační fázi (OND-59 → fáze 2).
Engineer podle něj staví Blade šablony a CSS, aniž by musel domýšlet rozložení, stavy nebo komponentové API.

- **Listing route:** `/projekty` (`projects.blade.php`)
- **Detail route:** `/projekty/{slug}` (`project.blade.php`)
- **Parent issue:** OND-59
- **Vychází z DB schématu:** `portfolio_projects`, `portfolio_project_translations`, `portfolio_project_screenshots`

---

## 0. Návrhové principy

1. **Mobile-first.** Začínáme od ≤ 640 px a postupně přidáváme sloupce/sekce.
2. **Reuse před novými třídami.** Stavíme na `page-hero`, `section-wrapper`, `section-header`, `container-site`, `btn-*`, `data-reveal*`. Nové BEM třídy přidáváme jen tam, kde existující neslouží.
3. **Design tokens, ne nové barvy.** Vše čerpáme z `@theme` v `resources/css/app.css` (gold-500/600/200/100, ink-50…950, ok/fail). Nové barvy jsou mimo scope.
4. **Konverze před dekorací.** Hierarchie: hero → filtry → grid → CTA. Detail: hero → galerie → case study → CTA.
5. **Sekce „snapshot/fit/why_me“ ZŮSTÁVÁ.** DB listing portfolia se vkládá **NAD** tyto sekce (viz § 1.1).

---

## 1. Listing `/projekty`

### 1.1 Pořadí sekcí na stránce (rozhodnutí)

```
1. page-hero            (existuje — H1 „PROJEKTY“, intro)
2. portfolio-filter     (NOVÉ — filtry kategorií)
3. portfolio-grid       (přepracované — karty z DB)
4. project-snapshots    (existuje — „Co už funguje“)
5. project-fit          (existuje — kvalifikace)
6. why-grid             (existuje — proč se mnou)
7. cta-block            (NOVÉ na listingu — finální výzva ke konzultaci)
```

**Proč nad snapshoty:** návštěvník přicházející na `/projekty` chce vidět **reálné realizace** dřív než obecné scénáře. Snapshoty fungují jako sekundární důkaz pro segment, který v gridu svůj případ nenašel. Fit/why_me uzavírají kvalifikační smyčku.

### 1.2 Wireframe — desktop (≥ 1024 px)

```
┌──────────────────────────────────────────────────────────────────┐
│  [page-hero]                                                     │
│   Realizované                                                    │
│   PROJEKTY                                                       │
│   Nechte se inspirovat ukázkami mé práce…                        │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [portfolio-filter]                                              │
│   ( Vše )  ( Stránky )  ( Aplikace )  ( Ostatní )    12 projektů │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [portfolio-grid] — 3 sloupce, gap 1.75rem                       │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐                     │
│  │ [thumb]   │  │ [thumb]   │  │ [thumb]   │                     │
│  │ kategorie │  │ kategorie │  │ kategorie │                     │
│  │ NÁZEV     │  │ NÁZEV     │  │ NÁZEV     │                     │
│  │ tagline.. │  │ tagline.. │  │ tagline.. │                     │
│  │ 2025·web  │  │ 2024·app  │  │ 2024·web  │                     │
│  └───────────┘  └───────────┘  └───────────┘                     │
│  ┌───────────┐  ┌───────────┐  ┌───────────┐                     │
│  │     …     │  │     …     │  │     …     │                     │
│  └───────────┘  └───────────┘  └───────────┘                     │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [project-snapshots] (existující)                                │
│  ...                                                             │
└──────────────────────────────────────────────────────────────────┘

… project-fit, why-grid, cta-block …
```

### 1.3 Wireframe — mobile (≤ 640 px)

```
┌────────────────────────┐
│  [page-hero]           │
│   Realizované          │
│   PROJEKTY             │
│   intro …              │
└────────────────────────┘

┌────────────────────────┐
│  [portfolio-filter]    │
│  vodorovný scroll      │
│  ( Vše ) ( Stránky )→  │
└────────────────────────┘

┌────────────────────────┐
│  [portfolio-grid]      │
│  1 sloupec, gap 1rem   │
│  ┌──────────────────┐  │
│  │ [thumb 16:10]    │  │
│  │ kategorie        │  │
│  │ NÁZEV            │  │
│  │ tagline          │  │
│  │ 2025 · web       │  │
│  └──────────────────┘  │
│  ┌──────────────────┐  │
│  │ …                │  │
└────────────────────────┘
```

### 1.4 Grid spec

| Breakpoint        | Sloupce | Min karta | Gap     | Container padding |
|-------------------|---------|-----------|---------|-------------------|
| ≤ 640 px (mobile) | 1       | 100 %     | 1 rem   | existující `container-site` |
| 641–1023 px       | 2       | 320 px    | 1.5 rem | existující |
| ≥ 1024 px         | 3       | 320 px    | 1.75 rem| existující |
| ≥ 1440 px         | 3       | viz výš   | 1.75 rem| max-width zachovat |

**Implementace:** stejný princip jako stávající `.projects-grid`:
```css
grid-template-columns: repeat(auto-fill, minmax(min(100%, 320px), 1fr));
```
Pro listing ponechat `auto-fill`, aby grid nedával prázdné stopy při lichém počtu kartiček.

**Featured projekt (volitelné):** pokud `featured = true` a je to první karta, na `≥ 1024 px` zabírá `grid-column: span 2;` a má vyšší vizuální hierarchii (větší thumbnail, výraznější nadpis). Pokud máš < 4 projekty featured, neimplementuj — vede to k nevyrovnanému gridu.

---

## 2. Karta projektu — `portfolio-card`

### 2.1 Co je vidět (z DB)

| Prvek            | Zdroj                                          | Povinné? |
|------------------|------------------------------------------------|----------|
| Thumbnail        | `screenshots[type=thumbnail]` → fallback `screenshots[type=hero]` → poslední placeholder | ano (s fallbackem) |
| Kategorie        | `portfolio_projects.category` (website/application/other) | ano |
| Rok              | `portfolio_projects.year`                      | ne |
| Klient           | `portfolio_projects.client_name`               | ne |
| Název            | `portfolio_project_translations.title`         | ano |
| Tagline          | `portfolio_project_translations.subtitle` → fallback `summary` (zkráceno na 110 znaků) | ano |
| Skrytá CTA šipka | dekorativní                                    | ano |

### 2.2 Vizuální struktura karty

```
┌─────────────────────────────────────────┐
│ ┌─────────────────────────────────────┐ │  ← obrázek 16:10
│ │                                     │ │     overflow:hidden
│ │            THUMBNAIL                │ │     scale 1→1.04 on hover
│ │                                     │ │
│ └─────────────────────────────────────┘ │
│                                         │
│  WEB · 2025 · ACME s.r.o.               │  ← __meta (caps, gold-500, 0.05em)
│                                         │
│  Název projektu, který chytí oko        │  ← __title (1.25rem, 600)
│                                         │
│  Krátký tagline o tom, co projekt       │  ← __tagline (0.9375rem, body)
│  reálně přinesl klientovi.              │
│                                         │
│  Zobrazit projekt  →                    │  ← __cta (gold, underline na hover)
└─────────────────────────────────────────┘
```

### 2.3 Proporce a spacing

- **Karta:** `border-radius: 1rem` · `border: 1px solid var(--border-subtle)` · `background: var(--bg-card)` · `overflow: hidden`
- **Thumbnail wrapper:** `aspect-ratio: 16 / 10` · `background: var(--bg-card-hover)` (placeholder, kdyby chybělo)
- **Obsah pod obrázkem:** `padding: 1.25rem 1.25rem 1.5rem`
- **Mezery uvnitř:** meta → 0.75rem → title → 0.5rem → tagline → 1rem → cta

### 2.4 Stavy

| Stav      | Změna                                                                                      |
|-----------|--------------------------------------------------------------------------------------------|
| default   | viz výše                                                                                   |
| hover     | `border-color: var(--border-gold)`; `background: var(--bg-card-hover)`; `transform: translateY(-2px)`; thumbnail `transform: scale(1.04)`; `__cta` zlatě podtrženo |
| focus-visible | `outline: 2px solid var(--color-gold-500)` na **odkazu uvnitř karty** (nikoli na celé kartě), `outline-offset: 4px`, `border-radius` zachován |
| active    | bez visuálního translate, jen `border-color: var(--border-gold)`                            |
| loading   | `__thumbnail img` má `loading="lazy"` (kromě prvních 3 karet — `loading="eager"` + `fetchpriority="high"` na 1. featured) |
| empty (žádné publikované projekty) | místo gridu zobrazit centrovaný `__empty` blok s textem `__('projects.empty')` a CTA na kontakt |

### 2.5 Dostupnost

- Celá karta **NENÍ** klikací element. Klikací je vnitřní `<a class="portfolio-card__link">`, který obaluje thumbnail + obsah. Důvod: čtečky obrazovek mají jeden pochopitelný odkaz, ne nested interactive elements.
- `<a>` má `aria-label="{{ $title }} — zobrazit projekt"` (čtečka pak řekne celý kontext, ne jen „zobrazit projekt“).
- Thumbnail má `alt=""` (dekorativní v rámci kartičky, název je vedle).
- `meta` chunk je `<dl>` se skrytými termíny, NEBO jednoduchý `<p class="portfolio-card__meta">` s vizuálně odděleným textem (preferuju druhé — méně markupu, čtečka přečte plynule).
- Touch target odkazu ≥ 44×44 px (zajištěno tím, že odkazem je celá kartička).

---

## 3. Filtr kategorií — `portfolio-filter`

### 3.1 Vstup

i18n klíče už existují: `projects.filter_all`, `projects.filter_websites`, `projects.filter_webapps`, `projects.filter_other`.

### 3.2 Chování (rozhodnutí: client-side bez routy)

Filtr **nemění URL** (žádné `?category=…`), pracuje čistě v JS na klientu. Důvody:

- < 50 projektů → není problém načíst všechny najednou.
- Zachová se reveal animace u stávajících sekcí pod gridem.
- Nezvyšuje TTFB, žádné dodatečné dotazy do DB.

**Engineer note:** pokud QA ukáže, že je projektů > 60, přejdi na server-side filtr přes query string. To není scope tohoto designu.

Default stav: `Vše` (žádný filtr).
Po kliknutí: `aria-pressed="true"` na aktivním tlačítku, ostatní `false`. Karty mimo kategorii se schovají přes atribut, ne přes display:none (kvůli reveal animaci):

```html
<article class="portfolio-card" data-category="website" data-filter-hidden="false">…</article>
```

JS pak jen přepne `data-filter-hidden="true"` a CSS skryje (`display: none`).

### 3.3 Vizuál — desktop

```
┌────────────────────────────────────────────────────────────────┐
│  ( Vše )  ( Stránky )  ( Aplikace )  ( Ostatní )    12 projektů │
└────────────────────────────────────────────────────────────────┘
```

- Tlačítka jako pill buttons, `border: 1px solid var(--border-subtle)`, `padding: 0.5rem 1.125rem`, `border-radius: 999px`, `font-size: 0.9375rem`.
- Aktivní: `background: var(--color-gold-500)`, `color: var(--color-ink-950)`, `border-color: var(--color-gold-500)`.
- Hover: `border-color: var(--border-gold)`, `color: var(--color-heading)`.
- Focus-visible: `outline: 2px solid var(--color-gold-500)`, offset 2 px.
- Vpravo zarovnaný počet (`__count`) — `color: var(--color-muted)`, mění se podle aktivního filtru.

### 3.4 Vizuál — mobile

- Filtry **horizontální scroll** uvnitř `container-site` (overflow-x: auto, scroll-snap-type: x mandatory).
- Počet projektů přesunut **pod filtry** na vlastní řádek, zarovnán doleva.
- Touch target: `min-height: 40px`, `padding: 0.5rem 1rem`.
- Indikátor scrollu: gradient fade z pravé strany (existuje pattern v projektu? pokud ne, jen `padding-right: 2rem` na rodiči).

### 3.5 Dostupnost

- `<div role="tablist" aria-label="Filtr kategorií">` jen pokud filtr opravdu mění obsah (což ano).
- Tlačítka: `<button type="button" role="tab" aria-pressed="true|false" aria-controls="portfolio-grid">`.
- Klávesnice: `Tab` mezi tlačítky, `Enter`/`Space` pro toggle. Šipky `←/→` přepínají mezi taby (volitelné, ale doporučené).
- Při zapnutí filtru oznámit počet výsledků přes `aria-live="polite"` na `__count` elementu.

---

## 4. Detail `/projekty/{slug}` — `portfolio-project`

### 4.1 Pořadí sekcí

```
1. portfolio-detail-hero    (NOVÉ — větší než aktuální page-hero--project)
2. portfolio-detail-gallery (NOVÉ — galerie screenshotů type=gallery)
3. portfolio-detail-body    (NOVÉ — case study: Výzva / Řešení / Výsledek)
4. portfolio-detail-meta    (NOVÉ — postranní info: rok, klient, kategorie, trvání, tagy)
5. portfolio-related        (NOVÉ — 3 další projekty, stejná kategorie + featured)
6. cta-block                (existuje — finální CTA, beze změny)
```

### 4.2 Wireframe — desktop (≥ 1024 px)

```
┌──────────────────────────────────────────────────────────────────┐
│  ← Zpět na projekty                                              │
│                                                                  │
│  WEB · 2025 · ACME s.r.o.                                        │
│  NÁZEV PROJEKTU                                                  │
│  Krátký podtitulek vystihující výsledek.                         │
│                                                                  │
│  [ Navštívit živý web ↗ ]   [ Domluvit konzultaci → ]            │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [portfolio-detail-gallery] — hero screenshot, full bleed         │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │                                                            │  │
│  │                  HERO SCREENSHOT (16:9)                    │  │
│  │                                                            │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  Galerie (type=gallery), 2 sloupce:                              │
│  ┌─────────────────────────┐  ┌─────────────────────────┐        │
│  │       screen 1          │  │       screen 2          │        │
│  └─────────────────────────┘  └─────────────────────────┘        │
│  ┌─────────────────────────┐  ┌─────────────────────────┐        │
│  │       screen 3          │  │       screen 4          │        │
│  └─────────────────────────┘  └─────────────────────────┘        │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [portfolio-detail-body] — 2 sloupce: 8/12 case study, 4/12 meta │
│                                                                  │
│  ┌────────────────────────────────────┐  ┌────────────────────┐  │
│  │  Výzva                             │  │  Klient            │  │
│  │  Lorem ipsum dolor sit amet…       │  │  ACME s.r.o.       │  │
│  │                                    │  │                    │  │
│  │  Řešení                            │  │  Rok               │  │
│  │  Lorem ipsum dolor sit amet…       │  │  2025              │  │
│  │                                    │  │                    │  │
│  │  Výsledek                          │  │  Trvání            │  │
│  │  Lorem ipsum dolor sit amet…       │  │  3 měsíce          │  │
│  │                                    │  │                    │  │
│  │                                    │  │  Kategorie         │  │
│  │                                    │  │  Webová aplikace   │  │
│  │                                    │  │                    │  │
│  │                                    │  │  Živý web          │  │
│  │                                    │  │  acme.cz ↗         │  │
│  └────────────────────────────────────┘  └────────────────────┘  │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [portfolio-related] — „Další projekty“                          │
│  3 karty (stejná logika jako portfolio-card)                     │
└──────────────────────────────────────────────────────────────────┘

┌──────────────────────────────────────────────────────────────────┐
│  [cta-block] — Chcete podobný výsledek? + Domluvit konzultaci    │
└──────────────────────────────────────────────────────────────────┘
```

### 4.3 Wireframe — mobile (≤ 640 px)

```
┌────────────────────────┐
│  ← Zpět na projekty    │
│                        │
│  WEB · 2025            │
│  NÁZEV PROJEKTU        │
│  Podtitulek…           │
│                        │
│  [ Navštívit web ↗ ]   │
│  [ Konzultace → ]      │
└────────────────────────┘

┌────────────────────────┐
│  [hero screenshot]     │
│  16:9, full width      │
└────────────────────────┘

┌────────────────────────┐
│  [galerie 1 sloupec]   │
│  ┌──────────────────┐  │
│  │   screen 1       │  │
│  └──────────────────┘  │
│  ┌──────────────────┐  │
│  │   screen 2       │  │
│  └──────────────────┘  │
└────────────────────────┘

┌────────────────────────┐
│  Výzva …               │
│  Řešení …              │
│  Výsledek …            │
└────────────────────────┘

┌────────────────────────┐
│  [meta — list, ne col] │
│  Klient   ACME s.r.o.  │
│  Rok      2025         │
│  Trvání   3 měsíce     │
│  Kategorie  Webapp     │
│  Živý web  acme.cz ↗   │
└────────────────────────┘

┌────────────────────────┐
│  Další projekty        │
│  [3 karty pod sebou]   │
└────────────────────────┘

┌────────────────────────┐
│  [cta-block]           │
└────────────────────────┘
```

### 4.4 Hero detailu — `portfolio-detail-hero`

- `padding-block: clamp(2.5rem, 6vw, 5rem)`
- Pořadí: zpět-link → meta řádek (caps, zlatě, oddělovač `·`) → H1 → podtitul → akce.
- H1: stejný styl jako stávající `page-hero h1` (zachovat konzistenci).
- Akce: 2 tlačítka — `btn-primary` na živý web (pokud `live_url`), `btn-secondary` na konzultaci. Pokud `live_url` chybí, použít jen `btn-secondary` na konzultaci jako primární (ale stále `btn-secondary` vizuál — nedrobit hierarchii).
- Externí odkaz na živý web má `target="_blank" rel="noopener"` a viditelnou ikonu `arrow-right` rotovanou `-rotate-45` (odpovídá patternu z projects listingu).

### 4.5 Galerie — `portfolio-detail-gallery`

- **Hero screenshot** = první screenshot s `type='hero'` (sort_order asc). Zobrazit přes celou šířku `container-site`, `aspect-ratio: 16/9`, `border-radius: 1rem`, `overflow: hidden`.
- **Gallery screenshoty** = `type='gallery'`, sort_order asc.
  - Desktop: 2 sloupce, `gap: 1.25rem`, `border-radius: 0.75rem`.
  - Tablet: 2 sloupce, gap 1rem.
  - Mobile: 1 sloupec, gap 1rem.
- Komponenta `<x-responsive-image>` (existuje), s `loading="lazy"` (kromě hero, který má `loading="eager"` + `fetchpriority="high"`).
- Lightbox: zachovat existující řešení z `project.blade.php` (`lightbox-gallery="portfolio-screenshots"`).
- Pokud screenshot nemá `type`, fallback do galerie. Pokud chybí hero, použít první gallery jako hero.

### 4.6 Tělo — `portfolio-detail-body`

Sekce odpovídají DB sloupcům `challenge`, `solution`, `result`. Pokud kterýkoli chybí, sekci **vynechat**, ne renderovat prázdný blok.

| Subsekce  | Nadpis (i18n)                       | Obsah          |
|-----------|-------------------------------------|----------------|
| Výzva     | `projects.detail.challenge`         | `challenge`    |
| Řešení    | `projects.detail.solution`          | `solution`     |
| Výsledek  | `projects.detail.result`            | `result`       |

Layout:
- Desktop ≥ 1024 px: `grid-template-columns: 8fr 4fr; gap: 3rem;` — case study vlevo, meta vpravo (sticky `top: 6rem` na vyšších viewportech, ne na tabletu).
- Tablet 641–1023 px: 1 sloupec, meta nad case study (rychlé skenování fakt).
- Mobile: 1 sloupec, meta **pod** case study (rychlé čtení příběhu).

Typografie sekcí:
- H2 (název subsekce): odpovídá globálnímu H2, ale `font-size: 1.5rem` (menší než hero), `border-left: 3px solid var(--color-gold-500)`, `padding-left: 1rem` — odpovídá existujícímu patternu v projektu (viz `app.css` řádek 303).
- Mezera mezi subsekcemi: `margin-top: 2.5rem`.

### 4.7 Postranní meta — `portfolio-detail-meta`

- Vizuálně karta: `background: var(--bg-card)`, `border: 1px solid var(--border-subtle)`, `border-radius: 1rem`, `padding: 1.5rem`.
- Definiční seznam `<dl>`:
  ```html
  <dl class="portfolio-detail-meta__list">
      <dt>Klient</dt>           <dd>ACME s.r.o.</dd>
      <dt>Rok</dt>              <dd>2025</dd>
      <dt>Trvání</dt>            <dd>3 měsíce</dd>
      <dt>Kategorie</dt>         <dd>Webová aplikace</dd>
      <dt>Živý web</dt>          <dd><a href="…" target="_blank" rel="noopener">acme.cz ↗</a></dd>
  </dl>
  ```
- Položky bez hodnoty **vynechat** (pokud chybí `client_name`, řádek nerenderovat).
- `dt` malé caps (`text-transform: uppercase; letter-spacing: 0.05em; color: var(--color-muted); font-size: 0.75rem`).
- `dd` `color: var(--color-heading); font-size: 0.9375rem`.

### 4.8 Související projekty — `portfolio-related`

- Dotaz: 3 projekty stejné `category`, vyloučit aktuální projekt, řadit podle `featured DESC, sort_order ASC, year DESC`.
- Pokud je < 3 výsledků ve stejné kategorii, doplnit z ostatních (featured first).
- Nadpis sekce: `projects.detail.related_heading` = „Další projekty“ (nový i18n klíč).
- Použít stejnou komponentu `<x-portfolio.card>` jako na listingu (princip jednoho zdroje pravdy).

---

## 5. Komponenty k vytvoření

Všechny komponenty leží v `resources/views/components/portfolio/`. Engineer dostane jasné API.

### 5.1 `<x-portfolio.filter>`

```blade
<x-portfolio.filter
    :categories="['all', 'website', 'application', 'other']"
    :counts="['all' => 12, 'website' => 7, 'application' => 4, 'other' => 1]"
    target="portfolio-grid"
/>
```

| Prop         | Typ           | Povinné | Popis |
|--------------|---------------|---------|-------|
| `categories` | `array`       | ano     | Pořadí filtrů. Hodnota `all` zobrazí všechny karty. |
| `counts`     | `array`       | ano     | Mapa `kategorie => počet`. Klíč `all` = celkový počet. |
| `target`     | `string`      | ano     | DOM `id` gridu, který se má filtrovat (pro `aria-controls`). |

Slots: žádné. Texty taháme z `__('projects.filter_*')`.

### 5.2 `<x-portfolio.card>`

```blade
<x-portfolio.card :project="$portfolioProject" :locale="$locale" :eager="$loop->index < 3" />
```

| Prop      | Typ                     | Povinné | Popis |
|-----------|-------------------------|---------|-------|
| `project` | `PortfolioProject` model | ano     | Včetně `translation($locale)` a screenshots. |
| `locale`  | `string`                | ano     | Pro výběr překladu a slug routy. |
| `eager`   | `bool`                  | ne      | Pokud true → `loading="eager" fetchpriority="high"` na thumbnail. Default false. |

Vykreslí celou kartičku včetně `data-category`, `aria-label` a fallbacků pro chybějící thumbnail.

### 5.3 `<x-portfolio.grid>`

Tenký wrapper kolem foreach-cyklu, aby šablona stránky zůstala čitelná:

```blade
<x-portfolio.grid :projects="$portfolioProjects" :locale="$locale">
    <x-slot:empty>
        <p>{{ __('projects.empty') }}</p>
        <a href="{{ lroute('contact') }}" class="btn btn-primary">{{ __('projects.cta.primary') }}</a>
    </x-slot:empty>
</x-portfolio.grid>
```

| Prop       | Typ        | Povinné | Popis |
|------------|------------|---------|-------|
| `projects` | `Collection` | ano   | Kolekce `PortfolioProject`. |
| `locale`   | `string`   | ano     | Předáno do karty. |
| Slot `empty` | `html`   | ne      | Render při prázdné kolekci. Pokud chybí, fallback je text z `projects.empty`. |

### 5.4 `<x-portfolio.detail-hero>`

```blade
<x-portfolio.detail-hero :project="$project" :translation="$translation" />
```

Renderuje zpět-link, meta řádek, H1, podtitulek a akční tlačítka.

| Prop          | Typ                           | Povinné | Popis |
|---------------|-------------------------------|---------|-------|
| `project`     | `PortfolioProject`            | ano     | |
| `translation` | `PortfolioProjectTranslation` | ano     | |

Logika tlačítek: `live_url` přítomen → `btn-primary` „Navštívit web“ + `btn-secondary` „Domluvit konzultaci“. Bez `live_url` → jen jediné `btn-primary` „Domluvit konzultaci“.

### 5.5 `<x-portfolio.detail-gallery>`

```blade
<x-portfolio.detail-gallery :screenshots="$project->screenshots" />
```

Sám si vytáhne hero (první `type=hero` nebo fallback) a zbytek vyrenderuje jako 2sloupcovou galerii. Lightbox gallery name = `portfolio-screenshots`.

### 5.6 `<x-portfolio.detail-meta>`

```blade
<x-portfolio.detail-meta :project="$project" />
```

Render `<dl>` postranního panelu. Sám si přeskakuje prázdné položky a sám si formátuje `live_url` (zobrazí jen host).

### 5.7 `<x-portfolio.detail-body>`

```blade
<x-portfolio.detail-body :translation="$translation" />
```

Renderuje `challenge` / `solution` / `result` sekce, vynechává prázdné. Sloupcový grid s `<x-portfolio.detail-meta>` řeší rodičovská šablona, ne tato komponenta — `detail-body` plní jen levý sloupec.

### 5.8 Co NEDĚLAT v komponentách

- **Žádný state v PHP.** Filtr je čistě klientský JS, komponenta jen vykreslí markup.
- **Žádné inline styly.** Vše skrz třídy v `app.css`.
- **Žádný `<style>` blok v Blade souborech.** CSS patří do `resources/css/app.css`.
- Komponenta nezná routu — odkazy generuje přes `lroute('projects')` a slug z modelu.

---

## 6. Vizuální vodítka

### 6.1 Barvy (token → použití)

| Token                   | Použití v portfoliu                                      |
|-------------------------|----------------------------------------------------------|
| `--color-gold-500`      | Aktivní filtr, focus outline, akcent meta řádku, levý border H2 |
| `--color-gold-600`      | Hover na primárním tlačítku (existuje)                   |
| `--color-gold-200`      | Sekundární akcent, gradient stop (pokud nutno)           |
| `--color-ink-950`       | Text na zlatém pozadí (aktivní filtr, primary btn)       |
| `--color-heading`       | Nadpisy H1/H2/H3 v kartách, hodnoty v `<dd>`             |
| `--color-body`          | Tagline kartičky, copy v case study                      |
| `--color-muted`         | Caps v meta, počet projektů u filtru, `<dt>` v meta panelu |
| `--bg-card`             | Pozadí karty, meta panelu                                |
| `--bg-card-hover`       | Hover stav karty                                         |
| `--border-subtle`       | Default border karty, filtru, meta panelu                |
| `--border-gold`         | Hover border karty, focus karty, aktivní stav            |

**Zákaz:** zavádět nové hex hodnoty. Pokud něco chybí, eskalovat do designu, ne hardcodovat.

### 6.2 Typografie

- Font: existující stack (Inter), žádný nový import.
- H1 detail: shodné s `page-hero h1` (existuje).
- H2 sekce v case study: `font-size: 1.5rem`, `font-weight: 700`, `border-left: 3px solid var(--color-gold-500)`, `padding-left: 1rem`.
- Karta `__title`: `font-size: 1.25rem`, `font-weight: 600`, `line-height: 1.35`.
- Karta `__tagline`: `font-size: 0.9375rem`, `line-height: 1.55`, `color: var(--color-body)`, max 3 řádky (`display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;`).
- Meta caps (karta i detail): `font-size: 0.75rem`, `text-transform: uppercase`, `letter-spacing: 0.05em`, `color: var(--color-gold-500)` (na kartě) nebo `var(--color-muted)` (v detailu meta).

### 6.3 Spacing scale

Držet se patternu, který je v projektu zavedený (Tailwind v4 default + existující `.section-wrapper`):
- mezi sekcemi `section-wrapper` (existuje, beze změny)
- uvnitř karty: 0.5 / 0.75 / 1 / 1.25 / 1.5 rem
- gap gridu: 1 / 1.5 / 1.75 rem podle breakpointu

### 6.4 Zaoblení a stíny

- Karty: `border-radius: 1rem` (sjednoceno se snapshotty).
- Tlačítka filtrů: `border-radius: 999px` (pill).
- Obrázky uvnitř karet: `border-radius: 0` (karta sama je obalí přes `overflow: hidden`).
- Galerie screenshotů (samostatné, ne v kartě): `border-radius: 0.75rem`.
- Meta panel detailu: `border-radius: 1rem`.
- Stíny **nepřidávat nové**. Jen existující `box-shadow: 0 16px 56px rgba(0, 0, 0, 0.55), 0 0 0 1px var(--border-gold)` při focusu/hoveru karty (volitelně, jinak stačí border + translate).

### 6.5 Animace — `data-reveal`

- Použít stávající `data-reveal` na `[portfolio-filter]`, `[portfolio-grid]`, `[portfolio-detail-gallery]`, `[portfolio-detail-body]`, `[portfolio-related]`.
- `data-reveal-group` na `.portfolio-grid` (stagger animace u kartiček, identický pattern jako `.projects-grid`).
- Žádné nové `@keyframes` ani `transition` mimo:
  - `transform: scale(1.04)` na thumbnailu při hover karty (`transition: transform 0.4s ease`).
  - `transform: translateY(-2px)` na kartě při hover (`transition: transform 0.25s ease, border-color 0.25s, background-color 0.25s`).
- `prefers-reduced-motion: reduce` musí všechny tyto transformace vypnout — globální pravidlo v `app.css` už existuje, ověřit a doplnit pokud chybí.

---

## 7. Mobile-first poznámky

### 7.1 Filtr na mobilu

- Horizontální scroll (`overflow-x: auto`), `scroll-snap-type: x mandatory`, položky `scroll-snap-align: start`.
- `padding-inline: 1rem` na rodiči, aby první/poslední tlačítko nelepilo k hraně.
- Skrýt scrollbar (`scrollbar-width: none; &::-webkit-scrollbar { display: none; }`).
- Počet projektů přesunout pod filtry, ne vedle (vizuálně by se pral o místo s pill tlačítky).
- Nepoužívat dropdown — uživatel musí na první pohled vidět, jaké kategorie existují.

### 7.2 Touch targets

- Všechny interaktivní prvky ≥ 44 × 44 px (filtry, CTA šipka v kartě, odkaz „Navštívit web“ v detailu).
- Spacing mezi pill tlačítky filtru ≥ 0.5 rem (palec se trefí).
- Karta jako celek je klikací plocha (komfortní touch target i s krátkým názvem).

### 7.3 Lazy loading obrázků

- **Listing:** první 3 thumbnaily `loading="eager"`, zbytek `loading="lazy"`. Na 1. featured navíc `fetchpriority="high"` (LCP kandidát).
- **Detail:** hero screenshot `loading="eager" fetchpriority="high"`, zbytek galerie `loading="lazy"`.
- Komponenta `<x-responsive-image>` už podporuje oboje — Engineer předá přes prop.

### 7.4 Kritické pro CLS

- Karty mají pevný `aspect-ratio: 16/10` u thumb wrapperu → žádné posuny při loadu obrázků.
- Galerie detailu má `aspect-ratio: 16/9` u hero, `aspect-ratio: 4/3` u gallery položek (sjednotí proporce i pro různě velké screenshoty).

### 7.5 Viewport breakpointy (sjednoceno s Tailwindem)

- Mobile: `≤ 640px`
- Tablet: `641px–1023px`
- Desktop: `≥ 1024px`
- Wide: `≥ 1440px` (jen pro úpravu max-width, žádné nové sloupce)

---

## 8. i18n — nové klíče k doplnění

Engineer a/nebo Content Writer doplní do `lang/{cs,en,de}/projects.php`:

```php
'count' => [
    'one'   => ':count projekt',
    'few'   => ':count projekty',
    'many'  => ':count projektů',
    'other' => ':count projektů',
],

'detail' => [
    'challenge'        => 'Výzva',
    'solution'         => 'Řešení',
    'result'           => 'Výsledek',
    'live_url'         => 'Živý web',
    'visit_live'       => 'Navštívit živý web',
    'related_heading'  => 'Další projekty',
    'meta' => [
        'client'    => 'Klient',
        'year'      => 'Rok',
        'duration'  => 'Trvání',
        'category'  => 'Kategorie',
        'live_url'  => 'Živý web',
    ],
    'category_label' => [
        'website'     => 'Web',
        'application' => 'Aplikace',
        'other'       => 'Ostatní',
    ],
],
```

Hodnoty výše jsou návrh — finální texty schvaluje Content Writer (parent OND-59 plán).

---

## 9. Akceptační kritéria (pro Engineera ve fázi 2)

- [ ] `/projekty` zobrazuje listing **nad** sekcemi snapshot/fit/why_me.
- [ ] Filtry pracují čistě client-side, výchozí stav `Vše`, počet projektů se aktualizuje v reálném čase.
- [ ] Karta projektu se chová podle § 2.4 (default/hover/focus/loading/empty).
- [ ] Mobile (≤ 640 px) má 1sloupcový grid a horizontální scroll filtrů.
- [ ] Tablet má 2 sloupce, desktop 3.
- [ ] Detail `/projekty/{slug}` má pořadí sekcí z § 4.1.
- [ ] Pokud projekt nemá `challenge`/`solution`/`result`, sekce se vůbec nerenderuje (žádné prázdné nadpisy).
- [ ] Pokud projekt nemá `live_url`, hero akce ukazuje jen CTA na konzultaci.
- [ ] Galerie respektuje `type=hero` vs `type=gallery`, lightbox funguje (existující řešení).
- [ ] Sekce „Další projekty“ vrací 3 položky stejné kategorie, doplněné z ostatních pokud nestačí.
- [ ] Žádné nové barvy mimo design tokeny.
- [ ] Žádný redesign navbaru, footeru ani existujících komponent (`<x-icon.*>`, `<x-responsive-image>`).
- [ ] `prefers-reduced-motion` vypne hover/scale/translate transformace.
- [ ] Lighthouse mobile ≥ 90 v kategorii Performance, ≥ 95 v kategorii Accessibility (to ověří QA).

---

## 10. Co designer záměrně NEDĚLÁ (nepřípustné)

- ❌ Žádný redesign **navbaru** ani **footeru** — mimo scope OND-59.
- ❌ Žádné **nové barvy** mimo `@theme` tokeny.
- ❌ Žádné **nové fonty** ani úpravy typografického měřítka mimo karty.
- ❌ Žádný **server-side filtr** (query string, route parametry) — viz § 3.2.
- ❌ Žádné **vlastní lightbox** řešení — používáme existující `<x-responsive-image lightbox-gallery="…">`.
- ❌ Žádná **drag-to-reorder** v UI — řazení řeší admin přes `sort_order`.
- ❌ Žádné **inline editování** kategorií či nadpisů — frontend je read-only.
- ❌ Žádný **infinite scroll** ani **paginace** v první verzi — < 50 projektů → vše najednou.
- ❌ Žádné **modální okno detailu projektu** — detail je samostatná routa kvůli SEO a sdílení odkazů.
- ❌ Žádný **placeholder lorem ipsum** v komponentách (jak ukazuje § 5, vše je strukturované, copy doplní Content Writer).

---

## 11. Handoff

| Role           | Co dostává                                             |
|----------------|--------------------------------------------------------|
| Engineer       | Tento dokument + DB schéma + i18n klíče (návrh § 8). Implementuje komponenty, šablony a CSS. |
| Content Writer | i18n klíče k doplnění (§ 8) + pokyn, že tagline na kartě má 110 znaků a `summary` má 500. |
| QA             | Akceptační kritéria § 9 + § 10 (negativní). |
| Designer       | Design QA po implementaci — kontrola spacing, hover stavy, mobile filtru, focus outline, fallbacků. |

**Další krok:** Engineer otevře implementační child issue z OND-59 a podle této specifikace začne fáze 2.
