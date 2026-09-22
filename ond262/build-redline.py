#!/usr/bin/env python3
"""OND-262 — sestaví redline.json a OVĚŘÍ každé staré znění proti zdroji.

Zdroje:
  lang / šablona → origin/staging (rozbaleno do STG), tj. stav po vlně 1 (OND-256)
  DB             → text živé produkce v ond262/pages/*.txt (staženo 22. 9. 2026)

Skript nic nemění, jen kontroluje a zapisuje. Návratový kód != 0 = neshoda.
"""
import json
import os
import re
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
STG = sys.argv[1] if len(sys.argv) > 1 else '/tmp/ond262-stg-27314'
PAGES = os.path.join(HERE, 'pages')

# id, vrstva, locale, soubor/tabulka, klíč, url, staré, nové
R = []


def lang(id_, loc, f, key, url, old, new, why, line=None):
    R.append(dict(id=id_, layer='lang', locale=loc, file=f'lang/{loc}/{f}.php',
                  key=key, line=line, url=url, old=old, new=new, why=why))


def db(id_, loc, table, key, url, old, new, why):
    R.append(dict(id=id_, layer='db', locale=loc, file=table, key=key,
                  line=None, url=url, old=old, new=new, why=why))


def tpl(id_, f, key, url, old, new, why):
    R.append(dict(id=id_, layer='template', locale='*', file=f, key=key,
                  line=None, url=url, old=old, new=new, why=why))


# ---------------------------------------------------------------- A3
lang('A3-1', 'de', 'home', 'how_i_work.steps[0].text', '/de/',
     'Ich beginne mit einem Gespräch, nicht mit einem Formular. Ich muss Ihr Unternehmen, Ihre Kunden und verstehen, was die Website wirklich tun soll — Kontakte bringen, ein Produkt verkaufen oder Vertrauen aufbauen.',
     'Ich beginne mit einem Gespräch, nicht mit einem Formular. Ich muss Ihr Unternehmen und Ihre Kunden verstehen — und wissen, was die Website wirklich leisten soll: Kontakte bringen, ein Produkt verkaufen oder Vertrauen aufbauen.',
     'P1-3 vysinuti z vazby', 130)
lang('A3-2', 'de', 'price', 'note', '/de/preisliste',
     'Kein Umsatzsteuerpflichtiger — die genannten Preise sind endgültig, es kommt nichts hinzu.',
     'Ich bin nicht umsatzsteuerpflichtig — die genannten Preise sind Endpreise, es kommt keine Mehrwertsteuer hinzu.',
     'P1-5 doslovny preklad nejsem platce DPH', 89)
lang('A3-3', 'de', 'home', 'showcase.intro', '/de/',
     'Das sind live Projekte, die Sie sich sofort ansehen können. Klicken Sie sich durch.',
     'Das sind Live-Projekte, die Sie sich sofort ansehen können. Klicken Sie sich durch.',
     'P2-1 live jako nesklonne adjektivum', 74)
lang('A3-4', 'de', 'home', 'services.primary.eshop.bullets[]', '/de/',
     'Anbindung an Buchhaltung, Spediteure und Zahlungsanbieter',
     'Anbindung an Buchhaltung, Versanddienstleister und Zahlungsanbieter',
     'P2-2 Spediteur = kamionova spedice, ne balikovy dopravce', 247)
lang('A3-5', 'de', 'home', 'problems.lead', '/de/',
     'Sie sprechen direkt mit mir — von der ersten Nachricht über den Launch und darüber hinaus.',
     'Sie sprechen direkt mit mir — von der ersten Nachricht bis zum Launch und darüber hinaus.',
     'P2-4 vazba von A ueber B hinaus neexistuje', 105)
lang('A3-6', 'de', 'home', 'why_me.bio', '/de/',
     'Sie sprechen direkt mit mir, von der ersten Beratung über den Launch hinaus.',
     'Sie sprechen direkt mit mir — von der ersten Beratung bis zum Launch und darüber hinaus.',
     'P2-4 druhy vyskyt, navic useknute', 302)
lang('A3-7', 'de', 'home', 'services.primary.aplikace.description', '/de/',
     'Interne Systeme, Kundenportale und Verwaltungstools, gebaut auf der Art, wie Ihr Betrieb tatsächlich funktioniert.',
     'Interne Systeme, Kundenportale und Verwaltungstools — zugeschnitten auf die Art, wie Ihr Betrieb tatsächlich arbeitet.',
     'P2-6 gebaut auf der Art je kalk', 235)

