<?php

return [

    // OND-201 (nález 5.7): titulek se definoval negací konkurence („Žádné
    // šablony, žádný WordPress"). Zákazník s rozpočtem 150 tisíc nehledá,
    // kdo nadává na konkurenci, a „WordPress" navíc neříká nic člověku,
    // který netuší, co to je (princip 0).
    'meta' => [
        'title'       => 'Weby a aplikace na míru | ONDRAWEB',
        'description' => 'Weby, e-shopy a webové aplikace na míru pro menší a střední firmy. Vlastní kód, přesná cena předem a jednáte přímo se mnou. Jsem Ondřej Kriška.',
    ],

    'hero' => [
        // OND-127 P0 incident hotfix (2026-05-14) — plagiátorské stringy
        // (page_mark_label, upline, subline + "Ale jaký..." heading)
        // odebrány z produkce. Placeholder copy vychází z meta description
        // = pre-redesign Ondřejova safe copy (B2B IT, weby na míru).
        // FINAL COPY: Content Writer dodá v OND-136 P3 (SLA 2h od 11:10 UTC).
        // Žádný fragment z reference screenshotu (governance: design ref = DNA only).
        'page_mark_label' => 'WEB NA MÍRU',
        // OND-145 P0.3: page_mark_index odebrán — agency-portfolio pagination
        // artefakt, itwebtech nemá „pages" hierarchy v hero kontextu (CEO 13:48).
        // OND-198 (nález 5.1): původní titulek „Web, který vám konečně vydělá."
        // sliboval výsledek za klienta. Nahrazen schválenou hero sekcí
        // z dokumentu homepage-texty (OND-186).
        'upline'          => 'Pro firmy, které poznají rozdíl.',
        'heading_html'    => 'Weby a aplikace <em>na míru</em>.<br>Postavím vám je sám, na vlastním kódu.',
        'subline'         => 'Jsem Ondřej Kriška, zkušený vývojář. Pracuju s vámi napřímo, bez agentury a bez prostředníků. Weby stavím tak, aby fungovaly roky a nezdržovaly vás údržbou.',
        'note'            => 'Ozvu se do 24 hodin v pracovní dny. Nezávazně proberu, co dává smysl.',

        // Backwards compat — staré klíče zachované pro non-hero spotřebitele
        // (consultation modal, fallback render). cta_secondary není v hero.
        'eyebrow'       => 'Webové stránky a aplikace na míru',
        'heading'       => 'Weby a aplikace na míru. Postavím vám je sám, na vlastním kódu.',
        'cta_primary'   => 'Napište mi, co potřebujete',
        'cta_secondary' => 'Domluvit 30min konzultaci',
        'phone_label'   => 'nebo zavolat:',
    ],

    'modal' => [
        'title'              => 'Domluvme se',
        'subtitle'           => 'Bezplatná konzultace — nezávazně, bez registrace.',
        'calendly_btn'       => 'Vybrat termín konzultace',
        'cta_note'           => 'Bezplatně. Bez závazku.',
        'play_btn'           => 'Přehrát video',
    ],

    'anchors' => [
        'how_i_work' => 'jak-pracuji',
        'poptavka'   => 'poptavka',
    ],

    'social_proof' => [
        'rating_aria'  => 'Hodnocení 5 z 5',
        // OND-231: řada log klientů má vlastní landmark label, aby čtečka
        // nečetla druhý blok pod stejným „Hodnocení 5 z 5".
        'clients_aria' => 'Klienti',
        'rating_value' => '5,0',
        'reviews'      => '(21 recenzí Google + Firmy.cz)',
        'projects'     => '23+ realizací',
        'experience'   => '18 let praxe',
        'response'     => 'Odpověď do 24 hodin v pracovní dny',
        // OND-201 (nález 5.11): ocenění TOP firma 2025 z Firmy.cz je ověřitelný
        // důkaz třetí strany, byl na obou starých webech a na stagingu chyběl.
        // Formulace podle sekce 2 dokumentu homepage-texty (OND-186).
        'award'        => 'TOP firma 2025 na Firmy.cz',
        'brands' => [
            ['name' => 'MAKOplast',             'image' => null],
            ['name' => 'Yolk Studio',           'image' => 'yolk_studio.png'],
            ['name' => 'BARANA s.r.o.',         'image' => null],
            ['name' => 'Nové Interiéry s.r.o.', 'image' => null],
            ['name' => 'Zubní Provázek',        'image' => null],
            ['name' => 'Autokemp Veselka',      'image' => null],
            ['name' => 'PitAréna',              'image' => 'pitarena.png'],
        ],
    ],

    // OND-202 (kap. 9 bod 2 master promptu): ukázky práce jako hlavní obrazový
    // materiál. Texty ze schválené sekce 2 dokumentu homepage-texty (OND-186).
    // Popisky = fakta z živých webů (obor firmy), žádná vymyšlená čísla.
    'showcase' => [
        'heading' => 'Weby, které běží v praxi',
        'intro'   => 'Tohle jsou živé projekty, na které se můžete podívat hned teď. Klikněte a projděte si je.',
        'visit'   => 'Otevřít živý web',
        'aria'    => 'Otevřít web :domain v novém okně',
        'sites'   => [
            [
                'slug'   => 'barana',
                'domain' => 'barana.cz',
                'url'    => 'https://www.barana.cz/',
                'desc'   => 'Bioklimatické pergoly, brány a ploty',
            ],
            [
                'slug'   => 'zubniprovazek',
                'domain' => 'zubniprovazek.cz',
                'url'    => 'https://www.zubniprovazek.cz/',
                'desc'   => 'Zubní ordinace pro děti i dospělé',
            ],
            [
                'slug'   => 'pitarena',
                'domain' => 'shop.pitarena.cz',
                'url'    => 'https://shop.pitarena.cz/',
                'desc'   => 'E-shop s motorkami a náhradními díly',
            ],
        ],
    ],

    // OND-201 (nález 5.7): první velká sekce homepage se definovala negací
    // konkurence („Co se opakuje u většiny webových projektů") a dva ze tří
    // bodů říkaly totéž (šablona vypadá jako u konkurence). Nově vede to, co
    // dělám já (`lead`), vymezení je krátké a duplicitní body jsou slité
    // do jednoho. Pořadí bloků v `home.blade.php` je tomu přizpůsobené.
    'problems' => [
        'heading'             => 'Jak weby stavím',
        'lead'                => 'Každý projekt začínám pochopením vašeho byznysu. Píšu vlastní kód od základu, takže web vychází z toho, jak vaše firma reálně funguje. Mluvíte přímo se mnou od první zprávy po spuštění i dál.',
        'transition_heading'  => 'Čemu se tím vyhnete',
        'transition_text'     => 'Dvě věci, které u webových projektů vídám nejčastěji.',
        'items' => [
            [
                'heading'      => 'Šablona vydávaná za řešení na míru',
                'text'         => 'Dodavatel použije rozvržení, které použil už pětkrát, a doplní vaše texty a logo. Výsledek vypadá profesionálně — dokud neotevřete web konkurence a nenajdete stejné sekce i stejná slova. Platforma vás navíc drží v měsíčním předplatném, ze kterého si web nevezmete s sebou.',
                'quote_text'   => 'Není to případ, kdy ostatní rádoby tvůrci webů pouze plní daty šablony za nehorázné částky.',
                'quote_author' => 'Petr Kroulík, Nové Interiéry s.r.o.',
            ],
            [
                'heading' => 'Nikdy nemluvíte s člověkem, který web dělá',
                'text'    => 'Ten, kdo vám prodává web, ho nestaví. Ti, kdo ho staví, s vámi nemluví. Uprostřed se ztrácí kontext a záměr — a výsledek neodpovídá tomu, co jste chtěli.',
            ],
        ],
    ],

    'how_i_work' => [
        'heading'  => 'Od první zprávy ke spuštěnému webu — 4 jasné kroky.',
        'cta_intro' => 'Pojďme rovnou ke kroku 1.',
        'cta_label' => 'Domluvit konzultaci',
        'steps'   => [
            [
                'heading'      => 'Konzultace',
                'time'         => '60 min, do týdne',
                'text'         => 'Začínám konzultací, ne formulářem. Potřebuji pochopit váš byznys, vaše zákazníky a co má web skutečně udělat — přivést kontakty, prodat produkt nebo vybudovat důvěru.',
                'quote_text'   => 'Pan Kriška opravdu naslouchal mým potřebám a následně tyto informace zpracoval až do mé úplné spokojenosti.',
                'quote_author' => 'Magda Pernicová, Realiťačky v akci',
            ],
            [
                'heading'      => 'Specifikace',
                'time'         => '2–5 dní',
                'text'         => 'Než začnu pracovat, dostanete písemnou specifikaci: co bude na webu, kolik stránek, jaká technologie a kolik to bude stát. Žádné překvapení na faktuře. Termín dodání odhadnu realisticky — a vždy předem, ne zpětně.',
                'quote_text'   => 'Důsledně analyzuje stav a chce poznat současné procesy. Shromažďuje požadavky od zákazníků a zjišťuje vize pro budoucnost.',
                'quote_author' => 'Jan Stybor, vedoucí projektového oddělení, Toyota',
                'note'         => 'Poznámka k termínům: web nevzniká jen na mé straně. Schvalování, podklady od klienta a zpětná vazba jsou součástí procesu. Termín je proto vždy odhad, ne závazek — a říkám to otevřeně od začátku.',
            ],
            [
                'heading' => 'Tvorba',
                'time'    => '3–10 týdnů',
                'text'    => 'Průběžně vás informuji o postupu a zapojuji vás do klíčových rozhodnutí. Výsledek odpovídá tomu, co jste si přáli — protože nečekám na konec projektu, abych to zjistil.',
            ],
            [
                'heading' => 'Spuštění a podpora',
                'time'    => 'do druhého dne',
                'text'    => 'Po schválení nasazení spouštím web obvykle do jednoho pracovního dne. Po spuštění zůstávám k dispozici — drobné úpravy, technické dotazy i pomoc s analytikou řeším přímo, bez ticketu a bez čekání.',
                'note'    => 'Start do 1 pracovního dne od schválení.',
            ],
        ],
    ],

    'ai' => [
        'subheading' => 'Šablona je hotová rychle. Poptávky tím rychle nepřijdou.',
        'heading'    => 'Generátor versus váš byznys',
        'intro'      => 'Generátory dneška umí naklikat layout, doplnit texty i obrázky. Co neumí: zjistit, komu prodáváte, proč si vás vybrat a kde se vám zákazník ztrácí. Web, který má prodávat, začíná u toho druhého.',
        'laik' => [
            'label'   => 'Laik + AI',
            'outcome' => 'Rychlý výsledek.',
            'items'   => [
                'Generický, neověřený, zaměnitelný',
                'Bez výzkumu zákazníků a konkurence',
                'Bez strategie, co má web říkat a v jakém pořadí',
                'Hezky vypadající — identický s desítkami dalších',
            ],
            'note' => 'AI web dává smysl, když zkoušíte nápad bez závazku.',
        ],
        'expert' => [
            'label'   => 'Odborník + AI',
            'outcome' => 'Stejně rychlé tam, kde to dává smysl. A bez generického výsledku.',
            'items'   => [
                'Postavené na strategii, datech a vašich zákaznících',
                'Kontrola a výsledek, za který někdo ručí',
                'Obsah navržený tak, aby člověk zůstal a kontaktoval vás',
                'Web, který se liší od konkurence — záměrně',
            ],
            'note' => 'Pokud provozujete byznys, je to rozdíl, který zákazníci poznají.',
        ],
        'closing' => 'AI používám jako nástroj — zkracuje rutinní práci. Rozhodnutí o tom, co má web říkat, komu a v jakém pořadí, ale za vás neudělá. Ta práce musí být hotová dřív, než se začne web stavět.',
    ],

    'toyota' => [
        'heading'      => '18 let v Toyotě. Pak jsem odešel.',
        'text'         => 'Automobilový průmysl mě naučil jedno: za špičkovým výsledkem stojí vždy stejné kroky. Analýza, návrh, testování, ověřování — a pak znovu. Ne zkratky, ne odhady. Principy, které fungují bez ohledu na obor.',
        'text_2'       => 'Tyto principy teď aplikuji na každý webový projekt. Poznáte to při první konzultaci, ve specifikaci, kterou dostanete před zahájením práce — a na výsledku.',
        'quote_text'   => 'Jednou z nejsilnějších stránek Ondry je velká chuť rozvíjet se — nejen uspokojení potřeb zákazníků, ale překonání jejich očekávání.',
        'quote_author' => 'Pavel Baudyš, ředitel řízení výroby, montáže a logistiky, Toyota Motor Manufacturing Czech Republic (2024)',
    ],

    'portfolio' => [
        'heading'    => 'Realizované projekty',
        'cta'        => 'Všechny projekty →',
        'detail_cta' => 'Více o projektu',
        'cards' => [
            'pitarena' => [
                'client'  => 'PitArena',
                'outcome' => 'Tréninky bývají obsazené měsíce dopředu — rezervace, vouchery i registrace běží přes web bez ručního zásahu.',
            ],
            'barana' => [
                'client'  => 'BARANA',
                // OND-198 (nález 5.5): „landing page" / „Meta Ads / Google Ads"
                // přepsáno do řeči klienta.
                'outcome' => 'Samostatná stránka pro placenou reklamu — návštěvník chápe nabídku bez nutnosti volat.',
            ],
            'nove-interiery' => [
                'client'  => 'Nové interiéry',
                'outcome' => 'Web předem odfiltruje irelevantní poptávky a vystupuje jako první obchodní schůzka — klient zpětně potvrzuje vyšší věrohodnost značky.',
            ],
        ],
    ],

    'services' => [
        'heading_primary'  => 'Co stavím — weby, aplikace a e-shopy na míru',
        'heading_other'    => 'Další služby k webu',
        'secondary_inline' => 'Také zajišťuji SEO, grafický design a správu sociálních sítí — :pricing_link nebo :contact_link.',
        'secondary_inline_pricing' => 'více v ceníku',
        'secondary_inline_contact' => 'napište mi',
        'primary' => [
            // OND-130 (B2 §1, klíčová direktiva 2): cenová taxonomie 25/55/95
            // sjednocena napříč webem (services 3-card, price_anchor, /cenik tiers).
            // Service-typové karty (web/aplikace/e-shop) ukazují minimální vstupní
            // cenu z odpovídajícího tieru — viz price_anchor / price.tiers níže.
            'weby' => [
                'title'       => 'Webové stránky na míru',
                // OND-198 (nález 5.1): „a začne přivádět zákazníky" byl slib
                // výsledku za klienta — nahrazeno tím, za co ručím já.
                'description' => 'Prezentační web, který vás odliší od šablon konkurence a srozumitelně vysvětlí, co děláte a v čem jste jiní.',
                'bullets'     => [
                    'Vlastní kód — bez WordPressu a šablon',
                    'Konverzní struktura postavená na vašem byznysu',
                    'Bezúdržbový provoz a rychlé načítání',
                ],
            ],
            'aplikace' => [
                'title'       => 'Webové aplikace',
                'description' => 'Interní systémy, zákaznické portály a evidenční nástroje postavené na tom, jak váš provoz reálně funguje.',
                'bullets'     => [
                    'Návrh procesu před prvním řádkem kódu',
                    'Integrace na vaše stávající nástroje',
                    'Vlastní administrace bez měsíčních licencí',
                ],
            ],
            'eshop' => [
                'title'       => 'E-shopy',
                'description' => 'E-shop postavený na míru produktu — bez nutnosti platit za pluginy a šablony každý měsíc.',
                'bullets'     => [
                    'Pokladna a katalog navržené pro váš sortiment',
                    'Napojení na účetnictví, dopravce a platební bránu',
                    'Bez měsíčních poplatků za platformu',
                ],
            ],
        ],
        'seo' => [
            'title'       => 'Zákazníci z Googlu — bez platby za klik',
            'description' => 'Placená reklama funguje jen dokud platíte. SEO pracuje pro vás dlouhodobě. Pomůžu vám tak, aby vás zákazníci našli v Googlu zdarma — i když zrovna nemáte rozpočet na reklamu.',
        ],
        'design' => [
            'title'       => 'Vizuální identita, kterou zákazníci zaznamenají',
            'description' => 'Logo a firemní identita, kterou vaši zákazníci poznají na první pohled. Navrhnu vizuální identitu, která sedí vašemu oboru — a odliší vás od generické konkurence.',
        ],
        'social' => [
            'title'       => 'Sociální sítě, které budují důvěru',
            'description' => 'Zákazníci si vaše sociální sítě prověří dřív, než objednají. Aktivní, konzistentní přítomnost buduje důvěru. Připravím obsah a strategii, která vás přiblíží vaší cílové skupině.',
        ],
    ],

    'price_anchor' => [
        'heading' => 'Kolik to bude stát?',
        // OND-198 (nález 5.4): očekávací věta musí padnout dřív než první číslo,
        // aby normou bylo pásmo 55–150 tis. Kč, ne nejlevnější vstup.
        // OND-198 (nález 5.5): „tiery" → „tři úrovně".
        'intro'   => 'Většina projektů, které stavím, vychází mezi 55 a 150 tisíci korunami. Pokud hledáte web do dvaceti tisíc, nebudu pro vás ten správný dodavatel a řeknu vám to rovnou. Níž jsou orientační vstupní ceny tří úrovní — přesnou nabídku dostanete písemně po krátké konzultaci.',
        // OND-130 (B2 §1, klíčová direktiva 2 + plán §3.6): pricing teaser
        // sjednocen s /cenik — Standard 55 / Custom od 95 / Startovní 25 tis. Kč.
        // OND-198 (nález 5.4): pořadí Standard → Custom → Startovní, nejlevnější
        // pásmo je poslední a rámované jako výjimka. Standard je zvýrazněný.
        // Service-typový rozklad (web vs. aplikace vs. e-shop) je v sekci „services".
        'featured_label' => 'Nejčastější volba',
        'items'   => [
            [
                'title'    => 'Standard',
                'price'    => '55 000 Kč',
                'desc'     => 'Vícejazyčný web s blogem, měřením konverzí a rezervačním systémem.',
                'featured' => true,
            ],
            [
                'title'    => 'Custom',
                'price'    => 'od 95 000 Kč',
                'desc'     => 'E-shop, webová aplikace nebo komplexní portál na míru.',
                'featured' => false,
            ],
            [
                'title'    => 'Startovní',
                'price'    => '25 000 Kč',
                'desc'     => 'Výjimka, ne standardní vstup: prezentační web do 5 stránek pro živnostníky. Beru ho jen tam, kde větší rozsah nedává smysl.',
                'featured' => false,
            ],
        ],
        'cta' => 'Detailní ceník →',
    ],

    'why_me' => [
        'video_aria' => 'Video: Ondřej Kriška — kdo jsem a jak stavím weby',
        'heading'   => 'Proč já',
        'photo_alt' => 'Ondřej Kriška — webový vývojář',
        'bio'       => '18 let jsem v Toyotě řídil projekty, ve kterých nesměla padnout linka. Dnes ty samé principy — přesná specifikace, analýza, ověřování — používám pro webové projekty. Pracuji sám, mluvíte přímo se mnou od první konzultace po spuštění i dál.',
        'advantages' => [
            [
                'heading' => 'Vlastní kód, žádné šablony',
                'text'    => 'Píšu na míru — web vychází z vašeho byznysu, ne ze šablony, kterou už použila konkurence.',
            ],
            [
                'heading' => 'Cena dopředu',
                'text'    => 'Specifikaci s přesnou cenou dostanete před zahájením práce. Co je ve specifikaci, to je na faktuře.',
            ],
            [
                'heading' => 'Přímý kontakt',
                'text'    => 'Komunikujete přímo se mnou — bez obchodníka, koordinátora a ticketovacího systému.',
            ],
            [
                'heading' => 'Vyrobím to tak, aby to drželo',
                'text'    => 'Bezúdržbový provoz bez WordPress aktualizací a pluginů — žádné měsíční opravy bezpečnostních děr.',
            ],
        ],
    ],

    'testimonials' => [
        'heading' => 'Co o spolupráci říkají moji klienti.',
        // OND-267 (audit P1-1): v EN/DE jde o překlad českých originálů —
        // bez téhle věty návštěvník originál na Google/Firmy.cz nedohledá.
        // Česky poznámka nedává smysl, šablona prázdnou hodnotu nevykreslí.
        'note'    => '',
    ],

    'guarantee' => [
        'heading' => 'Dvě věci, na které se můžete spolehnout.',
        'items'   => [
            [
                'heading' => 'Cena dopředu',
                'text'    => 'Dostanete specifikaci s přesnou cenou ještě před zahájením práce. Co je ve specifikaci, to je na faktuře. Bez vícenákladů, bez překvapení.',
            ],
            [
                'heading' => 'Přímý kontakt vždy',
                'text'    => 'Komunikujete přímo se mnou — ne s obchodníkem nebo koordinátorem. Zavolejte kdykoliv. V drtivé většině případů zvednu hned.',
            ],
        ],
    ],

    // OND-229 (F2 — důkazní vrstva, R3 plánu OND-226): sekce „Pod kapotou"
    // ukazuje řemeslo, které v kódu reálně je. Každé tvrzení je ověřitelné
    // v repu: 7 šířek obrázků = resources/js/app.js (`w: '320;…;1536'`),
    // vlastní kód = žádný CMS/builder v composeru, tokeny = resources/css.
    // Čas načtení měří Performance API v prohlížeči návštěvníka — nikdy
    // netvrdíme číslo, které jsme nenaměřili.
    'craft' => [
        'heading' => 'Pod kapotou',
        'intro'   => 'Web, který vám stavím, vypadá takhle i zevnitř. Tohle nejsou marketingové věty — všechno níž se dá ověřit přímo na stránce, na které právě jste.',
        'facts'   => [
            [
                'heading' => 'Vlastní kód',
                'text'    => 'Žádný WordPress, žádný page-builder, žádná platforma. Stránka je napsaná na míru a běží bez pluginů, které by bylo nutné měsíčně aktualizovat.',
            ],
            [
                'heading' => 'Obrázky šité na displej',
                'text'    => 'Každý obrázek tu existuje v sedmi velikostech a úsporném formátu AVIF. Váš prohlížeč si stáhl jen tu, kterou váš displej opravdu potřebuje.',
            ],
            [
                'heading' => 'Design drží systém',
                'text'    => 'Barvy, písmo a rozestupy neřídí šablona, ale vlastní systém proměnných. Proto nic nepřečnívá — a proto si na to níž můžete sáhnout.',
            ],
        ],
        'perf_prefix' => 'Tahle stránka se vám načetla za',
        'perf_suffix' => '— změřeno právě teď, ve vašem prohlížeči.',
    ],

    // OND-235: živé demo (OND-229) zrušeno — majitel webu sám neuměl
    // popsat přínos pro návštěvníka a na mobilu byl efekt ovladačů
    // mimo viditelnou oblast. Klíče i CSS (.pd-demo*) odstraněny.

    // OND-201 (nález 5.8): konec homepage byl tři výzvy za sebou —
    // „Pošlete mi pár vět o projektu" (inline_form), „Připraveni začít?
    // Konzultace je zdarma." (home.cta) a „Řeknu vám upřímný názor na váš
    // projekt." (home.final_cta). Bloky `cta` a `final_cta` jsou zrušené;
    // zůstala jedna výzva s jedním formulářem v `inline_form` níž.
    // Klientská citace z `final_cta` se přesunula k té jedné výzvě.

    'faq' => [
        'heading' => 'Časté otázky',
        // `key` je stabilní slug pro analytics (data-faq-key) a JSON-LD; ne lokalizovat.
        'items'   => [
            // OND-222 (kap. 6.3 bod 1): nejzávažnější námitka u zakázky za
            // 150 tisíc. Odpověď byla 2026-09-16 stažená (fakta nepotvrzená),
            // Ondra je potvrdil 2026-09-17 a dvě z nich opravil:
            //  - kód patří klientovi, ale fyzicky ho drží Ondra do doplacení;
            //    NE „od začátku máte u sebe" (to bylo nepravdivé);
            //  - Laravel se nezmiňuje — klientovi to nic neříká (princip 0);
            //  - dokumentace se standardně nedělá, jen na vyžádání.
            // Platí dál: bez slibu nepřetržité pohotovosti a bez věty
            // o „připraveném předání" (nedoložitelné).
            [
                'key'      => 'single-person',
                'question' => 'Jste jeden člověk. Co když onemocníte nebo skončíte?',
                'answer'   => 'Rozumím — u zakázky za víc než sto tisíc je to ta nejdůležitější otázka. Web neběží na platformě, ze které byste nemohli odejít: je to vlastní kód na běžném webhostingu. Přístupy do administrace hostingu a na FTP můžete mít po celou dobu, stačí si o ně říct. Po doplacení projektu je kód váš — předám vám ho, kdykoli si o něj řeknete, a pokračovat na něm může kterýkoli vývojář; když bude potřeba, sepíšu k tomu i dokumentaci. Nepřetržitou pohotovost nedržím a nebudu tvrdit, že ano. Ručím za to, že u mě nic nezůstane zamčené.',
            ],
            [
                'key'      => 'price',
                'question' => 'Kolik to bude stát?',
                // OND-198 (nález 5.5): „cenová kotva výše" byl žargon; odkaz teď
                // míří na skutečnou sekci. OND-198 (nález 5.4): vede Standard,
                // nejlevnější pásmo je uvedené jako výjimka.
                'answer'   => 'Většina projektů vychází mezi 55 a 150 tisíci korunami. Ceny najdete výš na této stránce v sekci „Kolik to bude stát?“ a podrobně v ceníku — Standard 55 000 Kč, Custom od 95 000 Kč. Startovní pásmo za 25 000 Kč je výjimka pro živnostníky, ne standardní vstup. Přesnou cenu dostanete písemně po krátké konzultaci, na faktuře je pak přesně to, co je ve specifikaci.',
            ],
            [
                'key'      => 'duration',
                'question' => 'Jak dlouho to trvá?',
                'answer'   => 'Od první zprávy ke spuštěnému webu typicky 4–12 týdnů — týden na konzultaci, 2–5 dní na specifikaci, 3–10 týdnů na tvorbu a spuštění do druhého dne po schválení. Detailní timing pro váš projekt sepíšu do specifikace.',
            ],
            [
                'key'      => 'satisfaction',
                'question' => 'Co když nebudu spokojený?',
                'answer'   => 'Pracuji v krátkých iteracích a posílám průběžné náhledy — nečekám na konec projektu, abych zjistil, jestli to sedí. Pokud něco nesedí, řešíme to hned, ne až po faktuře. Co je ve specifikaci, to dodám.',
            ],
            [
                'key'      => 'maintenance-free',
                'question' => 'Co jsou „bezúdržbové weby“?',
                'answer'   => 'Žádný WordPress, žádné pluginy, žádné měsíční bezpečnostní aktualizace. Web stojí na vlastním kódu — běží sám, nevyžaduje pravidelné opravy a nepadá kvůli kolizi šablon. Drobné změny obsahu řešíme přímo, bez ticketu.',
            ],
            // Archiv: další FAQ otázky se přesouvají mimo homepage (na /faq nebo /sluzby — mimo scope OND-121).
        ],
    ],

    'faq_form' => [
        'eyebrow'     => 'Máte jinou otázku?',
        'heading'     => 'Napište ji rovnou.',
        'description' => 'Zachytím to, ozvu se do 24 hodin v pracovní dny. Bez obchodního tlaku.',
        'name'        => 'Jméno',
        'email'       => 'E-mail',
        'message'     => 'Vaše otázka',
        'placeholders' => [
            'name'    => 'Jan Novák',
            'email'   => 'jan@firma.cz',
            'message' => 'Např. Stíháte to do konce kvartálu?',
        ],
        'submit'      => 'Odeslat otázku',
        'submitting'  => 'Odesílám…',
        'success'     => 'Děkuji, otázka dorazila. Ozvu se co nejdříve.',
    ],

    // OND-201 (nález 5.8): jediná závěrečná výzva homepage. Text ze schválené
    // sekce 9 dokumentu homepage-texty (OND-186).
    'inline_form' => [
        'eyebrow'         => 'Poptávka',
        'heading'         => 'Napište mi, co potřebujete',
        'description'     => 'Popište mi ve zkratce, co řešíte. Ozvu se do 24 hodin v pracovní dny a nezávazně probereme, jestli si sedneme a co dává smysl. Když zjistíme, že na sebe nepasujeme, řeknu vám to rovnou.',
        'quote_text'      => 'Díky individuálnímu přístupu, flexibilitě a profesionalitě odpovídá výsledek našim představám.',
        'quote_author'    => 'Hana Jaskmanická, výkonná ředitelka, VP Industry',
        'name'            => 'Jméno a příjmení',
        'email'           => 'E-mail',
        'phone'           => 'Telefon (nepovinný)',
        'phone_hint'      => 'S číslem se ozvu rychleji.',
        'message'         => 'Co potřebujete vyřešit?',
        'placeholders'    => [
            'name'    => 'Jan Novák',
            'email'   => 'jan@firma.cz',
            'phone'   => '+420 000 000 000',
            'message' => 'Např. nový web pro výrobní firmu, 5–10 stran',
        ],
        'submit'          => 'Poslat poptávku',
        'submitting'      => 'Odesílám…',
        'note'            => 'Nebo mi napište na ok@ondraweb.cz. Ozvu se osobně, ne přes formulářového robota.',
        'privacy_prefix'  => 'Odesláním souhlasíte se zpracováním osobních údajů v souladu se ',
        'privacy_link'    => 'zásadami ochrany osobních údajů',
        'success'         => 'Děkuji, poptávka dorazila. Ozvu se co nejdříve.',
        'error'           => 'Poptávku se teď nepodařilo uložit. Zkuste to prosím znovu.',
    ],

    'sticky' => [
        'cta'     => 'Domluvit konzultaci',
        'mobile'  => 'Poptávka',
        'phone'   => 'Zavolat',
    ],

];
