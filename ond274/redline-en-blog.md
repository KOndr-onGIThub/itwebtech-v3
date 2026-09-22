# OND-274 — Redline zbývajících čtyř EN článků

Dopsání anglického blogu tak, aby se kryl s přepsanou CS verzí. Formát a nároky
jsou stejné jako u části C redlinu OND-262 (EN článek o ceně): hotová znění po
polích databáze, k nasazení beze změn.

**Předloha obsahu:** české verze z `database/seeders/BlogContentSeeder.php`
(OND-204). **Kontrola struktury:** DE verze z `BlogContentDeSeeder.php`
(OND-219) — u článku o ceně se to osvědčilo, tak stejně i tady. EN mutace má
proto stejný počet `<h2>`, `<li>` i blockquotů jako DE a čte se odstavec po
odstavci stejně.

**Co se nemění:** CS ani DE verze. Článek 3 (cena) — ten je hotový v OND-262.
Obrázková pole (`img_preview`, `img_main`, `img_mid`, `img_end`), `bonus`,
`extra` — beze změny, stejně jako u DE. Texty jsou psané tak, aby nezávisely na
tom, jaká ilustrace je vedle nich (rozhodnutí 2 pro board na OND-259 zatím
nepadlo).

## Jak si to ověřit

```bash
python3 ond274/build-redline.py    # kontroly + zápis redline.json
python3 ond274/build-md.py         # regenerace tohohle dokumentu
```

Skript **nic nemění**, jen kontroluje a zapisuje; návratový kód != 0 = neshoda.
Ověřuje čtyři věci:

| # | Co | Proti čemu |
|---|---|---|
| 1 | každý citovaný starý anglický fragment v tabulkách „Co tím padá" | živý text produkce v `ond262/pages/b-en-*.txt` (staženo 22. 9. 2026) |
| 2 | každý český úsek, ze kterého nová EN verze vychází | `database/seeders/BlogContentSeeder.php` na `origin/staging` |
| 3 | částky v eurech | DE verze — EN a DE čtenář musí vidět stejné číslo, ne dva různé přepočty korun |
| 4 | počet `<h2>`, `<li>` a blockquotů | `BlogContentDeSeeder.php` |

Navíc kontroluje, že navržený nový slug nekoliduje s žádným slugem v
`database/sql/article_slugs.sql` ani s novými CS/DE slugy ze seederů —
`PageController::article()` hledá slug napříč locale, kolize by znamenala 301 na
cizí článek.

## Pro Engineera — kde to nasadit

EN překlady článků **nikdy nebyly v seederu**. Pocházejí ze starého SQL importu
`database/sql/article_translations.sql`, který `EnsureArticlesSeededSeeder` pouští
jen do prázdné tabulky. Vzor je `BlogContentDeSeeder` + migrace
`2026_09_16_120000_seed_de_blog_content.php`; potřeba je jejich anglický
protějšek, jinak se změna na čerstvé DB neprojeví. Tahle karta je jen podklad —
nasazení má vlastní kartu.

Dvě věci, které si vezmi z DE seederu, ne z CS:

- **Obrázky přebírej z CS řádku** (`imagesFromCs()`, `IMAGE_COLUMNS`). Jinak bude
  EN karta ve výpisu bez náhledu a detail bez hera.
- **Nepoužívej `updateOrInsert`** na `article_translations` — přepsal by
  `created_at`. DE seeder to řeší větví `exists()` → `update()` / `insert()`.

A jedna drobnost: v textech jsou **typografické uvozovky a apostrofy** (`“ ” ’`),
ne rovné. Je to anglická konvence, ne překlep — prosím nepřepisovat na `" '`.
Stejně tak `€4,000` má čárku jako oddělovač tisíců (EN), ne tečku (CS/DE).

---

# ČÁST A — hotová znění po polích

## A1 — článek 10, „potřebuje firma web"