# ---------------------------------------------------------------- A4
lang('A4-1', 'en', 'home', 'problems.items[0].quote_text', '/en/',
     'This is not the case where other would-be web designers just fill templates with data for outrageous fees.',
     'Unlike the would-be web designers who just pour your content into a template and charge outrageous fees.',
     'P2-3 doslovny preklad To neni pripad kdy', 114)
lang('A4-2', 'de', 'home', 'problems.items[0].quote_text', '/de/',
     'Das ist nicht der Fall, in dem andere Hobbyentwickler einfach Vorlagen mit Daten für überhöhte Preise füllen.',
     'Ganz anders als die Möchtegern-Webdesigner, die für überhöhte Preise einfach Vorlagen mit Inhalten befüllen.',
     'P2-3 tentyz kalk v nemcine', 112)
lang('A4-3', 'de', 'home', 'how_i_work.steps[0].quote_text', '/de/',
     'Er hörte wirklich meinen Bedürfnissen zu und verarbeitete diese Informationen bis zu meiner vollständigen Zufriedenheit.',
     'Er hat mir wirklich zugehört und daraus etwas gemacht, mit dem ich rundum zufrieden bin.',
     'P2-3 verarbeitete diese Informationen zni jako protokol', 131)
lang('A4-4', 'de', 'home', 'how_i_work.steps[1].quote_text', '/de/',
     'Er analysiert gründlich den Zustand und möchte die aktuellen Prozesse kennenlernen. Er sammelt Anforderungen von Kunden und erkundigt sich nach Zukunftsvisionen.',
     'Er analysiert die Ausgangslage gründlich und will die bestehenden Prozesse wirklich verstehen. Er sammelt die Anforderungen der Nutzer und fragt nach, wohin es gehen soll.',
     'P2-3 slovosled + Zustand bez atributu', 138)
lang('A4-5', 'en', 'home', 'how_i_work.steps[0].quote_text', '/en/',
     'He truly listened to my needs and then turned them into something I was completely satisfied with.',
     'He really listened to what I needed and then turned it into something I am completely happy with.',
     'konzistence s A2b/7 — tyz vyrok dvakrat na jedne strance', 133)
lang('A4-6', 'en', 'home', 'how_i_work.steps[1].quote_text', '/en/',
     'He rigorously analyses the situation and wants to understand current processes. He collects requirements from clients and explores visions for the future.',
     'He analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading.',
     'konzistence s A2b/16', 140)

# ---------------------------------------------------------------- A5, A6, A7
lang('A5-1', 'de', 'price', 'addons.items[0].price', '/de/preisliste',
     'ab €180 / Mo.', 'ab 180 € / Monat', 'P2-7 anglicky zapis meny na nemecke strance', 119)
lang('A5-2', 'de', 'price', 'addons.items[1].price', '/de/preisliste',
     'ab €400 / Mo.', 'ab 400 € / Monat', 'P2-7', 124)
lang('A5-3', 'de', 'price', 'addons.items[3].price', '/de/preisliste',
     'ab €190', 'ab 190 €', 'P2-7', 134)
lang('A6-1', 'en', 'price', 'guarantees.items[0].text', '/en/price',
     'Save thousands per year compared to WordPress — no monthly updates and no security patching costs.',
     'Save hundreds of euros a year compared to WordPress — no monthly updates and no security patching bills.',
     'P2-8 neprepoctene tvrzeni', 96)
lang('A6-2', 'de', 'price', 'guarantees.items[0].text', '/de/preisliste',
     'Sparen Sie jährlich Tausende gegenüber WordPress — keine monatlichen Updates und keine Kosten für Sicherheits-Patches.',
     'Sparen Sie jedes Jahr mehrere hundert Euro gegenüber WordPress — keine monatlichen Updates und keine Kosten für Sicherheits-Patches.',
     'P2-8', 96)
lang('A7-1', 'en', 'contact', 'agree', '/en/contact',
     'I agree to the transfer of personal data in accordance with the ',
     'I agree to the processing of my personal data in accordance with the ',
     'P2-9 pravne jde o zpracovani, ne predani', 33)

