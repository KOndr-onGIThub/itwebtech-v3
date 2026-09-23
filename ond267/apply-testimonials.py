#!/usr/bin/env python3
"""OND-267 / A2b — přepíše `role`, `text` a `badge` v lang/{en,de}/testimonials.php.

Překlady se NEOPISUJÍ ručně: parsují se přímo z tabulek v
`ond262/redline-en-de.md` (sekce A2b), takže mezi redlinem a nasazeným
souborem nemůže vzniknout přepisová chyba.

Skript je idempotentní jen v jednom směru — pouští se nad souborem, který
ještě obsahuje české znění (to je zároveň kontrola: když se český řetězec
nenajde, skript skončí chybou a nic nezapíše).
"""
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)
MD = os.path.join(ROOT, 'ond262', 'redline-en-de.md')

src = open(MD, encoding='utf-8').read()
# A2b sahá od nadpisu po začátek A3
body = src.split('### A2b — Texty recenzí')[1].split('## A3 —')[0]

CELL = re.compile(r'^\|\s*`(role|text|badge)`\s*\|(.*)\|\s*$')
BACKTICK = re.compile(r'`(.*)`')


def cell(raw):
    """Vytáhne doslovnou hodnotu z buňky; `_(beze změny)_` → None."""
    raw = raw.strip()
    if '_(beze změny)_' in raw:
        return None
    m = BACKTICK.search(raw)
    if not m:
        raise SystemExit(f'nečitelná buňka: {raw!r}')
    return m.group(1)


items = []          # [(cs, en, de), …]
for line in body.split('\n'):
    m = CELL.match(line)
    if not m:
        continue
    parts = m.group(2).split('|')
    if len(parts) != 3:
        raise SystemExit(f'čekám 3 sloupce, mám {len(parts)}: {line!r}')
    cs, en, de = (cell(p) for p in parts)
    if cs is None:
        raise SystemExit(f'staré znění nesmí být prázdné: {line!r}')
    items.append((m.group(1), cs, en, de))

print(f'položek z redlinu: {len(items)}')


def php_escape(value):
    return value.replace('\\', '\\\\').replace("'", "\\'")


fails = []
for loc, idx in (('en', 2), ('de', 3)):
    path = os.path.join(ROOT, 'lang', loc, 'testimonials.php')
    php = open(path, encoding='utf-8').read()
    changed = 0
    for field, cs, en, de in items:
        new = (en, de)[idx - 2]
        if new is None:                      # „beze změny"
            continue
        old_lit = "'" + php_escape(cs) + "'"
        new_lit = "'" + php_escape(new) + "'"
        if old_lit not in php:
            # `badge` má tentýž český řetězec dvakrát (Baudyš, Stybor) —
            # první náhrada vyřídí oba výskyty, druhý průchod tedy nic nenajde.
            if new_lit in php:
                continue
            fails.append(f'{loc}/{field}: nenašel jsem {cs[:50]}…')
            continue
        changed += php.count(old_lit)
        php = php.replace(old_lit, new_lit)
    if fails:
        continue
    # hlavička souboru lhala: texty už česky nejsou
    php = php.replace(
        "// Testimonials are always shown in Czech regardless of locale.\n"
        "// Same file is used for cs/en/de.\n",
        "// OND-267 (audit P1-1): recenze byly ve všech třech jazycích česky.\n"
        "// Přeložené jsou všechny (ne jen zobrazené) — přepnutí feature flagu\n"
        "// show_toyota_testimonial nebo přeskládání pořadí nesmí vrátit češtinu.\n"
        "// Vlastní jména, firmy, `image` a `source` se nepřekládají.\n",
    )
    open(path, 'w', encoding='utf-8').write(php)
    print(f'lang/{loc}/testimonials.php — nahrazeno {changed} řetězců')

if fails:
    for f in fails:
        print('  ✗', f)
    sys.exit(1)
