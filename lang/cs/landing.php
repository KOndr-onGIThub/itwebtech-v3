<?php

return [

    // OND-201 (bod 11, stejná pravidla jako OND-198): z meta description
    // pryč cenová kotva „od 25 000 Kč" (nález 5.4 — táhla očekávání dolů)
    // a žargon „bez WordPressu" (nález 5.5/5.7 — člověku, který netuší,
    // co WordPress je, to neříká nic; princip 0).
    'meta' => [
        'title' => 'Webové stránky na míru pro podnikatele a firmy | ONDRAWEB',
        'description' => 'Tvorba webových stránek na míru na vlastním kódu, bez pravidelné údržby. Přímá spolupráce s vývojářem a odpověď nejpozději následující pracovní den.',
        'schema_name' => 'Tvorba webových stránek na míru',
    ],

    'topbar' => [
        'cta' => 'Chci nezávaznou konzultaci',
    ],

    // OND-201 (bod 11): titulek sliboval výsledek za klienta (nález 5.1 —
    // „dělá poptávky" neovlivním sám), štítky opakovaly žargon „Bez
    // WordPressu" a kotvily cenu na 25 tisících (nález 5.4).
    'hero' => [
        'eyebrow' => 'Webové stránky na míru pro menší a střední firmy',
        'title' => 'Web na míru, postavený na vlastním kódu.',
        'description' => 'Vytvořím vám web, který rychle vysvětlí, co nabízíte, proč si vybrat právě vás a jak udělat další krok. Bez hotových stavebnic, bez zbytečných komplikací a bez přehazování mezi obchodníkem, grafikem a vývojářem.',
        'primary_cta' => 'Chci nezávaznou konzultaci',
        'secondary_cta' => 'Zobrazit orientační ceny',
        'microcopy' => 'Ozvu se nejpozději následující pracovní den. Bez tlaku, bez obchodníka, přímo s člověkem, který bude web řešit.',
        'chips' => [
            '18 let zkušeností',
            'Přímá spolupráce',
            'Vlastní kód bez údržby',
            'Většina projektů 55–150 tis. Kč',
        ],
        'trust' => [
            'title' => 'Rychlé ověřitelné body',
            'items' => [
                'Odpověď nejpozději následující pracovní den',
                'Vlastní kód, ne stavebnice',
                'Řešení na míru',
                '21 recenzí, 5 z 5',
            ],
        ],
    ],

    'problem' => [
        'title' => 'Možná už víte, že současný web nestačí. Jen nechcete udělat další drahý omyl.',
        // OND-198 (nález 5.5): „landing page" → řeč klienta.
        'intro' => 'Samostatná stránka pro reklamu má rychle potvrdit, že návštěvník řeší reálný problém a že tady najde srozumitelné řešení.',
        'items' => [
            'Máte web, ale nepřivádí stabilně nové poptávky.',
            'Vaše nabídka není na první pohled jasná a lidé odcházejí bez kontaktu.',
            'Nechcete další šablonu ani web, který bude potřeba neustále opravovat.',
            'Potřebujete partnera, který převezme odpovědnost a bude komunikovat přímo.',
        ],
    ],

    'benefits' => [
        'eyebrow' => 'Co získáte',
        'title' => 'Co má nový web udělat pro váš byznys',
        'intro' => 'Cílem není jen nový vzhled. Cílem je web, který usnadní rozhodnutí, posílí důvěru a zjednoduší cestu ke kontaktu.',
        'items' => [
            [
                'title' => 'Jasná nabídka',
                'text' => 'Návštěvník během pár vteřin pochopí, co děláte, pro koho to je a proč vás má oslovit právě teď.',
                'icon' => 'layers',
            ],
            [
                'title' => 'Web na míru',
                'text' => 'Žádná univerzální šablona. Struktura, obsah i technické řešení vychází z vašeho podnikání a cíle stránky.',
                'icon' => 'palette',
            ],
            [
                // OND-201 (nález 5.5): „WordPress" a „pluginy třetích stran"
                // přepsané do řeči klienta.
                'title' => 'Bezúdržbový provoz',
                'text' => 'Web nestojí na hotových doplňcích od cizích firem, které se musí pořád aktualizovat. Méně starostí, vyšší bezpečnost a méně pravidelných nákladů.',
                'icon' => 'shield',
            ],
            [
                'title' => 'Lepší cesta ke kontaktu',
                'text' => 'Stránka návštěvníka nebrzdí. Vede ho od prvního dojmu k poptávce jasně a bez zbytečných odboček.',
                'icon' => 'hand-heart',
            ],
        ],
    ],

    'why' => [
        'eyebrow' => 'Proč právě já',
        'title' => 'Jedno místo kontaktu. Jedno místo zodpovědnosti.',
        'text' => 'Neřešíte obchodníka, projektového manažera a vývojáře zvlášť. Mluvíte přímo se mnou od první konzultace po spuštění webu.',
        'items' => [
            '18 let zkušeností v Toyotě mi dalo silný důraz na kvalitu, proces a detail.',
            'Ozvu se nejpozději následující pracovní den, i po spuštění projektu.',
            // OND-201 (nález 5.4): normou je pásmo 55–150 tisíc, ne nejlevnější vstup.
            'Orientační ceny máte předem. Většina projektů vychází mezi 55 a 150 tisíci korunami.',
            // OND-201 (nález 5.5): žargon přepsaný do řeči klienta.
            'Weby stavím na vlastním kódu, bez hotových doplňků od cizích firem.',
        ],
    ],

    'process' => [
        'eyebrow' => 'Jak probíhá spolupráce',
        'title' => 'Jasný postup bez zmatku a překvapení',
        'microcopy' => 'Nejdřív pochopím váš byznys, teprve potom navrhuji řešení. Na konci prvního kroku víte, co dává smysl řešit a co ne.',
        'items' => [
            [
                'title' => 'Úvodní konzultace',
                'text' => 'Probereme vaše podnikání, cíle a to, co má web reálně přinést. Bez složité přípravy a bez závazku.',
            ],
            [
                'title' => 'Návrh řešení',
                'text' => 'Připravím doporučení rozsahu, struktury a orientační investice. Budete vědět, co dává smysl a co je naopak zbytečné.',
            ],
            [
                'title' => 'Realizace webu',
                'text' => 'Po odsouhlasení postupu vznikne web na míru s důrazem na rychlost, srozumitelnost a konverzi.',
            ],
            [
                'title' => 'Spuštění a podpora',
                'text' => 'Po spuštění nezmizím. Když budete potřebovat úpravu nebo radu, ozvu se nejpozději následující pracovní den.',
            ],
        ],
    ],

    'results' => [
        'eyebrow' => 'Výsledky a reference',
        'title' => 'Jak vypadá dobrý výsledek v praxi',
        'intro' => 'Reálné scénáře, kde web nebo digitální řešení odstranilo zbytečné překážky a zjednodušilo cestu ke kontaktu.',
        'logos' => [
            ['src' => 'brands/toyota.png', 'alt' => 'Toyota'],
            ['src' => 'brands/upstyle.png', 'alt' => 'Upstyle systems'],
            ['src' => 'brands/yolk_studio.png', 'alt' => 'Yolk studio'],
        ],
        'snapshots' => [
            [
                'type' => 'Firemní web',
                'title' => 'Nový web místo nečitelné prezentace',
                'summary' => 'Jasná nabídka služeb hned v prvním scrollu, méně odboček a přímější cesta na kontakt.',
            ],
            [
                'type' => 'Klientský proces',
                'title' => 'Méně ruční administrativy',
                'summary' => 'Lepší kontrola nad daty, rychlejší reakce a méně zbytečných manuálních kroků.',
            ],
            [
                'type' => 'E-shop na míru',
                'title' => 'Stabilnější provoz bez závislosti na cizí platformě',
                'summary' => 'Méně výpadků po aktualizacích a lépe řízená cesta k objednávce.',
            ],
        ],
        'references_heading' => 'Reálné reference',
        'references' => [
            [
                'name' => 'Pavel Baudyš',
                'company' => 'Toyota',
                'role' => 'ředitel řízení výroby, montáže a logistiky',
                'image' => 'Pavel_Baudys.jpg',
                'text' => 'S potěšením mohu poskytnout tuto referenci pro Ondřeje Krišku, který pracoval v naší společnosti Toyota 18 let. Jednou z nejsilnějších stránek Ondry je velká chuť rozvíjet se, což je viditelné na jeho výsledcích.',
            ],
            [
                'name' => 'Hana Jaskmanická',
                'company' => 'VP INDUSTRY',
                'role' => 'výkonná ředitelka',
                'image' => 'Hana_Jaskmanicka.jpeg',
                'text' => 'Chtěli jsme mít pro naši firmu kvalitní a odlišné webové stránky. Díky individuálnímu přístupu, flexibilitě a profesionalitě odpovídá výsledek našim představám. Vřele doporučuji.',
            ],
            [
                'name' => 'Jaroslav Zajíc',
                'company' => 'Střechy Zajíc',
                'role' => 'živnostník',
                'image' => 'Jaroslav-Zajic.jpg',
                'text' => 'Webové stránky vypadají skvěle a jejich ovládání je intuitivní. Díky proaktivnímu přístupu a odborným radám byl celý proces snadný. Určitě se obrátím znovu. Doporučuji.',
            ],
        ],
    ],

    'faq' => [
        'eyebrow' => 'FAQ',
        'title' => 'Odpovědi na nejčastější otázky',
        'items' => [
            [
                // OND-201 (nález 5.4): očekávací věta padne dřív než první
                // číslo, nejlevnější pásmo je poslední a rámované jako výjimka.
                'question' => 'Kolik stojí web na míru?',
                'answer' => 'Většina projektů, které stavím, vychází mezi 55 a 150 tisíci korunami. Vícejazyčný Standard začíná na 55 000 Kč, Custom (e-shop, aplikace, rezervace) od 95 000 Kč. Startovní web za 25 000 Kč beru jako výjimku pro živnostníky, ne jako standardní vstup. Přesnější cenu dává až krátká konzultace, kde si ujasníme rozsah, cíle a potřebné funkce.',
            ],
            [
                'question' => 'Jak dlouho trvá realizace?',
                'answer' => 'Jednodušší web lze zvládnout přibližně za 3 až 4 týdny od schválení zadání. U větších projektů záleží na rozsahu, funkcích a rychlosti dodání podkladů.',
            ],
            [
                // OND-201 (nález 5.7): otázka se dřív jmenovala „Proč
                // nestavíte na WordPressu?", tedy vymezení proti konkurenci
                // v žargonu. Nově vede to, na čem web stavím.
                'question' => 'Na čem web stavíte?',
                'answer' => 'Na vlastním kódu, který píšu od základu. Nepoužívám hotové stavebnice skládané z doplňků od různých autorů — u nich vzniká závislost na pravidelných aktualizacích a průběžné údržbě. Web na míru je stabilnější, rychlejší a dlouhodobě předvídatelnější z hlediska provozu i nákladů.',
            ],
            [
                'question' => 'Co když ještě nemám připravené texty nebo zadání?',
                'answer' => 'To je běžné. Právě od toho je úvodní konzultace. Společně rychle určíme, co je pro váš byznys opravdu potřeba a co by byly zbytečné náklady navíc.',
            ],
        ],
        'cta' => 'Chci probrat svůj projekt',
    ],

    'form' => [
        'eyebrow' => 'Nezávazná poptávka',
        'title' => 'Řekněte mi stručně, co potřebujete. Ozvu se nejpozději následující pracovní den.',
        'description' => 'Napište mi pár vět o vašem podnikání a současné situaci. Odesláním formuláře nezačíná žádný závazek. Jen první smysluplná konverzace.',
        'success' => 'Děkuji, zpráva dorazila. Ozvu se co nejdřív s dalším krokem.',
        'error' => 'Poptávku se teď nepodařilo uložit. Zkuste to prosím znovu.',
        'name' => 'Jméno a příjmení',
        'company' => 'Firma / značka',
        'email' => 'E-mail',
        'phone' => 'Telefon',
        'message' => 'Co má nový web vyřešit?',
        'budget' => 'Orientační rozpočet',
        'submit' => 'Chci probrat svůj projekt',
        'submitting' => 'Odesílám...',
        'privacy_prefix' => 'Souhlasím se zpracováním osobních údajů v souladu se ',
        'privacy_link' => 'zásadami ochrany osobních údajů',
        'placeholders' => [
            'name' => 'Jan Novák',
            'company' => 'Firma nebo značka',
            'email' => 'jan@firma.cz',
            'phone' => '+420 000 000 000',
            'message' => 'Například: potřebujeme nový firemní web, který jasně představí služby a přivede více poptávek.',
        ],
        'budget_options' => [
            // OND-136: budget pásma sjednocená s cenovou taxonomií 25/55/95.
            'Do 25 000 Kč',
            '25 000 až 55 000 Kč',
            '55 000 až 95 000 Kč',
            '95 000 Kč a více',
            'Potřebuji doporučit vhodný rozsah',
        ],
        'trust_title' => 'Nezávazná konzultace',
        'trust_items' => [
            'Odpověď nejpozději následující pracovní den',
            'Bez spamu a bez předávání kontaktu dál',
            'Jasný další krok ještě před začátkem projektu',
        ],
    ],

    'footer' => [
        // OND-201 (nález 5.5): žargon „bez WordPressu" pryč.
        'copy' => 'Tvorba webových stránek na míru na vlastním kódu, bez zbytečné údržby. Přímá spolupráce, jasný proces a důraz na poptávky.',
        'privacy' => 'Ochrana osobních údajů',
    ],

];
