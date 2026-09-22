# OND-263 — vizuální podklady k případovkám: mapping pro nasazení (OND-268)

Autor: Jack Dorsey · 22. 9. 2026 · zdroj nálezů: OND-254, plán: OND-259

---

## 0. Co musí Engineer vědět, než sáhne na soubory

### 0.1 Každý obrázek existuje ve dvou stromech a Vite je dedupuje

`resources/img/projects/<slug>/…` (nový název) a `resources/img/images/projects/<legacy>/…`
(starý název) mají **shodné md5**. Vite-imagetools z nich udělá **jeden** asset a vyhrává
legacy název (na produkci se dnes servíruje např. `before-AM7Gkb3-.avif`, ne `gallery-2-*`).

→ **Přepiš vždy obě kopie stejnými bajty.** V balíku jsou proto obě: `projects/…`
i `images/projects/…`. Kdo přepíše jen jednu, nechá v buildu druhou verzi.

### 0.2 Geometrie, ve které se snímky opravdu zobrazují

| pozice | CSS | důsledek |
|---|---|---|
| lead / band (`wide`, poměr ≥ 1.5) | `width:100%; height:auto` | nic se neořízne |
| karta galerie (`card`, poměr < 1.5) | `aspect-ratio:1/1; object-fit:cover; object-position:top` | **cokoli, co není 1:1, přijde o obsah** — vysoké o spodek, široké o boky |
| miniatura v kartě projektu | `aspect-ratio:16/10; object-fit:cover` | ořez na 16:10 |
| foto na /kontakt | `aspect-ratio:4/5; object-fit:cover; object-position:center top` | ořez na 4:5 |

Role se počítá z poměru stran souboru (`screenshot_gallery_role()`), lead = **první wide
v pořadí hero → gallery**. Proto: změna poměru stran = změna pozice na stránce. Všechny
nové soubory poměr stran **záměrně drží**, jedinou výjimkou jsou karty přepracované na 1:1
(bod 2) — ty roli nemění, jen přestanou ztrácet obsah.

### 0.3 Oprava premisy zadání

Audit u `vanspedition` psal, že „before" je **stlačený** (kulaté logo jako elipsa).
Ověřeno v prohlížeči na produkci: `object-fit` je `cover`, logo má poměr os **1,036**
(kruh), ne 1,33. Snímek je tedy **oříznutý**, ne deformovaný — chybí ~25 % šířky.
Důkaz: `.screenshots/jack263-vanspedition-karta-objectfit.jpg` (živý element vs. simulace
cover vs. simulace fill). Oprava je stejná (dodat 1:1), ale text nálezu je v tomhle bodě
chybný a nemá se šířit dál.

---

## 1. Hero neukazuje homepage klienta

Prohození **obsahu** slotů; role (wide/card) i názvy souborů zůstávají, takže layout se nehne.

| projekt | slot | nový soubor | co se tím opravuje |
|---|---|---|---|
| barana | `hero-1.png` (lead) | `projects/barana/hero-1.png` | lead ukazoval vnitřní slide na tabletu **vzhůru nohama**; nově homepage „Pohoda na terase…" |
| barana | `gallery-1.png` | `projects/barana/gallery-1.png` | původní hero otočený o 180° — text tabletu je čitelný |
| cyklocentrum | `gallery-4.png` (lead) | `projects/cyklocentrum/gallery-4.png` | lead byly tři telefony s ceníkem půjčovny; nově homepage „Půjčovna, prodej a servis kol…" |
| cyklocentrum | `gallery-5.webp` | `projects/cyklocentrum/gallery-5.webp` | tři telefony se přesunuly sem |
| nove-interiery | `hero-1.png` (lead) | `projects/nove-interiery/hero-1.png` | lead byl „Přijďte si sáhnout / Showroom Říčany"; nově homepage „Kvalitní podlahy a dveře…" |
| nove-interiery | `gallery-4.png` | `projects/nove-interiery/gallery-4.png` | původní hero (žena u notebooku) sem |
| zubni-provazek | `gallery-1.png` (lead) | `projects/zubni-provazek/gallery-1.png` | lead byl **blogový článek** „Co hradí zdravotní pojišťovna?"; nově homepage ordinace |
| zubni-provazek | `gallery-4.png` | `projects/zubni-provazek/gallery-4.png` | blogový článek se přesunul sem |

