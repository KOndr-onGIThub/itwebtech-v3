#!/usr/bin/env python3
"""OND-267 — mapování slugů mezi mutacemi po nasazení.

Audit jednu věc výslovně pochválil: přepínač jazyka na detailu projektu i
článku vede na tentýž obsah v druhé mutaci. Tenhle balík mění jeden EN slug,
takže se to musí ověřit celé, ne jen u toho jednoho článku.

Pro každou stránku se přečtou `<link rel="alternate" hreflang=…>`, každá
alternativa se načte a zkontroluje, že:
  * vrátí 200 (ne 404, ne řetěz přesměrování jinam),
  * její vlastní `hreflang` sada je **stejná** jako u výchozí stránky —
    tj. mutace ukazují navzájem na sebe, ne každá jinam.

Použití: python3 ond267/verify-slug-mapping.py [base-url]
"""
import re
import sys
import urllib.error
import urllib.request

BASE = (sys.argv[1] if len(sys.argv) > 1 else 'https://itwebtech.ondrejkriska.cz').rstrip('/')

HREF = re.compile(r'<link rel="alternate" hreflang="([a-zA-Z-]+)" href="([^"]+)"')


def fetch(url):
    req = urllib.request.Request(url, headers={'User-Agent': 'ond267-slug-check'})
    with urllib.request.urlopen(req) as r:
        return r.status, r.geturl(), r.read().decode('utf-8', 'replace')


def alternates(body):
    """hreflang => cesta; x-default se ignoruje, ukazuje na cs."""
    out = {}
    for lang, href in HREF.findall(body):
        if lang == 'x-default':
            continue
        out[lang] = href.replace(BASE, '')
    return out


def seeds():
    """Výchozí stránky: všechny CS detaily projektů a článků z sitemapy."""
    _, _, sm = fetch(BASE + '/sitemap.xml')
    locs = re.findall(r'<loc>([^<]+)</loc>', sm)
    keep = []
    for loc in locs:
        path = loc.replace(BASE, '')
        if path.startswith('/projekty/') or path.startswith('/jak-na-to/'):
            keep.append(path)
    return sorted(set(keep))


pages = seeds()
print(f'výchozích CS stránek ze sitemapy: {len(pages)}')

fails, checked = [], 0
for path in pages:
    try:
        status, _, body = fetch(BASE + path)
    except urllib.error.HTTPError as exc:
        fails.append(f'{path}: {exc.code}')
        continue

    alts = alternates(body)
    if set(alts) < {'cs', 'en', 'de'} and 'de' not in alts and 'en' not in alts:
        fails.append(f'{path}: žádné alternativy')
        continue

    for lang, alt_path in sorted(alts.items()):
        checked += 1
        try:
            status, final, alt_body = fetch(BASE + alt_path)
        except urllib.error.HTTPError as exc:
            fails.append(f'{path} → {lang} {alt_path}: HTTP {exc.code}')
            continue

        if final.replace(BASE, '') != alt_path:
            fails.append(f'{path} → {lang} {alt_path}: přesměrovalo na {final}')
            continue

        back = alternates(alt_body)
        if back != alts:
            diff = {k: (alts.get(k), back.get(k)) for k in set(alts) | set(back)
                    if alts.get(k) != back.get(k)}
            fails.append(f'{path} → {lang}: mutace si neodpovídají {diff}')

print(f'ověřených přechodů mezi mutacemi: {checked}, neúspěšných: {len(fails)}')
for f in fails:
    print('  ✗', f)
sys.exit(1 if fails else 0)
