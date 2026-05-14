<?php

return [

    // OND-136: minimální copy pro chybové stránky (404, 500, 503, …).
    // Zachovává tón webu — žádný štěkot, žádný vtip, jen rychlé vrácení
    // uživatele zpět na konverzní cestu (homepage / kontakt). Engineer (B2)
    // napojí v `resources/views/errors/{code}.blade.php`.

    '404' => [
        'meta' => [
            'title'       => 'Stránka nenalezena (404) — Ondřej Kriška',
            'description' => 'Tato stránka už neexistuje nebo byla přesunuta. Vraťte se na hlavní stránku nebo mi napište — pomůžu vám najít, co potřebujete.',
        ],
        'eyebrow'      => 'Chyba 404',
        'heading'      => 'Tady to bohužel není.',
        'subheading'   => 'Stránka, kterou hledáte, byla buď přesunuta, smazána, nebo nikdy neexistovala.',
        'help'         => 'Pokud jste sem přišli z odkazu, který by měl fungovat, dejte mi vědět — opravím to.',
        'cta_primary'  => 'Zpět na hlavní stránku',
        'cta_secondary'=> 'Napsat mi, co jste hledali',
    ],

    '500' => [
        'meta' => [
            'title'       => 'Chyba serveru (500) — Ondřej Kriška',
            'description' => 'Něco se na naší straně pokazilo. Zkuste to prosím za chvíli znovu, nebo mi napište přímo.',
        ],
        'heading'      => 'Něco se pokazilo na naší straně.',
        'subheading'   => 'Chyba je u mě, ne u vás. Zkuste to prosím za chvíli znovu.',
        'help'         => 'Pokud problém přetrvává, ozvěte se mi přímo — vyřeším to.',
        'cta_primary'  => 'Zkusit znovu',
        'cta_secondary'=> 'Napsat mi',
    ],

    '503' => [
        'meta' => [
            'title'       => 'Stránka je dočasně mimo provoz — Ondřej Kriška',
            'description' => 'Web je momentálně dočasně mimo provoz kvůli údržbě. Bude k dispozici za chvíli.',
        ],
        'heading'      => 'Děláme rychlou údržbu.',
        'subheading'   => 'Web bude za chvíli zase dostupný. Pokud potřebujete něco rychle, ozvěte se mi přímo.',
        'cta_primary'  => 'Napsat mi e-mail',
    ],

];
