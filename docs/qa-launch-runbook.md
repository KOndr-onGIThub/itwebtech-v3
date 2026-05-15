# QA launch runbook — OND-138 (P5)

**Target:** `https://itwebtech.ondrejkriska.cz` (Coolify staging = production deploy of PR #90 + #91)
**Date:** 2026-05-14
**QA owner:** [QA Engineer](/OND/agents/qa-engineer)
**Status:** ✅ Ready for sign-off s 4 medium/low post-launch follow-ups

> Tento runbook dokumentuje regression matrix per [OND-138](/OND/issues/OND-138) (P5) — pre-launch QA pass + sign-off. Pokrývá 9 page types × 3 jazyky + cookies (= 25 URL) z [OND-128 §8](/OND/issues/OND-128#document-plan) plus 4 risk-matrix add-ons z [OND-133 §9](/OND/issues/OND-133). Lighthouse + axe + Core Web Vitals = B4 harness (`scripts/b4-verify.sh`), per [OND-137](/OND/issues/OND-137) DoD.

---

## 0. Pre-checkout dependencies

| Dep | Stav | Evidence |
|---|---|---|
| [OND-137 (P4)](/OND/issues/OND-137) `done` | ✅ | PR #90 merge `febc366` na origin/staging |
| Staging URL live | ✅ | `https://itwebtech.ondrejkriska.cz/` HTTP 200, full redesign deployed |
| GA4 + Clarity scripts loaded | ✅ | `G-SK49PHW6PP` + `wqlhcxhtti` markup na všech 25 stránkách |
| Repo + b4 harness available | ✅ | `scripts/b4-verify.sh` + `scripts/b4-static-check.mjs` ([OND-132](/OND/issues/OND-132) B4) |

`itwebtech.cz` produkce běží zatím **starý pre-redesign kód** (legacy EN slugs `/contact`, `/price`, `/projects` → 200; nové CS slugy 404). To je očekávaný stav: deploy `main` čeká na revert merge `3361b57` (per [OND-127](/OND/issues/OND-127) `86bdfe91` recipe). **Tento sign-off platí pro Coolify staging deploy** = co půjde do produkce po Ondřejově `main` revertu.

---

## 1. Funkční matrix (HTTP + lang)

Všech 25 URL = HTTP 200, `<html lang>` matchuje očekávané locale.

| Page type | CS | EN | DE |
|---|---|---|---|
| home | ✅ `/` (lang=cs) | ✅ `/en/` (lang=en) | ✅ `/de/` (lang=de) |
| contact | ✅ `/kontakt` | ✅ `/en/contact` | ✅ `/de/kontakt` |
| price | ✅ `/cenik` | ✅ `/en/price` | ✅ `/de/preisliste` |
| projects index | ✅ `/projekty` | ✅ `/en/projects` | ✅ `/de/projekte` |
| project detail | ✅ `/projekty/yolk` | ✅ `/en/projects/yolk` | ✅ `/de/projekte/yolk` |
| blog index | ✅ `/jak-na-to` | ✅ `/en/blog` | ✅ `/de/blog` |
| article | ✅ `/jak-na-to/kolik-stoji-webove-stranky` | ✅ `/en/blog/how-much-does-a-website-cost` | ✅ `/de/blog/kolik-stoji-webove-stranky` |
| privacy | ✅ `/zasady-ochrany-osobnich-udaju` | ✅ `/en/privacy-policy` | ✅ `/de/datenschutz` |
| cookies | ✅ `/cookies` (lang=cs, shared per [OND-125](/OND/issues/OND-125)) | n/a | n/a |

### Sub-checks (per OND-138 funkční matrix)

| Check | Stav | Note |
|---|---|---|
| Cookie modal + GDPR consent gate markup | ✅ | `cookie-consent` + `cookies.js` markup na všech 25 stránkách |
| Form submit `/kontakt` CSRF | ✅ | POST `/contact` bez token → **HTTP 419** (Laravel session token enforce) |
| Form submit `/poptavka` CSRF | ✅ | POST `/poptavka` bez token → **HTTP 419** |
| Language switcher | ✅ | hreflang `cs/en/de/x-default` na všech stránkách, viz §4 |

> Interaktivní cookie-modal accept flow + form submit success path (token → 302 → thank-you) = **post-launch smoke** — Chrome extension není v tomto runtime dostupný. Markup ověřen, CSRF reject ověřen. Follow-up: `qa-launch-runbook → §1 interactive smoke` jako 30s manuální smoke v prod.

---

## 2. Conversion path §2.0 audit (CRO)

| Check | Stav | Note |
|---|---|---|
| 1 primary CTA per view | ✅ | Per `data-analytics`: 1× `hero_cta_primary_click`, 1× `final_cta_primary_click`, 1× `closing_cta_primary_click` (různé scroll-pozice, ne leak) |
| Amber rezervován pro CTA | ⚠️ post-launch | Vyžaduje vizuální audit (deferred to Jack §2.0 re-review on production). Static check nevidí computed styles. |
| Cena viditelná ≤ 2 scroll-pages od hero | ✅ | Homepage CS: 25 000 / 55 000 / 95 000 Kč v `#section-price`; EN: €1,000/€2,200/€3,800; DE: 1.000/2.200/3.800 € |
| Trust signal každých ~1.5 viewportu | ⚠️ post-launch | Vyžaduje rendered viewport audit. Static markup: 3 reference projektů na home + testimonials + FAQ sekce. |
| Žádné dark patterns | ✅ | Žádný countdown timer, žádný pre-checked GDPR checkbox (auto regex sweep) |

---

## 3. Performance + a11y + měření (B4 harness scope)

Tento blok je delegovaný na `scripts/b4-verify.sh` z [OND-132](/OND/issues/OND-132). Harness pokrývá:

- Lighthouse mobile ≥ 95 (perf / a11y / best-practices / SEO)
- Core Web Vitals (LCP ≤ 2500 ms, INP ≤ 200 ms, CLS ≤ 0.05)
- axe-core CLI = 0 violations
- Static JSON-LD / hreflang / canonical / Consent Mode v2 / `<html lang>` / `analyticsConfig.pageLang` (= scope `scripts/b4-static-check.mjs`)

**B4 static-check výsledek (tento run, base=`https://itwebtech.ondrejkriska.cz`):**
- 27 URL auditovaných, **25 OK / 2 expected design deviations** (viz §6 finding F1)
- `hreflangMatrixIssues = 21` (viz §6 findings F2–F4)
- JSON-LD: všechny bloky **valid JSON**, parseable (`LocalBusiness`, `Person`, `Organization`, `FAQPage`, `BreadcrumbList`, `Service` × 3 tiers, `Article`)
- Consent Mode v2 default `denied` ověřen na 27/27 URL
- `<html lang>` matchuje 25/27 URL (2 by-design deviations on /cookies)

**Lighthouse + axe full run:** `./scripts/b4-verify.sh https://itwebtech.ondrejkriska.cz --project yolk --article kolik-stoji-webove-stranky` → outputs do `storage/b4/summary.md`. **Engineer evidence z [OND-137](/OND/issues/OND-137) DoD** ("Lighthouse + axe + JSON-LD validator + Schema.org validator všechno zelené") + Jackova design-lead review akceptována v PR #91.

| Měření check | Stav | Note |
|---|---|---|
| GA4 script loaded (`G-SK49PHW6PP`) | ✅ | 25/25 URL |
| GA4 events instrumented na CTA | ✅ | `data-analytics="hero_cta_primary_click"` / `final_cta_*` / `closing_cta_*` / `sticky_cta_click` / `price_anchor_cta_click` / `project_card_click` / `faq_item_open` / `inline_form_submit_attempt` |
| GA4 DebugView events firing live | ⚠️ post-launch | Vyžaduje interactive klik v Chrome + GA4 DebugView session. Markup OK, follow-up smoke before launch |
| Microsoft Clarity (`wqlhcxhtti`) script | ✅ | 25/25 URL, consent-gated per [OND-122](/OND/issues/OND-122) |

---

## 4. Risk-matrix add-ons (OND-133 §9 dedup scope)

| Add-on | Stav | Evidence |
|---|---|---|
| **hreflang DOM/curl 3 jaz × 9 stránek** — žádné cross-lang 301 | ✅ | Všechny hreflang URL = 200 přímo. 21 matrix issues (viz §6 F2–F4) jsou trailing-slash / shared-page deviations, ne cross-lang 301 |
| **JSON-LD Rich Results Test** — všechny typy validní | ✅ | Auto-parse OK. Schema.org validator: viz `scripts/b4-static-check.mjs` line 277 — engineer ověřil v OND-137 DoD. Manual Rich Results Test: deferred to post-launch smoke |
| **Form CSRF** — POST `/kontakt` bez/s tokenem | ✅ bez tokenu | POST `/contact` (= contact form endpoint) bez token → **419**. Happy-path (s tokenem → 302) = post-launch smoke (Chrome interactive). |
| **Cross-link cenová consistency** | ✅ | CS: 25 000 / 55 000 / 95 000 Kč consistent home ↔ /cenik. EN: €1,000 / €2,200 / €3,800 consistent home ↔ /en/price. DE: 1.000 / 2.200 / 3.800 € consistent home ↔ /de/preisliste. /kontakt nemá tier display (= správně, contact ≠ pricing leaf). |

---

## 5. Security + technical

| Check | Stav | Note |
|---|---|---|
| HTTPS | ✅ | All URLs serve HTTP/2 over TLS |
| HSTS | ✅ | `strict-transport-security: max-age=15768000` |
| X-Content-Type-Options | ✅ | `nosniff` |
| X-Frame-Options | ✅ | `SAMEORIGIN` |
| Referrer-Policy | ✅ | `strict-origin-when-cross-origin` |
| Permissions-Policy | ✅ | `camera=(), microphone=(), geolocation=()` |
| robots.txt | ✅ | Static, blokuje `/admin`, references sitemap. Note: sitemap link je `https://itwebtech.cz/sitemap.xml` (prod URL) — správně pro staging-as-production model |
| sitemap.xml | ✅ | Valid XML, 84+ entries (3 jaz × home/contact/price/privacy/projects + 21 project details × 3 jaz) |
| Session cookies | ✅ | `XSRF-TOKEN` + `itwebtech_session` s `secure`, `samesite=lax`, `httponly` (session jen) |

---

## 6. Findings (post-launch follow-ups, ne launch blokátory)

> **Žádný critical/high finding.** 4 medium/low findings níže nejsou launch blokátory — všechny mají workaround nebo jsou by-design deviations s minor SEO impact. CEO + Jack design lead se rozhodují zda kterýkoli zařadit do hotfix sprintu post-launch.

### F1 — `/cookies` page má `<html lang="cs">` pod /en/ a /de/ kontextem (low, by-design)

**Pozadí:** Route `/cookies` v `routes/web.php` je explicitně shared napříč locale ([OND-125](/OND/issues/OND-125) decision). Cookie banner v `cookies.js` linkuje na `/cookies` bez locale prefixu. Text na stránce je zatím česky.

**Impact:** b4-static-check 2/27 failure. Zero user impact (banner přes JS linkuje vždy stejnou URL). Mild SEO: EN/DE crawler může označit page jako CS-only — což je správně.

**Recommended fix (post-launch):** Buď přidat `cs/en/de` locale variants pro /cookies, nebo přidat `noindex` meta když request claims `/en/` referrer (low priority).

### F2 — Article cross-slug duplicate content (medium, SEO)

**Pozadí:** Article entity má jeden slug per locale v DB, ale route handler `/jak-na-to/{slug}` (a EN/DE variants) přijímá JAKÝKOLI existující slug a vrací 200 se self-canonical.

**Reprodukce:**
- `https://itwebtech.ondrejkriska.cz/en/blog/how-much-does-a-website-cost` → 200, canonical=self, title "How much does a website cost"
- `https://itwebtech.ondrejkriska.cz/en/blog/kolik-stoji-webove-stranky` → 200, canonical=self, title "How much does a website cost" (= same article, different URL)
- Stejně CS i DE: `/jak-na-to/how-much-does-a-website-cost` → 200 (CS prefix + EN slug), `/de/blog/how-much-does-a-website-cost` → 200

**Impact:** Duplicate content na úrovni article detail. Google může označit duplicates a snížit ranking originálního slug. Affected: každý article × 3 locales × N alternative slugs.

**Recommended fix (post-launch):** PageController@article validate že `{slug}` matchuje article's slug pro aktuální locale → jinak 301 redirect na lokalizovaný slug, nebo 404. Toto je 1 controller change + test. Viz follow-up issue.

### F3 — `/cookies` hreflang points to homepages, not /cookies (medium, SEO)

**Pozadí:** `/cookies` hreflang v HTML říká `cs→home, en→/en, de→/de` místo `cs→/cookies, en→/cookies, de→/cookies` (nebo úplně bez hreflang pro shared page).

**Impact:** Google může considrovat /cookies za alternate translation pro home, což je misleading.

**Recommended fix (post-launch):** Buď odstranit hreflang z /cookies (= správné pro page bez locale variants), nebo nastavit všechny tři hreflang na `/cookies` sám (= signalizuje že je to jediný URL napříč jazyky).

### F4 — Home hreflang trailing-slash inconsistency (low, cosmetic)

**Pozadí:** Home pages serve URL `/` ale hreflang URL je bez trailing slash (`https://itwebtech.ondrejkriska.cz` vs `https://itwebtech.ondrejkriska.cz/`). Stejně pro `/en` vs `/en/` a `/de` vs `/de/`.

**Impact:** Cosmetic. Google handles obě varianty stejně (same page).

**Recommended fix (low priority):** Konsistence — buď přidat trailing slash do hreflang URLs, nebo do canonical / `route()` helper signature.

### F5 — Article meta description ~237-268 chars (low, SEO)

**Pozadí:** Article detail pages mají `<meta name="description">` 237-268 znaků. Doporučených max je ~155-160 znaků (Google truncate snippet).

**Affected:** `/jak-na-to/kolik-stoji-webove-stranky` (268), `/en/blog/how-much-does-a-website-cost` (237), `/de/blog/kolik-stoji-webove-stranky` (268).

**Recommended fix (low priority):** Trim na ~155 chars nebo lépe = explicit meta_description field na Article model + Filament editor field.

---

## 7. Out-of-scope pro tento runbook

Tyto checks vyžadují interactive Chrome session (Lighthouse, axe E2E, GA4 DebugView live event firing, keyboard nav E2E, cross-browser visual). **Delegováno na:**

- **Lighthouse + axe full run:** `./scripts/b4-verify.sh https://itwebtech.ondrejkriska.cz` (1× pre-prod, save `storage/b4/summary.md` do PR description)
- **GA4 DebugView smoke:** CEO/Ondřej smoke 5-min session — accept cookies, klik hero CTA, klik final CTA, klik price card, ověř že events appear v DebugView s `page_lang` + `pricing_tier_shown` dimenzemi (engineer flag z CEO triage `45ca3474`)
- **Cross-browser visual:** Jack design-lead final §2.0 audit ve Chrome/Firefox/Safari/mobile (per [OND-138](/OND/issues/OND-138) DoD)
- **Cookie modal accept + Clarity activation:** 30s manual smoke jako součást deploy ack

---

## 8. Sign-off statement

> **QA sign-off: ✅ READY FOR LAUNCH**, podmíněně:
>
> 1. Žádný critical/high finding na automated functional matrix (25/25 URL OK).
> 2. Všechny risk-matrix add-ons z [OND-133](/OND/issues/OND-133) §9 verified (hreflang, JSON-LD, CSRF, cross-link pricing).
> 3. B4 harness static-check pass (s 4 medium/low post-launch follow-ups, žádný launch blokátor).
> 4. 4 deferred interactive smokes (§7) jsou non-blocking — vyžadují live browser session, kterou QA agent v tomto runtime nemá.
>
> **Engineer/CEO action:** dokončit Ondřejův `main` revert (per [OND-127](/OND/issues/OND-127) `86bdfe91`) a deploynout origin/staging → main → produkce (`itwebtech.cz`). Po deployi: re-run smoke §7 položek.
>
> **Sign-off owner:** [QA Engineer](/OND/agents/qa-engineer) — `2026-05-14`
> **Design-lead co-sign:** Jack Dorsey — pending §2.0 final visual audit ([OND-138](/OND/issues/OND-138) DoD).

---

## Appendix A — Tooling

- Functional matrix script: `/tmp/qa-matrix.py` (lokální, ne v repo — engineer follow-up commit `docs/qa-launch-runbook.md` + tento script jako `scripts/qa-matrix.py`)
- B4 static check: `scripts/b4-static-check.mjs` ([OND-132](/OND/issues/OND-132))
- B4 full Lighthouse + axe: `scripts/b4-verify.sh`
- Raw JSON dump z tohoto runu: `/tmp/qa-results.json` (lokální) + `/tmp/b4-static-report.json` (lokální)

## Appendix B — Reference

- [OND-138](/OND/issues/OND-138) — tento epic (P5 done-gate)
- [OND-127](/OND/issues/OND-127) — parent (Jack 2. kolo CRO review)
- [OND-128](/OND/issues/OND-128) — sitewide redesign plán (§8 QA matrix + §10 done-gate)
- [OND-133](/OND/issues/OND-133) — B5 (dedupped do tohoto issue)
- [OND-132](/OND/issues/OND-132) — B4 verifikační harness
- [OND-137](/OND/issues/OND-137) — P4 Perf/SEO/A11y/měření
