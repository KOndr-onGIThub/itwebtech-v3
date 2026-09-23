<?php

// OND-267 (audit P1-1): recenze byly ve všech třech jazycích česky.
// Přeložené jsou všechny (ne jen zobrazené) — přepnutí feature flagu
// show_toyota_testimonial nebo přeskládání pořadí nesmí vrátit češtinu.
// Vlastní jména, firmy, `image` a `source` se nepřekládají.

return [

    'meta' => [
        'total'  => 16,
        'rating' => '5 z 5',
    ],

    'items' => [
        [
            'name'    => 'Radka Láníková Ouředníková',
            'company' => 'MAKOplast s.r.o.',
            'role'    => 'Geschäftsführerin',
            'image'   => 'makoplast.png',   // public/img/testimonials/
            'source'  => 'firmy_cz',
            'text'    => 'Hervorragende Zusammenarbeit, professionelles Vorgehen, die Website war pünktlich fertig und genau so, wie wir sie uns vorgestellt hatten. Ich empfehle Herrn Kriška allen, die eine ordentliche Website wollen.',
        ],
        [
            'name'    => 'Aleš Horký',
            'company' => 'ExHot',
            'role'    => 'Edelstahlprodukte',
            'image'   => 'ales_horky.png',  // resources/img/testimonials/
            'source'  => 'google',
            'text'    => 'Ondřej Kriška ist klar zu empfehlen — für seine erfinderische, unverbrauchte Arbeitsweise, die mit einem flexiblen und professionellen Umgang mit dem Kunden Hand in Hand geht.',
        ],
        [
            'name'    => 'Michal Cvrček',
            'company' => 'Cyklocentrum Březí',
            'role'    => 'Mitinhaber',
            'image'   => 'michal_cvrcek.jpg',
            'source'  => 'google',
            'text'    => 'Perfekte Zusammenarbeit. Ausgezeichnete Ideen und Herangehensweise. Schnell, entgegenkommend, hilfsbereit, professionell. Wärmstens empfohlen.',
        ],
        [
            'name'    => 'Adéla Polášková',
            'company' => 'Mušov21 restaurace',
            'role'    => 'Mitinhaberin',
            'image'   => 'adela_polaskova.jpg',
            'source'  => 'firmy_cz',
            'text'    => 'Danke an Ondra für die großartige Arbeit am Logo, damit es auf Firmentextilien verwendet werden kann. Alles lief schnell, präzise und innerhalb weniger Stunden. Klare Empfehlung.',
        ],
        [
            'name'    => 'Jana Veselá',
            'company' => 'Kemp Veselka',
            'role'    => 'Betreiberin des Campingplatzes',
            'image'   => 'jana_vesela.jpg',
            'source'  => 'facebook',
            'text'    => '100 % zufrieden mit der Erstellung unserer Website. Schön und funktional. Wir können ihn wärmstens empfehlen.',
        ],
        [
            'name'    => 'Peter Vidlička',
            'company' => 'Yolk studio',
            'role'    => 'Mitgründer',
            'image'   => 'peter_vidlicka.jpeg',
            'source'  => 'google',
            'text'    => 'Ondra ist ein sehr zuverlässiger und geschickter Entwickler, die Zusammenarbeit lief immer sehr gut.',
        ],
        [
            'name'    => 'Magda Pernicová Novotná',
            'company' => 'Realiťačky v akci',
            'role'    => 'Immobilienmaklerin',
            'image'   => 'magda_pernicova.jpeg',
            'source'  => 'firmy_cz',
            'text'    => 'Professionell und zugleich menschlich und geduldig. Herr Kriška hat mir wirklich zugehört und daraus etwas gemacht, mit dem ich rundum zufrieden bin. Wärmste Empfehlung.',
        ],
        [
            'name'    => 'Rostislav Toman',
            'company' => 'Tradiční výroba pralinek, s.r.o.',
            'role'    => 'Manager',
            'image'   => 'rostislav_toman.jpeg',
            'source'  => 'google',
            'text'    => 'Ich schätze die hohe Fachkompetenz und Professionalität. Schritt für Schritt haben wir Erwartungen und Realität in Einklang gebracht und auf seinen Rat hin auch den Datenfluss optimiert. Ich habe in der Praxis erlebt, wie Erwartungen übertroffen werden. Von meiner Seite eine klare Empfehlung.',
        ],
        [
            'name'    => 'Hana Jaskmanická',
            'company' => 'VP INDUSTRY',
            'role'    => 'geschäftsführende Direktorin',
            'image'   => 'Hana_Jaskmanicka.jpeg',
            'source'  => 'google',
            'text'    => 'Wir wollten für unsere Firma eine gute Website, die sich von anderen unterscheidet. Durch die individuelle Herangehensweise, Flexibilität und Professionalität entspricht das Ergebnis genau unseren Vorstellungen. Wärmste Empfehlung.',
        ],
        [
            'name'    => 'Ing. Ivo Štěpánek',
            'company' => 'J. K. fire and safety consulting',
            'role'    => 'Unternehmer im Arbeitsschutz',
            'image'   => 'Ivo_Stepanek.jpg',
            'source'  => 'google',
            'text'    => 'Die Dienste von Ondřej Kriška empfehle ich wärmstens. Er handelt schnell und effizient. Für mich war das ein großer Unterschied zum vorherigen IT-Dienstleister. Gut, dass es in diesem Land solche Fachleute gibt.',
        ],
        [
            'name'    => 'Václav Pešice',
            'company' => 'Upstyle systems',
            'role'    => 'Softwareentwickler',
            'image'   => 'Vaclav-Pesice.png',
            'source'  => 'google',
            'text'    => 'Die Zusammenarbeit mit Ondra ist großartig. Er versucht immer, das Maximum für seine Kunden herauszuholen. Er hat perfekte Arbeit geleistet. Er hat definitiv meine Empfehlung.',
        ],
        [
            'name'    => 'Pavel Baudyš',
            'company' => 'Toyota',
            'role'    => 'Direktor Produktion, Montage & Logistik',
            'image'   => 'Pavel_Baudys.jpg',
            'source'  => 'google',
            'badge'   => 'Aus meiner Zeit bei Toyota',
            'text'    => 'Ich gebe diese Referenz für Ondřej Kriška gerne ab. Er hat 18 Jahre in unserem Unternehmen Toyota gearbeitet. Eine seiner größten Stärken ist der echte Wille, sich weiterzuentwickeln — das sieht man an seinen Ergebnissen.',
        ],
        [
            'name'    => 'Stanislav Holcmann',
            'company' => 'Pitbike Aréna',
            'role'    => 'Inhaber',
            'image'   => 'Stanislav-Holcmann.jpg',
            'source'  => 'firmy_cz',
            'text'    => 'Dieser Web-Meister baut unsere Seiten und ich kann ihn nur wärmstens empfehlen. Ausgezeichnete Kommunikation, saubere Arbeit, jede Menge erfinderische, praktische Ideen.',
        ],
        [
            'name'    => 'Lukas Srnák',
            'company' => 'Elektro Srnák',
            'role'    => 'Selbstständiger',
            'image'   => 'Lukas-Srnak.jpg',
            'source'  => 'google',
            'text'    => 'Schnell, hilfsbereit, entgegenkommend. Ein absolut perfekter Umgang. Ich kann ihn wärmstens empfehlen.',
        ],
        [
            'name'    => 'Jaroslav Zajíc',
            'company' => 'Střechy Zajíc',
            'role'    => 'Selbstständiger',
            'image'   => 'Jaroslav-Zajic.jpg',
            'source'  => 'firmy_cz',
            'text'    => 'Die Website sieht großartig aus und lässt sich intuitiv bedienen. Durch das proaktive Vorgehen und die fachliche Beratung war der ganze Prozess einfach. Ich komme sicher wieder. Empfehlenswert.',
        ],
        [
            'name'    => 'Jan Stybor',
            'company' => 'Toyota',
            'role'    => 'Leiter der Projektabteilung',
            'image'   => 'Jan-Stybor.jpg',
            'source'  => 'google',
            'badge'   => 'Aus meiner Zeit bei Toyota',
            'text'    => 'Ich schätze seine professionelle Arbeitsweise. Bei der Entwicklung einer Anwendung analysiert er die Ausgangslage gründlich und will die bestehenden Prozesse wirklich verstehen. Er sammelt die Anforderungen der Nutzer und fragt nach, wohin es gehen soll. Daraus macht er einen Plan und stimmt die wichtigsten Meilensteine mit dem Kunden ab.',
        ],
    ],

];
