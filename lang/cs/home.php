<?php

return [

    // OND-201 (nález 5.7): titulek se definoval negací konkurence („Žádné
    // šablony, žádný WordPress"). Zákazník s rozpočtem 150 tisíc nehledá,
    // kdo nadává na konkurenci, a „WordPress" navíc neříká nic člověku,
    // který netuší, co to je (princip 0).
    'meta' => [
        'title'       => 'Weby na míru: cenu počítám, ne odhaduju | ONDRAWEB',
        'description' => 'Weby, e-shopy a webové aplikace na míru. Cenu spočítám předem, stavím sám na vlastním kódu. 5,0 z 21 recenzí, odpověď nejpozději následující pracovní den.',
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
        // OND-333: Ondřej vybral na OND-330 cestu B — variantu P ve
        // zmenšeném nadpisu (52 px na desktopu, 33 px na mobilu, viz
        // `.pd-heading--hp` v podpis.css). Tři řádky jsou tři samostatné
        // slovesné protiklady, proto je zalomení tvrdé (`<br>`), ne
        // ponechané na šířce okna. `<em>` (podtržení klíčového slova)
        // v téhle variantě není — tři protiklady nemají jedno klíčové
        // slovo a na nafocené variantě žádné podtržení nebylo.
        'upline'          => 'Pro firmy, které rostou.',
        'heading_html'    => 'Ptám se, ne hádám.<br>Počítám, ne odhaduju.<br>Ručím, ne slibuju.',
        'subline'         => 'Jsem Ondřej Kriška. Weby a aplikace stavím na vlastním kódu a pracuju na nich sám. Cenu spočítám před začátkem, hodnocení mám 5,0 z 21 recenzí.',
        'note'            => 'Ozvu se nejpozději následující pracovní den. Nezávazně proberu, co dává smysl.',

        // Backwards compat — staré klíče zachované pro non-hero spotřebitele
        // (fallback render). Musí souhlasit s vybranou variantou titulku.
        // OND-308: `cta_secondary` („Domluvit 30min konzultaci") a
        // `phone_label` („nebo zavolat:") zrušené — rezervace nejsou
        // od OND-303 a telefon v heru tříštil rozhodnutí hned pod hlavní
        // výzvou. Číslo zůstává v liště a ve spodní mobilní liště.
        'eyebrow'       => 'Weby a aplikace na míru',
        'heading'       => 'Ptám se, ne hádám. Počítám, ne odhaduju. Ručím, ne slibuju.',
        'cta_primary'   => 'Napište mi, co potřebujete',
    ],

    'anchors' => [
        'how_i_work' => 'jak-pracuji',
        'poptavka'   => 'poptavka',
    ],

    'social_proof' => [
        // OND-315: `rating_aria` popisuje jen hodnocení, proto sedí u toho
        // jednoho údaje, ne na celé sekci — v pruhu jsou i realizace, praxe,
        // doba odpovědi a ocenění. Landmark pruhu popisuje `strip_aria`.
        'rating_aria'  => 'Hodnocení 5 z 5',
        'strip_aria'   => 'Čísla o mojí práci',
        // OND-231: řada log klientů má vlastní landmark label, aby čtečka
        // nečetla druhý blok pod stejným „Hodnocení 5 z 5".
        'clients_aria' => 'Klienti',
        'rating_value' => '5,0',
        'reviews'      => '(21 recenzí Google + Firmy.cz)',
        'projects'     => '23+ realizací',
        'experience'   => '18 let praxe',
        'response'     => 'Odpověď nejpozději následující pracovní den',
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

    // OND-269 (audit OND-254, nález 7): blok `showcase` („Weby, které běží
    // v praxi" — tři dlaždice s odkazem na živý web) je zrušený. Dvě sekce
    // projektů nad sebou říkaly totéž a BARANA i PitArena byly v obou.
    // Slito do jedné sekce `portfolio` níž, která nese titulek odsud
    // a doplnila si odkaz na živý web (`live_cta` / `live_aria`).

    // OND-308: blok `problems` („Jak weby stavím" / „Čemu se tím vyhnete" /
    // „Šablona je hotová rychle") je zrušený. Sekce se definovala negací
    // konkurence, na nové stránce stejný argument nese sekce „Co stavím"
    // jedinou větou („Nepoužívám šablonu, kterou už má vaše konkurence.").
    // Místo něj stojí hned pod herem odstavec o situaci klienta — popis
    // stavu, u kterého klient kývne hlavou, bez nadpisu a bez tlačítka.
    //
    // OND-320 (varianta G, vybral Ondra): věta je fakt o Ondrovi — kdo mu
    // píše — ne tvrzení o návštěvníkovi. Původní znění návštěvníkovi
    // podsouvalo, jak vypadá jeho firma („máte víc práce", „přepisujete
    // objednávky"). Nepřeklápět zpátky do druhé osoby a nestrašit; klient
    // roste, nemá problém. Stejné pravidlo platí pro en i de. Odstavec se
    // má vejít na jeden vykreslený řádek (`.pd-lead--wide`, 52 ch): 70 znaků
    // projde, zhruba od 74 se zalomí.
    'situation' => [
        'text' => 'Nejčastěji mi píšou lidi, kterým se daří a web jim přestal stačit.',
    ],

    // OND-308: `cta_label` („Domluvit konzultaci") zrušený — vedl na
    // rezervaci, která od OND-303 neexistuje, a byl to třetí odesílací
    // podnět na stránce. `cta_intro` zůstává jako text, tlačítko pod ním ne.
    'how_i_work' => [
        'heading'  => 'Od první zprávy ke spuštěnému webu ve čtyřech krocích',
        'cta_intro' => 'Pojďme rovnou ke kroku 1.',
        'steps'   => [
            [
                'heading'      => 'Konzultace',
                'time'         => '60 min, do týdne',
                'text'         => 'Napíšete mi přes formulář dole, co řešíte. Ozvu se nejpozději následující pracovní den a domluvíme se na hovoru nebo na schůzce. Mluvíte se mnou, ne s obchodníkem — zajímá mě, komu prodáváte, jak u vás vzniká poptávka a co má web udělat.',
                'quote_text'   => 'Pan Kriška opravdu naslouchal mým potřebám a následně tyto informace zpracoval až do mé úplné spokojenosti.',
                'quote_author' => 'Magda Pernicová, Realiťačky v akci',
            ],
            [
                'heading'      => 'Specifikace',
                'time'         => '2–5 dní',
                'text'         => 'Dostanete písemně, co na webu bude, kolik stránek to má a kolik to bude stát. Co je ve specifikaci, to je na faktuře. Termín dodání odhaduju předem, ne zpětně.',
                'quote_text'   => 'Důsledně analyzuje stav a chce poznat současné procesy. Shromažďuje požadavky od zákazníků a zjišťuje vize pro budoucnost.',
                'quote_author' => 'Jan Stybor, vedoucí projektového oddělení, Toyota',
                'note'         => 'Termín je odhad, ne závazek. Schvalování a podklady z vaší strany jsou součástí práce a říkám to rovnou na začátku.',
            ],
            [
                'heading' => 'Tvorba',
                'time'    => '3–10 týdnů',
                'text'    => 'Píšu vlastní kód, takže web vychází z vaší firmy a ne z hotového rozvržení. Průběžně posílám náhledy a ptám se na rozhodnutí, která má smysl udělat s vámi. Na konci nezjišťujete, jestli to sedí — víte to celou dobu.',
            ],
            [
                'heading' => 'Spuštění a podpora',
                'time'    => 'do druhého dne',
                // OND-308: sem patří ještě doložení bezúdržbovosti reálným
                // provozem (`proof`). Klíč se zakládá až se schváleným
                // zněním — data si vyžádal CEO od Ondry na OND-305.
                'text'    => 'Po schválení spouštím web obvykle do jednoho pracovního dne. Pak na něm není co udržovat — nemá doplňky, které si vynucují měsíční aktualizace, takže vám za dva roky nepřijde faktura za opravu něčeho, co se samo rozbilo. Drobné úpravy a dotazy po spuštění řešíte přímo se mnou.',
                'note'    => 'Start do 1 pracovního dne od schválení.',
            ],
        ],
    ],

    // OND-308: Toyota je zpátky samostatnou sekcí — třetí na stránce, hned
    // po odstavci o situaci klienta. Nese video, oba odstavce i citaci Pavla
    // Baudyše; z řady referencí níž proto Baudyš mizí (stál by tam podruhé).
    // OND-317: `example` je konkrétní ukázka z reálné zakázky — znění schválil
    // CEO v OND-316 (dokument „Návrh textu", revize 2). Každý fakt je doložený
    // z případovky `/projekty/pitarena-eshop` a z recenze Jana Stybora
    // (`lang/cs/testimonials.php`). Nesahat bez OND-316: text se nepřepisuje
    // tady, ale v dokumentu. En/de verze zatím nejsou — překlady jdou
    // samostatnou dávkou, do té doby klíč visí jen v `cs`.
    'toyota' => [
        'heading'      => '18 let v Toyotě. Pak jsem odešel.',
        'text'         => 'Začínal jsem jako dělník v logistice a skončil jako starší specialista v projektovém týmu. Osmnáct let jsem hledal, kde se ve výrobě a montáži ztrácí čas, a napsal k tomu firemní aplikaci, která ušetřila miliony korun. Ve výrobě si nemůžete dovolit, aby vám něco spadlo. Tam jsem se naučil, že software se dělá pořádně, nebo vůbec.',
        'text_2'       => 'Weby dělám stejně. Než napíšu první řádek, chci vědět, jak u vás vzniká poptávka a co se s ní děje potom. Teprve podle toho stránka vznikne. Poznáte to na specifikaci, kterou dostanete dřív, než začnu pracovat.',
        'example'      => 'U e-shopu PitArena jsem se nejdřív ptal, jak si jeho zákazníci vybírají díl. Bez modelu a ročníku nakupují naslepo a vracejí. Katalog je proto rozdělený podle 19 modelů motorek — člověk klikne na svůj model a ze 4 551 položek vidí jen ty, které sednou.',
        'quote_text'   => 'Jednou z nejsilnějších stránek Ondry je velká chuť rozvíjet se — nejen uspokojení potřeb zákazníků, ale překonání jejich očekávání.',
        'quote_author' => 'Pavel Baudyš, ředitel řízení výroby, montáže a logistiky, Toyota Motor Manufacturing Czech Republic (2024)',
    ],

    // OND-269: jediná sekce projektů na homepage (dřív `showcase` + `portfolio`).
    // Titulek i intro pocházejí ze zrušeného `showcase` — mluví o živých webech,
    // ne o „realizacích", a to je pro návštěvníka konkrétnější.
    'portfolio' => [
        'heading'    => 'Weby, které běží v praxi',
        'intro'      => 'Tohle jsou živé projekty, na které se můžete podívat hned teď. U každého je i to, co klientovi přinesl.',
        'cta'        => 'Všechny projekty →',
        'detail_cta' => 'Více o projektu',
        'live_cta'   => 'Otevřít živý web',
        'live_aria'  => 'Otevřít web :client v novém okně',
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
        'heading_primary'  => 'Co stavím',
        // OND-308: závazné znění ze zadání. Zmínka o vlastním kódu se sem
        // stěhuje z hero podtitulku a z bloku `problems`.
        'subheading'       => 'Píšu vlastní kód. Nepoužívám šablonu, kterou už má vaše konkurence.',
        'heading_other'    => 'Další služby k webu',
        'secondary_inline' => 'Také zajišťuji SEO, grafický design a správu sociálních sítí — :pricing_link nebo :contact_link.',
        'secondary_inline_pricing' => 'více v ceníku',
        'secondary_inline_contact' => 'napište mi',
        'primary' => [
            // OND-130 (B2 §1, klíčová direktiva 2): cenová taxonomie 25/55/95
            // sjednocena napříč webem (services 3-card, price_anchor, /cenik tiers).
            // Service-typové karty (web/aplikace/e-shop) ukazují minimální vstupní
            // cenu z odpovídajícího tieru — viz price_anchor / price.tiers níže.
            // OND-308: odrážka je nově dvojice [hlavní věta, doplněk].
            // Hlavní věta mluví jazykem klienta, doplněk pod ní nese
            // technický popis — ten se tím neztrácí, jen přestává být
            // to první, co člověk přečte. Šablona snese i holý řetězec,
            // protože en/de drží starý text do překladové karty (S4).
            'weby' => [
                'title'       => 'Webové stránky na míru',
                'description' => 'Web, který vysvětlí, co děláte a proč si vybrat vás. Vzniká podle vaší firmy, ne podle hotového rozvržení.',
                'bullets'     => [
                    ['Stejný web nenajdete o ulici dál.', 'Píšu vlastní kód, nepoužívám šablony.'],
                    ['Stránky jdou za sebou v pořadí, v jakém se váš zákazník rozhoduje.', 'Strukturu navrhuju podle toho, jak u vás vzniká poptávka.'],
                    ['Za dva roky vám nepřijde faktura za opravu něčeho, co se samo rozbilo.', 'Web neběží na doplňcích, které si vynucují měsíční aktualizace.'],
                ],
            ],
            'aplikace' => [
                'title'       => 'Webové aplikace',
                'description' => 'Interní systémy, zákaznické portály a evidenční nástroje postavené na tom, jak váš provoz reálně funguje.',
                'bullets'     => [
                    ['Než začnu psát, projdeme si, jak to u vás chodí dnes.', 'Návrh procesu vznikne před prvním řádkem kódu.'],
                    ['Objednávky si web předá sám tam, kde je už evidujete. Nikdo nic nepřepisuje.', 'Napojím ho na nástroje, které používáte.'],
                    ['Administrace je vaše a neplatíte za ni každý měsíc.', 'Žádné licence za uživatele ani za počet záznamů.'],
                ],
            ],
            'eshop' => [
                'title'       => 'E-shopy',
                'description' => 'E-shop postavený na vašem sortimentu a na tom, jak ho prodáváte.',
                'bullets'     => [
                    ['Pokladna i katalog sedí na to, co prodáváte.', 'Navrhuju je podle sortimentu, ne podle šablony.'],
                    ['Objednávku si web předá sám do účetnictví, dopravci i na platební bránu.', 'Napojení řeším při tvorbě, ne po spuštění.'],
                    ['Neplatíte nikomu nájem za to, že váš e-shop vůbec existuje.', 'Žádné měsíční poplatky za platformu ani za doplňky.'],
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
                // OND-308: omluvná věta („Výjimka, ne standardní vstup…")
                // je pryč, úroveň i cena zůstávají.
                'desc'     => 'Prezentační web do pěti stránek pro živnostníky.',
                'featured' => false,
            ],
        ],
        'cta' => 'Detailní ceník →',
    ],

    // OND-308: ze sekce „Proč já" zbyly dva popisky pro čtečku — video
    // a fotka patří k sekci o Toyotě. `heading`, `bio` i čtyři `advantages`
    // jsou zrušené: bio říkalo totéž co podtitulek hero a krok 1 procesu,
    // čtyři výhody jsou rozpuštěné v krocích procesu a v sekci „Co stavím".
    'why_me' => [
        'video_aria' => 'Video: Ondřej Kriška — kdo jsem a jak stavím weby',
        'photo_alt' => 'Ondřej Kriška — webový vývojář',
    ],

    'testimonials' => [
        'heading' => 'Co říkají klienti',
        // OND-267 (audit P1-1): v EN/DE jde o překlad českých originálů —
        // bez téhle věty návštěvník originál na Google/Firmy.cz nedohledá.
        // Česky poznámka nedává smysl, šablona prázdnou hodnotu nevykreslí.
        'note'    => '',
    ],

    // OND-308: technická sekce „Pod kapotou" (vlastní kód, AVIF, design
    // systém) je zrušená — mluvila jazykem dodavatele, ne klienta, a svůj
    // jediný klientský argument („žádné doplňky k měsíční aktualizaci")
    // říká sekce „Co stavím" i krok 4 procesu. Zůstal naměřený čas načtení:
    // je to jediné tvrzení, které si návštěvník ověří sám na sobě, a stojí
    // nově v pruhu čísel. Performance API měří v prohlížeči návštěvníka —
    // nikdy netvrdíme číslo, které jsme nenaměřili.
    'craft' => [
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
        'heading' => 'Na co se mě ptáte nejčastěji',
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
            // OND-269 (audit OND-254, nález 7): otázka „Kolik to bude stát?“
            // odsud vypadla — stejný titulek i stejná čísla stojí o čtyři
            // sekce výš v cenové kotvě (`price_anchor`) a v ceníku.
            // OND-266 tu mezitím opravil rovné uvozovky v odpovědi na cenu;
            // ta oprava zanikla se smazanou položkou, ne přetažením ze staré
            // větve. Druhá oprava z OND-266 („bezúdržbové weby“) zůstává níž.
            [
                'key'      => 'duration',
                'question' => 'Jak dlouho to trvá?',
                // OND-308: „Detailní timing" → „Přesný časový plán".
                'answer'   => 'Od první zprávy ke spuštěnému webu typicky 4 až 12 týdnů. Týden na konzultaci, dva až pět dní na specifikaci, tři až deset týdnů na tvorbu a spuštění do druhého dne po schválení. Přesný časový plán pro váš projekt sepíšu do specifikace.',
            ],
            [
                'key'      => 'satisfaction',
                'question' => 'Co když nebudu spokojený?',
                'answer'   => 'Pracuji v krátkých iteracích a posílám průběžné náhledy — nečekám na konec projektu, abych zjistil, jestli to sedí. Pokud něco nesedí, řešíme to hned, ne až po faktuře. Co je ve specifikaci, to dodám.',
            ],
            // OND-308: dnešní otázka se ptala „Co jsou ‚bezúdržbové weby'?" —
            // to je moje slovo, ne klientovo, a odpověď jmenovala cizí
            // technologii. Nová otázka je ta, kterou si klient klade sám.
            [
                'key'      => 'maintenance-free',
                'question' => 'Bude web potřebovat pravidelnou údržbu?',
                'answer'   => 'Ne. Nestojí na hotové platformě s doplňky, které se musí každý měsíc aktualizovat, takže se nemá co samo rozbít. Když budete chtít změnit obsah nebo přidat stránku, napíšete mi a udělám to.',
            ],
            // Archiv: další FAQ otázky se přesouvají mimo homepage (na /faq nebo /sluzby — mimo scope OND-121).
        ],
    ],

    // OND-308: blok `faq_form` („Máte jinou otázku?" / „Odeslat otázku")
    // je zrušený. Žádná šablona ho nevykreslovala už před přestavbou —
    // byl to odpad po mikro-formuláři zrušeném v OND-201. Endpoint
    // `source=home.faq` v HomeLeadController a past na boty v
    // tests/Feature/HoneypotTest.php zůstávají: dají se trefit zvenčí.

    // OND-201 (nález 5.8): jediná závěrečná výzva homepage. Text ze schválené
    // sekce 9 dokumentu homepage-texty (OND-186).
    // OND-309: citace u formuláře byla Jaskmanická — stejná věta ale stojí
    // o obrazovku výš jako její recenze v sekci „Co říkají klienti"
    // (řada referencí je rozhodnutí z OND-308 a zůstává). Nahrazená
    // zkráceným citátem Ing. Iva Štěpánka z `lang/cs/testimonials.php`:
    // u formuláře stojí člověk, který typicky už nějakého dodavatele má.
    'inline_form' => [
        'eyebrow'         => 'Poptávka',
        'heading'         => 'Napište mi, co potřebujete',
        'description'     => 'Napište ve zkratce, co řešíte. Ozvu se nejpozději následující pracovní den a nezávazně probereme, co dává smysl. Když zjistím, že na sebe nepasujeme, řeknu vám to rovnou.',
        'quote_text'      => 'Jedná rychle a efektivně. Byl to pro mě velký rozdíl mezi předchozím IT dodavatelem.',
        'quote_author'    => 'Ing. Ivo Štěpánek, J. K. fire and safety consulting',
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

    // OND-308: `cta` slibovalo kalendář, který od OND-303 neexistuje.
    // Cíl odkazu se nemění (kotva formuláře na HP, /kontakt jinde), mění
    // se jen text — a ten musí sedět ve všech třech jazycích.
    'sticky' => [
        'cta'     => 'Napsat poptávku',
        'mobile'  => 'Poptávka',
        'phone'   => 'Zavolat',
    ],

];
