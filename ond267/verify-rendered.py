#!/usr/bin/env python3
"""OND-267 — ověření na vykreslených stránkách, ne v migraci.

Běží proti lokální instanci s produkčními daty, na kterých proběhly migrace
z tohohle balíku. Každá položka redlinu se hledá v HTML stránky, na které
má být vidět; zároveň se kontroluje, že staré znění zmizelo.

Použití: python3 ond267/verify-rendered.py [base-url]
"""
import html
import json
import os
import re
import sys
import urllib.request

BASE = sys.argv[1] if len(sys.argv) > 1 else 'http://127.0.0.1:8199'
HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)

_cache = {}


def page(path):
    if path not in _cache:
        req = urllib.request.Request(BASE + path, headers={'User-Agent': 'ond267-verify'})
        with urllib.request.urlopen(req) as r:
            body = r.read().decode('utf-8', 'replace')
        # JSON-LD a skripty pryč: jsou plné rovných uvozovek v syntaxi,
        # kontrola typografie by na nich falešně padala.
        body = re.sub(r'<script.*?</script>', ' ', body, flags=re.S | re.I)
        # ať se dá hledat doslovné znění včetně — a „ “
        _cache[path] = html.unescape(re.sub(r'<[^>]+>', ' ', body))
    return _cache[path]


checks = []      # (jméno, url, [musí být], [nesmí být])


def check(name, url, must=(), must_not=()):
    checks.append((name, url, list(must), list(must_not)))


# ---------------------------------------------------------------- lang A3–A8, E
R = json.load(open(os.path.join(ROOT, 'ond262', 'redline.json'), encoding='utf-8'))
URL_MAP = {'/de/': '/de', '/en/': '/en'}
# `contact.hero.heading` je backwards-compat klíč (modál) — stránka renderuje
# `heading_html`. Opravený je podle redlinu, ale v HTML stránky není.
NOT_RENDERED = {'E-5c'}
for r in R:
    if r['layer'] != 'lang':
        continue
    if r['id'] in NOT_RENDERED:
        src = open(os.path.join(ROOT, r['file']), encoding='utf-8').read()
        assert r['new'] in src and r['old'] not in src, r['id']
        continue
    url = URL_MAP.get(r['url'], r['url'])
    check(r['id'], url, [r['new']], [r['old']])

# E-1 (šablona → lang klíč) a E-8 (Webseite → Website)
check('E-1', '/de/kontakt', ['E-Mail'], [])
check('E-8', '/de/projekte', [], ['Webseite', 'Webseiten'])

# ---------------------------------------------------------------- A2 recenze
check('A2a/en', '/en', ['Translated from the Czech originals on Google, Firmy.cz and Facebook.'])
check('A2a/de', '/de', ['Aus dem Tschechischen übersetzt — die Originale stehen auf Google, Firmy.cz und Facebook.'])
check('A2a/cs', '/', [], ['Translated from the Czech originals'])
check('A2b/en', '/en',
      ['It is my pleasure to give this reference for Ondřej Kriška',
       'I appreciate the high level of expertise and professionalism',
       'Executive Director', 'software developer'],
      ['S potěšením mohu poskytnout tuto referenci',
       'Oceňuji vysokou odbornost a profesionalitu',
       'výkonná ředitelka'])
check('A2b/de', '/de',
      ['Ich gebe diese Referenz für Ondřej Kriška gerne ab',
       'Ich schätze die hohe Fachkompetenz und Professionalität',
       'geschäftsführende Direktorin', 'Softwareentwickler'],
      ['S potěšením mohu poskytnout tuto referenci',
       'výkonná ředitelka'])

# `badge` dnes žádná šablona nevykresluje (ověřeno grepem přes resources/views),
# překlad je pojistka pro případ, že se začne renderovat — kontrola ve zdroji.
for _loc, _txt in (('en', 'From my years at Toyota'), ('de', 'Aus meiner Zeit bei Toyota')):
    _src = open(os.path.join(ROOT, 'lang', _loc, 'testimonials.php'), encoding='utf-8').read()
    assert _src.count(_txt) == 2 and 'Z mého působení v Toyotě' not in _src, _loc