**Alt texty**: `hero-1` má u všech projektů alt „… domovská stránka …". U barany a nových
interiérů je teď alt konečně pravdivý. U **cyklocentra** a **zubního provázku** zůstává
`hero-1` (čtvercová karta) s altem „domovská stránka", ačkoli ukazuje vnitřní obrazovku —
to je textová oprava, patří do balíku textů, ne sem. Vypisuju to, ať to nespadne pod stůl.

### 1b. vp-industry — **potřebuju podklad od Ondry**
V žádném z 6 obrázků není homepage vpindustry.cz. `gallery-3` je mockup kategorie
„Značení inkoustem", `gallery-2` je landing „Vyzkoušejte si průmyslové značení zdarma".
Nový screenshot jsem nepořizoval: bez potvrzení, že je web klienta v aktuální podobě
a že ho smíme nafotit znovu, by to byl odhad. **Ask: potvrdit URL a to, že se má pořídit
nový záběr homepage.** Pak je to na 20 minut.

---

## 2. Deformované a useknuté obrázky → karty do 1:1

Podklad je vepsaný (contain) doprostřed čtverce na podklad **#1C1F24**, což je přesně barva
karty (`--bg-surface #101318` + bílá 7,2 %) — rám splyne se stránkou. Nic se neupscaluje.

| projekt | slot | nový soubor | rozměr | co se tím opravuje |
|---|---|---|---|---|
| vanspedition | `gallery-2.webp` | `projects/vanspedition/gallery-2.webp` + `images/projects/vanspedition/before.webp` | 1200×1200 | „před" ztrácelo ~25 % šířky |
| vanspedition | **nový** `gallery-3.webp` | `projects/vanspedition/gallery-3.webp` | 1200×1200 | protějšek „po" — dvojice před/po zaplní řádek mřížky (viz bod 4) |
| picker | `gallery-2.jpg` | `projects/picker/gallery-2.jpg` + `images/projects/picker/picker_main_page_h.jpg` | 796×796 | chyběla tabulka DÍL/SKLAD/POČET a tlačítko DALŠÍ (−42 % výšky) |
| picker | `gallery-4.jpg` | `projects/picker/gallery-4.jpg` + `images/projects/picker/picker_call_abn.jpg` | 508×508 | useknuté pod SCW, chybělo VLASTNÍ ZPRÁVA |
| frl-creator | `hero-1.jpg` | `projects/frl-creator/hero-1.jpg` + `images/projects/FLR/FRLcreator_main_page.jpg` | 650×650 | formulář se ořezával z obou stran (chyběly oblasti 1 a 4) |
| frl-creator | `gallery-2.jpg` | `projects/frl-creator/gallery-2.jpg` + `images/projects/FLR/FRLcreator_pdf_list.jpg` | 1257×1257 | 25 z 30 řádků bylo prázdných a ořez bral pravé sloupce (GAP, Box Type) → výřez vyplněné části, všechny sloupce |
| excel-tools | `gallery-1.jpg` | `projects/excel-tools/gallery-1.jpg` + `images/projects/excel/FLR_backup_case2.jpg` | 1297×1297 | postup se sekal za krokem 2, teď jsou vidět všechny kroky 0–4 |

### 2b. excel-tools `gallery-2` (`address_data`, 1076×2545) — **navrhuju smazat, soubor nedodávám**
Není to ukázka práce, je to **interní návod** s kurzorovými šipkami — a nese cesty na
klientskou infrastrukturu: `P:\D_PRODUCTION_CONTROL\LOGISTICS\EXTERNAL_LOGISTIC\…`,
názvy serverů (`Network > ilssczs > ARS > BackupData`), soubory `EDGBS41B.DS06I181.X00000011_ILSSCZS…`.
V žádném ořezu to v 598 px nebude čitelné a zveřejňovat adresářovou strukturu zákazníka
je horší problém než estetika. **Doporučení: řádek screenshotu smazat, bez náhrady.**

---

## 3. Zapečená play tlačítka + screenshot přehrávače

Mockupy překreslené v DOM (Playwright), ne retušované. Rovné telefony, bez falešného play.

