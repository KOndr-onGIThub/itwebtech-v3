# OND-261 — Redline textů případovek (CS)

**Zdroj starých znění:** živá produkce `https://itwebtech.ondrejkriska.cz/projekty/<slug>`, staženo a rozparsováno 22. 9. 2026.
Každé „staré znění“ v tomhle souboru je **ověřeno skriptem proti produkci** — doslovně, včetně diakritiky a uvozovek (0 neshod z 59 ověřitelných položek). Strojově čitelná verze: `redline.json` vedle tohoto souboru.

**Stav vlny 1 v okamžiku měření:** všech šest oprav z [OND-256](/OND/issues/OND-256) bylo na produkci ještě *nenasazeno* (nasazení 22. 9. večer). Staré řetězce, které vlna 1 mění, tedy v tabulkách níž najdete v předchozí podobě — viz sekce **Vlna 1** a sloupec Pozor u `josefopa`.

## Mapa polí do databáze

Ověřeno ve `resources/views/components/portfolio/detail-{hero,body}.blade.php` na `origin/staging`:

| Co je na stránce vidět | Sloupec |
|---|---|
| H1 a název na kartě projektu | `portfolio_project_translations.title` |
| Řádek pod H1 | `…subtitle` (když je prázdný, šablona spadne na `…summary`) |
| Perex nad „Výzva“ | `…description` — **ne `summary`** |
| Výzva / Řešení / Výsledek | `…challenge` / `…solution` / `…result` |
| SEO titulek a popis | `…meta_title` / `…meta_description` |
| Řádek „Doba realizace“ | `portfolio_projects.duration` |
| Řádek „Kategorie“ | lang klíč `projects.detail.category_label.<category>`, **ne DB** |
| Štítky v řádku Technologie | `portfolio_tag_translations.name` (sdílené mezi projekty) |

Vzor migrace je hotový: `database/migrations/2026_09_22_110000_ond256_opravy_textu_pripadovek.php` (podmíněný `LIKE` + `str_replace`, druhý běh no-op, ruční úpravy z Filamentu nepřepíše). Nezapomenout na `docs/portfolio-data.yaml`, aby čerstvý seed dával stejný výsledek.

---

## Průřezové položky

### X1 — Sjednocení značky „PitArena“

| Projekt | Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|---|
| PitArena — e-shop | H1 / název (title) | `PitAréna — e-shop` | **PitArena — e-shop** | Sjednocení značky. pitarena.cz i doména klienta píší PitArena; PitAréna se objevuje jen na shop.pitarena.cz. V portfoliu stojí obě varianty vedle sebe na jedné kartě (klient „PitArena“, titulek „PitAréna“). Pole title pohání H1, kartu v „Další projekty“ i výpis /projekty. |
| PitArena — e-shop | meta titulek (meta_title) | `PitAréna — e-shop s motorkami YCF a náhradními díly` | **PitArena — e-shop s motorkami YCF a náhradními díly** | Totéž v SEO titulku, aby značka nebyla ve výsledcích vyhledávání psaná jinak než na webu klienta. |

### X2 — Řádek „Doba realizace“

**Systém, který navrhuju:**

1. Jednorázová zakázka → **řád v týdnech nebo měsících**: „5 týdnů“, „několik týdnů“, „několik měsíců“.
2. Dlouhodobá spolupráce → **„průběžně od &lt;rok&gt;“**.
3. **Nikdy název měsíce** — to je datum zahájení, ne doba; rok už je o řádek výš.
4. **Nikdy hodnotící přívlastek** („kratší“, „krátká“). „Doba realizace: kratší realizace“ je tautologie a „krátká zakázka“ navíc sama podbízí malou hodnotu práce.
5. Když přesný řád neznám, **řádek vypustit**. Prázdný řádek je lepší než vágní.

Deset řádků níž navrhuju vypustit, protože jejich skutečnou délku nemám z čeho doložit. Když ji Ondřej zná, stačí místo vypuštění doplnit konkrétní řád podle bodu 1 — Engineer to má jako jednu hodnotu na projekt.

