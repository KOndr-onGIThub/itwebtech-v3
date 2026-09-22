#!/usr/bin/env python3
"""OND-274 — sestaví ond274/redline.json a OVĚŘÍ vstupy proti zdrojům.

Co se ověřuje (skript nic nemění, jen kontroluje a zapisuje):

  1. KILLS — každý citovaný starý anglický fragment musí být doslova
     v živém textu produkce, tj. v ond262/pages/b-en-*.txt (staženo 22. 9. 2026).
     Kdyby mezitím někdo EN článek upravil, kontrola spadne.

  2. CS_ANCHORS — každý český zdrojový úsek, ze kterého nová EN verze vychází,
     musí být v database/seeders/BlogContentSeeder.php na origin/staging.
     Kdyby se CS verze změnila, nová EN verze by se s ní rozešla.

  3. SLUGS — navržený nový EN slug nesmí kolidovat s žádným slugem
     v database/sql/article_slugs.sql ani s novými CS/DE/EN slugy ze seederů.
     (`PageController::article()` hledá slug napříč locale.)

  4. STRUCTURE — počet a pořadí <h2>/<ol>/<li> v nové EN verzi musí sedět
     na DE verzi z BlogContentDeSeeder.php. DE je strukturní kontrola,
     obsahovou předlohou je CS.

Použití:
    python3 ond274/build-redline.py [cesta_k_rozbalenemu_origin/staging]

Bez argumentu si skript vytáhne soubory z gitu sám (`git show origin/staging:...`).
Návratový kód != 0 = neshoda.
"""
import json
import os
import re
import subprocess
import sys

HERE = os.path.dirname(os.path.abspath(__file__))
REPO = os.path.dirname(HERE)
PAGES = os.path.join(REPO, 'ond262', 'pages')
STG = sys.argv[1] if len(sys.argv) > 1 else None

ERRORS = []


def staging_file(rel):
    """Obsah souboru ze stavu origin/staging."""
    if STG:
        with open(os.path.join(STG, rel), encoding='utf-8') as fh:
            return fh.read()
    return subprocess.run(
        ['git', '-C', REPO, 'show', f'origin/staging:{rel}'],
        capture_output=True, text=True, check=True).stdout


def norm(s):
    """Normalizace pro porovnání se scrapem: whitespace, uvozovky, pomlčky."""
    s = s.replace('’', "'").replace('‘', "'")
    s = s.replace('“', '"').replace('”', '"')
    s = s.replace('„', '"').replace('–', '-').replace('—', '-')
    s = re.sub(r'\s+', ' ', s)
    return s.strip()


# --------------------------------------------------------------------------
# Články. article_id → (starý EN slug / soubor scrapu, nový obsah)
# --------------------------------------------------------------------------

ARTICLES = {}

