<?php

// OND-397 — /recenze (předloha OND-396 §7). Texty recenzí jsou
// v testimonials.php, seskupení a počty na profilech v config/reviews.php.
// Číslo 26 = Google 14 + Firmy.cz 12 (inventura OND-395), totéž jako
// v pruhu na homepage (`home.social_proof.reviews`).

return [

    'meta' => [
        'title'       => 'Recenze — Ondřej Kriška, ONDRAWEB',
        'description' => '5,0 z 26 hodnocení na Googlu a Firmy.cz. Co o spolupráci napsali klienti, u každé recenze odkaz na originál.',
    ],

    'eyebrow'      => 'Recenze',
    'heading_html' => '5,0 z <em>26 hodnocení</em> na Googlu a Firmy.cz',
    'intro'        => 'Co o spolupráci napsali klienti. U každé recenze je odkaz na originál.',

    'profiles' => [
        'google'   => 'Google · :count hodnocení',
        'firmy_cz' => 'Firmy.cz · :count hodnocení',
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