| Projekt | Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|---|
| PitArena | Doba realizace (duration) | `průběžně 2023–dnes` | **průběžně od 2023** | Jednotný tvar pro dlouhodobé spolupráce: „průběžně od <rok>“. „–dnes“ je redundantní. |
| HCMS | Doba realizace (duration) | `několik měsíců (2021), provoz dodnes` | **několik měsíců, aplikace běží dodnes** | Závorka s rokem duplikuje řádek „Rok: 2021“ hned nad tím. Věta bez závorky se čte přirozeně. |
| YOLK | Doba realizace (duration) | `průběžná spolupráce` | **průběžně od 2025** | „Průběžná spolupráce“ neříká jak dlouho. Rok 2025 je v datech projektu, jednotný tvar s PitArenou. |
| Realiťačky v Akci | Doba realizace (duration) | `září 2024` | **_(řádek vypustit)_** | Název měsíce je datum zahájení, ne doba realizace — řádek slibuje jinou informaci, než dodá. Rok 2024 je na stránce o řádek výš. Bez ověřeného řádu (týdny/měsíce) řádek vypustit. |
| Josef Opa | Doba realizace (duration) | `říjen 2024` | **_(řádek vypustit)_** | Stejný důvod jako u realitacky-v-akci — datum místo doby. |
| Logo — Realiťačky v Akci | Doba realizace (duration) | `leden 2024` | **_(řádek vypustit)_** | Stejný důvod — datum místo doby. |
| VAN spedition | Doba realizace (duration) | `kratší realizace` | **_(řádek vypustit)_** | „Doba realizace: kratší realizace“ je tautologie a hodnotící přívlastek místo údaje. Bez ověřeného řádu vypustit. |
| Elektro Srnák | Doba realizace (duration) | `kratší realizace` | **_(řádek vypustit)_** | Tatáž tautologie. |
| Článek na Motorkáři.cz | Doba realizace (duration) | `krátká spolupráce` | **_(řádek vypustit)_** | Hodnotící přívlastek místo údaje; „krátká“ navíc podbízí malou hodnotu práce. |
| Video — Pitbike Akademie | Doba realizace (duration) | `krátká produkce` | **_(řádek vypustit)_** | Hodnotící přívlastek místo údaje. |
| Animace pro upoutání pozornosti | Doba realizace (duration) | `krátká produkce` | **_(řádek vypustit)_** | Hodnotící přívlastek místo údaje. |
| Reklamní cedule PitArena | Doba realizace (duration) | `krátká zakázka` | **_(řádek vypustit)_** | Hodnotící přívlastek místo údaje. |
| Excel Tools (VBA) | Doba realizace (duration) | `průběžně` | **_(řádek vypustit)_** | Samotné „průběžně“ bez rozsahu nic neříká a proti řádku „Rok: 2020“ působí rozporně. Pokud Ondřej potvrdí rozsah, nasadit „průběžně 2019–2021“ (nebo skutečné roky); jinak vypustit. |

### Q1 — Zavírací uvozovky (22×, plošně)

CELOWEBOVÁ TYPOGRAFIE: ve všech 22 uvozených úsecích v textech projektů je otevírací uvozovka správná česká „ (U+201E), ale zavírací je rovná palcová " (U+0022) místo české “ (U+201C). Ověřeno na produkci 22. 9. 2026: 22 otevíracích „, 22 rovných ", nula správných “. Protože se rovná uvozovka v těchto polích nevyskytuje nikde jinde, je bezpečná plošná záměna U+0022 → U+201C napříč poli title, subtitle, summary, challenge, solution, result, meta_title, meta_description všech projektů. Dotčené projekty: pitarena (2), cyklocentrum (3), zubni-provazek (3), nove-interiery (2), barana, realitacky-v-akci, kemp-veselka, strechy-zajic, vanspedition, yolk, picker, choccoboard, frl-creator, excel-tools, logo-realitacky, animace-delejme (po 1).

Engineer: jedna podmíněná náhrada U+0022 → U+201C přes uvedená pole. Kontrola po nasazení: 22 × U+201E (`„`), 22 × U+201C (`“`), nula × U+0022.

---

## Po projektech

### PitArena  <span style="font-weight:400">`pitarena`</span>

**Ponechat:** Texty. Bez textového nálezu; čísla ze Search Console jsou zdrojovaná včetně období. Mění se jen Doba realizace (X2-1) a uvozovky (Q1).

Průřezově se tohoto projektu týká: X2-1.