# ---------------------------------------------------------------- článek 10
ARTICLES[10] = dict(
    page='b-en-does-your-business-need-a-website.txt',
    slug='does-your-business-need-a-website',
    slug_change=None,
    cs_title='Potřebuje vaše firma web? Někdy ne a řeknu vám kdy',
    de_title='Braucht Ihre Firma eine Website? Manchmal nicht',
    title='Does your business need a website? Sometimes not',
    description='I am not impartial about this — I build websites. Even so, there are '
                'situations where a website will not help you. Here is which ones and '
                'what to do instead.',
    perex='<blockquote><p>I make my living building websites, so I am not writing this '
          'impartially and I will not pretend otherwise. Even so, I know cases where a '
          'website does not help a company and the money is better spent elsewhere. '
          'Here it is straight: which ones.</p></blockquote>',
    content_1="""<h2>When you really do not need a website</h2>
<p><strong>You are fully booked and the work comes from referrals.</strong> If you have months of work ahead of you and you would turn new enquiries down anyway, a website will bring you nothing right now. Come back to it when you want to grow or to change the kind of work you take.</p>
<p><strong>You sell to one large customer.</strong> If your company stands on two long-term contracts, a website is a business card, not a sales channel. A simple page with contact details is enough, and you do not need to spend €4,000 on it.</p>
<p><strong>There is nobody to pick up the phone.</strong> A website that brings in enquiries nobody answers is worse than no website at all. The customer remembers that you did not get back to them.</p>
<p><strong>You are looking for a miracle.</strong> A website is a tool, not a solution. If the company is not clear about what it sells and to whom, a website will not fix that. It will only write it in bigger letters.</p>
<h2>When a website does make sense</h2>
<p><strong>People check you out before they call.</strong> Almost everyone does this now. If all they find is a directory listing from 2019, they mark you down.</p>
<p><strong>You keep explaining the same thing.</strong> If you repeat in every meeting how the work goes and what is included in the price, the website explains it for you. You then talk to people who already know.</p>
<p><strong>Your competitors look better than they work.</strong> That is uncomfortable, but it decides.</p>
<p><strong>You want different work from the work you have.</strong> A website is the cheapest way to show that you do bigger and more demanding jobs too.</p>""",
    content_mid='<blockquote><p>A website will not win the work for you. But it says up '
                'front what you otherwise explain again in every meeting.</p></blockquote>',
    content_2="""<h2>What a website cannot do</h2>
<p>I will not promise you how many enquiries it will bring. I have no influence over what the demand in your field looks like, what your prices are, or how fast you answer. Anyone who promises you that number is guessing.</p>
<p>What I can influence is the work I deliver. That the website is fast and understandable, that it looks good on a phone, and that nothing falls apart on it in two years.</p>
<h2>Before you decide</h2>
<p>Try to answer one question. If someone who had never heard of you landed on your website tomorrow, would they understand within ten seconds what you do and whether it is for them? If not, that is where the problem is, and it makes no difference whether you have a website or not.</p>""",
)

# ----------------------------------------------------------------- článek 4
ARTICLES[4] = dict(
    page='b-en-how-to-define-website-development-requirements.txt',
    slug='how-to-define-website-development-requirements',
    slug_change='how-to-prepare-for-a-new-website',
    cs_title='Co si připravit, než oslovíte vývojáře webu',
    de_title='Neun Fragen, die Sie vor dem Website-Projekt klären',
    title='What to prepare before you contact a web developer',
    description='Nine questions we will have to answer anyway. If you go through them in '
                'advance, we both save time and you get a more accurate price the first '
                'time round.',
    perex='<blockquote><p>Almost nobody writes to me with a finished brief, and that is '
          'fine. Asking is what I am here for. But if you go through the questions below '
          'in advance, we cut the whole round trip by a few weeks and you get a more '
          'accurate price straight away.</p></blockquote>',
    content_1="""<h2>You do not need an answer to everything</h2>
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
<p>If you already have a website, write to me specifically about what annoys you on it. Can it not be edited? Can it not be found? Does it look old? Does nothing come through it?</p>""",
    content_mid='<blockquote><p>The worst brief is “make it look nice”. The best one is '
                '“this specific thing annoys me”.</p></blockquote>',
    content_2="""<h2>6. Who will manage the content</h2>
<p>If you want to change texts and photos yourself, I will build you simple content management and show you how it works. If you do not, we do not have to build it and you save money. Either is fine, I just need to know in advance.</p>
<h2>7. What you already have</h2>
<p>A logo, photos, texts, access to the domain and the hosting, a shop with products in some system. The more of it there is, the less has to be made from scratch.</p>
<h2>8. What your budget is</h2>
<p>I know nobody likes this question. I ask it so that I can tell you straight away whether I can do it for that price. If I cannot, I say so immediately and neither of us wastes time.</p>
<h2>9. When you need it by</h2>
<p>If you have a fixed date because of a trade fair or an opening, tell me right at the start. That is how I know whether I can make it.</p>
<h2>What happens next</h2>
<p>From your answers I write a specification. It says what I will build and a price that holds. Only then do you decide whether we go ahead. You sign nothing up front.</p>""",
)