# ---------------------------------------------------------------- A8
lang('A8-1', 'de', 'privacy', 'body', '/de/datenschutz',
     'Diese Seite informiert Sie über unsere Richtlinien bezüglich der Erhebung, Verwendung und Weitergabe personenbezogener Daten',
     'Auf dieser Seite erkläre ich, welche personenbezogenen Daten ich erhebe, wie ich sie verwende und an wen ich sie weitergebe, wenn Sie meine Website nutzen',
     'P2-10 wir/unser lame ich-formu celeho webu', 34)
lang('A8-2', 'de', 'privacy', 'body', '/de/datenschutz',
     'Wir erheben verschiedene Arten von Informationen für verschiedene Zwecke, um Ihnen unseren Dienst bereitzustellen und zu verbessern.',
     'Ich erhebe verschiedene Arten von Informationen zu verschiedenen Zwecken, um die Website bereitzustellen und zu verbessern.',
     'P2-10', 37)
lang('A8-3', 'de', 'privacy', 'body', '/de/datenschutz',
     'Adresse, Bundesland, Postleitzahl, Stadt', 'Adresse, PLZ und Ort',
     'P2-10 Bundesland = prelozene americke State', 46)
lang('A8-4', 'de', 'privacy', 'body', '/de/datenschutz',
     'Die Sicherheit Ihrer Daten ist uns wichtig. Obwohl wir uns bemühen, kommerziell akzeptable Mittel zum Schutz Ihrer personenbezogenen Daten einzusetzen',
     'Die Sicherheit Ihrer Daten ist mir wichtig. Ich schütze sie nach dem Stand der Technik',
     'P2-10 ich-forma + kalk kommerziell akzeptable Mittel', 67)
lang('A8-5', 'de', 'privacy', 'body', '/de/datenschutz',
     'Unser Dienst richtet sich nicht an Personen unter 18 Jahren. Wir erheben wissentlich keine personenbezogenen Daten von Personen unter 18 Jahren.',
     'Meine Website richtet sich nicht an Personen unter 18 Jahren. Ich erhebe wissentlich keine personenbezogenen Daten von Personen unter 18 Jahren.',
     'P2-10', 75)

# ---------------------------------------------------------------- E (P3)
tpl('E-1', 'resources/views/pages/contact.blade.php', 'dt E-mail', '/de/kontakt',
    '<dt>E-mail</dt>', 'lang klic: cs E-mail / en Email / de E-Mail',
    'P3 nemecky jen E-Mail; retezec je natvrdo v sablone pro vsechny jazyky')
lang('E-2', 'en', 'home', 'projects.items[].outcome', '/en/',
     'the client reports a noticeably stronger brand credibility',
     'the client reports noticeably stronger brand credibility',
     'P3 nepocitatelne credibility clen nebere', 211)
lang('E-3', 'de', 'home', 'craft.facts[].text', '/de/',
     'Deshalb ragt nichts heraus',
     'Deshalb wirkt nichts fehl am Platz',
     'P3 kalk z nic nevycniva; herausragen znamena spis vynikat', 358)
lang('E-4', 'en', 'about', 'story.items[].text', '/en/about',
     'Programming was more enjoyable to me than anything else.',
     'I enjoyed programming more than anything else.',
     'P3 doslovny preklad, anglicky cinne', 24)
lang('E-5a', 'de', 'contact', 'hero.upline', '/de/kontakt',
     'Sie schreiben direkt mir.', 'Sie schreiben mir direkt.',
     'P2-11 priznakovy slovosled', 62)
lang('E-5b', 'de', 'contact', 'hero.eyebrow', '/de/kontakt',
     'Sie schreiben direkt mir', 'Sie schreiben mir direkt', 'P2-11', 64)
lang('E-5c', 'de', 'contact', 'hero.heading', '/de/kontakt',
     'Sie schreiben direkt mir, Ondřej.', 'Sie schreiben direkt an mich, Ondřej.', 'P2-11', 65)
lang('E-6', 'de', 'contact', 'steps.items[1].title', '/de/kontakt',
     'Wir vereinbaren 30 Minuten Gespräch', 'Wir vereinbaren ein 30-minütiges Gespräch',
     'P2-5 chybi clen', 80)