### PitArena — e-shop  <span style="font-weight:400">`pitarena-eshop`</span>

**Ponechat:** Texty mimo značku. Bez textového nálezu; čísla z administrace e-shopu jsou zdrojovaná. Mění se jen psaní značky (X1).

Průřezově se tohoto projektu týká: X1-1, X1-2.

### BARANA  <span style="font-weight:400">`barana`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| podtitul (subtitle) | `Premium web pro bioklimatické pergoly připravený na kampaně` | **Prémiový web pro bioklimatické pergoly připravený na kampaně** | Podtitul píše anglicky „Premium web“, zbytek textu česky „prémiové“. U projektu, který prodává prémiovost i copywriting, je mix obzvlášť vidět. |
| meta titulek (meta_title) | `BARANA — premium web pro pergoly, ploty a brány` | **BARANA — prémiový web pro pergoly, ploty a brány** | Sjednocení s podtitulem a perexem. |
| meta popis (meta_description) | `Případová studie: premium prezentace BARANA s landingem pro Meta Ads a Google Ads. Postaveno za 5 týdnů, ready pro placené kampaně.` | **Případová studie: prémiová prezentace BARANA se samostatnou stránkou pro Meta Ads a Google Ads. Postaveno za 5 týdnů.** | Tři anglicismy v jedné SEO větě („premium“, „landing“, „ready“). „Samostatná stránka pro reklamu“ je formulace, kterou už web používá ve štítcích u téhož projektu. |

_Vlna 1 (OND-256), needěláme znovu:_ `Postavil jsem premiové prezentační stránky` → `Postavil jsem prémiové prezentační stránky`

### Nové interiéry  <span style="font-weight:400">`nove-interiery`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Řešení (solution) | `Texty mluví bez jargonu o tom, co klient od spolupráce dostane.` | **Texty bez žargonu říkají, co klient od spolupráce dostane.** | Česky „žargon“. Věta o textech bez žargonu s pravopisnou chybou působí ironicky. Zároveň „mluví … o tom, co“ je vata. |

_Vlna 1 (OND-256), needěláme znovu:_ `že si zákazníci dopředu vědomí` → `že zákazníci dopředu vědí`

### Cyklo Centrum  <span style="font-weight:400">`cyklocentrum`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výsledek (result) | `Klient potvrzuje, že má za sebou „prezentaci, kterou je za co schovat".` | **Klient potvrzuje, že má konečně prezentaci, za kterou se nemusí stydět.** | Uvozená věta v češtině nedává smysl (zřejmě zkomolené „za kterou se nemusí stydět“) a jako doslovný citát je to chyba přepisu. Nové znění uvozovky vypouští a formuluje to jako parafrázi — tím nic klientovi nevkládám do úst. POZOR: pokud se dohledá původní znění reference, nasadit doslovný citát v uvozovkách místo parafráze. |

**Ponechat:** Zbytek textů. Kromě zkomolené citace (cyklo-1) je stránka v pořádku, SEO a GA čísla jsou zdrojovaná.

### Realiťačky v Akci  <span style="font-weight:400">`realitacky-v-akci`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výzva (challenge) | `Měla působit profesionálně, ale lidsky, a měla z webu jít poznat, jak pracuje.` | **Web měl působit profesionálně, ale lidsky, a mělo z něj jít poznat, jak makléřka pracuje.** | Předchozí věta mluví o webu (rod mužský), takže „Měla působit“ nemá podmět, ke kterému by se vázalo. Druhá polovina má navíc nesprávný rod („měla z webu jít poznat“ → „mělo“) a nejasné „jak pracuje“ (kdo?). |

Průřezově se tohoto projektu týká: X2-4.

### Zubní Provázek  <span style="font-weight:400">`zubni-provazek`</span>

**Ponechat:** Audit u této stránky neuvádí žádnou textovou vadu a kontrolní čtení perexu, Výzvy, Řešení i Výsledku ji nenašlo: shody sedí, čísla jsou zdrojovaná (GA, datum měření), žargon žádný. Jediná změna je plošná oprava uvozovek (Q1).