# ---------------------------------------------------------------- článek 13
ARTICLES[13] = dict(
    page='b-en-website-redesign-reasons-signals-and-how-to-do-it.txt',
    slug='website-redesign-reasons-signals-and-how-to-do-it',
    slug_change=None,
    cs_title='Kdy má smysl předělat web a kdy je to vyhozený výdaj',
    de_title='Wann ein Website-Relaunch Sinn ergibt und wann nicht',
    title='When a website redesign is worth it and when it is money wasted',
    description='Five reasons a website redesign makes sense and three reasons it does '
                'not. Plus what to watch out for so that your traffic does not drop after '
                'the relaunch.',
    perex='<blockquote><p>A redesign usually comes up the moment somebody stops liking '
          'the website. That is the weakest reason I know. Here is when redoing a website '
          'makes sense, when it does not, and what most often goes wrong along the '
          'way.</p></blockquote>',
    content_1="""<h2>Five reasons a redesign makes sense</h2>
<p><strong>1. The website cannot be maintained.</strong> Changing a phone number means writing to somebody who gets back to you in a week. That on its own is worth a redesign.</p>
<p><strong>2. It is unusable on a phone.</strong> Most people look at websites on a phone these days. If they have to zoom in and scroll sideways, they leave.</p>
<p><strong>3. The website is falling apart or going down.</strong> Typically with page builders assembled from plugins by different authors. One update and the order form stops working.</p>
<p><strong>4. The company has changed.</strong> You do something different, you want to sell something different, you are in a different price bracket. The website stayed where you were five years ago.</p>
<p><strong>5. The websites of the people you compete with look a class better.</strong> The customer compares you side by side whether you like it or not.</p>
<h2>Three situations where you should keep your money</h2>
<p><strong>The website is two years old and it works.</strong> Age on its own is not a reason. If it can be maintained, it is fast and people find what they need on it, leave it alone.</p>
<p><strong>You do not like it, but your customers do not mind it.</strong> Your taste and your customer’s taste are not the same thing. Before you put a few thousand euros into it, ask five clients what they were missing on the website.</p>
<p><strong>The real problem is somewhere else.</strong> If enquiries are not coming because you are three classes more expensive than everyone around you and you explain that nowhere, a new look will not fix it.</p>""",
    content_mid='<blockquote><p>A redesign is work on the content and the structure. '
                'The look is what follows from it.</p></blockquote>',
    content_2="""<h2>What most often goes wrong in a redesign</h2>
<p><strong>The page addresses get thrown away.</strong> The new website has a different structure and the old addresses stop working. Search engines and links from other people’s websites suddenly lead nowhere. The fix is simple and it is done before launch: the old address has to redirect permanently to the new one. I want you to demand that from whoever builds your website.</p>
<p><strong>Only the look gets redone.</strong> The texts are copied across one to one, including the ones nobody understood. The website then looks new and works just as badly.</p>
<p><strong>Things that were working disappear.</strong> Sometimes the old website has a page that half the traffic goes to. Before anything is deleted, somebody needs to look at the statistics.</p>
<p><strong>Nobody carries the content over.</strong> References, photos of finished jobs, documents to download. There is usually more of it than anyone expects.</p>
<h2>How I approach a redesign</h2>
<p>First I look at what works on the old website and I keep that. Then we go through what the website is supposed to do and who it is supposed to say it to. Only after that do we deal with how it will look. I sort the page addresses out before launch, not after.</p>
<p>I write my own code, without ready-made plugins by other authors. Those are usually the reason a website falls apart after a while and has to be redone.</p>""",
)