| projekt | slot | nový soubor | rozměr | co se tím opravuje |
|---|---|---|---|---|
| video-pitbike-akademie | `hero-1.jpg` | `projects/video-pitbike-akademie/hero-1.jpg` + `images/projects/pitarena/video-pitbike-akademie.jpg` | 1500×750 | pryč je červené play kolečko mimo telefon **i celé okno přehrávače** (lišta, název souboru `pitarena_pitbike…`, časová osa 0:00:01/0:00:43). Tři snímky z videa = začátek / akce / CTA „Pro děti od 5 let i pro dospělé." Prázdný bílý štítek s osamoceným „– PRAVICE" nahrazen dokresleným stavem. |
| animace-delejme | `hero-1.jpg` | `projects/animace-delejme/hero-1.jpg` + `images/projects/itwebtech/video_delejme_animace.jpg` | 1500×750 | pryč play kolečko; tři textové slidy „Zvyšte zájem s Animacemi a Videi" → „Upoutání Pozornosti" → „Podpora Konverzí" |
| logo-realitacky | `hero-1.png` | `projects/logo-realitacky/hero-1.png` + `images/projects/realitacky/logo_realitacky_v_akci.png` | 1200×480 | pryč play kolečko i rámeček telefonu, který uřezával velké R; dvě varianty loga s odsazením místo tří naskládaných na sebe |

Snímky z videí: lokální Playwright Chromium neumí H.264, tak přes Higgsfield sandbox
(`ffmpeg -ss`) — zdroje `pitarena_pitbike_akademie (1080×1920).mp4` a `delejte_animace.mp4`
z `reference/itwebtech/public/images/projects/`.

### 3b. logo-realitacky — **limit podkladu**
Všechny tři varianty loga existují jen jako raster **uvnitř** toho telefonu, ~278 px široký
a lehce v perspektivě. Bílou variantu jsem zahodil (telefon jí uřezává stojku R), zbylé dvě
jsou narovnané a kompletní, ale měkké. **Ask: originál loga (AI/SVG/PNG bez telefonu).**
S ním je to na 10 minut a případovka o návrhu značky bude konečně ukazovat značku.

### 3c. animace-delejme — **rozhodnutí boardu**
Zdrojové video je promo **itwebtechu**: na kapotě auta je logo `itwebtech DEVELOPMENT`
a závěr je „itwebtech.cz". Je v každém snímku, ořezem to nejde odstranit. Stejná třída
problému jako raketa v článku (bod 7). Buď to necháme (je to poctivě Ondrova starší práce),
nebo případovku stáhneme. **Nerozhoduju za board, jen pojmenovávám.**

---

## 4. Prázdné buňky v mřížkách galerií — **řeší se pořadím, ne obrázky**

Mřížka karet je dvousloupcová a **každý wide band přeruší běh karet**. Lichý běh = černá díra.
Změřeno na všech projektech (skript `ond263/`):

| projekt | běhy karet dnes | prázdných buněk | oprava |
|---|---|---|---|
| kemp-veselka | [3, 1] | 2 | přesunout wide `gallery-4` hned za lead → běh [4] |
| nove-interiery | [3, 1] | 2 | přesunout wide `gallery-4` hned za lead → běh [4] |
| zubni-provazek | [3, 1] | 2 | přesunout wide `gallery-4` hned za lead → běh [4] |
| picker | [1, 1] | 2 | přeskládat na g1, g3, g5 (wide) → g2, g4 (karty) → běh [2] |
| vanspedition | [1] | 1 | **vyřešeno novým `gallery-3.webp`** („po" k „před") → běh [2] |

`zubni-provazek` audit nejmenoval, má díry taky.

**Systémově (návrh pro B1, ne pro mě):** buď v `detail-gallery.blade.php` neseskupovat karty
po blocích a dát je do jednoho běhu, nebo v CSS nechat poslední lichou kartu přes oba sloupce
(`.portfolio-detail-gallery__grid > :last-child:nth-child(odd){grid-column:1/-1}`). Pak je to
odolné vůči jakémukoli budoucímu pořadí. Přeskládání pozic je okamžitá oprava bez kódu.

---

## 5. Amatérské koláže