### VP Industry  <span style="font-weight:400">`vp-industry`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výsledek (result) | `Web je technicky připravený na růst — chybí jen aktivní obsahová a kampaňová práce, kterou klient zatím odložil. Potenciál pro organický růst je v infrastruktuře nachystaný a čeká na další fázi. Tohle je férový výsledek: dělám to, co bylo zadáno, a otevřeně říkám, kde leží další kus práce.` | **Klient dostal web, který je technicky hotový: produktové stránky s parametry a videi, správně nastavené SEO, napojenou analytiku a strukturu, která unese další články bez přestavby. Obsahovou a kampaňovou fázi zatím odložil — až ji zapne, nic se nebude muset předělávat.** | Poslední věta je sebeobhajoba, ne výsledek — čtenář se dozví, jak se Ondřej cítí, ne co dostal klient. Nové znění popisuje hotový stav konkrétně a další fázi odbude jednou neutrální větou. Žádný slib výsledku zůstává zachován. |

### Autokemp Veselka  <span style="font-weight:400">`kemp-veselka`</span>

**Ponechat:** Audit u této stránky hlásí jen vizuální nálezy (prázdné sloupce galerie, prázdný CTA blok). Texty jsou v pořádku, citace klientky je uvozená a atribuovaná. Jediná změna je Q1.

### Střechy Zajíc  <span style="font-weight:400">`strechy-zajic`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Řešení (solution) | `Web funguje stejně dobře na desktopu i na chytrém telefonu, kterým si Honzové ze stavby běžně ověřují, „ten chlap jak se jmenuje".` | **Web funguje stejně dobře na počítači i na telefonu — a právě z telefonu si lidé firmu nejčastěji vyhledají.** | Vtip na účet cílovky klienta („Honzové ze stavby“) v oficiální případovce, navíc syntakticky rozpadlý (uvozená část se na větu nenapojuje). Klient si ten text může přečíst. |

### Josef Opa  <span style="font-weight:400">`josefopa`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výzva (challenge) | `Klient potřeboval visačku stavební firmy, která bude působit jako solidní lokální partner v Německu.` | **Klient potřeboval, aby jeho stavební firma v Německu působila jako solidní lokální partner.** | Navazuje na vlnu 1 (OND-256 mění „visačku“ → „vizitku“). I po té opravě věta nefunguje: vztažná věta se váže k vizitce, takže jako solidní partner má působit vizitka, ne firma. <br>**Pozor:** vlna 1 tenhle řetězec mění — staré znění brát až po ní. |
| podtitul (subtitle) | `Vícejazyčná prezentace stavební firmy s vlastním brandem` | **Dvojjazyčná prezentace stavební firmy s vlastním brandem** | Stránka si protiřečí: podtitul a meta říkají „vícejazyčná“, perex „dvojjazyčný web“, meta popis upřesňuje (DE/EN). Dva jazyky = dvojjazyčný. Sjednoceno na konkrétní a ověřitelný fakt. |
| Výzva (challenge) | `Vícejazyčnost byla zásadní — komunikace s klienty probíhá převážně německy, ale poptávky chodí i v angličtině.` | **Dvojjazyčnost byla zásadní — komunikace s klienty probíhá převážně německy, ale poptávky chodí i v angličtině.** | Sjednocení téhož. |
| Řešení (solution) | `Postavil jsem responzivní vícejazyčný web, navrhl výrazné a hravé logo` | **Postavil jsem responzivní dvojjazyčný web v němčině a angličtině, navrhl výrazné a hravé logo** | Sjednocení téhož a doplnění, které dva jazyky to jsou. |
| meta titulek (meta_title) | `Josef Opa — vícejazyčný web stavební firmy v Německu` | **Josef Opa — dvojjazyčný web stavební firmy v Německu** | Sjednocení téhož v SEO titulku. |
| štítek (tag) | `Web ve více jazycích` | **Web ve dvou jazycích** | Štítek u projektu tvrdí „více jazyků“. Engineer: jde o sdílený řádek v portfolio_tag_translations — ověřit, že štítek nevisí i u jiného projektu; podle produkčních dat ho má jen josefopa. |

Průřezově se tohoto projektu týká: X2-5.

_Vlna 1 (OND-256), needěláme znovu:_ `visačku` → `vizitku`