# ----------------------------------------------------------------- článek 6
# title/description/slug řeší OND-262 část D. perex a tělo jsou tady.
ARTICLES[6] = dict(
    page='b-en-how-simple-web-application-can-save-your-business-millions.txt',
    slug='how-simple-web-application-can-save-your-business-millions',
    slug_change='when-a-custom-app-beats-a-spreadsheet',  # z OND-262 část D
    cs_title='Kdy se firmě vyplatí aplikace na míru místo tabulky v Excelu',
    de_title='Wann sich eine eigene Anwendung statt Excel lohnt',
    title=None,        # OND-262 část D
    description=None,  # OND-262 část D
    perex='<blockquote><p>I worked at Toyota for eighteen years. I started as a labourer '
          'in logistics and I finished as a senior specialist in the project team. In '
          'that time I saw plenty of processes running on spreadsheets and paper, and I '
          'replaced a few of them with an application. Here is how you can tell that you '
          'are at that point too.</p></blockquote>',
    content_1="""<h2>The spreadsheet is not the enemy</h2>
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
<p>If one of those fits, leave it be. If three or more fit, it is worth doing the sums.</p>""",
    content_mid='<blockquote><p>You do not need an application because it is modern. You '
                'need one when the manual work costs you more than building it '
                'does.</p></blockquote>',
    content_2="""<h2>What I did at Toyota</h2>
<p>My job was to make logistics more efficient in step with assembly. In production you cannot afford for the line to stop, so every change has to be thought through in advance and tested.</p>
<p>I programmed a web application there called TSM that replaced part of the manual work in logistics. Over time I put several more applications into operation.</p>
<p>None of it was about spectacular technology. It was about finding the place where time was being wasted and removing that place. I think the same way today when I build a custom application for a company.</p>
<h2>How to do the sums yourself</h2>
<p>Take an activity that is done by hand. How many minutes a day does it take? How often a month does a mistake happen with it, and what does that mistake cost? Multiply it by twelve months. If you end up with a number in the thousands of euros a year, a custom application pays for itself in a few years and after that it only saves. If you end up with a few hundred euros, leave it alone and buy yourself a decent spreadsheet instead.</p>
<p>I will do this calculation for you free of charge on our first call. If it comes out that it is not worth it, I will tell you.</p>
<h2>What a custom application is and what it is not</h2>
<p>It is a program built around exactly how your company works. Records, orders, planning, reports. It runs in a browser, so you install nothing and you can get to it from your phone too.</p>
<p>It is not an off-the-shelf system that you have to adapt to. That is the main difference, and also the reason it costs more than a monthly subscription to something in a box.</p>""",
)

# --------------------------------------------------------------------------
# KILLS — co se novou verzí odstraňuje. Každý fragment se ověřuje proti scrapu.
# --------------------------------------------------------------------------

