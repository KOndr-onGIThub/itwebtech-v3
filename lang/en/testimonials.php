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
            'role'    => 'managing director',
            'image'   => 'makoplast.png',   // public/img/testimonials/
            'source'  => 'firmy_cz',
            'text'    => 'Great collaboration, a professional approach, the site finished on time and the way we imagined it. I recommend Mr Kriška to anyone who wants a website done properly.',
        ],
        [
            'name'    => 'Aleš Horký',
            'company' => 'ExHot',
            'role'    => 'stainless steel products',
            'image'   => 'ales_horky.png',  // resources/img/testimonials/
            'source'  => 'google',
            'text'    => 'I would definitely recommend Ondřej Kriška for his inventive, fresh way of working, which goes hand in hand with a flexible and professional attitude towards the customer.',
        ],
        [
            'name'    => 'Michal Cvrček',
            'company' => 'Cyklocentrum Březí',
            'role'    => 'co-owner',
            'image'   => 'michal_cvrcek.jpg',
            'source'  => 'google',
            'text'    => 'Perfect collaboration. Excellent ideas and approach. Fast, helpful, willing, professional. Warmly recommended.',
        ],
        [
            'name'    => 'Adéla Polášková',
            'company' => 'Mušov21 restaurace',
            'role'    => 'co-owner',
            'image'   => 'adela_polaskova.jpg',
            'source'  => 'firmy_cz',
            'text'    => 'Thank you to Ondra for the great work on adapting our logo so it could be used on company clothing. It was done quickly, precisely and within a few hours. I can definitely recommend him.',
        ],
        [
            'name'    => 'Jana Veselá',
            'company' => 'Kemp Veselka',
            'role'    => 'campsite operator',
            'image'   => 'jana_vesela.jpg',
            'source'  => 'facebook',
            'text'    => '100% satisfied with how our website was built. Beautiful and it works. We can warmly recommend him.',
        ],
        [
            'name'    => 'Peter Vidlička',
            'company' => 'Yolk studio',
            'role'    => 'co-founder',
            'image'   => 'peter_vidlicka.jpeg',
            'source'  => 'google',
            'text'    => 'Ondra is a very reliable and skilled developer; cooperation has always gone well.',
        ],
        [
            'name'    => 'Magda Pernicová Novotná',
            'company' => 'Realiťačky v akci',
            'role'    => 'estate agent',
            'image'   => 'magda_pernicova.jpeg',
            'source'  => 'firmy_cz',
            'text'    => 'Professional and at the same time human and patient. Mr Kriška really listened to what I needed and then turned it into something I am completely happy with. Warmly recommended.',
        ],
        [
            'name'    => 'Rostislav Toman',
            'company' => 'Tradiční výroba pralinek, s.r.o.',
            'role'    => 'manager',
            'image'   => 'rostislav_toman.jpeg',
            'source'  => 'google',
            'text'    => 'I appreciate the high level of expertise and professionalism. Step by step we brought expectations and reality together, and on his advice we optimised the data flow as well. I got to see expectations exceeded in practice. From my side, a clear recommendation.',
        ],
        [
            'name'    => 'Hana Jaskmanická',
            'company' => 'VP INDUSTRY',
            'role'    => 'Executive Director',
            'image'   => 'Hana_Jaskmanicka.jpeg',
            'source'  => 'google',
            'text'    => 'We wanted a website for our company that was good and different from the rest. Thanks to an individual approach, flexibility and professionalism, the result matches what we had in mind. Warmly recommended.',
        ],
        [
            'name'    => 'Ing. Ivo Štěpánek',
            'company' => 'J. K. fire and safety consulting',
            'role'    => 'occupational safety consultant',
            'image'   => 'Ivo_Stepanek.jpg',
            'source'  => 'google',
            'text'    => 'I warmly recommend Ondřej Kriška\'s services. He acts fast and efficiently. For me it was a big difference compared with my previous IT supplier. It is good that this country has specialists like him.',
        ],
        [
            'name'    => 'Václav Pešice',
            'company' => 'Upstyle systems',
            'role'    => 'software developer',
            'image'   => 'Vaclav-Pesice.png',
            'source'  => 'google',
            'text'    => 'Working with Ondra is great. He always tries to do the most he can for his clients. He did a perfect job. He definitely has my recommendation.',
        ],
        [
            'name'    => 'Pavel Baudyš',
            'company' => 'Toyota',
            'role'    => 'Director of Manufacturing, Assembly & Logistics',
            'image'   => 'Pavel_Baudys.jpg',
            'source'  => 'google',
            'badge'   => 'From my years at Toyota',
            'text'    => 'It is my pleasure to give this reference for Ondřej Kriška, who worked at our company Toyota for 18 years. One of Ondra\'s greatest strengths is a real appetite to keep developing, and it shows in his results.',
        ],
        [
            'name'    => 'Stanislav Holcmann',
            'company' => 'Pitbike Aréna',
            'role'    => 'owner',
            'image'   => 'Stanislav-Holcmann.jpg',
            'source'  => 'firmy_cz',
            'text'    => 'This web meister builds our sites and I can only recommend him warmly. Excellent communication, quality work, plenty of inventive, practical ideas.',
        ],
        [
            'name'    => 'Lukas Srnák',
            'company' => 'Elektro Srnák',
            'role'    => 'sole trader',
            'image'   => 'Lukas-Srnak.jpg',
            'source'  => 'google',
            'text'    => 'Fast, willing, helpful. An absolutely perfect approach and dealings. I can warmly recommend him.',
        ],
        [
            'name'    => 'Jaroslav Zajíc',
            'company' => 'Střechy Zajíc',
            'role'    => 'sole trader',
            'image'   => 'Jaroslav-Zajic.jpg',
            'source'  => 'firmy_cz',
            'text'    => 'The website looks great and it is intuitive to operate. Thanks to a proactive approach and expert advice, the whole process was easy. I will definitely come back. Recommended.',
        ],
        [
            'name'    => 'Jan Stybor',
            'company' => 'Toyota',
            'role'    => 'Head of Project Department',
            'image'   => 'Jan-Stybor.jpg',
            'source'  => 'google',
            'badge'   => 'From my years at Toyota',
            'text'    => 'I appreciate his professional approach to the work. When developing an application he analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading. Then he puts together a plan and agrees the key milestones with the client.',
        ],
    ],

];