### VAN spedition  <span style="font-weight:400">`vanspedition`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výzva (challenge) | `Klienti potřebují rychle vědět, co umíte, kdo to dělá a jak se ozvat.` | **Zákazníci spedice potřebují rychle vědět, co firma umí, kdo za ní stojí a jak se ozvat.** | Věta popisuje zákazníky spediční firmy, ale „co umíte“ najednou osloví čtenáře webu — adresát se uprostřed věty přehodí. Navíc „klienti“ tu znamená zákazníky klienta, což mate. |

Průřezově se tohoto projektu týká: X2-7.

### YOLK  <span style="font-weight:400">`yolk`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| H1 / název (title) | `YOLK — vývoj na klinikových portálech` | **YOLK — vývoj na webech zdravotnických klinik** | „Klinikový“ není české slovo (správně „klinický“, ale ani to sem nesedí — nejde o klinické studie). Pole title pohání H1 i kartu projektu, takže se ten patvar opakuje na dalších stránkách. |
| Výzva (challenge) | `Agentura má klienty s rozsáhlými portály, kde každý drobný chybný kus tlačí dolů kvalitu zákaznické zkušenosti pacientů. Bylo potřeba spolehlivě udržovat běh, dělat sezónní změny v akcích a dotazníkách a doplňovat custom funkce, které WordPress sám neumí.` | **Agentura má klienty s rozsáhlými weby, na kterých si pacienti hledají péči — každá drobná chyba tam shazuje dojem z celé kliniky. Bylo potřeba weby spolehlivě držet v chodu, dělat sezónní změny v akcích a dotaznících a doplňovat funkce, které WordPress sám neumí.** | Tři vady v jednom odstavci: „v dotazníkách“ → správně „v dotaznících“; „každý drobný chybný kus tlačí dolů kvalitu zákaznické zkušenosti“ je doslovný překlad z angličtiny, nikdo tak nemluví; „custom funkce“ je žargon. |
| Řešení (solution) | `Pracuji jako externí vývojář na konkrétní zakázky — sezónní úpravy, opravy chyb, custom PHP/JS rozšíření a úpravy formulářů. Komunikace probíhá rovnou s týmem agentury, výstup je pro klienty agentury prakticky neviditelný v tom dobrém smyslu — všechno jede.` | **Pracuji jako externí vývojář na konkrétní zakázky — sezónní úpravy, opravy chyb, rozšíření v PHP a JavaScriptu a úpravy formulářů. WordPress na nových webech nestavím, ale když na něm klient už stojí, umím ho opravit a udržet v chodu. Komunikace jde rovnou s týmem agentury a pacient z mojí práce nepozná nic — a přesně o to jde.** | Doplněna rámující věta k WordPressu: web na jiných stránkách WordPress zavrhuje, zatímco tahle případovka otevřeně popisuje práci na něm. Bez jedné věty to vypadá jako rozpor, s ní je to postoj. Zároveň odstraněn „custom“ a vata „prakticky neviditelný v tom dobrém smyslu — všechno jede“. |

Průřezově se tohoto projektu týká: X2-3.

### Elektro Srnák  <span style="font-weight:400">`elektro-srnak`</span>

**Ponechat:** Texty. Audit výslovně chválí, že ukončený hosting je na dvou místech poctivě přiznaný — to neměnit. Mění se jen řádek Doba realizace (X2-8).

Průřezově se tohoto projektu týká: X2-8.

### HCMS  <span style="font-weight:400">`hcms`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Řešení (solution) | `Po analýze procesů na hale jsem navrhl doménový model, use case mapu a postavil webovou aplikaci s tabletovým rozhraním pro operátory a desktopovým pohledem pro vedoucí.` | **Po analýze procesů přímo na hale jsem zmapoval, co všechno se při výpadku dílu děje a kdo v tom hraje jakou roli, a z toho postavil webovou aplikaci — tabletové rozhraní pro operátory, desktopový přehled pro vedoucí.** | „Doménový model“ a „use case mapa“ jsou pojmy z vývojářské analýzy; majiteli firmy neřeknou nic. Nová věta popisuje tutéž práci jako výsledek, ne jako název metody. |

Průřezově se tohoto projektu týká: X2-2.