KILLS = [
    # --- článek 10
    (10, 'In this article, we\'ll look at the various aspects and arguments',
     'Redakční „we" u jednočlenné firmy; zbytek webu mluví v ich-formě.'),
    (10, 'The COVID-19 pandemic has accelerated the shift to digital channels.',
     'Datovaný obsah z roku 2020, v roce 2026 působí jako neudržovaný text.'),
    (10, 'don\'t miss my article What is SEO .',
     'Odkaz na článek 5 („Co je SEO"), od OND-204 depublikovaný '
     '(UNPUBLISHED_ARTICLE_IDS). Není v REMOVED_ARTICLE_REDIRECTS, takže '
     '`redirectRemovedArticle()` čtenáře 301 vyhodí na výpis blogu — kliknul '
     'na slíbený článek o SEO a přistane nikde. Navíc mezera před tečkou.'),
    (10, 'Ten Reasons Why You Need a Website',
     'Osnova 6 + 10 + 10 + 5 bodů, kterou CS ani DE verze nemají; 31 odrážek '
     'místo rozhodovacího vodítka.'),
    (10, 'A small coffee shop will implement analytics tools on its website',
     'Vymyšlené modelové příběhy bez vazby na Ondrovy reference.'),
    (10, 'Amazon or Etsy',
     'Doporučuje čtenáři cizí prodejní platformy místo vlastního webu — '
     'nesouvisí s nabídkou.'),
    # --- článek 4
    (4, 'With our guide you will learn the key questions',
     'Druhé „our" na webu (první našel audit v článku o ceně, P1-4) — '
     'agenturní množné číslo u sólo živnostníka.'),
    (4, 'create a website that exactly matches your ideas!"',
     'Perex končí osamocenou uvozovkou — zbytek po nedotaženém copy-paste.'),
    (4, 'The story of Martin and his journey to the perfect side',
     '„side" místo „site"; celý fiktivní příběh o kavárníkovi v CS ani DE není.'),
    (4, 'He decided that on the site should:',
     'Rozpadlá věta bez předmětu — strojový překlad.'),
    (4, 'one of which cost 20.000,- CZK and the other 120.000,- CZK',
     'České korunové částky a český zápis „20.000,-" v anglickém textu; '
     'navíc čísla neodpovídají EN ceníku (€1,000 / €2,200 / od €3,800).'),
    (4, 'Read more about SEO in this article What is SEO and why is it so important?',
     'Druhý odkaz na depublikovaný článek 5 — stejné 301 na výpis blogu.'),
    (4, 'To submit a demand to a developer',
     'Doslovný překlad „podat poptávku"; anglicky nedává smysl.'),
    # --- článek 13
    (13, 'We examine how people use the site',
     'Redakční „we" — a slibuje uživatelský výzkum, který v nabídce není.'),
    (13, 'up to 94% of a visitor\'s first impression is related to the visual design',
     'Nedoložená statistika bez zdroje.'),
    (13, '88% of users will not return to a website',
     'Nedoložená statistika bez zdroje.'),
    (13, 'can increase conversion rates by up to 200%',
     'Nedoložená statistika; navíc slib výsledku, který web sám neovlivní.'),
    (13, 'over 80% of businesses redesign their existing websites',
     'Nedoložená statistika bez zdroje.'),
    (13, 'Crazy Egg',
     'Cizí případovka místo vlastní reference.'),
    (13, 'source: davidkoci.cz',
     'Jako zdroj je uvedený web jiného českého webaře — konkurence '
     'citovaná na vlastním blogu.'),
    (13, 'The UX designer will design the optimal structure',
     'Popisuje proces agenturního týmu (UX designér, grafik, vývojový tým), '
     'zatímco web staví na „pracuju sám".'),
    (13, 'I recommend reading the article How to Create a Successful Website .',
     'Odkaz na článek 11, depublikovaný v OND-204. REMOVED_ARTICLE_REDIRECTS '
     'ho 301 posílá na článek 4, tedy na úplně jiné téma než slibuje '
     'anchor text. Opět mezera před tečkou.'),
    # --- článek 6
    (6, 'Amazon , which started as an online bookstore, has grown to become the '
        'largest company in the world.',
     'Věcně nepravdivé tvrzení; navíc mezera před čárkou po odkazu.'),
    (6, 'Shopify is set to make $3.7 billion in 2022',
     'Dolarové číslo z roku 2022 v textu, který má prodávat aplikaci na míru '
     'českému a německému klientovi.'),
    (6, 'You can see how much my app saved for Toyota in this project of mine .',
     'Nedokončená věta s vloženým odkazem a mezerou před tečkou — '
     'stejná vada jako P1-4 v článku o ceně.'),
    (6, 'Having a web development team to ensure the success of your project',
     'Radí čtenáři vývojový tým; Ondra pracuje sám.'),
    (6, 'You can choose a company that is experienced',
     'Posílá čtenáře ke konkurenci.'),
    (6, 'a web app can save your business millions',
     'Slib milionových úspor, který text nedokládá — důvod pro nový slug '
     'i titulek (OND-262 část D).'),
]

# --------------------------------------------------------------------------
# CS_ANCHORS — z čeho nová EN verze vychází. Musí být v CS seederu.
# --------------------------------------------------------------------------