# ---------------------------------------------------------------- A1 doba realizace
check('A1/cs pitarena', '/projekty/pitarena', ['průběžně od 2023'], ['průběžně 2023–dnes'])
check('A1/en pitarena', '/en/projects/pitarena', ['ongoing, since 2023'], ['průběžně'])
check('A1/de pitarena', '/de/projekte/pitarena', ['laufend, seit 2023'], ['průběžně'])
check('A1/en hcms', '/en/projects/hcms', ['a few months; the app is still running'], ['několik měsíců'])
check('A1/de hcms', '/de/projekte/hcms', ['einige Monate, die Anwendung läuft bis heute'], ['několik měsíců'])
check('A1/en barana', '/en/projects/barana', ['5 weeks'], ['5 týdnů'])
check('A1/de barana', '/de/projekte/barana', ['5 Wochen'], ['5 týdnů'])
check('A1/en yolk', '/en/projects/yolk', ['ongoing, since 2025'], ['průběžná spolupráce'])
check('A1/en choccoboard', '/en/projects/choccoboard', ['a few weeks'], ['několik týdnů'])
check('A1/de picker', '/de/projekte/picker', ['einige Monate'], ['několik měsíců'])
# deset projektů řádek ztrácí úplně
for url, label in (('/projekty/vanspedition', 'Doba realizace'),
                   ('/en/projects/vanspedition', 'Duration'),
                   ('/de/projekte/vanspedition', 'Dauer')):
    check('X2 vanspedition bez řádku', url, [], [label])

# ---------------------------------------------------------------- CS případovky
CS = json.load(open(os.path.join(ROOT, 'ond261', 'redline.json'), encoding='utf-8'))
SLUG_URL = {'pitarena-eshop': 'pitarena-eshop', 'animace-delejme': 'animace-delejme'}
for r in CS:
    if r['field'] in ('*', 'category_label', 'slug', 'tag', 'duration') or r['id'].startswith('W'):
        continue
    if r['field'] in ('meta_title', 'meta_description'):
        continue          # v textu stránky nejsou, ověřeno v DB
    slug = SLUG_URL.get(r['slug'], r['slug'])
    new = r['new'].replace('"', '“')     # po migraci uvozovek
    check(r['id'], f'/projekty/{slug}', [new], [])

# ---------------------------------------------------------------- uvozovky v datech
check('Q1 cyklocentrum', '/projekty/cyklocentrum', [], ['"'])
check('Q1 zubni-provazek', '/projekty/zubni-provazek', ['„', '“'], [])
check('B4 de zubni-provazek', '/de/projekte/zubni-provazek', ['„', '“'], [])

# ---------------------------------------------------------------- kategorie
check('ex-1 kategorie', '/projekty/excel-tools', ['Aplikace'], ['Webová aplikace'])

# ---------------------------------------------------------------- blog
check('C — EN článek 3', '/en/blog/how-much-does-a-website-cost',
      ['What a custom website costs and what goes into the price',
       'The three tiers I work in',
       'I am not registered for VAT',
       'within 24 hours on business days'],
      ['comprehensive guide', 'Keep your websites uncluttered', 'from 1,750 to 4,500'])
check('D — EN článek 6', '/en/blog/when-a-custom-app-beats-a-spreadsheet',
      ['When a custom app is worth it instead of a spreadsheet',
       'eighteen years in Toyota logistics'],
      ['How a simple web application can save your business millions'])
check('B3 — DE blog DPH', '/de/blog/was-kostet-eine-website',
      ['Ich bin nicht umsatzsteuerpflichtig',
       '24 Stunden an Arbeitstagen'],          # vlna 1 nesmí zmizet
      ['Ich bin kein Umsatzsteuerpflichtiger'])

# ---------------------------------------------------------------- běh
fails = 0
for name, url, must, must_not in checks:
    try:
        body = page(url)
    except Exception as exc:                      # noqa: BLE001
        print(f'  ✗ {name:26} {url} — {exc}')
        fails += 1
        continue

    problems = [f'chybí: {m[:60]}' for m in must if m not in body]
    problems += [f'zbylo staré: {m[:60]}' for m in must_not if m in body]

    if problems:
        fails += 1
        print(f'  ✗ {name:26} {url}')
        for p in problems:
            print(f'      {p}')

print(f'\nkontrol: {len(checks)}, neúspěšných: {fails}')
sys.exit(1 if fails else 0)