### Picker  <span style="font-weight:400">`picker`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výzva (challenge) | `Klasické „papír s checklistem" v Toyotě tempo neudržel` | **Klasický papír s checklistem v Toyotě tempo neudržel** | Shoda: „klasické“ se neshoduje s „papír“. Uvozovky tu nic necitují, jen maskují chybnou shodu — pryč s nimi. |
| Řešení (solution) | `monitorovacím dashboardem pro vedoucí a admin sekcí pro konfiguraci procesů` | **přehledovou obrazovkou pro vedoucího a nastavením, ve kterém si firma sama upraví průběh procesů** | „Monitorovací dashboard“ a „admin sekce pro konfiguraci procesů“ jsou tři žargony v jedné větě. Výsledek přitom totéž umí říct lidsky („bez vývojářského zásahu“) — Řešení se tomu jen přizpůsobuje. |

### Choccoboard  <span style="font-weight:400">`choccoboard`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| podtitul (subtitle) | `BI dashboard, který nahradil 30 minut ruční práce denně` | **Přehled prodejních čísel, který nahradil 30 minut ruční práce denně** | „BI dashboard“ je zkratka z oboru; majitel výrobny pralinek ji nemusí znát. Meta karta téhož projektu to už umí česky („Přehled čísel na jedné obrazovce“) — podtitul se k ní srovnává. |
| perex (description) | `Choccoboard je webová aplikace pro vedení firmy, která zpřístupňuje prodejní KPI a klíčové ukazatele v reálném čase z jakéhokoli zařízení.` | **Choccoboard je webová aplikace pro vedení firmy: ukazuje aktuální prodejní čísla a další klíčové ukazatele z jakéhokoli zařízení.** | „Prodejní KPI“ je totéž co „klíčové ukazatele“ hned vedle — zkratka navíc a tautologie. „V reálném čase“ nahrazeno srozumitelným „aktuální“. |
| Řešení (solution) | `jednou přehledovou obrazovkou s KPI a flexibilním filtrováním` | **jednou přehledovou obrazovkou s hlavními čísly a filtrováním** | Zbylá zkratka KPI; „flexibilní filtrování“ je vata — filtrování, které nic neumí, by tam nebylo. |
| meta popis (meta_description) | `Případová studie: webový BI dashboard, který eliminoval 30 minut ručního stahování dat denně.` | **Případová studie: webový přehled prodejních čísel, který ušetřil 30 minut ručního stahování dat denně.** | Tentýž žargon v SEO popisu, na který se klikne ve výsledcích vyhledávání. |

_Vlna 1 (OND-256), needěláme znovu:_ `se k ní dostaneš` → `se k ní dostanete`

### FRL Creator  <span style="font-weight:400">`frl-creator`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| podtitul (subtitle) | `Generátor regálových štítků z proměnných dat` | **Štítky do regálů, které se samy poskládají z aktuálních dat** | „Generátor … z proměnných dat“ je popis pro vývojáře. Nové znění říká totéž z pohledu člověka, který ty štítky potřebuje. |
| Řešení (solution) | `Při testování se rychle odstraňovaly nálezy.` | **Chyby, které se při testování objevily, jsem opravoval průběžně.** | „Nálezy“ je hantýrka z testování. Zároveň trpný rod zakrývá, kdo to dělal — na webu psaném v ich-formě to působí vyhýbavě. |
| Výsledek (result) | `důslednou analýzu procesů a rychlé řešení nálezů během testování (5/5 hvězd)` | **důslednou analýzu procesů a rychlé opravy chyb během testování (5/5 hvězd)** | Tatáž hantýrka v parafrázi reference (mimo uvozovky, takže citát to neposouvá). |
| meta popis (meta_description) | `Analýza procesů, rychlé řešení nálezů, 5/5 hvězd.` | **Analýza procesů, rychlé opravy během testování, 5/5 hvězd.** | Tatáž hantýrka v SEO popisu. |

_Vlna 1 (OND-256), needěláme znovu:_ `chybovo` → `chybově`