CS_ANCHORS = [
    (10, 'Živím se stavěním webů, takže tenhle článek nepíšu nestranně'),
    (10, 'Máte plno a zakázky chodí z doporučení.'),
    (10, 'nemusíte za ni dát sto tisíc'),
    (10, 'Web nepřinese zakázky za vás.'),
    (10, 'pochopil by do deseti vteřin, co děláte'),
    (4, 'Skoro nikdo mi nenapíše s hotovým zadáním a je to v pořádku.'),
    (4, 'Nejhorší zadání je „udělejte to hezky".'),
    (4, '9. Do kdy to potřebujete'),
    (4, 'Nic nepodepisujete dopředu.'),
    (13, 'Redesign se většinou řeší ve chvíli, kdy se web někomu přestane líbit.'),
    (13, 'Zahodí se adresy stránek.'),
    (13, 'Než do toho dáte sto tisíc, zeptejte se pěti klientů'),
    (13, 'Kód píšu vlastní, bez hotových doplňků od cizích autorů.'),
    (6, 'Osmnáct let jsem pracoval v Toyotě.'),
    (6, 'Excel je výborný nástroj'),
    (6, 'Naprogramoval jsem tam webovou aplikaci TSM'),
    (6, 'Když vám vyjde číslo v řádu desítek tisíc ročně'),
    (6, 'Není to hotový systém, kterému se musíte přizpůsobit.'),
]

# Euro kotvy: CS má koruny, EN musí mít stejná čísla jako DE, ne přepočet.
EURO_PARITY = [
    (10, 'viertausend Euro', '€4,000'),
    (13, 'ein paar tausend Euro', 'a few thousand euros'),
    (6, 'mehreren tausend Euro pro Jahr', 'thousands of euros a year'),
    (6, 'ein paar hundert Euro', 'a few hundred euros'),
]


# --------------------------------------------------------------------------
# Kontroly
# --------------------------------------------------------------------------

def check_kills():
    cache = {}
    for article_id, fragment, _why in KILLS:
        page = ARTICLES[article_id]['page']
        if page not in cache:
            with open(os.path.join(PAGES, page), encoding='utf-8') as fh:
                cache[page] = norm(fh.read())
        if norm(fragment) not in cache[page]:
            ERRORS.append(f'KILL nenalezen ve zdroji ({page}): {fragment[:70]!r}')


def check_cs_anchors(cs_src):
    for article_id, fragment in CS_ANCHORS:
        if fragment not in cs_src:
            ERRORS.append(f'CS kotva článku {article_id} není v BlogContentSeeder: '
                          f'{fragment[:70]!r}')


def check_euro_parity(de_src):
    for article_id, de_fragment, en_fragment in EURO_PARITY:
        if de_fragment not in de_src:
            ERRORS.append(f'DE částka článku {article_id} není v BlogContentDeSeeder: '
                          f'{de_fragment!r}')
            continue
        body = ' '.join(str(ARTICLES[article_id].get(f) or '')
                        for f in ('perex', 'content_1', 'content_mid', 'content_2'))
        if en_fragment not in body:
            ERRORS.append(f'EN částka článku {article_id} nesedí na DE: {en_fragment!r}')


def known_slugs(slugs_sql, cs_src, de_src):
    found = set(re.findall(r"'([a-z0-9][a-z0-9-]{6,})'", slugs_sql))
    found |= set(re.findall(r"'([a-z0-9][a-z0-9-]{6,})'", cs_src))
    found |= set(re.findall(r"'([a-z0-9][a-z0-9-]{6,})'", de_src))
    return found


def check_slugs(slugs_sql, cs_src, de_src):
    taken = known_slugs(slugs_sql, cs_src, de_src)
    for article_id, art in ARTICLES.items():
        if art['slug'] not in taken:
            ERRORS.append(f'starý slug článku {article_id} není ve zdrojích: '
                          f'{art["slug"]!r}')
        new = art['slug_change']
        if new and new in taken:
            ERRORS.append(f'nový slug článku {article_id} koliduje: {new!r}')