**Rozsah:** kompletní náhrada obsahu. Slug `does-your-business-need-a-website`
zůstává — sedí na nový titulek a je to funkční SEO adresa.

**Kde to žije:** `article_translations`, `article_id = 10`, `locale = 'en'`.

| | |
|---|---|
| CS předloha | *Potřebuje vaše firma web? Někdy ne a řeknu vám kdy* |
| DE kontrola | *Braucht Ihre Firma eine Website? Manchmal nicht* |
| Rozsah | EN 2756 znaků čistého textu (CS 2270 · DE 2982) |

### `title`

```
Does your business need a website? Sometimes not
```

### `description` (meta popis, max 500 znaků)

```
I am not impartial about this — I build websites. Even so, there are situations where a website will not help you. Here is which ones and what to do instead.
```

### `perex`

```html
<blockquote><p>I make my living building websites, so I am not writing this impartially and I will not pretend otherwise. Even so, I know cases where a website does not help a company and the money is better spent elsewhere. Here it is straight: which ones.</p></blockquote>
```

### `content_1`

```html
<h2>When you really do not need a website</h2>
<p><strong>You are fully booked and the work comes from referrals.</strong> If you have months of work ahead of you and you would turn new enquiries down anyway, a website will bring you nothing right now. Come back to it when you want to grow or to change the kind of work you take.</p>
<p><strong>You sell to one large customer.</strong> If your company stands on two long-term contracts, a website is a business card, not a sales channel. A simple page with contact details is enough, and you do not need to spend €4,000 on it.</p>
<p><strong>There is nobody to pick up the phone.</strong> A website that brings in enquiries nobody answers is worse than no website at all. The customer remembers that you did not get back to them.</p>
<p><strong>You are looking for a miracle.</strong> A website is a tool, not a solution. If the company is not clear about what it sells and to whom, a website will not fix that. It will only write it in bigger letters.</p>
<h2>When a website does make sense</h2>
<p><strong>People check you out before they call.</strong> Almost everyone does this now. If all they find is a directory listing from 2019, they mark you down.</p>
<p><strong>You keep explaining the same thing.</strong> If you repeat in every meeting how the work goes and what is included in the price, the website explains it for you. You then talk to people who already know.</p>
<p><strong>Your competitors look better than they work.</strong> That is uncomfortable, but it decides.</p>
<p><strong>You want different work from the work you have.</strong> A website is the cheapest way to show that you do bigger and more demanding jobs too.</p>
```

### `content_mid`

```html
<blockquote><p>A website will not win the work for you. But it says up front what you otherwise explain again in every meeting.</p></blockquote>
```

### `content_2`

```html
<h2>What a website cannot do</h2>
<p>I will not promise you how many enquiries it will bring. I have no influence over what the demand in your field looks like, what your prices are, or how fast you answer. Anyone who promises you that number is guessing.</p>
<p>What I can influence is the work I deliver. That the website is fast and understandable, that it looks good on a phone, and that nothing falls apart on it in two years.</p>
<h2>Before you decide</h2>
<p>Try to answer one question. If someone who had never heard of you landed on your website tomorrow, would they understand within ten seconds what you do and whether it is for them? If not, that is where the problem is, and it makes no difference whether you have a website or not.</p>
```

### Co tím padá

