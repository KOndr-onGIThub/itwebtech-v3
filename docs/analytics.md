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

## Reference

- Spec: [OND-122](/OND/issues/OND-122), plán [OND-99 §9](/OND/issues/OND-99#document-plan)
- Parent: [OND-98](/OND/issues/OND-98)
- Cookie consent: [OND-125](/OND/issues/OND-125)
- GA Consent Mode v2: <https://developers.google.com/tag-platform/security/concepts/consent-mode>
- Microsoft Clarity consent API: <https://learn.microsoft.com/en-us/clarity/setup-and-installation/cookie-consent>
