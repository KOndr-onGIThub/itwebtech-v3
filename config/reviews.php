<?php

/*
|--------------------------------------------------------------------------
| /recenze — seskupení a profily (OND-397, předloha OND-396 §3 a §5)
|--------------------------------------------------------------------------
| Seskupení a pořadí jsou ve všech jazycích stejné, proto žijí tady a ne
| třikrát v `lang/{cs,en,de}/testimonials.php` (tři kopie by se rozešly).
|
| `groups`: čtyři skupiny podle otázky, kterou si člověk v pochybnosti
| klade; uvnitř skupiny jde nejkonkrétnější recenze první. Položka je `id`
| z `testimonials.php`. Pole id = jedna kartička pro víc lidí se stejným
| textem (Cyklocentrum) — text se bere od prvního.
|
| Každé `id` z `testimonials.php` musí být v `groups` právě jednou, jinak
| nově přidaná recenze na stránce tiše chybí. Hlídá Ond397ReviewsPageTest.
|
| `profiles`: počty hodnocení na platformách (inventura OND-395, 27. 9. 2026).
| Součet je číslo v pruhu na homepage a v H1 stránky (26). Na profilech
| poroste, tady je natvrdo — při změně upravit i `home.social_proof.reviews`
| a `reviews.heading` ve třech jazycích.
*/

return [

    'groups' => [
        'results' => [
            'ycf-cup', 'petr-kroulik', 'rostislav-toman', 'jaroslav-zajic',
            'hana-jaskmanicka', 'marie-mikova', 'jana-vesela',
        ],
        'context' => [
            'magda-pernicova', 'kamil-travnik', 'ales-horky', 'stanislav-holcmann',
            ['michal-cvrcek', 'petr-knourek'],
        ],
        'working' => [
            'ivo-stepanek', 'adela-polaskova', 'radka-lanikova-ourednikova',
            'vaclav-pesice', 'peter-vidlicka', 'lukas-srnak', 'roman-antos',
        ],
        'toyota' => ['jan-stybor', 'pavel-baudys'],
    ],

    'profiles' => [
        'google' => [
            'count' => 14,
            'url'   => 'https://www.google.com/maps/place/OndraWeb.cz+-+Ond%C5%99ej+Kri%C5%A1ka/@48.8205331,16.5665234,17z/data=!4m8!3m7!1s0x66be6e0a59ec45a7:0xcea91464a27623df!8m2!3d48.8205331!4d16.5665234!9m1!1b1!16s%2Fg%2F11ss5bkbsy',
        ],
        'firmy_cz' => [
            'count' => 12,
            'url'   => 'https://www.firmy.cz/detail/13470851-profesionalni-webove-stranky-a-aplikace-ondrej-kriska-brezi.html#hodnoceni',
        ],
    ],

];
