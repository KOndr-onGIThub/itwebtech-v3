# Měření úspěchu homepage (OND-122)

Tento dokument je handoff pro Ondřeje. Popisuje, jaký měřicí stack je
napojený na homepage, jak ho zapnout/vypnout a kde najít data.

## Co měříme

11 mikrokonverzí per plán §9.2 ([OND-99 plan](/OND/issues/OND-99#document-plan)):

| Event | Spouštěč | Kde v kódu |
|---|---|---|
| `hero_cta_primary_click` | klik na primární CTA v hero („Získat cenovou nabídku") | `resources/views/pages/home.blade.php` (`data-analytics`) |
| `hero_cta_secondary_click` | klik na sekundární CTA (Reservanto widget) v hero | `resources/views/components/booking/reservanto-widget.blade.php` (přes `analyticsEvent` prop) |
| `sticky_cta_click` | klik na sticky / mobile bottom bar | `resources/views/layouts/app.blade.php` (mobile bottom bar) |
| `phone_click` | klik na `tel:` link | `resources/views/components/phone-cta.blade.php` |
| `price_anchor_view` | scroll do sekce „Investice do webu" (intersection ≥ 40 %) | `data-analytics-view` na `#section-price` |
| `project_card_click` | klik na kartu v „Realizované projekty" | `home-projects-card__visual` + `__cta` v `home.blade.php` |
| `testimonial_view` | scroll do sekce testimonials (intersection ≥ 40 %) | `data-analytics-view` na `#section-testimonials` |
| `faq_open` | rozbalení FAQ položky (event s `q_id` jako prop) | listener v `resources/js/analytics.js`, hooks na `.faq-item details` |
| `inline_form_submit_attempt` | klik na submit / Enter v inline formuláři | `resources/views/partials/home-inline-form.blade.php` |
| `inline_form_submit_success` | server-side success (200) z `/poptavka` | dispatch z `home.blade.php` po `session('home_lead_success')` |
| `calendly_booking_complete` | webhook z Reservanto | **out-of-scope** — Reservanto widget je popup, nemáme přímý callback; viz „Známé gapy" |

Eventy se odesílají z `resources/js/analytics.js` přes:

- `data-analytics="event_name"` → click delegate (bubble-uje, takže funguje i u widgetů, kde se DOM injektuje JS-em).
- `data-analytics-view="event_name"` → `IntersectionObserver` (jednou, ≥ 40 % viditelnost).
- `data-analytics-props='{"k":"v"}'` → volitelný JSON s props (např. `slug` u project_card).

Plausible volání: `plausible(event, { props: {...} })`. GA4 volání: `gtag('event', event, props)`. Oboje, pokud jsou nakonfigurovaná, se posílají paralelně.

## Konfigurace

Vše řeší `config/site.php` → `analytics` (čte env):

```env
# Master vypínač — na stagingu false, na produkci true
ANALYTICS_ENABLED=true

# Plausible (preferované, GDPR-friendly, bez cookie banneru)
PLAUSIBLE_DOMAIN=itwebtech.cz
# Volitelně přepsat URL skriptu:
# PLAUSIBLE_SCRIPT_URL=https://plausible.io/js/script.tagged-events.js

# GA4 (volitelně paralelně k Plausible)
GA4_MEASUREMENT_ID=G-XXXXXXXX
# Pozn.: fallback na legacy GOOGLE_ANALYTICS_ID, pokud GA4_MEASUREMENT_ID prázdné.

# Microsoft Clarity — heatmapy + session recordings
CLARITY_PROJECT_ID=xxxxxxxxx
```

Nech `ANALYTICS_ENABLED=false` všude mimo produkci (jinak testovací kliknutí
zkreslí data). Master vypínač skryje **všechny** skripty (Plausible, GA4 i Clarity).

## Setup checklist (produkce)

1. **Plausible** — založit účet na <https://plausible.io>, přidat doménu `itwebtech.cz`, do produkčního env nastavit `PLAUSIBLE_DOMAIN=itwebtech.cz`.
2. **Microsoft Clarity** — založit projekt na <https://clarity.microsoft.com>, vzít Project ID (Settings → Setup → Tracking code), nastavit `CLARITY_PROJECT_ID=xxxxxx`.
3. **GA4** (volitelně) — pokud Ondřej chce paralelní měření, založit GA4 property, `GA4_MEASUREMENT_ID=G-...`.
4. `ANALYTICS_ENABLED=true` jen na produkci (Coolify / Forge env).
5. Po nasazení smoke test:
   - otevřít homepage v inkognito;
   - kliknout primární CTA v hero, na tel: link, vyplnit a odeslat inline formulář;
   - v Plausible Dashboard → Goals (nebo GA4 → Reports → Events) by se měly do 30 minut objevit `hero_cta_primary_click`, `phone_click`, `inline_form_submit_success`;
   - v Clarity → Recordings první session do ~5 minut.

## Kde Ondřej najde data

- **Plausible dashboard** — <https://plausible.io/itwebtech.cz>. Vlevo nahoře přepínač datumu (last 30d / 7d / today). Sekce „Goals" ukazuje konverzní eventy.
- **Microsoft Clarity dashboard** — <https://clarity.microsoft.com>, vybrat projekt „itwebtech.cz" (nebo jak nazvete). Heatmaps → vyber URL, Session Recordings → seznam relací, Rage clicks → friction body.
- **GA4** (pokud zapnuté) — <https://analytics.google.com>, property „itwebtech.cz". Reports → Engagement → Events.

## Známé gapy / out-of-scope

- **`calendly_booking_complete`** — Reservanto widget otevírá iframe popup;
  nemáme přímý webhook ani callback po dokončené rezervaci. Cesty řešení
  (mimo OND-122):
  - zjistit, jestli Reservanto umožňuje webhook na nový lead → server-side endpoint, který trigne Plausible Custom Events API;
  - alternativně po dokončené rezervaci přesměrovat na náš thanks-page s UTM parametry a tam fírnout event;
  - zatím měříme jen `hero_cta_secondary_click` jako proxy pro „někdo otevřel booking flow".

- **`faq_open`** — FAQ sekce na homepage zatím není v `staging`. Až bude
  OND-121 (Sprint 3 P2) smerge-ovaná, automaticky se nahodí přes listener
  v `analytics.js` (hledá `.faq-item details` toggle, posílá `q_id` jako prop
  z `data-q-id` nebo `id` na položce).

- **A/B testy** — out-of-scope (per plán §9.4 až po 4–8 týdnech stabilizace).

- **Server-side tracking / anti-adblock** — nice to have, ne teď.

## Lokální dev a smoke test

Pro debug nastav v `resources/js/analytics.js`:

```js
const DEBUG = true;
```

a v dev env:

```env
ANALYTICS_ENABLED=true
PLAUSIBLE_DOMAIN=  # nech prázdné, jen GA4 / Clarity vypneme níže
GA4_MEASUREMENT_ID=
CLARITY_PROJECT_ID=
```

V devtools console uvidíš `[analytics] event_name {...}` u každého kliknutí — bez odesílání nikam (žádný provider není nastaven, dispatcher to ticho swallowne).

## Cookie consent (OND-125)

GA4 a Microsoft Clarity jsou od OND-125 **gated cookie consent bannerem** —
bez explicitního souhlasu se jejich skripty nenahrají (požadavek ePrivacy /
GDPR v EU). Plausible je cookieless, banner se ho netýká.

### Architektura

| Vrstva | Soubor | Co dělá |
|---|---|---|
| Config (server → window) | `resources/views/partials/analytics.blade.php` | Vykresluje `window.itwebtechAnalyticsConfig = { measurementId, clarityId }`, pre-flush queue (`dataLayer`, `gtag`, `clarity`) a `gtag('consent','default', …)` se vším `denied`. **NEinjektuje** `gtag/js` ani `clarity.ms/tag`. |
| Modal markup | `resources/views/partials/cookies-modal.blade.php` | Statický HTML modal, includuje se z `layouts/app.blade.php` na konci body. |
| CSS | `resources/css/components/cookies.css` | Centrovaný modal, overlay s backdrop-blur, fade/scale animace. |
| Logika | `resources/js/cookies.js` | IIFE, čte config, načítá/píše `localStorage`, řídí viditelnost modalu, boot GA4/Clarity po souhlasu, kill-switch + cookie purge při odmítnutí. |
| Cookie policy | `resources/views/pages/cookies.blade.php` + route `/cookies` | Text v češtině, tlačítko „Odvolat souhlas“ volá `window.ItwebtechAnalytics.revokeConsent()`. |

### Flow

1. **Žádný uložený consent** → ~0,8 s po `DOMContentLoaded` vyskočí modal s overlay + backdrop blur. Tlačítka jsou ve sloupci („Přijmout vše“ primární, „Odmítnout“ jako subtilní textový link).
2. **„Přijmout vše“** → `localStorage` (`itwebtech_cookies`, TTL 365 d), `bootGA4()` (Consent Mode v2: `analytics_storage` přepne na `granted`, reklamní storage zůstává `denied`), `bootClarity()` (`clarity('consent', true)`). Skripty se vloží dynamicky.
3. **„Odmítnout“** → `localStorage` (TTL 180 d), `window['ga-disable-G-XXX'] = true`, vyprázdnění `dataLayer`, aktivní vymazání cookies `_ga*`, `_gid`, `_gat*`, `_clck`, `_clsk`, `CLID`, `MUID`, `ANONCHK` napříč doménami/subdoménami (host + root domain + `.host` + `.root`).
4. **Křížek / klik mimo modal / Esc** → jen schová, **žádný consent se neukládá**. Při příští návštěvě se modal znovu objeví.

### Public API

```js
window.ItwebtechAnalytics = {
    acceptConsent(),   // udělí souhlas + boot GA4/Clarity
    rejectConsent(),   // odmítnutí + cookie purge + kill switch
    revokeConsent(),   // smaže localStorage + cookies (modal vyskočí po reloadu)
    hasConsent(),      // → null | 'accepted' | 'rejected'
    measurementId,     // validovaný (regex ^G-[A-Z0-9]+$), jinak null
    clarityId,
};
```

### Stavový model

`localStorage.itwebtech_cookies` JSON:

```json
{ "status": "accepted" | "rejected", "timestamp": 1731494400000, "expiresAt": 1763030400000, "version": 1 }
```

`version` umožňuje invalidaci starých souhlasů (zvyš `STORAGE_VERSION` v `cookies.js`).

### Jak revokovat

- Na `/cookies` kliknout „Odvolat souhlas a smazat cookies“ → JS smaže `localStorage` + cookies + reload → banner se znovu objeví.
- Z DevTools: `window.ItwebtechAnalytics.revokeConsent()` + reload.

### Smoke test (po deployi)

1. Inkognito → otevři homepage → po ~0,8 s se objeví modal.
2. Network tab: bez consentu žádný request na `googletagmanager.com/gtag/js` ani `clarity.ms/tag`. Plausible (pokud nakonfigurované) jede normálně.
3. Klik „Přijmout vše“ → modal fade-out, v Network tab requesty na GA4 + Clarity, do 30 s nový user v GA4 Realtime.
4. Inkognito (čistá session) → klik „Odmítnout“ → modal zmizí, `document.cookie` neobsahuje `_ga*`/`_clck`/`MUID`, reload → modal se neukáže.

## Canonical eventy (OND-137 P4 §6 — Jack §6)

P2/P3/P4 redesign zavedl 5 normalizovaných eventů, které jsou stabilním
základem pro GA4 goals + CR reporty. Specific event taxonomy (z OND-122
výše) zůstává — `analytics.js` posílá VEDLE specific eventu i canonical
alias, pokud match-uje. V GA4 si Ondřej staví goals na canonical:

| Canonical event | Spec | Zdrojové specific eventy |
|---|---|---|
| `cta_primary_click` | hlavní CTA klik | `hero_cta_primary_click`, `final_cta_primary_click`, `final_cta_closing_primary_click`, `pricing_tier_cta_primary_click`, jakékoli `*_cta_*_primary_click` |
| `cta_secondary_click` | sekundární akce | `phone_click`, `sticky_cta_click`, `price_anchor_cta_click` *(matches `*_cta_*_secondary_click` patterns)* |
| `form_submit` | lead form success | `inline_form_submit_success`, `contact_form_submit_success`, `faq_form_submit_success`, jakékoli `*_form_submit_success` |
| `pricing_tier_view` | tier visible ≥40 % viewport | `pricing_tier_view` (na /cenik), `price_anchor_view` (homepage proxy) |
| `case_study_view` | otevření case study | `project_card_click` (intent), `case_study_view` (skutečné otevření hero na /projekty/{slug}) |

Mapování řeší `canonicalize()` v `resources/js/analytics.js`. Při změně
specific event jména stačí přidat regex/match tam — pages nemusí dvojitě
emit-ovat.

### Custom dimensions

Posílají se s **každým** eventem (GA4 event params + Plausible props):

| Dimension | Hodnota | Zdroj |
|---|---|---|
| `page_lang` | `cs` / `en` / `de` | `window.__analyticsConfig.pageLang` (set v `partials/analytics.blade.php` ze `app()->getLocale()`) |
| `pricing_tier_shown` | `25` / `55` / `95` | `data-analytics-props='{"pricing_tier_shown":"…"}'` na pricing-tier elementech (/cenik) |
| `specific_event` | původní specific event jméno | automaticky doplněno do canonical aliasu (debug + drill-down) |

V GA4 admin: Reports → Custom Definitions → Create custom dimension:
- `page_lang` — event-scoped, parameter `page_lang`
- `pricing_tier_shown` — event-scoped, parameter `pricing_tier_shown`

### Conversion goal

V GA4 admin → Configure → Events → `form_submit` → toggle „Mark as
conversion". Sekundární conversion: `cta_primary_click` pro mid-funnel
měření.

### Baseline (CR pre-launch)

Pre-launch CR baseline + post-launch comparison framework je v separátním
souboru: [`docs/analytics-baseline.md`](./analytics-baseline.md). Před
deployem P4 na produkci vyplnit pre-launch hodnoty z GA4 (last 30d).

## SEO infrastruktura (OND-137 P4 §SEO)

### Structured data (JSON-LD)

| Schema type | Kde | Soubor |
|---|---|---|
| `LocalBusiness` | každá stránka | `resources/views/layouts/app.blade.php` |
| `Organization` | každá stránka | `resources/views/layouts/app.blade.php` |
| `Service` (per tier) | /cenik | `resources/views/pages/price.blade.php` (`@push('jsonld')`) |
| `Article` | /jak-na-to/{slug} | `resources/views/pages/article.blade.php` |
| `BreadcrumbList` | /cenik, /kontakt, /projekty, /projekty/{slug}, /jak-na-to, /jak-na-to/{slug} | příslušné `pages/*.blade.php` |

Per-page JSON-LD se vkládá přes `@push('jsonld')` → `@stack('jsonld')` v
layout `<head>`. Pro validaci:
- <https://search.google.com/test/rich-results> (Google)
- <https://validator.schema.org> (Schema.org)

### Sitemap + robots

- `routes/web.php` → `GET /sitemap.xml` (`SitemapController` + `App\Services\SitemapGenerator`, cached 10 min).
- `routes/web.php` → `GET /robots.txt` (`RobotsController`, dynamicky odkazuje na sitemap na aktuální doméně — viz proč v komentáři controlleru).

### Hreflang + canonical

Hreflang tagy (`cs` / `en` / `de` / `x-default`) + `<link rel="canonical">`
jsou v `resources/views/layouts/app.blade.php`. Per-page override přes
section `hreflangs` proměnnou (např. pro article slug-history mapping).

## Reference

- Spec: [OND-122](/OND/issues/OND-122), plán [OND-99 §9](/OND/issues/OND-99#document-plan)
- P4 (canonical events + SEO + a11y + perf): [OND-137](/OND/issues/OND-137)
- Parent: [OND-98](/OND/issues/OND-98) (sprint 1), [OND-127](/OND/issues/OND-127) (sitewide redesign)
- Cookie consent: [OND-125](/OND/issues/OND-125)
- GA Consent Mode v2: <https://developers.google.com/tag-platform/security/concepts/consent-mode>
- Microsoft Clarity consent API: <https://learn.microsoft.com/en-us/clarity/setup-and-installation/cookie-consent>
