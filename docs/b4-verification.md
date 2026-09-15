# B4 — Perf / SEO / A11y verifikace (OND-132)

Tento dokument je verifikační handoff k balíku B2 (sitewide redesign) podle
[plánu OND-128 §5 + §6 + §9](/OND/issues/OND-128#document-plan). Obsahuje:

1. **Verifikační harness** (`scripts/b4-verify.sh`) — kompletní suite proti
   běžícímu staging / preview / produkčnímu URL.
2. **Statickou audit část** kterou lze validovat čistě z kódu (bez headless
   prohlížeče) — sem patří JSON-LD struktura, hreflang matrix, canonical,
   Consent Mode v2 default, `<html lang>` a analytics page_lang dimension.
3. **Definici „done"** — průchozí stav harness reportu = OK do staging merge.

## Verifikační scope (per OND-132)

| Co | Cíl | Tooling |
|---|---|---|
| Lighthouse mobile | ≥95 perf / a11y / best-practices / SEO | `lighthouse --form-factor=mobile` |
| Core Web Vitals | LCP ≤2500 ms / INP ≤200 ms / CLS ≤0.05 | Lighthouse audits |
| axe-core CLI | 0 violations | `@axe-core/cli` |
| JSON-LD | FAQPage, LocalBusiness, Person, Article (+ Organization, BreadcrumbList, Service) validní | `b4-static-check.mjs` + <https://validator.schema.org> |
| Hreflang matrix | 3 jazyky × 9 stránek, žádný cross-lang 301 | `b4-static-check.mjs` (HEAD redirect chain + cross-page kontrola) |
| GA4 5 nových eventů + 2 dimenze | viz §GA4 níže | GA4 DebugView (manuální) |
| Consent Mode v2 | default `denied` napříč skripty | `b4-static-check.mjs` (snippet check) |

## Page set (9 URL × 3 locale = 27 verifikačních cílů)

Per `config/slugs.php` + dynamické detail routy:

| Label | cs | en | de |
|---|---|---|---|
| home | `/` | `/en/` | `/de/` |
| contact | `/kontakt` | `/en/contact` | `/de/kontakt` |
| price | `/cenik` | `/en/price` | `/de/preisliste` |
| privacy | `/zasady-ochrany-osobnich-udaju` | `/en/privacy-policy` | `/de/datenschutz` |
| projects | `/projekty` | `/en/projects` | `/de/projekte` |
| project | `/projekty/{slug}` | `/en/projects/{slug}` | `/de/projekte/{slug}` |
| blog | `/jak-na-to` | `/en/blog` | `/de/blog` |
| article | `/jak-na-to/{slug}` | `/en/blog/{slug}` | `/de/blog/{slug}` |
| cookies | `/cookies` | `/cookies` | `/cookies` |

`/cookies` je per `routes/web.php` mimo localized group a nemá hreflang
alternativy (jediná URL napříč jazyky). Static check ji vyhodnocuje
benevolentně.

Source-of-truth: `scripts/b4-pages.txt`.

## Jak pustit kompletní suite

### Předpoklady na hostu

- Node 20+ (Lighthouse 12 vyžaduje).
- Chrome / Chromium binary v PATH **nebo** `CHROME_PATH` env.
  - V Paperclip workspace defaultně `~/.cache/ms-playwright/chromium-*/chrome*`
    — `b4-verify.sh` ho auto-detectuje, viz hlavička skriptu.
  - Lokálně (macOS / Ubuntu) stačí běžný `chromium` / `google-chrome`.
- `npx --yes lighthouse` + `npx --yes @axe-core/cli` (skripty si je nahájí
  on-the-fly; pokud chceš offline, nainstaluj globálně `npm i -g lighthouse
  @axe-core/cli`).

### Run

```bash
# Preview (per docs/deploy-preview.md):
./scripts/b4-verify.sh https://preview-sitewide-redesign.itwebtech.cz

# Staging:
./scripts/b4-verify.sh https://staging.itwebtech.cz

# Po deployi na prod (smoke):
./scripts/b4-verify.sh https://itwebtech.cz \
    --project hellsearch \
    --article jak-vybrat-cms
```

Project / article slugy musí existovat v dané DB (Filament admin → Portfolio
/ Articles). Default `hellsearch` + `jak-vybrat-cms` jsou stabilní záznamy
z `database/seeders`. Bez existujícího slugu vrátí route 404 a všechny
audity dané stránky se v summary objeví jako červené.

### Output

```
storage/b4-static-report.json              ← JSON, machine-readable
storage/b4/lighthouse/<locale>-<label>.json
storage/b4/axe/<locale>-<label>.json
storage/b4/summary.md                      ← Markdown agregát, ten posílej Ondřejovi
```

`summary.md` má per-URL tabulku `Perf / A11y / BP / SEO / LCP / CLS / INP /
axe violations` se ✅ / ❌ podle prahů z OND-132 §DoD. Pokud cokoli červené,
jdi do detail JSONu a vyber failed audit.

### Jen statický kus (rychlejší než plný Lighthouse run)

Když potřebuješ jen ověřit JSON-LD / hreflang / canonical bez Chrome:

```bash
node scripts/b4-static-check.mjs \
    --base https://preview-sitewide-redesign.itwebtech.cz \
    --project hellsearch --article jak-vybrat-cms \
    --out storage/b4-static-report.json
```

Exit code 0 = vše OK; jinak počet failed problémů.

## Statický audit B2 kódu (stav k 2026-05-14)

Toto je verifikace **na úrovni source code** — ne runtime. Runtime audit
(Lighthouse + axe + GA4 DebugView) vyžaduje běžící staging / preview URL,
viz §Blokery.

### JSON-LD coverage

| Schema | Kde | Status |
|---|---|---|
| `LocalBusiness` | `layouts/app.blade.php` (sitewide) | ✅ |
| `Organization` | `layouts/app.blade.php` (sitewide) | ✅ |
| `Person` (autor / E-E-A-T) | `layouts/app.blade.php` (sitewide) | ✅ |
| `FAQPage` | `pages/home.blade.php` (mainEntity ze stejných lang klíčů jako accordion) | ✅ |
| `Article` | `pages/article.blade.php` (`@push('jsonld')`, PHP blok) | ✅ |
| `BreadcrumbList` | price, blog, article, contact, projects, project | ✅ |
| `Service` (per tier) | `pages/price.blade.php` (3 tiers × locale-aware priceCurrency) | ✅ |

`@context` klíč je všude řešený přes `@php` blok + `json_encode()` (viz
memory `feedback_jsonld_context_blade_trap.md`) nebo escaped `@@context`
v statickém JSON v layoutu — Laravel 12 Blade `CompilesContexts` direktivu
nepřepisuje.

### Hreflang + canonical

- Layout vyrenderuje `cs / en / de / x-default` z `lroute($currentPage, $locale)`
  helper-u (`app/helpers.php`). Pokud route name v daném locale neexistuje,
  Laravel hodí RouteNotFoundException — všechny statické stránky mají v
  každém locale platnou routu (`config/slugs.php`), takže to projde.
- Dynamické stránky (`project`, `article`) předávají vlastní `$hreflangs`
  pole z `PageController` (`PageController.php:99` pro project,
  `PageController.php:175` pro article) — slug se v každém jazyce řeší
  zvlášť (article má `ArticleSlug` pivot s per-locale slugy, project má
  jazyk-neutrální slug).
- `<link rel="canonical">` = `url()->current()`.
- `x-default` = cs URL (default locale).

Cross-language 301 audit ověřuje runtime část harness přes `redirect: follow`
+ porovnání `finalUrl` s vyžádaným URL.

### Consent Mode v2

`resources/views/partials/analytics.blade.php:70-76` posílá
`gtag('consent','default', { ... })` s **všemi 4 v2 storage typy denied**:

- `analytics_storage: denied`
- `ad_storage: denied`
- `ad_user_data: denied` ← v2 only
- `ad_personalization: denied` ← v2 only
- `wait_for_update: 500`

Po souhlasu `resources/js/cookies.js` (`bootGA4`) přepne `analytics_storage`
na `granted`; reklamní storage zůstává `denied` (web nemá ad ekosystém).
Statický check (`hasConsentDefaultV2`) ověřuje přítomnost obou v2 polí.

### Custom dimensions

`resources/views/partials/analytics.blade.php:91` exposuje `pageLang` do
`window.__analyticsConfig.pageLang`. `resources/js/analytics.js:56-61`
(`globalDims()`) přidává `page_lang` ke každému eventu.

`pricing_tier_shown` (25/55/95) se posílá z `pages/price.blade.php:96-121`
v `data-analytics-props='{"pricing_tier_shown":"..."}'`.

## GA4 event spec divergence (FINDING)

OND-132 description §6 vyžaduje **5 nových eventů** + **2 dimenze**:

| Spec (OND-132) | Implementace v B2 (staging) | Stav |
|---|---|---|
| `form_start` | — | ❌ není v kódu |
| `form_field_error` | — | ❌ není v kódu |
| `pricing_tier_click` | `pricing_tier_cta_primary_click` (mapuje se canonical → `cta_primary_click`) | ⚠️ jiný název |
| `project_card_open` | `project_card_click` (mapuje se canonical → `case_study_view`) | ⚠️ jiný název |
| `inarticle_cta_click` | — | ❌ není v kódu |
| dim `lead_source_ref` | — | ❌ není v kódu |
| dim `pricing_tier` | `pricing_tier_shown` (price page) | ⚠️ jiný název |

Pravděpodobně jde o divergenci mezi:

- **plánem OND-128 §5/§6** (referenční spec, kde Jack/Ondřej zvolili tyto
  konkrétní jména), a
- **OND-137 P4 §6 canonical event taxonomy** (5 normalizovaných eventů
  `cta_primary_click` / `cta_secondary_click` / `form_submit` /
  `pricing_tier_view` / `case_study_view`), které B2 reálně implementoval.

Před B4 sign-off potřebujeme od Ondřeje rozhodnutí:

- **A) přejmenovat** kanonickou množinu na OND-132 spec (5 nových eventů +
  rename `pricing_tier_shown` → `pricing_tier`, doplnit `lead_source_ref`),
  **nebo**
