<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * OND-503 — tři nové případovky ze zakázky OND-495: ZOOMORAVA (OND-498,
 * dokument `pripadovka` v2), MAKOplast (OND-499, v3) a ExHot (OND-500, v3),
 * všechny schválené Ondřejem 1. 10. 2026. Texty cs/en/de jsou z oddílů A–E
 * doslova. Dva odstavce v jednom poli dělí prázdný řádek; šablona detailu
 * je vypisuje přes `nl2br()`.
 *
 * Snímky v `resources/img/projects/{slug}/`: ZOOMORAVA z Ondřejových ukázek
 * staré případovky na Frameru, MAKOplast hero = Ondřejův návrh (příloha
 * OND-499), ostatní záběry z živých webů klientů 1. 10. 2026. Cesty jsou
 * natvrdo (`path`), takže je PortfolioSeeder nepřečísluje (past `gallery-N`).
 *
 * Inertní na prázdné DB — tam projekty založí PortfolioSeeder z
 * `docs/portfolio-data.yaml`, kde jsou tytéž záznamy. Idempotentní po
 * projektech: slug, který už existuje (druhý běh, ruční založení ve
 * Filamentu), se přeskočí. `down()` projekty smaže; překlady, snímky
 * a štítky odejdou přes FK cascade.
 */
