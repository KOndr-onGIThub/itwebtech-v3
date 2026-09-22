# OND-246 — tři návrhy hloubky homepage

Prototypy ke třem návrhům, které dostal board. **Nic z toho zatím není
produkční kód** — jsou to vrstvy, které se za běhu přikládají na současnou
homepage, aby šlo rozhodnout, kterým směrem jít. Po výběru se vítězná
varianta přepíše do `resources/css/podpis.css` (a pro podstránky do
`resources/css/app.css`), zbylé dvě se smažou.

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
