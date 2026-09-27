<?php

return [

    'nav' => [
        'home'     => 'Úvod',
        'contact'  => 'Kontakt',
        'price'    => 'Ceník',
        'projects' => 'Projekty',
        'blog'     => 'Zápisky',
        'about'    => 'O mně',
        'lang_switcher' => 'Přepínač jazyků',
    ],

    // OND-369: pozůstatek po OND-307 — klíč se nikde nevykresluje (hlavní
    // tlačítko v hlavičce i v draweru bere `home.sticky.cta`). Znění
    // sjednoceno se zbytkem webu, aby nešlo omylem nasadit starou řeč.
    'cta' => [
        'contact' => 'Napsat poptávku',
    ],

    'footer' => [
        'rights'    => 'Všechna práva vyhrazena.',
        'developer' => 'Web vytvořil',
        // OND-387: claim a navigace se přestěhovaly z předpatičky (zrušená,
        // §3 základu podstránek). `prefooter.cta` a `prefooter.nav_label`
        // s ní zanikly — tlačítko v patičce není.
        'tagline'   => 'Weby a aplikace na míru. Napřímo.',
        'nav_label' => 'Navigace v patičce',
    ],

    'modal' => [
        'close' => 'Zavřít',
    ],

    // OND-167 — Cookie consent modal (singulární, freelance tón).
    'cookies' => [
        'title'       => 'Smím nasadit pár cookies?',
        // OND-231 F3: z modalu se stala lišta u spodní hrany, takže text
        // musí být krátký — dřívější tři věty zabraly na mobilu půl
        // obrazovky. Účel měření zůstává pojmenovaný, detail nese odkaz
        // na zásady (informovaný souhlas beze změny).
        'body'        => 'Měřím jen pár čísel o tom, co na webu funguje. Žádné prodávání dat.',
        'policy_link' => 'Detail v zásadách',
        'accept'      => 'Přijmout vše',
        'reject'      => 'Odmítnout',
        'close'       => 'Zavřít',
    ],

    'gdpr_form_note' => 'Odesláním souhlasíte se',
    'gdpr_form_link' => 'zásadami ochrany osobních údajů',
    // OND-266 (3): v patičce stojí odkaz samostatně, ne ve větě — 7. pád
    // („zásadami…“) tam byl bez řídící věty. `gdpr_form_*` výš zůstává
    // pro formulářovou variantu „Odesláním souhlasíte se …“.
    'footer_privacy_link' => 'Zásady ochrany osobních údajů',
    'cookies_link'   => 'Cookies',

    'meta' => [
        'description' => 'Tvorba webových stránek a aplikací. Pomáhám podnikatelům uspět v online světě.',
    ],

];
