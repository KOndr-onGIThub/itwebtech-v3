#!/usr/bin/env python3
"""OND-274 — vygeneruje ond274/redline-en-blog.md ze stejného zdroje jako redline.json.

Texty žijí na jednom místě (`build-redline.py`), aby se .md a .json nikdy
nerozešly. Spouštěj až po `build-redline.py` (ten kontroluje vstupy).

    python3 ond274/build-redline.py && python3 ond274/build-md.py
"""
import importlib.util
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))

spec = importlib.util.spec_from_file_location(
    'redline', os.path.join(HERE, 'build-redline.py'))
R = importlib.util.module_from_spec(spec)
sys.argv = [sys.argv[0]]  # ať si build-redline nebere náš argv jako STG
spec.loader.exec_module(R)

ORDER = [10, 4, 13, 6]

HEADINGS = {
    10: ('A1', 'článek 10, „potřebuje firma web"'),
    4:  ('A2', 'článek 4, příprava na nový web (+ nový slug)'),
    13: ('A3', 'článek 13, redesign webu'),
    6:  ('A4', 'článek 6, tělo článku o aplikaci na míru'),
}

# Délka čistého textu CS a DE mutace téhož článku — kontext k rozsahu EN verze.
# Měřeno ze seederů na origin/staging (stejná metoda jako plain() níž).
SIBLING_LENGTHS = {10: (2270, 2982), 4: (2522, 3167), 13: (2734, 3529),
                   6: (2789, 3530)}

NOTES = {
    10: """**Rozsah:** kompletní náhrada obsahu. Slug `does-your-business-need-a-website`
zůstává — sedí na nový titulek a je to funkční SEO adresa.

**Kde to žije:** `article_translations`, `article_id = 10`, `locale = 'en'`.""",
    4: """**Rozsah:** kompletní náhrada obsahu **a nový slug** — viz ČÁST B níž.

**Kde to žije:** `article_translations` + `article_slugs`, `article_id = 4`,
`locale = 'en'`.""",
    13: """**Rozsah:** kompletní náhrada obsahu. Slug
`website-redesign-reasons-signals-and-how-to-do-it` zůstává: „reasons, signals and
how to do it" pořád popisuje, co v článku je (důvody, signály, postup), a adresa
je zaběhlá. Měnit ji jen kvůli délce by stálo víc, než by přineslo.

**Kde to žije:** `article_translations`, `article_id = 13`, `locale = 'en'`.""",
    6: """**Rozsah:** `perex` + tělo. `title`, `description` a nový slug
`when-a-custom-app-beats-a-spreadsheet` jsou v **části D redlinu OND-262** —
nasazují se spolu s tímhle, ne zvlášť, jinak si stránka odporuje.

> **Pozor — `perex` v OND-262 není.** Část D uvádí v tabulce jen
> `article_translations.title` a `article_translations.description`. `perex` je
> samostatný sloupec a dnes v něm stojí *„A web application can save your business
> millions by streamlining communications…"*. Kdyby se nasadila jen část D, nový
> titulek „When a custom app is worth it instead of a spreadsheet" by měl hned pod
> sebou starý slib milionů. Proto je `perex` tady.

**Kde to žije:** `article_translations`, `article_id = 6`, `locale = 'en'`.""",
}

FIELDS = ('title', 'description', 'perex', 'content_1', 'content_mid', 'content_2')


def plain(article):
    body = ''.join(str(article.get(f) or '') for f in FIELDS)
    return len(re.sub(r'\s+', ' ', re.sub(r'<[^>]+>', '', body)))


def main():
    out = []
    w = out.append

    w('# OND-274 — Redline zbývajících čtyř EN článků\n')
    w("""Dopsání anglického blogu tak, aby se kryl s přepsanou CS verzí. Formát a nároky
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
nepadlo).\n""")

    w('## Jak si to ověřit\n')
    w("""```bash
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
cizí článek.\n""")

    w('## Pro Engineera — kde to nasadit\n')
    w("""EN překlady článků **nikdy nebyly v seederu**. Pocházejí ze starého SQL importu
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
Stejně tak `€4,000` má čárku jako oddělovač tisíců (EN), ne tečku (CS/DE).\n""")

    # ---- část A/B: články
    w('---\n')
    w('# ČÁST A — hotová znění po polích\n')
    for aid in ORDER:
        art = R.ARTICLES[aid]
        code, heading = HEADINGS[aid]
        w(f'## {code} — {heading}\n')
        w(NOTES[aid] + '\n')
        cs_len, de_len = SIBLING_LENGTHS[aid]
        w(f'| | |\n|---|---|\n'
          f'| CS předloha | *{art["cs_title"]}* |\n'
          f'| DE kontrola | *{art["de_title"]}* |\n'
          f'| Rozsah | EN {plain(art)} znaků čistého textu '
          f'(CS {cs_len} · DE {de_len}) |\n')
        for field in FIELDS:
            value = art.get(field)
            if value is None:
                continue
            extra = ' (meta popis, max 500 znaků)' if field == 'description' else ''
            w(f'### `{field}`{extra}\n')
            fence = '' if field in ('title', 'description') else 'html'
            w(f'```{fence}\n{value}\n```\n')

        kills = [(f, why) for a, f, why in R.KILLS if a == aid]
        if kills:
            w('### Co tím padá\n')
            w('| Nález ve staré EN verzi | Proč |\n|---|---|')
            for fragment, why in kills:
                frag = fragment.replace('|', '\\|')
                w(f'| `{frag}` | {why} |')
            w('')
        w('---\n')

    # ---- část B: slug
    w('# ČÁST B — nový EN slug článku 4\n')
    art4 = R.ARTICLES[4]
    w(f"""Dnes: `/en/blog/{art4['slug']}`.

Stejná situace jako u článku 6 v části D redlinu OND-262. CS i DE tohle framování
opustily — CS má `jak-se-pripravit-na-novy-web` (`BlogContentSeeder::NEW_CS_SLUGS`),
DE `vorbereitung-auf-die-neue-website` (`BlogContentDeSeeder::DE_SLUGS`). EN
zůstalo samo u „define development requirements".

| Co | Staré | Nové |
|---|---|---|
| slug (`article_slugs.slug`, `article_id=4`, `locale='en'`) | `{art4['slug']}` | `{art4['slug_change']}` |

**Proč tenhle tvar.** Odpovídá obsahu (devět otázek, co si připravit) i novému
titulku *„{art4['title']}"* — adresa a nadpis si neodporují, což byl u části D
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
aplikace.\n""")
    w('---\n')

    # ---- část C: co zůstává
    w('# ČÁST C — co zůstává otevřené\n')
    w("""1. **`perex` článku 6 není v OND-262.** Viz poznámka u A4 výš. Kdo bude nasazovat
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
   v OND-204, sám jsem ji neverifikoval.\n""")

    path = os.path.join(HERE, 'redline-en-blog.md')
    with open(path, 'w', encoding='utf-8') as fh:
        fh.write('\n'.join(out))
    print(f'OK — zapsáno {path} ({len("".join(out))} znaků)')


if __name__ == '__main__':
    main()
