#!/usr/bin/env python3
"""OND-267 / části A3–A8 a E — aplikuje lang náhrady z `ond262/redline.json`.

Znění se neopisuje ručně, bere se z ověřeného redlinu (viz
`ond262/build-redline.py`, 70 kontrol). Položky vrstvy `db` sem nepatří —
ty řeší migrace. Položka `template` (E-1) se řeší ručně, protože zakládá
nový lang klíč.

Skript je fail-fast: když se staré znění nenajde ani jednou a nové tam
zároveň není, nezapíše se nic.
"""
import json
import os
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)

SKIP = {'E-1'}          # šablona, ne lang — ruční zásah
R = json.load(open(os.path.join(ROOT, 'ond262', 'redline.json'), encoding='utf-8'))

edits, fails, done = {}, [], []
# Delší znění první: `Sie schreiben direkt mir` (E-5b) je podřetězcem
# `Sie schreiben direkt mir, Ondřej.` (E-5c) i `… mir.` (E-5a). V původním
# pořadí by E-5b sedělo na tři místa naráz.
for r in sorted(R, key=lambda r: -len(r['old'])):
    if r['layer'] != 'lang' or r['id'] in SKIP:
        continue
    for lit in (r['old'], r['new']):
        if "'" in lit or '\\' in lit:
            raise SystemExit(f"{r['id']}: znění obsahuje ' nebo \\ — escapování řeš ručně")

    path = os.path.join(ROOT, r['file'])
    php = edits.get(path) or open(path, encoding='utf-8').read()

    n = php.count(r['old'])
    if n == 0:
        if r['new'] in php:
            done.append(r['id'])
            continue
        fails.append(f"{r['id']}: staré znění není v {r['file']}")
        continue
    if n > 1:
        fails.append(f"{r['id']}: staré znění je v {r['file']} {n}×, čekám 1×")
        continue

    edits[path] = php.replace(r['old'], r['new'])
    print(f"{r['id']:5} {r['file']:28} {r['key']}")

if fails:
    for f in fails:
        print('  ✗', f)
    sys.exit(1)

for path, php in edits.items():
    open(path, 'w', encoding='utf-8').write(php)

print(f'\nzapsáno souborů: {len(edits)}')
if done:
    print('už bylo aplikované:', ', '.join(done))