| Nález ve staré EN verzi | Proč |
|---|---|
| `In this article, we'll look at the various aspects and arguments` | Redakční „we" u jednočlenné firmy; zbytek webu mluví v ich-formě. |
| `The COVID-19 pandemic has accelerated the shift to digital channels.` | Datovaný obsah z roku 2020, v roce 2026 působí jako neudržovaný text. |
| `don't miss my article What is SEO .` | Odkaz na článek 5 („Co je SEO"), od OND-204 depublikovaný (UNPUBLISHED_ARTICLE_IDS). Není v REMOVED_ARTICLE_REDIRECTS, takže `redirectRemovedArticle()` čtenáře 301 vyhodí na výpis blogu — kliknul na slíbený článek o SEO a přistane nikde. Navíc mezera před tečkou. |
| `Ten Reasons Why You Need a Website` | Osnova 6 + 10 + 10 + 5 bodů, kterou CS ani DE verze nemají; 31 odrážek místo rozhodovacího vodítka. |
| `A small coffee shop will implement analytics tools on its website` | Vymyšlené modelové příběhy bez vazby na Ondrovy reference. |
| `Amazon or Etsy` | Doporučuje čtenáři cizí prodejní platformy místo vlastního webu — nesouvisí s nabídkou. |

---

## A2 — článek 4, příprava na nový web (+ nový slug)

**Rozsah:** kompletní náhrada obsahu **a nový slug** — viz ČÁST B níž.

**Kde to žije:** `article_translations` + `article_slugs`, `article_id = 4`,
`locale = 'en'`.

| | |
|---|---|
| CS předloha | *Co si připravit, než oslovíte vývojáře webu* |
| DE kontrola | *Neun Fragen, die Sie vor dem Website-Projekt klären* |
| Rozsah | EN 2951 znaků čistého textu (CS 2522 · DE 3167) |

### `title`

```
What to prepare before you contact a web developer
```

### `description` (meta popis, max 500 znaků)

```
Nine questions we will have to answer anyway. If you go through them in advance, we both save time and you get a more accurate price the first time round.
```

### `perex`

```html
<blockquote><p>Almost nobody writes to me with a finished brief, and that is fine. Asking is what I am here for. But if you go through the questions below in advance, we cut the whole round trip by a few weeks and you get a more accurate price straight away.</p></blockquote>
```

### `content_1`

```html
<h2>You do not need an answer to everything</h2>
<p>This article is not a test. It is a list of things I am going to ask about anyway. If you know the answer, write it to me straight away. If you do not, write “I do not know” and we will go through it together. That is a perfectly legitimate answer and I hear it often.</p>
<h2>1. What the website should do for your company</h2>
<p>Should it bring in enquiries? Save you phone calls because people read the answers for themselves? Sell? Or just exist so that nobody rules you out of a tender? Those are all valid goals, but they lead to three different websites.</p>
<h2>2. Who it is for</h2>
<p>The owner of a small company, a buyer at a large one and an end customer all read completely differently. The more specifically you describe who calls you today, the better I can write the page structure.</p>
<h2>3. What a person should do on the website</h2>
<p>One main thing per page. Call, fill in the form, download the price list, order. When a website is meant to do five things at once, it does none of them properly.</p>
<h2>4. What people do not know about you and should</h2>
<p>This is the most valuable thing you can give me. Usually it is something you say in meetings over and over and it is missing from the website.</p>
<h2>5. What does not work on your current website</h2>
<p>If you already have a website, write to me specifically about what annoys you on it. Can it not be edited? Can it not be found? Does it look old? Does nothing come through it?</p>
```

### `content_mid`

```html
<blockquote><p>The worst brief is “make it look nice”. The best one is “this specific thing annoys me”.</p></blockquote>
```

### `content_2`

```html
<h2>6. Who will manage the content</h2>
<p>If you want to change texts and photos yourself, I will build you simple content management and show you how it works. If you do not, we do not have to build it and you save money. Either is fine, I just need to know in advance.</p>
<h2>7. What you already have</h2>
<p>A logo, photos, texts, access to the domain and the hosting, a shop with products in some system. The more of it there is, the less has to be made from scratch.</p>
<h2>8. What your budget is</h2>
<p>I know nobody likes this question. I ask it so that I can tell you straight away whether I can do it for that price. If I cannot, I say so immediately and neither of us wastes time.</p>
<h2>9. When you need it by</h2>
<p>If you have a fixed date because of a trade fair or an opening, tell me right at the start. That is how I know whether I can make it.</p>
<h2>What happens next</h2>
<p>From your answers I write a specification. It says what I will build and a price that holds. Only then do you decide whether we go ahead. You sign nothing up front.</p>
```

### Co tím padá

| Nález ve staré EN verzi | Proč |
|---|---|
| `With our guide you will learn the key questions` | Druhé „our" na webu (první našel audit v článku o ceně, P1-4) — agenturní množné číslo u sólo živnostníka. |
| `create a website that exactly matches your ideas!"` | Perex končí osamocenou uvozovkou — zbytek po nedotaženém copy-paste. |
| `The story of Martin and his journey to the perfect side` | „side" místo „site"; celý fiktivní příběh o kavárníkovi v CS ani DE není. |
| `He decided that on the site should:` | Rozpadlá věta bez předmětu — strojový překlad. |
| `one of which cost 20.000,- CZK and the other 120.000,- CZK` | České korunové částky a český zápis „20.000,-" v anglickém textu; navíc čísla neodpovídají EN ceníku (€1,000 / €2,200 / od €3,800). |
| `Read more about SEO in this article What is SEO and why is it so important?` | Druhý odkaz na depublikovaný článek 5 — stejné 301 na výpis blogu. |
| `To submit a demand to a developer` | Doslovný překlad „podat poptávku"; anglicky nedává smysl. |

---

## A3 — článek 13, redesign webu

**Rozsah:** kompletní náhrada obsahu. Slug
`website-redesign-reasons-signals-and-how-to-do-it` zůstává: „reasons, signals and
how to do it" pořád popisuje, co v článku je (důvody, signály, postup), a adresa
je zaběhlá. Měnit ji jen kvůli délce by stálo víc, než by přineslo.

**Kde to žije:** `article_translations`, `article_id = 13`, `locale = 'en'`.

| | |
|---|---|
| CS předloha | *Kdy má smysl předělat web a kdy je to vyhozený výdaj* |
| DE kontrola | *Wann ein Website-Relaunch Sinn ergibt und wann nicht* |
| Rozsah | EN 3269 znaků čistého textu (CS 2734 · DE 3529) |

### `title`

```
When a website redesign is worth it and when it is money wasted
```

### `description` (meta popis, max 500 znaků)

```
Five reasons a website redesign makes sense and three reasons it does not. Plus what to watch out for so that your traffic does not drop after the relaunch.
```

### `perex`

```html
<blockquote><p>A redesign usually comes up the moment somebody stops liking the website. That is the weakest reason I know. Here is when redoing a website makes sense, when it does not, and what most often goes wrong along the way.</p></blockquote>
```

### `content_1`

```html
<h2>Five reasons a redesign makes sense</h2>
<p><strong>1. The website cannot be maintained.</strong> Changing a phone number means writing to somebody who gets back to you in a week. That on its own is worth a redesign.</p>
<p><strong>2. It is unusable on a phone.</strong> Most people look at websites on a phone these days. If they have to zoom in and scroll sideways, they leave.</p>
<p><strong>3. The website is falling apart or going down.</strong> Typically with page builders assembled from plugins by different authors. One update and the order form stops working.</p>
<p><strong>4. The company has changed.</strong> You do something different, you want to sell something different, you are in a different price bracket. The website stayed where you were five years ago.</p>
<p><strong>5. The websites of the people you compete with look a class better.</strong> The customer compares you side by side whether you like it or not.</p>
<h2>Three situations where you should keep your money</h2>
<p><strong>The website is two years old and it works.</strong> Age on its own is not a reason. If it can be maintained, it is fast and people find what they need on it, leave it alone.</p>
<p><strong>You do not like it, but your customers do not mind it.</strong> Your taste and your customer’s taste are not the same thing. Before you put a few thousand euros into it, ask five clients what they were missing on the website.</p>
<p><strong>The real problem is somewhere else.</strong> If enquiries are not coming because you are three classes more expensive than everyone around you and you explain that nowhere, a new look will not fix it.</p>
```

### `content_mid`

```html
<blockquote><p>A redesign is work on the content and the structure. The look is what follows from it.</p></blockquote>
```

### `content_2`

```html
<h2>What most often goes wrong in a redesign</h2>
<p><strong>The page addresses get thrown away.</strong> The new website has a different structure and the old addresses stop working. Search engines and links from other people’s websites suddenly lead nowhere. The fix is simple and it is done before launch: the old address has to redirect permanently to the new one. I want you to demand that from whoever builds your website.</p>
<p><strong>Only the look gets redone.</strong> The texts are copied across one to one, including the ones nobody understood. The website then looks new and works just as badly.</p>
<p><strong>Things that were working disappear.</strong> Sometimes the old website has a page that half the traffic goes to. Before anything is deleted, somebody needs to look at the statistics.</p>
<p><strong>Nobody carries the content over.</strong> References, photos of finished jobs, documents to download. There is usually more of it than anyone expects.</p>
<h2>How I approach a redesign</h2>
<p>First I look at what works on the old website and I keep that. Then we go through what the website is supposed to do and who it is supposed to say it to. Only after that do we deal with how it will look. I sort the page addresses out before launch, not after.</p>
<p>I write my own code, without ready-made plugins by other authors. Those are usually the reason a website falls apart after a while and has to be redone.</p>
```

### Co tím padá

| Nález ve staré EN verzi | Proč |
|---|---|
| `We examine how people use the site` | Redakční „we" — a slibuje uživatelský výzkum, který v nabídce není. |
| `up to 94% of a visitor's first impression is related to the visual design` | Nedoložená statistika bez zdroje. |
| `88% of users will not return to a website` | Nedoložená statistika bez zdroje. |
| `can increase conversion rates by up to 200%` | Nedoložená statistika; navíc slib výsledku, který web sám neovlivní. |
| `over 80% of businesses redesign their existing websites` | Nedoložená statistika bez zdroje. |
| `Crazy Egg` | Cizí případovka místo vlastní reference. |
| `source: davidkoci.cz` | Jako zdroj je uvedený web jiného českého webaře — konkurence citovaná na vlastním blogu. |
| `The UX designer will design the optimal structure` | Popisuje proces agenturního týmu (UX designér, grafik, vývojový tým), zatímco web staví na „pracuju sám". |
| `I recommend reading the article How to Create a Successful Website .` | Odkaz na článek 11, depublikovaný v OND-204. REMOVED_ARTICLE_REDIRECTS ho 301 posílá na článek 4, tedy na úplně jiné téma než slibuje anchor text. Opět mezera před tečkou. |

---

## A4 — článek 6, tělo článku o aplikaci na míru

**Rozsah:** `perex` + tělo. `title`, `description` a nový slug
`when-a-custom-app-beats-a-spreadsheet` jsou v **části D redlinu OND-262** —
nasazují se spolu s tímhle, ne zvlášť, jinak si stránka odporuje.

> **Pozor — `perex` v OND-262 není.** Část D uvádí v tabulce jen
> `article_translations.title` a `article_translations.description`. `perex` je
> samostatný sloupec a dnes v něm stojí *„A web application can save your business
> millions by streamlining communications…"*. Kdyby se nasadila jen část D, nový
> titulek „When a custom app is worth it instead of a spreadsheet" by měl hned pod
> sebou starý slib milionů. Proto je `perex` tady.

**Kde to žije:** `article_translations`, `article_id = 6`, `locale = 'en'`.

| | |
|---|---|
| CS předloha | *Kdy se firmě vyplatí aplikace na míru místo tabulky v Excelu* |
| DE kontrola | *Wann sich eine eigene Anwendung statt Excel lohnt* |
| Rozsah | EN 3041 znaků čistého textu (CS 2789 · DE 3530) |

### `perex`

```html
<blockquote><p>I worked at Toyota for eighteen years. I started as a labourer in logistics and I finished as a senior specialist in the project team. In that time I saw plenty of processes running on spreadsheets and paper, and I replaced a few of them with an application. Here is how you can tell that you are at that point too.</p></blockquote>
```

### `content_1`

```html
<h2>The spreadsheet is not the enemy</h2>
<p>Excel is an excellent tool and plenty of companies can run on it for years without trouble. I am not going to talk you into needing an application. Most of the time you do not.</p>
<p>The problem starts the moment several people work in the spreadsheet at once, the moment something gets printed from it that people then work to, and the moment a mistake in it costs money. That is where the question starts to make sense.</p>
<h2>Five signs the spreadsheet has hit its limit</h2>
<ol>
<li><strong>There are several versions of the same spreadsheet</strong> and nobody knows exactly which one is the valid one.</li>
<li><strong>Somebody retypes data from one system into another.</strong> By hand, every day, over and over.</li>
<li><strong>When that person is off sick, the work stops.</strong> The process hangs on one person and their spreadsheet.</li>
<li><strong>Mistakes are found late.</strong> A typo in a cell only shows up at the customer.</li>
<li><strong>Nobody can say where things stand right now.</strong> The number has to be assembled from five files.</li>
</ol>
<p>If one of those fits, leave it be. If three or more fit, it is worth doing the sums.</p>
```

### `content_mid`

```html
<blockquote><p>You do not need an application because it is modern. You need one when the manual work costs you more than building it does.</p></blockquote>
```

### `content_2`

```html
<h2>What I did at Toyota</h2>
<p>My job was to make logistics more efficient in step with assembly. In production you cannot afford for the line to stop, so every change has to be thought through in advance and tested.</p>
<p>I programmed a web application there called TSM that replaced part of the manual work in logistics. Over time I put several more applications into operation.</p>
<p>None of it was about spectacular technology. It was about finding the place where time was being wasted and removing that place. I think the same way today when I build a custom application for a company.</p>
<h2>How to do the sums yourself</h2>
<p>Take an activity that is done by hand. How many minutes a day does it take? How often a month does a mistake happen with it, and what does that mistake cost? Multiply it by twelve months. If you end up with a number in the thousands of euros a year, a custom application pays for itself in a few years and after that it only saves. If you end up with a few hundred euros, leave it alone and buy yourself a decent spreadsheet instead.</p>
<p>I will do this calculation for you free of charge on our first call. If it comes out that it is not worth it, I will tell you.</p>
<h2>What a custom application is and what it is not</h2>
<p>It is a program built around exactly how your company works. Records, orders, planning, reports. It runs in a browser, so you install nothing and you can get to it from your phone too.</p>
<p>It is not an off-the-shelf system that you have to adapt to. That is the main difference, and also the reason it costs more than a monthly subscription to something in a box.</p>
```

### Co tím padá

| Nález ve staré EN verzi | Proč |
|---|---|
| `Amazon , which started as an online bookstore, has grown to become the largest company in the world.` | Věcně nepravdivé tvrzení; navíc mezera před čárkou po odkazu. |
| `Shopify is set to make $3.7 billion in 2022` | Dolarové číslo z roku 2022 v textu, který má prodávat aplikaci na míru českému a německému klientovi. |
| `You can see how much my app saved for Toyota in this project of mine .` | Nedokončená věta s vloženým odkazem a mezerou před tečkou — stejná vada jako P1-4 v článku o ceně. |
| `Having a web development team to ensure the success of your project` | Radí čtenáři vývojový tým; Ondra pracuje sám. |
| `You can choose a company that is experienced` | Posílá čtenáře ke konkurenci. |
| `a web app can save your business millions` | Slib milionových úspor, který text nedokládá — důvod pro nový slug i titulek (OND-262 část D). |

---

# ČÁST B — nový EN slug článku 4

Dnes: `/en/blog/how-to-define-website-development-requirements`.

Stejná situace jako u článku 6 v části D redlinu OND-262. CS i DE tohle framování
opustily — CS má `jak-se-pripravit-na-novy-web` (`BlogContentSeeder::NEW_CS_SLUGS`),
DE `vorbereitung-auf-die-neue-website` (`BlogContentDeSeeder::DE_SLUGS`). EN
zůstalo samo u „define development requirements".

| Co | Staré | Nové |
|---|---|---|
| slug (`article_slugs.slug`, `article_id=4`, `locale='en'`) | `how-to-define-website-development-requirements` | `how-to-prepare-for-a-new-website` |

**Proč tenhle tvar.** Odpovídá obsahu (devět otázek, co si připravit) i novému
titulku *„What to prepare before you contact a web developer"* — adresa a nadpis si neodporují, což byl u části D
hlavní důvod, proč se slug neměnil sám. Zrcadlí CS i DE slug, takže tři mutace
téhož článku mluví o téže věci. Trefuje reálný search intent
(`how to prepare for a new website`, `what to prepare before hiring a web developer`)
a nikomu neslibuje metodiku sběru požadavků, kterou článek nedodává.

Zvažoval jsem `what-to-prepare-before-hiring-a-web-developer` — je blíž titulku,
ale je o polovinu delší a zbytečně zužuje na „hiring". Kratší tvar pokrývá i
čtenáře, který web teprve zvažuje.

**301 se neprogramuje.** Mechanismus už existuje: starý slug zůstane v
`article_slugs` jako `active = 0` a `PageController::article()` na něj udělá 301 —
přesně jako u CS slugů v OND-204. Nový slug je unikátní napříč celou tabulkou
(ověřeno skriptem proti `database/sql/article_slugs.sql` a proti novým CS/DE/EN
slugům ze seederů).

**Mapu přesměrování to nerozbije.** `PageController::REMOVED_ARTICLE_REDIRECTS`
posílá stažené články `7`, `8` a `11` právě na článek 4. Mapuje ale na **id**, ne
na slug, a cílovou adresu si dohledává přes `->slug($locale)` — po změně EN slugu
tedy 301 dál vede správně. Ověřeno čtením `PageController::article()`, ne během
aplikace.

---

# ČÁST C — co zůstává otevřené

1. **`perex` článku 6 není v OND-262.** Viz poznámka u A4 výš. Kdo bude nasazovat
   část D redlinu OND-262, musí vzít `perex` odtud, jinak zůstane starý slib
   milionů pod novým titulkem.
2. **Druhé „our" na webu.** Audit (P1-4) označil `our comprehensive guide`
   v článku o ceně za jediný výskyt agenturního množného čísla. Není — perex
   článku 4 má `With our guide you will learn`. Tímhle redlinem padá, ale stojí
   za zmínku, že nález v auditu byl podhodnocený.
3. **Tři odkazy na depublikované články.** Články 10 a 4 odkazují na *What is SEO*
   (článek 5), článek 13 na *How to Create a Successful Website* (článek 11).
   Oba cíle jsou od OND-204 depublikované (`UNPUBLISHED_ARTICLE_IDS`).
   `PageController::redirectRemovedArticle()` čtenáře nenechá na 404, ale pošle
   ho jinam: článek 5 není v `REMOVED_ARTICLE_REDIRECTS`, takže 301 končí na
   výpisu blogu; článek 11 má mapování na článek 4, takže 301 vede na text o
   přípravě na nový web. V obou případech čtenář dostane něco jiného, než mu
   anchor text slíbil. Tímhle redlinem odkazy mizí. **Nekontroloval jsem, jestli
   stejné odkazy nezůstaly i jinde** (CS/DE mutace, lang soubory, případovky) —
   to je práce na crawl, ne na copy.
4. **Obrázky v článcích.** Mimo rozsah, čeká na rozhodnutí 2 pro board na
   OND-259. Texty na nich nestojí.
5. **Osmnáctiletá Toyota v článku 6.** Beru ji z CS verze jako fakt ověřený
   v OND-204, sám jsem ji neverifikoval.