| projekt | slot | co s tím |
|---|---|---|
| clanek-motorkari-cz | `hero-1.jpg` → `projects/clanek-motorkari-cz/hero-1.jpg` + `images/projects/pitarena/clanek_motorkari_cz.jpg` | nalepený panel s logem Motorkáři.cz přes hero **zakrýval polovinu titulku**. Nový snímek je **čerstvý živý záběr článku** (motorkari.cz, 22. 9. 2026) v okně prohlížeče — titulek celý, autorství „Zdroj: Ondřej Kriška" čitelné. |
| clanek-motorkari-cz | `gallery-1.jpg` → `projects/clanek-motorkari-cz/gallery-1.jpg` + `images/projects/pitarena/clanek_motorkari_cz_mobile.jpg` | telefon rotovaný o 35° s textem pod úhlem → rovný telefon s mobilní verzí článku. `gallery-2` (originál textu pro Standu) nechávám beze změny. |
| josefopa | `hero-1.jpg` → `projects/josefopa/hero-1.jpg` + `images/projects/josefopa/josefopa.jpg` | AI sci-fi pozadí (červenomodré „speed lines") → čistá bílá verze téhož mockupu |
| josefopa | `gallery-2.webp` | **smazat řádek** — po výměně hero je to doslovný duplikát |
| josefopa | `gallery-3.webp` | **smazat řádek, bez náhrady** — mřížka 3×3 klipartových maskotů („The Ea…", „flyin Eag…" useknuté, černý obdélník přetéká kruhový ořez). Na webu, který prodává práci na míru, je cizí kliparty horší než prázdno. |

---

## 6. Miniatura HCMS v „Další projekty"

| co | soubor |
|---|---|
| **nový** `projects/hcms/thumbnail-1.jpg` (1600×1000, 16:10) | čitelný náhled (Toyota + notebook s obrazovkou HCMS) místo nečitelného functions-model diagramu |

Kód vybírá miniaturu takto (`portfolio_card_thumbnail()`): `type='thumbnail'` → první karta
(poměr < 1.5) → hero. HCMS má jako první kartu `gallery-4` = functions model, proto ta šedozelená plocha.

**Engineer:**
1. přidat screenshot řádek `projects/hcms/thumbnail-1.jpg` s `type='thumbnail'`;
2. v `detail-gallery.blade.php` vyřadit thumbnail z galerie —
   `$screens = collect($screenshots)->reject(fn ($s) => $s->type === 'thumbnail');`
   Jinak se stejný obrázek objeví i v galerii detailu. Je to jednořádková změna a zároveň
   obecný nástroj na kteroukoli další špatnou miniaturu.

Diagramy (`gallery-3` doménový model, `gallery-4` functions model, `gallery-5` use case)
**nechávám být** — čekají na otázku 5 boardu. Varianta „zredukovat galerii": nechat
`gallery-1` (VOLÁNÍ), `gallery-2` (tablet), `gallery-6` (historie) a tři diagramy zahodit.

---

## 7. Obrázky v článcích `/jak-na-to`

### 7a. Hotové náhrady (průnik obou odpovědí boardu — dodáno bez čekání)

| slot | nový soubor | co se tím opravuje |
|---|---|---|
| `articles/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony.webp` (1140×760) | hero článku „Kdy se vyplatí aplikace na míru" | AI raketa se **starým logem itwebtech**, vesmír, jachta a sloupce mincí → reálná obrazovka HCMS (Toyota) + terminál Pickeru. Článek říká „žádné zázraky" — obrázek to teď neříká naopak. |
| `articles/jak-muze-…-miliony_preview.webp` (377×378) | náhledovka do výpisu `/jak-na-to` | totéž pro dlaždici; obrazovka Pickeru je čitelná i v 378 px |
| `articles/digi_marketing.webp` (1140×760) | anglická koláž „DIGITAL MARKETING" → homepage BARANA | odstavec pod ní mluví o tom, že web řekne dopředu to, co jinak vysvětlujete na schůzce — ukazuje to teď web, který to dělá |
| `articles/best_web.webp` (1140×760) | anglická koláž „BEST WEB DESIGN" → homepage Nové interiéry | zavírá článek reálnou realizací místo stocku |

### 7b. Inventura zbytku — **čeká na otázku 2, soubory nedodávám**

| soubor | kde | co v něm je | návrh |
|---|---|---|---|
| `articles/kavarna.webp` | `/jak-na-to/jak-se-pripravit-na-novy-web`, pod „5. Co dnes na webu nefunguje" | stock: čtyři lidi s hrnky kávy nad tablety, `alt=""` | **vyhodit bez náhrady.** Odstavec „Nejhorší zadání je »udělejte to hezky«" se pod ní čte jako popisek. Text sám o sobě nic neztratí. |
| `articles/jak-definovat-pozadavky-na-vyvoj-webove-stranky.jpg` | hero téhož článku | plochá ilustrace muže s rukama za hlavou a bublinou | ponechat, nebo nahradit záběrem reálného zadání (wireframe/brief). Není falešná, jen generická. |
| `articles/potrebuje-vase-firma-webovou-stranku.jpg` | hero „Potřebuje vaše firma webovou stránku" | karikatura muže na rozcestí | ponechat. Metafora sedí k textu a nic neslibuje. |
| `articles/redesign_preview.webp` | výpis `/jak-na-to` | AI stůl s UI prvky, tužka, palety | nahradit reálným před/po z portfolia (vanspedition má obojí) |
| `articles/SEO.webp`, `analyza_konkurence.webp`, `findom.webp`, `webcena.webp`, `webhodnota.webp`, `casti_domeny.webp`, `live_2.webp`, `update.webp`, `before-after_2.webp` | starší články | neprošlo auditem | projít až po rozhodnutí; stejné pravidlo — reálný screenshot, typografická karta, nebo nic |

Pravidlo, podle kterého jsem to dělal a podle kterého se dá dodělat zbytek:
**reálný screenshot z portfolia > typografická karta > prázdno > cizí stock.**

---

## 8. Fotka na `/kontakt` — **není to vkusová otázka, je to chyba v `<picture>`**

`contact.blade.php` má:

```
<source srcset="{{ asset_v('img/about/ondrej_kriska_preview.webp') }}" type="image/webp">
<img src="{{ asset_v('img/about/ondrej_kriska.jpg') }}" …>
```

- `ondrej_kriska_preview.webp` = **500×500 momentka u oranžové zdi** → tu vidí každý moderní prohlížeč
- `ondrej_kriska.jpg` = **1500×2250 tmavý profesionální portrét** → fallback, který se nikdy nezobrazí

Ten správný portrét už v repu je, jen ho nikdo nevidí. Nové soubory (obojí 1000×1250, tedy
přesně 4:5, které layout chce, ořez zkontrolovaný — hlava není useknutá):

| soubor | poznámka |
|---|---|
| `public/img/about/ondrej_kriska_preview.webp` | 4:5 výřez tmavého portrétu, q84 |
| `public/img/about/ondrej_kriska.jpg` | stejný výřez v JPG, aby se webp i fallback shodovaly |

`asset_v()` zajistí novou cache-busting verzi, takže immutable cache na `public/` nevadí.
Před/po: `.screenshots/jack263-kontakt-fotka-before-after.jpg` (v reálné velikosti karty).

---

## Přehled dodaných souborů

```
projects/animace-delejme/hero-1.jpg           projects/josefopa/hero-1.jpg
projects/barana/gallery-1.png                 projects/logo-realitacky/hero-1.png
projects/barana/hero-1.png                    projects/nove-interiery/gallery-4.png
projects/clanek-motorkari-cz/gallery-1.jpg    projects/nove-interiery/hero-1.png
projects/clanek-motorkari-cz/hero-1.jpg       projects/picker/gallery-2.jpg
projects/cyklocentrum/gallery-4.png           projects/picker/gallery-4.jpg
projects/cyklocentrum/gallery-5.webp          projects/vanspedition/gallery-2.webp
projects/excel-tools/gallery-1.jpg            projects/vanspedition/gallery-3.webp   (nový)
projects/frl-creator/gallery-2.jpg            projects/video-pitbike-akademie/hero-1.jpg
projects/frl-creator/hero-1.jpg               projects/zubni-provazek/gallery-1.png
projects/hcms/thumbnail-1.jpg      (nový)     projects/zubni-provazek/gallery-4.png

images/projects/…  — 12 legacy dvojčat (viz 0.1), bajt v bajt shodných

articles/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony.webp
articles/jak-muze-jednoducha-webova-aplikace-usetrit-vasi-firme-miliony_preview.webp
articles/digi_marketing.webp
articles/best_web.webp

public/img/about/ondrej_kriska.jpg
public/img/about/ondrej_kriska_preview.webp
```

Datové změny bez souboru (pro OND-268): smazat `josefopa/gallery-2`, `josefopa/gallery-3`,
`excel-tools/gallery-2`; přidat `hcms/thumbnail-1` jako `type='thumbnail'`; přeskládat pozice
u kemp-veselka, nove-interiery, zubni-provazek a picker (bod 4).

## Co zůstává na Ondrovi

1. **vp-industry** — potvrdit, že se má pořídit nový screenshot homepage vpindustry.cz.
2. **logo-realitacky** — originál loga „Realiťačky v akci" (SVG/AI/PNG bez telefonu).
3. **animace-delejme** — smí případovka ukazovat promo se starým logem itwebtech? (stejná otázka jako raketa)
4. **otázka 2** (stock v článcích) a **otázka 5** (interní vývojové podklady v galeriích) — inventura a varianty výše.