return new class extends Migration
{
    private const PROJECTS = [
        'exhot' => [
            'project' => [
                'category' => 'website',
                'client_name' => 'ExHot — Aleš Horký',
                'live_url' => 'https://exhot.cz/',
                'year' => 2025,
                'duration' => 'několik týdnů',
                'featured' => false,
                'sort_order' => 58,
            ],
            'tags' => ['web', 'logo', 'copywriting', 'vicejazycny', 'remeslo', 'galerie'],
            'translations' => [
                'cs' => [
                    'title' => 'ExHot',
                    'subtitle' => 'Web, který uprostřed práce změnil obor',
                    'summary' => 'Web měl nabízet výfuky na motorky. Klient uprostřed práce změnil obor a web jsem předělal na nerezová zábradlí, brány, ploty a nábytek. Česky i německy, s logem podle klientovy kresby.',
                    'description' => 'Aleš Horký z Hrušovan nad Jevišovkou celý život vyráběl nerezové výfuky a svody na motorky. Dnes pod značkou ExHot vyrábí na míru zábradlí, brány, ploty a zahradní nábytek z nerezu, na jižní Moravě i v Dolním Rakousku. Web měl být původně o výfucích. Když klient změnil obor, předělal jsem ho. Logo jsem udělal podle jeho kresby, napsal texty a připravil i německou verzi.',
                    'challenge' => 'Aleš Horký prodával výfuky a svody roky přes inzeráty a dlouho to fungovalo. V posledních letech ale zájem klesal, hlavně kvůli levným dovozovým výfukům. Web neměl a na internetu o něm nikdo nevěděl. Chtěl web, přes který mu budou zakázky chodit pravidelně, nejradši tak, že mu zákazník rovnou zavolá.'
                        ."\n\n".'Začali jsme rozborem, kdo jsou jeho zákazníci a kdo konkurence. Měli jsme hotovou strukturu webu i první návrhy vzhledu. Pak se klient rozhodl výfuky opustit. Kvůli předpisům a levné konkurenci v nich už neviděl budoucnost. Dílnu, materiál i zkušenosti s nerezem ale měl, a tak se rozhodl vyrábět zábradlí, brány, ploty a nábytek. Web musel začít znovu, pro nový obor a nové zákazníky.'
                        ."\n\n".'Výrobek na míru se nedá ukázat v katalogu s cenou. Web proto musel vysvětlit, jak zakázka probíhá a proč se nerez vyplatí, i když je dražší než natřené železo nebo dřevo. Hrušovany leží kousek od rakouských hranic, takže byla potřeba i němčina.',
                    'solution' => 'Rozpracovaný web jsem předělal pro nový obor. K logu jsem připravil několik návrhů v různých směrech. Klient měl ale vlastní představu, kterou nedokázal popsat, a tak ji nakreslil fixou. Z jeho kresby jsem udělal skutečné logo, které vypadá ostře v každé velikosti, a vzhled celého webu jsem k němu přizpůsobil.'
                        ."\n\n".'Zábradlí, brány a ploty i nábytek mají každé vlastní stránku a všechny jsou postavené stejně. Nejdřív to, co u běžných materiálů zlobí, třeba rez, opakované natírání nebo viklající se spoje. Pak to, co nerez přináší. Následuje průběh zakázky ve čtyřech krocích od zaměření po montáž, obvyklý termín a časté otázky.'
                        ."\n\n".'V Referencích jsou fotky skutečných zakázek. Stránky výrobků i kontakt vyjmenovávají obce v Česku i v Rakousku, kde ExHot montuje. Telefon je v záhlaví na každé stránce. Do kontaktního formuláře zákazník přiloží až pět souborů, třeba fotku balkonu nebo vlastní náčrt.',
                    'result' => 'Aleš Horký má web pro obor, kterým se rozhodl živit, a logo, které si sám vymyslel. Zákazník na webu zjistí, co ExHot vyrábí, jak zakázka probíhá a kde montuje, a rovnou zavolá nebo pošle poptávku i s fotkou. Aleš Horký o spolupráci napsal: „Ondřeje Krišku bych rozhodně doporučil pro jeho vynalézavý a neotřelý styl práce, jdoucí ruku v ruce s flexibilním a profesionálním přístupem k zákazníkovi.“',
                    'live_hint' => 'Otevřete stránku Zábradlí a projděte si, jak zakázka probíhá od zaměření po montáž.',
                    'meta_title' => 'ExHot — web pro nerezová zábradlí, brány a nábytek na míru',
                    'meta_description' => 'Případová studie: klient uprostřed práce přešel od výfuků na motorky k nerezovým zábradlím, branám a nábytku. Web jsem předělal, logo udělal podle jeho kresby. Česky i německy.',
                ],
                'en' => [
                    'title' => 'ExHot',
                    'subtitle' => 'A website that changed trades halfway through',
                    'summary' => 'The website was meant to sell motorcycle exhausts. Halfway through, the client changed trades, and I reworked the site for stainless steel railings, gates, fences and furniture. In Czech and German, with a logo based on the client\'s own drawing.',
                    'description' => 'Aleš Horký from Hrušovany nad Jevišovkou spent his working life making stainless steel exhausts and headers for motorcycles. Today, under the ExHot name, he makes made-to-measure railings, gates, fences and garden furniture from stainless steel, in South Moravia and Lower Austria. The website was originally going to be about exhausts. When the client changed trades, I reworked it. I made the logo from his drawing, wrote the copy and prepared the German version too.',
                    'challenge' => 'For years, Aleš Horký sold exhausts and headers through classified ads, and it worked for a long time. In recent years, though, demand fell, mainly because of cheap imported exhausts. He had no website and nobody could find him online. He wanted a website that would bring in jobs steadily, ideally with customers simply calling him.'
                        ."\n\n".'We started by working out who his customers were and who the competition was. The site structure and the first design drafts were ready. Then the client decided to give up exhausts. Because of regulations and cheap competition, he no longer saw a future in them. But he had the workshop, the material and the experience with stainless steel, so he decided to make railings, gates, fences and furniture instead. The website had to start again, for a new trade and new customers.'
                        ."\n\n".'You can\'t show a made-to-measure product in a catalogue with a price. So the site had to explain how a job works and why stainless steel is worth it, even though it costs more than painted iron or wood. Hrušovany is close to the Austrian border, so German was needed too.',
                    'solution' => 'I reworked the half-finished website for the new trade. For the logo, I prepared several drafts in different directions. But the client had his own idea that he couldn\'t put into words, so he drew it with a marker. I turned his drawing into a proper logo that stays sharp at any size, and adapted the design of the whole website to it.'
                        ."\n\n".'Railings, gates and fences, and furniture each have their own page, and all of them follow the same order. First, what goes wrong with ordinary materials, such as rust, repainting or wobbly joints. Then what stainless steel brings. After that, how a job goes in four steps from measuring up to installation, the usual lead time and frequent questions.'
                        ."\n\n".'The References page shows photos of real jobs. The product pages and the contact page list the towns in Czechia and Austria where ExHot installs. The phone number is in the header on every page. In the contact form, customers can attach up to five files, such as a photo of their balcony or their own sketch.',
                    'result' => 'Aleš Horký has a website for the trade he chose to make his living from, and a logo he came up with himself. On the website, customers find out what ExHot makes, how a job goes and where it installs, and then call or send an enquiry with a photo straight away. Aleš Horký on working together: “I would definitely recommend Ondřej Kriška for his inventive, fresh way of working, which goes hand in hand with a flexible and professional attitude towards the customer.”',
                    'live_hint' => 'Open the Railings page and see how a job goes from measuring up to installation. The site is in German too.',
                    'meta_title' => 'ExHot — a website for made-to-measure stainless steel railings and gates',
                    'meta_description' => 'Case study: halfway through the project, the client moved from motorcycle exhausts to stainless steel railings, gates and furniture. I reworked the website and made the logo from his drawing. In Czech and German.',
                ],
                'de' => [
                    'title' => 'ExHot',
                    'subtitle' => 'Eine Website, die mitten im Projekt die Branche gewechselt hat',
                    'summary' => 'Die Website sollte Motorradauspuffe anbieten. Mitten im Projekt wechselte der Kunde die Branche, und ich habe die Website auf Geländer, Tore, Zäune und Möbel aus Edelstahl umgebaut. Auf Tschechisch und Deutsch, mit einem Logo nach der Zeichnung des Kunden.',
                    'description' => 'Aleš Horký aus Hrušovany nad Jevišovkou hat sein Leben lang Auspuffe und Krümmer aus Edelstahl für Motorräder gebaut. Heute fertigt er unter dem Namen ExHot Geländer, Tore, Zäune und Gartenmöbel aus Edelstahl nach Maß, in Südmähren und in Niederösterreich. Die Website sollte ursprünglich von Auspuffen handeln. Als der Kunde die Branche wechselte, habe ich sie umgebaut. Das Logo habe ich nach seiner Zeichnung gestaltet, die Texte geschrieben und auch die deutsche Version erstellt.',
                    'challenge' => 'Aleš Horký hat Auspuffe und Krümmer jahrelang über Kleinanzeigen verkauft, und das hat lange funktioniert. In den letzten Jahren ließ die Nachfrage aber nach, vor allem wegen billiger Import-Auspuffe. Eine Website hatte er nicht, im Internet war er nicht zu finden. Er wollte eine Website, über die regelmäßig Aufträge kommen, am liebsten so, dass Kunden ihn direkt anrufen.'
                        ."\n\n".'Wir haben damit angefangen, wer seine Kunden sind und wer die Konkurrenz ist. Die Struktur der Website und die ersten Entwürfe standen. Dann beschloss der Kunde, die Auspuffe aufzugeben. Wegen der Vorschriften und der billigen Konkurrenz sah er darin keine Zukunft mehr. Werkstatt, Material und Erfahrung mit Edelstahl hatte er aber, also entschied er sich für Geländer, Tore, Zäune und Möbel. Die Website musste neu anfangen, für eine neue Branche und neue Kunden.'
                        ."\n\n".'Ein Produkt nach Maß lässt sich nicht in einem Katalog mit Preis zeigen. Die Website musste deshalb erklären, wie ein Auftrag abläuft und warum sich Edelstahl lohnt, auch wenn er teurer ist als gestrichenes Eisen oder Holz. Hrušovany liegt nahe der österreichischen Grenze, also brauchte es auch Deutsch.',
                    'solution' => 'Die halbfertige Website habe ich für die neue Branche umgebaut. Für das Logo habe ich mehrere Entwürfe in verschiedene Richtungen vorbereitet. Der Kunde hatte aber eine eigene Vorstellung, die er nicht in Worte fassen konnte, also hat er sie mit einem Filzstift gezeichnet. Aus seiner Zeichnung habe ich ein richtiges Logo gemacht, das in jeder Größe scharf bleibt, und das Design der ganzen Website daran angepasst.'
                        ."\n\n".'Geländer, Tore und Zäune sowie Möbel haben jeweils eine eigene Seite, und alle sind gleich aufgebaut. Zuerst, was bei üblichen Materialien Ärger macht, etwa Rost, ständiges Streichen oder wackelnde Verbindungen. Dann, was Edelstahl bringt. Danach der Ablauf eines Auftrags in vier Schritten vom Aufmaß bis zur Montage, die übliche Lieferzeit und häufige Fragen.'
                        ."\n\n".'Unter Referenzen stehen Fotos echter Aufträge. Die Produktseiten und die Kontaktseite nennen die Orte in Tschechien und Österreich, in denen ExHot montiert. Die Telefonnummer steht auf jeder Seite oben. Im Kontaktformular kann man bis zu fünf Dateien anhängen, zum Beispiel ein Foto des Balkons oder eine eigene Skizze.',
                    'result' => 'Aleš Horký hat eine Website für die Branche, von der er leben will, und ein Logo, das er sich selbst ausgedacht hat. Auf der Website erfährt man, was ExHot fertigt, wie ein Auftrag abläuft und wo montiert wird, und ruft dann an oder schickt gleich eine Anfrage mit Foto. Aleš Horký über die Zusammenarbeit: „Ondřej Kriška ist klar zu empfehlen — für seine erfinderische, unverbrauchte Arbeitsweise, die mit einem flexiblen und professionellen Umgang mit dem Kunden Hand in Hand geht.“',
                    'live_hint' => 'Öffnen Sie die Seite Geländer und sehen Sie, wie ein Auftrag vom Aufmaß bis zur Montage abläuft. Die Website gibt es auch auf Deutsch.',
                    'meta_title' => 'ExHot — Website für Geländer und Tore aus Edelstahl nach Maß',
                    'meta_description' => 'Case Study: Mitten im Projekt wechselte der Kunde von Motorradauspuffen zu Geländern, Toren und Möbeln aus Edelstahl. Ich habe die Website umgebaut und das Logo nach seiner Zeichnung gestaltet. Auf Tschechisch und Deutsch.',
                ],
            ],
            /** [soubor v resources/img/projects/{slug}, type, alt cs/en/de] */
            'screenshots' => [
                ['hero-1.webp', 'hero', [
                    'cs' => 'ExHot – úvodní stránka webu o nerezových výrobcích na míru',
                    'en' => 'ExHot – homepage of the made-to-measure stainless steel website',
                    'de' => 'ExHot – Startseite der Website für Edelstahl nach Maß',
                ]],
                ['gallery-1.webp', 'gallery', [
                    'cs' => 'Průběh zakázky ve čtyřech krocích od zaměření po montáž',
                    'en' => 'How a job goes, in four steps from measuring up to installation',
                    'de' => 'Ablauf eines Auftrags in vier Schritten vom Aufmaß bis zur Montage',
                ]],
                ['gallery-2.webp', 'gallery', [
                    'cs' => 'Reference s fotkami hotových zábradlí a laviček',
                    'en' => 'References with photos of finished railings and benches',
                    'de' => 'Referenzen mit Fotos fertiger Geländer und Bänke',
                ]],
                ['gallery-3.webp', 'gallery', [
                    'cs' => 'Kontaktní formulář, ke kterému jde přiložit fotky nebo náčrt',
                    'en' => 'Contact form that accepts photos or a sketch',
                    'de' => 'Kontaktformular, an das man Fotos oder eine Skizze anhängen kann',
                ]],
                ['gallery-4.webp', 'gallery', [
                    'cs' => 'Německá verze webu ExHot',
                    'en' => 'The German version of the ExHot website',
                    'de' => 'Die deutsche Version der ExHot-Website',
                ]],
            ],
        ],
        'zoomorava' => [
            'project' => [
                'category' => 'website',
                'client_name' => 'ZOOMORAVA s.r.o.',
                'live_url' => 'https://zoomorava.cz/',
                'year' => 2026,
                'duration' => 'několik týdnů',
                'featured' => false,
                'sort_order' => 56,
            ],
            'tags' => ['web', 'vicejazycny', 'copywriting', 'seo', 'b2b', 'galerie', 'logistika'],
            'translations' => [
                'cs' => [
                    'title' => 'ZOOMORAVA',
                    'subtitle' => 'Web ve čtyřech jazycích pro obchod a přepravu hospodářských zvířat',
                    'summary' => 'První vlastní web firmy, která provozuje odpočinkové stanoviště pro zvířata na dlouhé cestě, shromažďovací středisko a obchod se zvířaty. Česky, anglicky, německy a polsky.',
                    'description' => 'ZOOMORAVA s.r.o. z Dobrého Pole na jižní Moravě funguje od roku 2003. Provozuje kontrolní stanoviště, kde zvířata na dlouhé cestě po Evropě povinně odpočívají, a shromažďovací středisko, kde se zvířata z více chovů soustředí před vývozem. K tomu nakupuje a prodává skot, prasata a ovce. Navrhl jsem celý web, napsal k němu texty a naprogramoval ho od základu ve čtyřech jazycích.',
                    'challenge' => 'Za víc než dvacet let si ZOOMORAVA v oboru udělala dobré jméno, jenže na internetu nebyla vidět. Vlastní web neměla. Kdo do Googlu napsal její název, našel jen IČO a kontakt na cizích stránkách. Kdo firmu osobně neznal, nezjistil o ní nic. Poptávek měla firma dost, o ty nešlo. Chtěla se ukázat profesionálně a chtěla, aby si partneři to podstatné zjistili sami a nemuseli se na všechno doptávat.'
                        ."\n\n".'Služby ZOOMORAVY jsou přitom odborné a řídí je přísná pravidla. Kdo do oboru nepatří, nepozná rozdíl mezi kontrolním stanovištěm a shromažďovacím střediskem. Kdo do oboru patří, potřebuje rychle zjistit, jestli má provoz platné schválení, kolik zvířat pojme a jak si místo zarezervovat. Bylo potřeba to podat srozumitelně a nic důležitého přitom nevynechat.'
                        ."\n\n".'Na web chodí lidé s různým záměrem. Přepravce hledá zastávku na trase, chovatel chce zvířata prodat, obchodník je chce koupit. Část z nich je ze zahraničí, a proto web potřeboval víc jazyků. Formuláře musely být pro návštěvníka jednoduché a zároveň firmě říct všechno, co potřebuje k nabídce.',
                    'solution' => 'Klient si z mé nabídky vybral nejširší rozsah, se čtyřmi jazyky a podrobným popisem každé služby. Každá ze tří služeb má vlastní stránku. Je na ní, k čemu slouží a jak spolupráce probíhá krok za krokem. U kontrolního stanoviště i střediska je tabulka, kolik kusů kterých zvířat pojmou. Vedle každého schvalovacího čísla je odkaz, kterým si ho návštěvník ověří v registru Státní veterinární správy. Rozhodnutí o schválení a provozní řády jsou ke stažení. Stránka Legislativa vysvětluje, podle jakých předpisů firma pracuje a kdo provoz veterinárně hlídá.'
                        ."\n\n".'Skutečný areál ukazuje galerie fotek a videí, kterou si návštěvník vyfiltruje podle toho, co ho zajímá. Rezervace stanoviště, nákup a prodej zvířat mají každý svůj formulář. V kontaktech je, na koho se s čím obrátit, a pro řidiče kamionů GPS a informace o příjezdu.'
                        ."\n\n".'Web je česky, anglicky, německy a polsky. Každý jazyk má vlastní adresy stránek, ne jen přeložený text.',
                    'result' => 'Firma má poprvé vlastní web. Kdo dnes hledá ZOOMORAVU podle názvu, najde v Googlu její stránky, ne jen IČO na cizích webech. Přes vyhledávač na web chodí lidé i ze Slovinska, Německa nebo Maďarska. Návštěvník na něm zjistí, kterou službu potřebuje, ověří si platné schválení, prohlédne si skutečný areál a rovnou pošle rezervaci nebo poptávku. Marie Miková ze ZOOMORAVY o webu napsala: „Vše je přehledné, splňuje to všechny naše požadavky.“',
                    'live_hint' => 'Otevřete stránku Staging Point a ověřte si schvalovací číslo v registru veterinární správy.',
                    'meta_title' => 'ZOOMORAVA — web ve čtyřech jazycích pro obchod se zvířaty',
                    'meta_description' => 'Případová studie: první vlastní web ZOOMORAVA s.r.o. pro obchod a přepravu hospodářských zvířat. Česky, anglicky, německy a polsky.',
                ],
                'en' => [
                    'title' => 'ZOOMORAVA',
                    'subtitle' => 'A four-language website for livestock trade and transport',
                    'summary' => 'The first website of its own for a company that runs a rest stop for animals on long journeys, a gathering centre and a livestock trade. In Czech, English, German and Polish.',
                    'description' => 'ZOOMORAVA s.r.o. from Dobré Pole in South Moravia has been in business since 2003. It runs a control post where animals on long journeys across Europe must stop and rest, and a gathering centre where animals from several farms are brought together before export. It also buys and sells cattle, pigs and sheep. I designed the whole website, wrote the copy and built it from scratch in four languages.',
                    'challenge' => 'In more than twenty years ZOOMORAVA had built a good name in the trade, but online it was invisible. It had no website of its own. Anyone who searched for its name on Google found only a company ID and contact details on other people\'s sites. If you didn\'t know the firm personally, you couldn\'t learn anything about it. Enquiries were never the issue; the company had plenty. It wanted to present itself professionally and let partners find out the essentials for themselves, without having to ask about everything.'
                        ."\n\n".'ZOOMORAVA\'s services are also specialised and tightly regulated. People outside the trade can\'t tell a control post from a gathering centre. People inside it need to find out fast whether the site holds a valid approval, how many animals it takes and how to book a place. It all had to be clear without leaving out anything important.'
                        ."\n\n".'Visitors come with different aims. A haulier needs a stop on the route, a farmer wants to sell animals, a trader wants to buy them. Some of them come from abroad, so the site needed more than one language. The forms had to be easy for visitors and still give the company everything it needs to make an offer.',
                    'solution' => 'From my proposal, the client chose the widest scope, with four languages and a detailed description of every service. Each of the three services has its own page. It explains what the service is for and how working together goes, step by step. For the control post and the gathering centre, a table shows how many animals of each kind they take. Next to every approval number is a link to check it in the register of the Czech State Veterinary Administration. The approval decisions and operating rules can be downloaded. The Legislation page explains which regulations the company works under and who carries out veterinary supervision.'
                        ."\n\n".'A gallery of photos and videos shows the real site, and visitors can filter it by what interests them. Booking the control post, buying animals and selling animals each have their own form. The contact page says who to ask about what, and gives lorry drivers GPS coordinates and arrival details.'
                        ."\n\n".'The website is in Czech, English, German and Polish. Each language has its own page addresses, not just translated text.',
                    'result' => 'For the first time, the company has a website of its own. Anyone searching for ZOOMORAVA by name now finds its own pages on Google, not just a company ID on other sites. Visitors come through search from Slovenia, Germany and Hungary too. On the website they find out which service they need, check the valid approval, look around the real site and send a booking or enquiry straight away. Marie Miková of ZOOMORAVA on the website: “Everything is clear and it meets all our requirements.”',
                    'live_hint' => 'Open the Staging Point page and check the approval number in the veterinary register. The site is in English too.',
                    'meta_title' => 'ZOOMORAVA — a four-language website for livestock trade',
                    'meta_description' => 'Case study: the first website of its own for ZOOMORAVA s.r.o., a livestock trade and transport company. In Czech, English, German and Polish.',
                ],
                'de' => [
                    'title' => 'ZOOMORAVA',
                    'subtitle' => 'Website in vier Sprachen für Viehhandel und Tiertransport',
                    'summary' => 'Die erste eigene Website eines Unternehmens, das eine Ruhestation für Tiere auf langen Transporten, eine Sammelstelle und einen Viehhandel betreibt. Auf Tschechisch, Englisch, Deutsch und Polnisch.',
                    'description' => 'Die ZOOMORAVA s.r.o. aus Dobré Pole in Südmähren gibt es seit 2003. Sie betreibt eine Kontrollstelle, an der Tiere auf langen Transporten durch Europa vorgeschriebene Ruhezeiten einlegen, und eine Sammelstelle, in der Tiere aus mehreren Betrieben vor dem Export zusammengeführt werden. Außerdem kauft und verkauft sie Rinder, Schweine und Schafe. Ich habe die ganze Website entworfen, die Texte geschrieben und sie von Grund auf in vier Sprachen programmiert.',
                    'challenge' => 'In über zwanzig Jahren hatte sich ZOOMORAVA in der Branche einen guten Namen gemacht, im Internet war das Unternehmen aber unsichtbar. Eine eigene Website gab es nicht. Wer den Namen bei Google eingab, fand nur die Firmennummer und Kontaktdaten auf fremden Seiten. Wer die Firma nicht persönlich kannte, erfuhr nichts über sie. Anfragen hatte das Unternehmen genug, darum ging es nicht. Es wollte sich professionell zeigen, und Partner sollten das Wichtigste selbst nachlesen können, ohne alles erfragen zu müssen.'
                        ."\n\n".'Dazu kommt: Die Leistungen von ZOOMORAVA sind fachlich anspruchsvoll und streng geregelt. Wer nicht aus der Branche kommt, kennt den Unterschied zwischen Kontrollstelle und Sammelstelle nicht. Wer aus der Branche kommt, will schnell wissen, ob der Betrieb gültig zugelassen ist, wie viele Tiere er aufnimmt und wie man einen Platz bucht. Das alles sollte verständlich sein, ohne Wichtiges wegzulassen.'
                        ."\n\n".'Besucher kommen mit unterschiedlichen Absichten. Ein Transportunternehmer sucht einen Halt auf der Route, ein Landwirt will Tiere verkaufen, ein Händler will sie kaufen. Ein Teil davon kommt aus dem Ausland, deshalb brauchte die Website mehrere Sprachen. Die Formulare sollten für Besucher einfach sein und dem Unternehmen trotzdem alles liefern, was es für ein Angebot braucht.',
                    'solution' => 'Der Kunde hat sich aus meinem Angebot für den größten Umfang entschieden, mit vier Sprachen und einer ausführlichen Beschreibung jeder Leistung. Jede der drei Leistungen hat eine eigene Seite. Dort steht, wofür sie da ist und wie die Zusammenarbeit Schritt für Schritt abläuft. Für Kontrollstelle und Sammelstelle zeigt eine Tabelle, wie viele Tiere welcher Art sie aufnehmen. Neben jeder Zulassungsnummer steht ein Link, mit dem man sie im Register der tschechischen Staatlichen Veterinärverwaltung prüfen kann. Zulassungsbescheide und Betriebsordnungen stehen zum Download bereit. Die Seite Rechtsvorschriften erklärt, nach welchen Regeln das Unternehmen arbeitet und wer den Betrieb tierärztlich überwacht.'
                        ."\n\n".'Eine Galerie mit Fotos und Videos zeigt das echte Gelände, Besucher können sie nach Themen filtern. Für die Buchung der Kontrollstelle, für den Kauf und für den Verkauf von Tieren gibt es jeweils ein eigenes Formular. Die Kontaktseite sagt, an wen man sich womit wendet, und gibt Lkw-Fahrern GPS-Koordinaten und Hinweise zur Anfahrt.'
                        ."\n\n".'Die Website gibt es auf Tschechisch, Englisch, Deutsch und Polnisch. Jede Sprache hat eigene Seitenadressen, nicht nur übersetzten Text.',
                    'result' => 'Das Unternehmen hat zum ersten Mal eine eigene Website. Wer ZOOMORAVA heute über den Namen sucht, findet bei Google die eigenen Seiten der Firma, nicht nur die Firmennummer auf fremden Websites. Über die Suche kommen Besucher auch aus Slowenien, Deutschland und Ungarn. Auf der Website erfahren sie, welche Leistung sie brauchen, prüfen die gültige Zulassung, sehen sich das echte Gelände an und schicken gleich eine Buchung oder Anfrage. Marie Miková von ZOOMORAVA über die Website: „Alles ist übersichtlich und erfüllt alle unsere Anforderungen.“',
                    'live_hint' => 'Öffnen Sie die Seite zum Staging Point und prüfen Sie die Zulassungsnummer im Register der Veterinärverwaltung. Die Website gibt es auch auf Deutsch.',
                    'meta_title' => 'ZOOMORAVA — Website in vier Sprachen für den Viehhandel',
                    'meta_description' => 'Case Study: die erste eigene Website der ZOOMORAVA s.r.o. für Viehhandel und Tiertransport. Auf Tschechisch, Englisch, Deutsch und Polnisch.',
                ],
            ],
            /** [soubor v resources/img/projects/{slug}, type, alt cs/en/de] */
            'screenshots' => [
                ['hero-1.webp', 'hero', [
                    'cs' => 'ZOOMORAVA – web na počítači, tabletu a mobilu',
                    'en' => 'ZOOMORAVA – the website on a desktop, tablet and phone',
                    'de' => 'ZOOMORAVA – die Website auf Computer, Tablet und Handy',
                ]],
                ['gallery-1.webp', 'gallery', [
                    'cs' => 'Anglická stránka Staging Point na mobilu se schvalovacím číslem a odkazem do registru',
                    'en' => 'The Staging Point page on a phone, with the approval number and a link to the register',
                    'de' => 'Die englische Staging-Point-Seite auf dem Handy mit Zulassungsnummer und Link zum Register',
                ]],
                ['gallery-2.webp', 'gallery', [
                    'cs' => 'Stránka Nákup a prodej na notebooku',
                    'en' => 'The buying and selling page on a laptop',
                    'de' => 'Die Seite Ankauf und Verkauf auf einem Laptop',
                ]],
                ['gallery-3.webp', 'gallery', [
                    'cs' => 'Galerie fotek z areálu ZOOMORAVA na tabletu',
                    'en' => 'Photo gallery of the ZOOMORAVA site on a tablet',
                    'de' => 'Fotogalerie des ZOOMORAVA-Geländes auf einem Tablet',
                ]],
                ['gallery-4.webp', 'gallery', [
                    'cs' => 'Náhledový obrázek webu ZOOMORAVA pro sdílení odkazu',
                    'en' => 'ZOOMORAVA preview image for sharing a link',
                    'de' => 'Vorschaubild der ZOOMORAVA-Website zum Teilen eines Links',
                ]],
            ],
        ],
        'makoplast' => [
            'project' => [
                'category' => 'website',
                'client_name' => 'MAKOplast s.r.o.',
                'live_url' => 'https://makoplast.eu/',
                'year' => 2026,
                'duration' => 'několik týdnů',
                'featured' => false,
                'sort_order' => 57,
            ],
            'tags' => ['web', 'vicejazycny', 'copywriting', 'logo', 'pdf', 'b2b', 'prumysl', 'vyroba', 'seo'],
            'translations' => [
                'cs' => [
                    'title' => 'MAKOplast',
                    'subtitle' => 'Web ve třech jazycích pro výrobce plastových dílů',
                    'summary' => 'Web pro firmu z Mikulova, která vyrábí plastové díly pro výrobce kotlů, bojlerů a spotřebičů v Evropě. Česky, anglicky a německy, s katalogem dílů a poptávkou podle výkresu.',
                    'description' => 'MAKOplast s.r.o. z Mikulova vyrábí plastové díly vstřikováním. Rozety a krytky prodává z katalogu, výrobcům kotlů, bojlerů a průmyslových zařízení dělá díly podle jejich výkresů. Navrhl jsem celý web, napsal k němu texty a naprogramoval ho od základu ve třech jazycích. Logo měla firma jen na vizitkách, tak jsem ho překreslil, aby šlo použít na webu i jinde.',
                    'challenge' => 'Starý web firmy před lety zanikl a nikdo ho neobnovil. MAKOplast chtěl oslovovat výrobce v Německu, Rakousku a dalších zemích EU. Kdo od něj dostane e-mail, potřebuje si firmu ověřit na webu a ve svém jazyce.'
                        ."\n\n".'Než jsem začal, zjistil jsem, kolik lidí takové díly hledá na Googlu a jak se prezentuje konkurence v Evropě. Hledá je jen málo lidí. Web proto nemá lákat davy z vyhledávače. Má přesvědčit nákupčího nebo technika, který už ví, co potřebuje.'
                        ."\n\n".'V roce 2022 se firma přejmenovala z Hödl Plastik na MAKOplast. Nové jméno nesmělo vypadat jako nová firma bez historie.',
                    'solution' => 'Web má pro každého zákazníka vlastní cestu. Katalog dřív existoval jen v PDF. Převedl jsem ho na stránky webu. Každá ze čtyř skupin dílů má vlastní stránku s tabulkou rozměrů a katalogovými čísly a poptávku jde poslat přímo z ní. Aktualizovaný katalog v PDF jde pořád stáhnout.'
                        ."\n\n".'Kdo potřebuje díl na zakázku, najde stránku Zakázková výroba. Popisuje krok za krokem, jak to probíhá od poptávky po sériovou výrobu, a v tabulce ukazuje, co výroba zvládne. Stránka Výroba a kapacity vypisuje stroje, materiály a to, jak firma kontroluje kvalitu. Poptávku jde poslat rovnou s výkresem nebo 3D modelem.'
                        ."\n\n".'Stránka O firmě vysvětluje, že MAKOplast dřív nesl jméno Hödl Plastik, takže stálí zákazníci poznají stejného dodavatele. Web je česky, anglicky a německy. Každý jazyk má vlastní adresy stránek, ne jen přeložený text.',
                    'result' => 'MAKOplast má web, na který může odkázat v každém e-mailu novému zákazníkovi. Nákupčí na něm najde díl podle katalogového čísla a stáhne si PDF. Technik zjistí, jestli MAKOplast jeho díl vyrobí, a pošle poptávku i s výkresem. Obojí česky, anglicky nebo německy. Jednatelka Radka Láníková Ouředníková o spolupráci napsala: „Skvělá spolupráce, profesionální přístup, web hotový včas a podle představ.“',
                    'live_hint' => 'Otevřete v katalogu Rozety s klemou a podívejte se na tabulku variant. Web je i v angličtině a němčině.',
                    'meta_title' => 'MAKOplast — web ve třech jazycích pro výrobce plastových dílů',
                    'meta_description' => 'Případová studie: web MAKOplast s.r.o. z Mikulova. Katalog rozet a krytek s rozměry, poptávka dílu podle výkresu a výroba na zakázku. Česky, anglicky a německy.',
                ],
                'en' => [
                    'title' => 'MAKOplast',
                    'subtitle' => 'A three-language website for a plastic parts manufacturer',
                    'summary' => 'A website for a company from Mikulov that makes plastic parts for manufacturers of boilers, water heaters and appliances across Europe. In Czech, English and German, with a parts catalogue and enquiries based on drawings.',
                    'description' => 'MAKOplast s.r.o. from Mikulov makes plastic parts by injection moulding. It sells escutcheons and caps from its catalogue, and makes parts to their own drawings for manufacturers of boilers, water heaters and industrial equipment. I designed the whole website, wrote the copy and built it from scratch in three languages. The company only had its logo on business cards, so I redrew it for use on the website and elsewhere.',
                    'challenge' => 'The company\'s old website had disappeared years ago and nobody had replaced it. MAKOplast wanted to approach manufacturers in Germany, Austria and other EU countries. Anyone who gets an email from it needs to be able to check the company on a website, in their own language.'
                        ."\n\n".'Before I started, I looked at how many people search for parts like these on Google and how competitors in Europe present themselves. Very few people search for them. So the website isn\'t meant to pull crowds from search engines. It has to convince a buyer or an engineer who already knows what they need.'
                        ."\n\n".'In 2022 the company changed its name from Hödl Plastik to MAKOplast. The new name must not look like a new company without a history.',
                    'solution' => 'The website has a separate path for each kind of customer. The catalogue used to exist only as a PDF. I turned it into pages on the website. Each of the four groups of parts has its own page with a table of sizes and catalogue numbers, and an enquiry can be sent straight from it. The updated PDF catalogue can still be downloaded.'
                        ."\n\n".'Anyone who needs a custom part finds the Custom Production page. It explains step by step how it works, from enquiry to series production, and a table shows what production can handle. The Production page lists the machines, the materials and how the company checks quality. Enquiries can be sent straight away with a drawing or 3D model.'
                        ."\n\n".'The About page explains that MAKOplast used to be called Hödl Plastik, so regular customers recognise the same supplier. The website is in Czech, English and German. Each language has its own page addresses, not just translated text.',
                    'result' => 'MAKOplast has a website it can link to in every email to a new customer. Buyers find a part by its catalogue number and download the PDF. Engineers find out whether MAKOplast can make their part and send an enquiry with the drawing. Both in Czech, English or German. Managing director Radka Láníková Ouředníková on working together: “Great collaboration, a professional approach, the site finished on time and the way we imagined it.”',
                    'live_hint' => 'Open Clamp escutcheons in the catalogue and look at the table of variants. The site is in English too.',
                    'meta_title' => 'MAKOplast — a three-language website for a plastic parts maker',
                    'meta_description' => 'Case study: the MAKOplast s.r.o. website. A catalogue of escutcheons and caps with sizes, enquiries for parts based on drawings and custom production. In Czech, English and German.',
                ],
                'de' => [
                    'title' => 'MAKOplast',
                    'subtitle' => 'Website in drei Sprachen für einen Hersteller von Kunststoffteilen',
                    'summary' => 'Website für ein Unternehmen aus Mikulov, das Kunststoffteile für Hersteller von Heizkesseln, Boilern und Haushaltsgeräten in ganz Europa fertigt. Auf Tschechisch, Englisch und Deutsch, mit Teilekatalog und Anfrage nach Zeichnung.',
                    'description' => 'Die MAKOplast s.r.o. aus Mikulov stellt Kunststoffteile im Spritzguss her. Rosetten und Kappen verkauft sie aus dem Katalog, für Hersteller von Heizkesseln, Boilern und Industrieanlagen fertigt sie Teile nach deren Zeichnungen. Ich habe die ganze Website entworfen, die Texte geschrieben und sie von Grund auf in drei Sprachen programmiert. Das Logo hatte das Unternehmen nur auf Visitenkarten, also habe ich es neu gezeichnet, damit es auf der Website und anderswo nutzbar ist.',
                    'challenge' => 'Die alte Website des Unternehmens war vor Jahren verschwunden, und niemand hatte sie ersetzt. MAKOplast wollte Hersteller in Deutschland, Österreich und anderen EU-Ländern ansprechen. Wer eine E-Mail von MAKOplast bekommt, muss das Unternehmen auf einer Website prüfen können, in seiner eigenen Sprache.'
                        ."\n\n".'Bevor ich angefangen habe, habe ich geprüft, wie viele Menschen bei Google nach solchen Teilen suchen und wie sich Wettbewerber in Europa präsentieren. Es suchen nur wenige. Die Website soll deshalb keine Massen aus Suchmaschinen anlocken. Sie soll Einkäufer und Techniker überzeugen, die schon wissen, was sie brauchen.'
                        ."\n\n".'2022 hat sich das Unternehmen von Hödl Plastik in MAKOplast umbenannt. Der neue Name sollte nicht wie eine neue Firma ohne Geschichte wirken.',
                    'solution' => 'Die Website hat für jede Kundengruppe einen eigenen Weg. Den Katalog gab es früher nur als PDF. Ich habe ihn in Seiten der Website umgesetzt. Jede der vier Teilegruppen hat eine eigene Seite mit einer Tabelle der Maße und Artikelnummern, und eine Anfrage lässt sich direkt von dort senden. Der aktualisierte PDF-Katalog steht weiter zum Download bereit.'
                        ."\n\n".'Wer ein Teil nach Maß braucht, findet die Seite Auftragsfertigung. Sie erklärt Schritt für Schritt den Ablauf von der Anfrage bis zur Serienfertigung, und eine Tabelle zeigt, was die Fertigung schafft. Die Seite Produktion nennt Maschinen, Materialien und wie das Unternehmen die Qualität prüft. Anfragen lassen sich direkt mit Zeichnung oder 3D-Modell senden.'
                        ."\n\n".'Die Seite Über uns erklärt, dass MAKOplast früher Hödl Plastik hieß, damit Stammkunden denselben Lieferanten wiedererkennen. Die Website gibt es auf Tschechisch, Englisch und Deutsch. Jede Sprache hat eigene Seitenadressen, nicht nur übersetzten Text.',
                    'result' => 'MAKOplast hat eine Website, auf die das Unternehmen in jeder E-Mail an einen neuen Kunden verweisen kann. Einkäufer finden dort ein Teil über die Artikelnummer und laden das PDF herunter. Techniker erfahren, ob MAKOplast ihr Teil fertigen kann, und schicken die Anfrage gleich mit Zeichnung. Beides auf Tschechisch, Englisch oder Deutsch. Geschäftsführerin Radka Láníková Ouředníková über die Zusammenarbeit: „Hervorragende Zusammenarbeit, professionelles Vorgehen, die Website war pünktlich fertig und genau so, wie wir sie uns vorgestellt hatten.“',
                    'live_hint' => 'Öffnen Sie im Katalog die Rosetten mit Klemmzunge und sehen Sie sich die Variantentabelle an. Die Website gibt es auch auf Deutsch.',
                    'meta_title' => 'MAKOplast — Website in drei Sprachen für einen Kunststoffteile-Hersteller',
                    'meta_description' => 'Case Study: die Website der MAKOplast s.r.o. Katalog für Rosetten und Kappen mit Maßen, Anfrage nach Zeichnung und Auftragsfertigung. Auf Tschechisch, Englisch und Deutsch.',
                ],
            ],
            /** [soubor v resources/img/projects/{slug}, type, alt cs/en/de] */
            'screenshots' => [
                ['hero-1.webp', 'hero', [
                    'cs' => 'Úvodní část webu MAKOplast s nadpisem Plastové komponenty pro výrobce v EU',
                    'en' => 'The top of the MAKOplast home page with the headline Plastic components for EU manufacturers',
                    'de' => 'Der obere Teil der MAKOplast-Startseite mit der Überschrift Kunststoffkomponenten für EU-Hersteller',
                ]],
                ['gallery-1.webp', 'gallery', [
                    'cs' => 'Stránka Rozety s klemou s tabulkou rozměrů a katalogových čísel',
                    'en' => 'The Clamp escutcheons page with a table of sizes and catalogue numbers',
                    'de' => 'Die Seite Rosetten mit Klemmzunge mit Tabelle der Maße und Artikelnummern',
                ]],
                ['gallery-2.webp', 'gallery', [
                    'cs' => 'Stránka Zakázková výroba s postupem od poptávky po sériovou výrobu',
                    'en' => 'The Custom Production page with the steps from enquiry to series production',
                    'de' => 'Die Seite Auftragsfertigung mit dem Ablauf von der Anfrage bis zur Serienfertigung',
                ]],
                ['gallery-3.webp', 'gallery', [
                    'cs' => 'Německá verze webu MAKOplast',
                    'en' => 'The German version of the MAKOplast website',
                    'de' => 'Die deutsche Version der MAKOplast-Website',
                ]],
            ],
        ],
    ];

    public function up(): void
    {
        if (! DB::table('portfolio_projects')->exists()) {
            return;
        }

        foreach (self::PROJECTS as $slug => $data) {
            if (DB::table('portfolio_projects')->where('slug', $slug)->exists()) {
                continue;
            }

            DB::transaction(fn () => $this->createProject($slug, $data));
        }
    }

    public function down(): void
    {
        DB::table('portfolio_projects')->whereIn('slug', array_keys(self::PROJECTS))->delete();
    }

    private function createProject(string $slug, array $data): void
    {
        $now = now();

        $projectId = DB::table('portfolio_projects')->insertGetId($data['project'] + [
            'slug'         => $slug,
            'published_at' => $now,
            'created_at'   => $now,
            'updated_at'   => $now,
        ]);

        foreach ($data['translations'] as $locale => $fields) {
            DB::table('portfolio_project_translations')->insert($fields + [
                'project_id' => $projectId,
                'locale'     => $locale,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        foreach ($data['screenshots'] as $order => [$file, $type, $alts]) {
            $screenshotId = DB::table('portfolio_project_screenshots')->insertGetId([
                'project_id' => $projectId,
                'path'       => 'projects/'.$slug.'/'.$file,
                'type'       => $type,
                'sort_order' => $order,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            foreach ($alts as $locale => $alt) {
                DB::table('portfolio_project_screenshot_translations')->insert([
                    'screenshot_id' => $screenshotId,
                    'locale'        => $locale,
                    'alt'           => $alt,
                    'caption'       => null,
                    'created_at'    => $now,
                    'updated_at'    => $now,
                ]);
            }
        }

        // Všechny štítky v DB jsou (i s názvy z OND-223); chybějící by se
        // jen přeskočil, nezakládá se tu.
        $tagIds = DB::table('portfolio_tags')->whereIn('slug', $data['tags'])->pluck('id');
        foreach ($tagIds as $tagId) {
            DB::table('portfolio_project_tag')->insert([
                'project_id' => $projectId,
                'tag_id'     => $tagId,
            ]);
        }
    }
};
