# Conversion Rate baseline (OND-137 P4 §6)

Tento dokument zachycuje pre-launch baseline metriky a definuje srovnávací
framework, který použijeme po nasazení P2+P3+P4 redesignu na produkci. Cíl:
mít evidenci, jestli nové stránky skutečně zvedly konverzi vůči staré verzi,
nebo jenom „vypadají hezky".

> Plán §9 (Měření) — **"Measure or it didn't happen" prime directive.**

## Baseline (poslední 30 dnů před P4 launch)

GA4 Property: `G-SK49PHW6PP` (itwebtech.cz)
Microsoft Clarity: `wqlhcxhtti`
Plausible: `itwebtech.cz`

> **Jak vyplnit:** Před deployem P4 do produkce otevřít GA4 → Reports →
> Engagement → Events, vybrat date range „Last 30 days", a zapsat hodnoty
> z následujících eventů. Stejné hodnoty se zapíší znova 30 dní po launchi
> pro srovnání.

| Metric | Pre-launch (30d) | Post-launch (+30d) | Δ |
|---|---|---|---|
| `users` (unique) | _TBD před launch_ | — | — |
| `sessions` | _TBD_ | — | — |
| `engagement_rate` (%) | _TBD_ | — | — |
| `average_session_duration` (s) | _TBD_ | — | — |
| `bounce_rate` (%) | _TBD_ | — | — |
| `cta_primary_click` (count) | _TBD_ | — | — |
| `cta_secondary_click` (count) | _TBD_ | — | — |
| `form_submit` (count) | _TBD_ | — | — |
| `pricing_tier_view` (count) | _TBD_ | — | — |
| `case_study_view` (count) | _TBD_ | — | — |
| **CR primary** = `form_submit` / `users` (%) | _TBD_ | — | — |
| **CR sekundární** = `cta_primary_click` / `users` (%) | _TBD_ | — | — |
| **CR pricing-funnel** = `pricing_tier_view` → `cta_primary_click` (%) | _TBD_ | — | — |

> **Pozn.** První tři řádky (users/sessions/engagement) jsou standardní GA4
> reports, ostatní jsou custom events zavedené v rámci OND-122 + OND-137.
> Před P4 launch ještě některé z 5 canonical eventů (`cta_primary_click`,
> `cta_secondary_click`, `form_submit`, `pricing_tier_view`, `case_study_view`)
> nemusí mít plnou 30d historii — v takovém případě uveď „N/A (event zaveden
> v OND-137)" a baseline se sebere ex-post po 30 dnech provozu.

## Per-locale split

GA4 secondary dimension: `page_lang` (cs / en / de). Stejnou tabulku
udržuj per jazyk — DE má jiný traffic profile a smíchané čísla mohou skrýt
locale regrese.

## Per-tier split (pricing)

GA4 secondary dimension: `pricing_tier_shown` (`presentation` / `business` /
`custom`). Použij pro analýzu, která úroveň nejvíc rezonuje, a jestli redesign
/cenik posunul distribuci viditelností (očekávaně dominuje `business`).

**Pozor na zlom v datech 26. 9. 2026 (OND-354).** Do té doby dimenze nesla
číslo z ceny (`25` / `55` / `95` tisíc Kč) a úrovně se jmenovaly
Standard / Custom / Startovní. Ceník od té doby cenové úrovně nemá — nese
prahové číslo a rozpětí, úrovně se jmenují podle rozsahu a dimenze nese
technický klíč. Mapování starých hodnot na nové: `55` → `business`,
`95` → `custom`, `25` → `presentation`. Přes ten zlom se nedá segmentovat
jedním filtrem.

## Definition of „úspěch"

Per CEO direktivu a Jack §2.0 rule #8 — měření je hard gate na success
review:

- **CR primary +20 %** (statistically significant, n ≥ 100 conversions).
- **engagement_rate +10 %** nebo **bounce_rate -10 %**.
- **`pricing_tier_view` → `form_submit` funnel** se nezhoršil (ideálně
  zlepšil — to byl smysl ceník redesignu).

Pokud +20 % CR po 30 dnech nedosáhneme, spustit A/B testovací fázi
(out-of-scope pro OND-137, viz plán §9.4).

## Tooling

- **GA4 DebugView** — `gtag('event', …)` se v debug módu objeví do 2 s.
  Smoke test po deployi: otevřít GA4 DebugView, projít homepage → klik
  primary CTA → vyplnit kontakt formulář. Měly by se objevit
  `hero_cta_primary_click` + canonical `cta_primary_click` (P4 §6).
- **Clarity Heatmaps** — kontrola, jestli nový pricing layout dostává klicky
  do správných míst (CTA, ne třeba do prázdného whitespace).
- **Plausible Goals** — paralelní cookieless měření (GDPR-friendly fallback,
  kdyby cookie consent ratio bylo nižší než očekáváme).

## Reference

- Spec: [OND-137](/OND/issues/OND-137)
- Plán: [OND-128 §6](/OND/issues/OND-128#document-plan) (Měření)
- Analytics infra (eventy + dimenze): [docs/analytics.md](./analytics.md)
- Parent: [OND-127](/OND/issues/OND-127)