def check_structure(de_src):
    """Počet <h2> a <li> v nové EN verzi musí sedět na DE verzi."""
    blocks = {}
    for match in re.finditer(r'^\s{12}(\d+) => \[(.*?)^\s{12}\],', de_src,
                             re.S | re.M):
        blocks[int(match.group(1))] = match.group(2)

    for article_id, art in ARTICLES.items():
        de_block = blocks.get(article_id)
        if de_block is None:
            ERRORS.append(f'DE blok článku {article_id} nenalezen')
            continue
        en_body = ' '.join(str(art.get(f) or '')
                           for f in ('perex', 'content_1', 'content_mid',
                                     'content_2'))
        for tag in ('h2', 'li', 'blockquote'):
            de_n = len(re.findall(f'<{tag}>', de_block))
            en_n = len(re.findall(f'<{tag}>', en_body))
            if de_n != en_n:
                ERRORS.append(f'článek {article_id}: <{tag}> DE={de_n} EN={en_n}')


def check_lengths():
    for article_id, art in ARTICLES.items():
        for field in ('description',):
            value = art.get(field)
            if value and len(value) > 500:
                ERRORS.append(f'článek {article_id}: {field} má {len(value)} znaků '
                              f'(limit 500)')


# --------------------------------------------------------------------------

def main():
    cs_src = staging_file('database/seeders/BlogContentSeeder.php')
    de_src = staging_file('database/seeders/BlogContentDeSeeder.php')
    slugs_sql = staging_file('database/sql/article_slugs.sql')

    check_kills()
    check_cs_anchors(cs_src)
    check_euro_parity(de_src)
    check_slugs(slugs_sql, cs_src, de_src)
    check_structure(de_src)
    check_lengths()

    records = []
    for article_id, art in ARTICLES.items():
        url_old = f'/en/blog/{art["slug"]}'
        url_new = f'/en/blog/{art["slug_change"] or art["slug"]}'
        for field in ('title', 'description', 'perex', 'content_1',
                      'content_mid', 'content_2'):
            if art.get(field) is None:
                continue
            records.append(dict(
                id=f'B{article_id}-{field}',
                layer='db', locale='en', file='article_translations',
                article_id=article_id, key=field,
                url=url_new, old=None, new=art[field],
                why='kompletni nahrada strojoveho prekladu, predloha CS verze '
                    '(BlogContentSeeder), struktura dle DE (BlogContentDeSeeder)'))
        if art['slug_change']:
            records.append(dict(
                id=f'B{article_id}-slug',
                layer='db', locale='en', file='article_slugs',
                article_id=article_id, key='slug',
                url=url_old, old=art['slug'], new=art['slug_change'],
                why='stary slug drzi framovani, ktere CS i DE opustily; '
                    '301 resi PageController::article() pres active=0'))

    records.append(dict(
        id='B-kills', layer='db', locale='en', file='article_translations',
        article_id=None, key='(co padá)', url=None, old=None, new=None,
        why='seznam odstranenych pasazi', kills=[
            dict(article_id=a, fragment=f, why=w) for a, f, w in KILLS]))

    out = os.path.join(HERE, 'redline.json')
    with open(out, 'w', encoding='utf-8') as fh:
        json.dump(records, fh, ensure_ascii=False, indent=2)
        fh.write('\n')

    if ERRORS:
        print(f'NESHODY ({len(ERRORS)}):')
        for err in ERRORS:
            print('  -', err)
        return 1

    print(f'OK — {len(records)} záznamů zapsáno do {out}')
    print(f'    ověřeno: {len(KILLS)} starých fragmentů proti scrapu produkce,')
    print(f'             {len(CS_ANCHORS)} českých kotev proti BlogContentSeeder,')
    print(f'             {len(EURO_PARITY)} částek proti DE verzi,')
    print(f'             struktura 4 článků proti BlogContentDeSeeder.')
    for article_id, art in sorted(ARTICLES.items()):
        body = ''.join(str(art.get(f) or '') for f in
                       ('title', 'description', 'perex', 'content_1',
                        'content_mid', 'content_2'))
        text = re.sub(r'<[^>]+>', '', body)
        print(f'    článek {article_id:>2}: {len(text)} znaků čistého textu')
    return 0


if __name__ == '__main__':
    sys.exit(main())
