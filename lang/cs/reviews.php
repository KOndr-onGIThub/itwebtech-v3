<?php

// OND-397 — /recenze (předloha OND-396 §7). Texty recenzí jsou
// v testimonials.php, seskupení a odkazy na profily v config/reviews.php.
// OND-490 (bod 5): počet hodnocení se nepíše, jen „5,0 z 5“ — totéž jako
// v pruhu na homepage (`home.social_proof.reviews`).

return [

    'meta' => [
        'title'       => 'Recenze — Ondřej Kriška, ONDRAWEB',
        'description' => 'Hodnocení 5,0 z 5 na Googlu a Firmy.cz. Co o spolupráci napsali klienti, u každé recenze odkaz na originál.',
    ],

    'eyebrow'      => 'Recenze',
    'heading_html' => '<em>5,0 z 5</em> na Googlu a Firmy.cz',
    'intro'        => 'Co o spolupráci napsali klienti. U každé recenze je odkaz na originál.',

    'profiles' => [
        'google'   => 'Hodnocení na Googlu',
        'firmy_cz' => 'Hodnocení na Firmy.cz',
    ],

    // Nadpisy jsou otázky ve třetí osobě, ne tvrzení (předloha §3).
    'groups' => [
        'results' => 'Co to klientům přineslo?',
        'context' => 'Pochopí, jak to u klienta chodí?',
        'working' => 'Jak se s ním pracuje?',
        'toyota'  => 'A jaký byl v Toyotě?',
    ],

    'original' => [
        'google'   => 'Originál na Googlu',
        'firmy_cz' => 'Originál na Firmy.cz',
        'facebook' => 'Originál na Facebooku',
    ],

    // Odkaz na stránku z homepage pod trojicí recenzí (vstup V2).
    'all' => 'Všechna hodnocení →',

];
