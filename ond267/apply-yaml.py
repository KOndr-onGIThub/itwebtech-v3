#!/usr/bin/env python3
"""OND-267 — promítne redline OND-261 do `docs/portfolio-data.yaml`.

Migrace opraví existující DB, YAML opraví čerstvý seed (`PortfolioSeeder`).
Když se upraví jen jedno, čerstvá instalace dá jiný výsledek než produkce.

Dva zádrhely, kvůli kterým tohle není `sed`:
  1. Textová pole jsou složené skaláry `>-` — v DB jsou jednořádková, v YAML
     rozlámaná přes víc řádků. Hledá se proto ve složeném (normalizovaném)
     tvaru a po náhradě se znovu zalomí na původní šířku a odsazení.
  2. Pole `duration` a `title`/`subtitle`/`meta_*` jsou naopak jednořádkové
     skaláry v uvozovkách — ty se mění přímo.

Skript nic nehádá: každou náhradu ověří proti naparsovanému YAML (PyYAML),
a když staré znění nesedí, skončí chybou a nezapíše nic.
"""
import json
import os
import re
import sys
import textwrap

import yaml

HERE = os.path.dirname(os.path.abspath(__file__))
ROOT = os.path.dirname(HERE)
YAML_PATH = os.path.join(ROOT, 'docs', 'portfolio-data.yaml')

FOLDED = {'description', 'challenge', 'solution', 'result', 'summary'}
SKIP_FIELDS = {'*', 'category_label', 'slug', 'tag'}

R = json.load(open(os.path.join(ROOT, 'ond261', 'redline.json'), encoding='utf-8'))
items = [r for r in R if r['field'] not in SKIP_FIELDS and not r['id'].startswith('W')]

# jo-1 bere staré znění až po vlně 1 (OND-256 „visačku" → „vizitku") — stejně
# jako migrace. YAML má vlnu 1 zapracovanou, produkce taky (ověřeno 22. 9.).
for r in items:
    if r['id'] == 'jo-1':
        r['old'] = r['old'].replace('visačku', 'vizitku')

# B2 je jediná datová položka z redlinu OND-262 (DE podtitul) — v ond261 není.
items.append({
    'id': 'B2', 'slug': 'nove-interiery', 'field': 'subtitle',
    'old': 'Eine Website, die wie ein erstes Geschäftstreffen wirkt',
    'new': 'Eine Website, die wie ein erstes Verkaufsgespräch wirkt',
})

raw = open(YAML_PATH, encoding='utf-8').read()
data = yaml.safe_load(raw)
projects = {p['slug']: p for p in data['projects']}

fails, applied = [], []


def project_span(slug):
    """Řádkový rozsah bloku projektu v syrovém textu."""
    lines = raw.split('\n')
    start = next(i for i, l in enumerate(lines) if l.startswith(f'  - slug: {slug}'))
    end = len(lines)
    for i in range(start + 1, len(lines)):
        if lines[i].startswith('  - slug: '):
            end = i
            break
    return start, end


def replace_scalar(slug, field, old, new, locale='cs'):
    """Jednořádkový skalár v uvozovkách (title, subtitle, meta_*, duration)."""
    global raw
    lines = raw.split('\n')
    start, end = project_span(slug)
    if field != 'duration':
        # posuň začátek za `translations:` → `<locale>:`
        loc_line = next(i for i in range(start, end)
                        if lines[i].strip() == f'{locale}:')
        start = loc_line

    pattern = re.compile(rf'^(\s*{field}: )"(.*)"\s*$')
    for i in range(start, end):
        m = pattern.match(lines[i])
        if not m:
            continue
        if old not in m.group(2):
            continue
        lines[i] = m.group(1) + '"' + m.group(2).replace(old, new) + '"'
        raw = '\n'.join(lines)
        return True
    return False


def replace_folded(slug, field, old, new, locale='cs'):
    """Složený skalár `>-`: normalizovat → nahradit → znovu zalomit."""
    global raw
    lines = raw.split('\n')
    start, end = project_span(slug)
    loc_line = next(i for i in range(start, end) if lines[i].strip() == f'{locale}:')

    header = re.compile(rf'^(\s*){field}: >-\s*$')
    for i in range(loc_line, end):
        m = header.match(lines[i])
        if not m:
            continue
        indent = m.group(1) + '  '
        body = []
        j = i + 1
        while j < len(lines) and (lines[j].startswith(indent) or not lines[j].strip()):
            if not lines[j].strip():
                break
            body.append(lines[j].strip())
            j += 1

        folded = ' '.join(body)
        if old not in folded:
            return False

        wrapped = textwrap.wrap(folded.replace(old, new), width=72,
                                break_long_words=False, break_on_hyphens=False)
        lines[i + 1:j] = [indent + w for w in wrapped]
        raw = '\n'.join(lines)
        return True
    return False


for r in items:
    slug, field, old, new = r['slug'], r['field'], r['old'], r['new']

    if field == 'duration':
        current = projects.get(slug, {}).get('duration')
        if current != old:
            fails.append(f"{r['id']}: duration v YAML je {current!r}, čekám {old!r}")
            continue
        if new:
            ok = replace_scalar(slug, 'duration', old, new)
        else:
            # řádek celý pryč — prázdné `duration` by seeder uložil jako ""
            lines = raw.split('\n')
            s, e = project_span(slug)
            idx = next((i for i in range(s, e)
                        if re.match(r'^\s*duration: ', lines[i])), None)
            if idx is None:
                ok = False
            else:
                del lines[idx]
                raw = '\n'.join(lines)
                ok = True
    else:
        tr = projects.get(slug, {}).get('translations', {}).get('cs', {})
        if field == 'subtitle' and r['id'] == 'B2':
            tr = projects.get(slug, {}).get('translations', {}).get('de', {})
        if old not in (tr.get(field) or ''):
            fails.append(f"{r['id']}: {slug}.{field} — staré znění není v YAML")
            continue
        fn = replace_folded if field in FOLDED else replace_scalar
        ok = fn(slug, field, old, new)

    if ok:
        applied.append(r['id'])
    else:
        fails.append(f"{r['id']}: {slug}.{field} — náhradu se nepodařilo zapsat")

if fails:
    for f in fails:
        print('  ✗', f)
    sys.exit(1)

open(YAML_PATH, 'w', encoding='utf-8').write(raw)

# kontrola: YAML je pořád platný a hodnoty sedí na nové znění
check = yaml.safe_load(open(YAML_PATH, encoding='utf-8'))
after = {p['slug']: p for p in check['projects']}
for r in items:
    if r['field'] == 'duration':
        got = after[r['slug']].get('duration')
        want = r['new'] or None
        assert got == want, (r['id'], got, want)
    else:
        loc = 'de' if r['id'] == 'B2' else 'cs'
        got = after[r['slug']]['translations'][loc][r['field']]
        assert r['new'] in got, (r['id'], got[:120])

print(f'aplikováno položek: {len(applied)}, YAML po zápisu prošel kontrolou')
