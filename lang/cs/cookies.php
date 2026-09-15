<?php

return [

    'meta' => [
        'title'       => 'Cookies a souhlas se zpracováním — ondraweb.cz',
        'description' => 'Informace o cookies a měřicích nástrojích, které používáme na ondraweb.cz, a jak svůj souhlas kdykoli odvolat.',
    ],

    'hero' => [
        'page_mark_label' => 'COOKIES A MĚŘENÍ',
        'upline'          => 'Bez reklamních cookies. Bez prodeje dat.',
        'heading_html'    => 'Co měřím a <em>proč</em> to dělám.',
        'subline'         => 'Anonymní statistika návštěvnosti — abych věděl, co funguje. Žádné cílení reklam, žádní prostředníci.',
    ],

    'tldr' => [
        'eyebrow' => 'V kostce',
        'items'   => [
            'Měřím jen anonymní návštěvnost (GA4) a anonymizované heatmapy (Clarity).',
            'Žádné reklamní cookies ani cílení reklam — <code>ad_storage</code> je trvale <code>denied</code>.',
            'Svůj souhlas můžete kdykoli odvolat tlačítkem dole nebo smazáním cookies v prohlížeči.',
        ],
    ],

    'intro' => 'Tato stránka shrnuje, jaké cookies a měřicí nástroje na webu <strong>ondraweb.cz</strong> používáme, k čemu slouží a jak souhlas s jejich používáním kdykoli odvoláte.',

    'what_we_use' => [
        'heading' => 'Co používáme',
        'items'   => [
            '<strong>Google Analytics 4 (GA4)</strong> — anonymní statistika návštěvnosti, ze které vidíme, kolik lidí web navštíví, odkud přicházejí a které sekce zaujmou.',
            '<strong>Microsoft Clarity</strong> — heatmapy a nahrávky relací (s anonymizovaným obsahem), které pomáhají odhalit, kde mají návštěvníci problém najít to, co hledají.',
        ],
        'note'    => 'Žádné reklamní cookies nebo cílení reklam nepoužíváme. V GA4 zůstávají reklamní souhlasy (<code>ad_storage</code>, <code>ad_user_data</code>, <code>ad_personalization</code>) trvale na hodnotě <code>denied</code>.',
    ],

    'what_we_measure' => [
        'heading' => 'Co měříme',
        'items'   => [
            'Návštěvnost a zdroje (odkud lidé přicházejí, kolik stránek shlédnou, jak dlouho zůstanou).',
            'Interakci s primárními CTA — klik na „Získat cenovou nabídku“, telefonní číslo, otevření formuláře, odeslání poptávky.',
            'Nahrávky relací (Clarity) — anonymizovaný video záznam pohybu kurzoru a kliků, aby šlo odhalit místa, kde návštěvník bloudí.',
        ],
    ],

    'retention' => [
        'heading' => 'Doba uchování',
        'items'   => [
            'Souhlas „Přijmout vše“ — uložen v prohlížeči (<code>localStorage</code>) na <strong>365 dnů</strong>, poté budete znovu dotázáni.',
            '„Odmítnout“ — uložen na <strong>180 dnů</strong>; po tuto dobu se vás banner znovu nezeptá a žádné měřicí cookies se nenastavují.',
            'Cookies GA4 (<code>_ga</code>, <code>_ga_*</code>) — standardně 2 roky (pouze pokud udělíte souhlas).',
            'Cookies Microsoft Clarity (<code>_clck</code>, <code>_clsk</code>, <code>MUID</code>, <code>CLID</code>) — dle nastavení Microsoftu (typicky 1 rok).',
        ],
    ],

    'revoke' => [
        'heading'      => 'Jak souhlas odvolat',
        'description'  => 'Pokud chcete svůj souhlas odvolat, klikněte na následující tlačítko. Smaže se uložený souhlas i případné GA / Clarity cookies a po obnovení stránky se znovu zobrazí banner.',
        'button'       => 'Odvolat souhlas a smazat cookies',
        'manual'       => 'Alternativně můžete cookies pro doménu <code>ondraweb.cz</code> smazat ručně v nastavení vašeho prohlížeče.',
    ],

    'controller' => [
        'heading' => 'Správce dat',
        'name'    => 'Ondřej Kriška – ONDRAWEB',
        'email_label' => 'E-mail',
        'see_privacy_html' => 'Pro detailnější informace o zpracování osobních údajů viz :link.',
        'see_privacy_link' => 'zásady ochrany osobních údajů',
    ],

];