### Excel Tools (VBA)  <span style="font-weight:400">`excel-tools`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| kategorie (lang) | `Webová aplikace` | **Aplikace** | Sada VBA maker v Excelu není webová aplikace — kategorie popírá obsah stránky (vlastní štítky projektu říkají „Makra v Excelu“, „Excel“). DOPORUČENÁ VARIANTA: přejmenovat popisek kategorie `application` z „Webová aplikace“ na „Aplikace“ (EN „Application“, DE „Anwendung“). Klíč `projects.detail.category_label.application` v lang/{cs,en,de}/projects.php, žádná datová migrace. Zůstane pravdivý i pro HCMS, Picker, Choccoboard a FRL Creator — u těch stránka sama v podtitulu i ve štítcích říká, že běží v prohlížeči. Proč ne „Aplikace na míru“: tenhle název už na týchž stránkách visí jako štítek v řádku Technologie, kategorie by ho duplikovala. ZÁLOŽNÍ VARIANTA, pokud má filtr „Webová aplikace“ zůstat beze změny: přesunout jen excel-tools do kategorie `other` („Ostatní“) — jednořádková datová migrace, ale sada nástrojů pak sedí vedle loga a cedule. |
| Řešení (solution) | `Šest interních aplikací: FLR Backup 1 (CSV generátor podle parametrů), FLR Backup 2 (krok-za-krokem záloha pro dodávku dílů), Address Data (sloučení dat z více zdrojů), Kanban (generátor PDF kanbanů), Kombinace procesů (vizualizace plánovaných dodávek napříč procesy) a Report (Excel→PDF export s automatickým e-mailem a maskováním dat).` | **Šest nástrojů šitých na konkrétní úkoly. Tři z nich dělají většinu práce: záložní postup pro dodávku dílů na linku, který člověka provede krok za krokem; generátor štítků, který je rovnou vysází do PDF k tisku; a rozesílač reportů, který si data sám vytáhne, skryje citlivé údaje a pošle je e-mailem. Zbylé tři připravují podklady a plánování dodávek.** | Šest interních názvů a zkratek („FLR Backup 1“, „CSV“, „kanban“, „maskování dat“) neřekne nic nikomu mimo Toyotu. Nové znění drží počet šest (fakt), ale popisuje tři nástroje přínosem a zbytek shrne. |

Průřezově se tohoto projektu týká: X2-13.

### Článek na Motorkáři.cz  <span style="font-weight:400">`clanek-motorkari-cz`</span>

**Ponechat:** Texty. Jediná textová vada („motopotálu“) patří vlně 1 (OND-256). Mění se jen Doba realizace (X2-9).

Průřezově se tohoto projektu týká: X2-9.

_Vlna 1 (OND-256), needěláme znovu:_ `motopotálu` → `motoportálu`

### Logo — Realiťačky v Akci  <span style="font-weight:400">`logo-realitacky`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výsledek (result) | `Bylo zdarma součástí webového balíčku, takže celý brand má jednu autorskou ruku a tedy konzistentní pocit.` | **Vzniklo jako součást webového balíčku, takže celý brand má jednu autorskou ruku a drží pohromadě.** | „Zdarma“ snižuje vnímanou hodnotu práce, kterou ta stránka zrovna předvádí — čtenář si odnese, kolik to stálo, ne co to umí. Zároveň „a tedy konzistentní pocit“ je kostrbaté. |

Průřezově se tohoto projektu týká: X2-6.

### Video — Pitbike Akademie  <span style="font-weight:400">`video-pitbike-akademie`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výsledek (result) | `Klient má video formát, který sedí na Facebookové publikum a rozšiřuje povědomí o akademii bez nutnosti rozjíždět drahou videoprodukci. Snadno se reuse-uje pro další sezóny.` | **Klient má formát, který sedí facebookovému publiku a rozšiřuje povědomí o akademii bez drahé videoprodukce. Pro další sezóny se dá použít znovu s minimem úprav.** | Tři vady: „reuse-uje“ je patvar; „Facebookové“ jako přídavné jméno se píše malým písmenem; „sedí na publikum“ → vazba je „sedí publiku“. |

Průřezově se tohoto projektu týká: X2-10.

### Reklamní cedule PitArena  <span style="font-weight:400">`pitarena-cedule`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| Výsledek (result) | `Cedule je nad servisem fyzicky umístěná a posiluje brand každého, kdo do areálu zajede.` | **Cedule visí nad servisem a připomíná značku každému, kdo do areálu zajede.** | Logický lapsus: takhle cedule posiluje značku návštěvníků, ne PitAreny. Navíc „je fyzicky umístěná“ je úřednické. |

Průřezově se tohoto projektu týká: X2-12.