lang('E-7', 'de', 'contact', 'address_registration', '/de/kontakt',
     'Unternehmens-ID 19231407, nicht umsatzsteuerpflichtig',
     'IČO (tschechische Unternehmens-ID) 19231407, nicht umsatzsteuerpflichtig',
     'P3 podle ICO si klient najde firmu v ceskem rejstriku', 19)
lang('E-9', 'en', 'price', 'note', '/en/price',
     'Not VAT-registered — these prices are final, nothing is added.',
     'I am not registered for VAT — the prices above are final, no VAT is added.',
     'P3 volitelne: tataz bezpodmeta stavba jako v DE (A3-2)', 89)

# ---------------------------------------------------------------- B (DB)
db('B2', 'de', 'portfolio_project_translations.subtitle', 'nove-interiery',
   '/de/projekte/nove-interiery',
   'Eine Website, die wie ein erstes Geschäftstreffen wirkt',
   'Eine Website, die wie ein erstes Verkaufsgespräch wirkt',
   'P3 Geschaeftstreffen = neutralni schuzka; EN ma first sales meeting')
db('B3', 'de', 'article_translations.content_1', 'article_id=3',
   '/de/blog/was-kostet-eine-website',
   'Ich bin kein Umsatzsteuerpflichtiger.',
   'Ich bin nicht umsatzsteuerpflichtig. Der Preis, den ich Ihnen nenne, ist ein Endpreis — es kommt keine Mehrwertsteuer hinzu.',
   'P1-5 druhy vyskyt; pozor: zmenit i BlogContentDeSeeder')

# ---------------------------------------------------------------- ověření
def read(p):
    return open(p, encoding='utf-8').read()


fails, checks = [], 0

for r in R:
    checks += 1
    if r['layer'] == 'lang':
        p = os.path.join(STG, r['file'])
        if r['old'] not in read(p):
            fails.append(f"{r['id']}: staré znění NENÍ v {r['file']} (origin/staging)")
    elif r['layer'] == 'template':
        p = os.path.join(STG, r['file'])
        if r['old'] not in read(p):
            fails.append(f"{r['id']}: staré znění NENÍ v {r['file']}")
    else:  # db → produkce
        page = {'B2': 'p-de-nove-interiery.txt',
                'B3': 'b-de-was-kostet-eine-website.txt'}[r['id']]
        if r['old'] not in read(os.path.join(PAGES, page)):
            fails.append(f"{r['id']}: staré znění NENÍ na produkci ({page})")

# recenze: všech 16 českých textů musí ležet v lang/en i lang/de
cs_texts = re.findall(r"'text'\s*=>\s*'(.*?)',\n", read(os.path.join(STG, 'lang/cs/testimonials.php')), re.S)
for loc in ('en', 'de'):
    src = read(os.path.join(STG, f'lang/{loc}/testimonials.php'))
    for t in cs_texts:
        checks += 1
        if t not in src:
            fails.append(f"testimonials/{loc}: chybí český text {t[:40]}…")
if len(cs_texts) != 16:
    fails.append(f'testimonials: čekám 16 recenzí, našel jsem {len(cs_texts)}')

# duration: sloupec nesmí být v translations tabulce
mig = read(os.path.join(STG, 'database/migrations/2026_05_06_120001_create_portfolio_project_translations_table.php'))
checks += 1
if 'duration' in mig:
    fails.append('duration: je v translations tabulce — část B1 neplatí, přepsat')

# duration: 22 projektů s českým textem na EN
czech = re.compile(r'[ěščřžůĚŠČŘŽŮ]|týdn|měsíc|průběžně|kratší|krátká|říjen|leden|září')
cnt = 0
for f in sorted(os.listdir(PAGES)):
    if not f.startswith('p-en-') or not f.endswith('.txt'):
        continue
    L = read(os.path.join(PAGES, f)).split('\n')
    for i, l in enumerate(L):
        if l.strip() == 'Duration' and i + 1 < len(L) and czech.search(L[i + 1]):
            cnt += 1
checks += 1
if cnt != 23:
    fails.append(f'duration: čekám 23 projektů s českým textem na EN, napočítal jsem {cnt}')

json.dump(R, open(os.path.join(HERE, 'redline.json'), 'w', encoding='utf-8'),
          ensure_ascii=False, indent=2)

print(f'položek v redline.json: {len(R)}')
print(f'kontrol: {checks}, neshod: {len(fails)}')
for f in fails:
    print('  ✗', f)
sys.exit(1 if fails else 0)