- **B) potvrdit** stávající canonical 5 jako finální taxonomy a v OND-132
  upravit DoD (event jména) na to, co B2 reálně doručil.

Řešení té volby leží mimo Engineer scope — viz issue comment na OND-132 s
prosbou o direktivu. Engineer doúčastní vybraný směr v samostatném PR.

## Blokery runtime verifikace

Tento heartbeat **nemohl** dotáhnout runtime část DoD (Lighthouse / axe /
GA4 DebugView), protože:

1. `https://staging.itwebtech.cz` — TLS handshake stojí, není odpověď do
   timeoutu (ověřeno `curl -m 30`).
2. `https://preview-sitewide-redesign.itwebtech.cz` — stejné jako staging.
3. `https://itwebtech.cz` — vrací **starou** verzi (před P2 redesignem,
   schema `ProfessionalService` místo `LocalBusiness`, žádné FAQPage,
   žádný hreflang). Lighthouse run proti prod by neměřil B2.
4. Lokální `php artisan serve` — workspace nemá PHP runtime, jen Node +
   Vite build artefakty.

Harness je připravený a otestovaný proti prod URL (smoke run 27/27 URL,
korektně reportuje očekávané fail-y staré verze — sanity OK).

**Unblock akce (mimo Engineer scope):**

- Ops / Ondřej: zvednout staging / preview deploy s aktuálním HEAD `staging`
  (PR #90 + dalším mergem) a sdělit jeho URL.
- Po unblocku: rerun `./scripts/b4-verify.sh <staging-url>` → `storage/b4/summary.md`
  jde do issue OND-132 jako důkaz DoD.

## Reference

- Spec: [OND-132](/OND/issues/OND-132), [OND-128 plán §5/§6/§9](/OND/issues/OND-128#document-plan)
- Implementace dovedená: [OND-137 P4](/OND/issues/OND-137)
- Analytics infra: [docs/analytics.md](./analytics.md)
- Deploy preview procedure: [docs/deploy-preview.md](./deploy-preview.md)
- Schema.org validator: <https://validator.schema.org/>
- Rich Results test: <https://search.google.com/test/rich-results>
- Consent Mode v2 docs: <https://developers.google.com/tag-platform/security/concepts/consent-mode>