### Animace pro upoutání pozornosti  <span style="font-weight:400">`animace-delejme`</span>

| Pole | Staré znění (doslovně) | Nové znění | Proč |
|---|---|---|---|
| H1 / název (title) | `Animace upoutání pozornosti` | **Animace pro upoutání pozornosti** | Chybí předložka — bez ní to čteme jako „animace něčeho“. Pole title pohání H1 i kartu projektu. |
| meta titulek (meta_title) | `Animace upoutání pozornosti — ukázka pro sociální sítě` | **Animace pro upoutání pozornosti — ukázka pro sociální sítě** | Totéž v SEO titulku. |
| Výzva (challenge) | `Většina obsahu na sociálních sítích projde kolem člověka aniž by si ho všiml.` | **Většina obsahu na sociálních sítích projde kolem člověka, aniž by si jí všiml.** | Před „aniž“ patří čárka. Zájmeno „ho“ navíc míří na „člověka“, ne na obsah — správně „jí“ (většiny). |
| slug | `animace-delejme` | **animace-pro-upoutani-pozornosti** | Slug „delejme“ se k názvu ani obsahu nevztahuje — je to zbytek po jiném projektu a v adrese ho návštěvník vidí. Engineer: změna slugu vyžaduje 301 přesměrování ze staré adresy a promítnutí do sitemapy; pokud je to nad rámec balíčku, nechat na samostatnou kartu. |

Průřezově se tohoto projektu týká: X2-11.

---

## Vlna 1 — co už řeší OND-256 (needěláme znovu)

| Projekt | Pole | Z | Na |
|---|---|---|---|
| BARANA | perex (description) | `Postavil jsem premiové prezentační stránky` | `Postavil jsem prémiové prezentační stránky` |
| Nové interiéry | Výsledek (result) | `že si zákazníci dopředu vědomí` | `že zákazníci dopředu vědí` |
| Josef Opa | Výzva (challenge) | `visačku` | `vizitku` |
| Choccoboard | Řešení (solution) | `se k ní dostaneš` | `se k ní dostanete` |
| FRL Creator | Výzva (challenge) | `chybovo` | `chybově` |
| Článek na Motorkáři.cz | Výsledek (result) | `motopotálu` | `motoportálu` |

Ověřeno v `database/migrations/2026_09_22_110000_ond256_opravy_textu_pripadovek.php`. Past s `chybovo` uvnitř `chybovost` je tam už ošetřená (matchuje se `chybovo a se zbytečnou`) — při psaní vlny 2 stejnou past hlídat znovu.

---

## Co potřebuje jedno slovo od Ondřeje (nic z toho nebrzdí nasazení)

Ke každé položce je níž uvedená bezpečná varianta, kterou Engineer může nasadit hned. Potvrzení ji jen vylepší.

1. **Citace u Cyklo Centra.** Nasazuju parafrázi bez uvozovek („za kterou se nemusí stydět“), protože doslovné znění reference nemám z čeho ověřit a nechci klientovi vkládat do úst větu, kterou neřekl. Když se původní znění najde, vrátíme uvozovky.
2. **Doba realizace u deseti projektů** (Realiťačky, Josef Opa, Logo, VAN spedition, Elektro Srnák, Článek, Video, Cedule, Animace, Excel Tools). Bezpečná varianta = řádek vypustit. Když Ondřej ví „to bylo tak tři týdny“, řekne číslo a nasadí se místo vypuštění.
3. **Kategorie u Excel Tools.** Doporučuju přejmenovat popisek `application` na „Aplikace“ (EN „Application“, DE „Anwendung“). Kdyby měl filtr „Webová aplikace“ zůstat, záložní varianta je přesunout jen Excel Tools do „Ostatní“.
4. **Značka PitArena vs PitAréna.** Sjednocuju na **PitArena** — tak to má pitarena.cz i doména. Vlastní e-shop klienta (shop.pitarena.cz) ale píše „PitAréna“; pokud je to záměr klienta, sjednotíme opačně.
5. **Slug `animace-delejme`.** Návrh `animace-pro-upoutani-pozornosti` potřebuje 301 přesměrování a zásah do sitemapy — pokud je to nad rámec tohohle balíčku, nechat na samostatnou kartu a slug zatím neměnit.

