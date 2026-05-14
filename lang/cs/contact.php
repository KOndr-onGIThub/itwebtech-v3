<?php

return [

    'meta' => [
        'title'       => 'Kontakt — Ondřej Kriška',
        'description' => 'Zavolejte nebo napište a já se ozvu zpět. Kontaktní formulář, telefon a adresa.',
    ],

    'subheading'          => 'Pomůžu vám',
    'heading'             => 'Napište mi a do 24 hodin se ozvu',
    'address_label'       => 'Adresa',
    'hours_label'         => 'Dostupnost',
    'open_hours'          => 'Po–Pá: 9:00–19:00<br>So–Ne: 12:00–17:00',
    'cta_consultation'    => 'Naplánovat online schůzku',

    'form_heading'        => 'Kontaktní formulář',
    'form_subheading'     => 'Získejte zdarma a nezávazně nabídku — nebo mi pošlete jakýkoli dotaz.',
    'name'                => 'Celé jméno',
    'email'               => 'Email',
    'tel'                 => 'Tel. číslo',
    'subject'             => 'Předmět',
    'message'             => 'Vaše zpráva',
    'message_placeholder' => 'Stručně popište, co byste potřebovali — nebo jen napište, kdy vám mám zavolat…',
    'agree'               => 'Souhlasím se zpracováním osobních údajů v souladu se ',
    'policy'              => 'zásadami ochrany osobních údajů',
    'send'                => 'Odeslat zprávu',
    'sending'             => 'Odesílám...',
    'required'            => 'Vyplňte prosím toto pole.',
    'enter_valid_email'   => 'Vložte platnou emailovou adresu.',
    'policy_not_agreed'   => 'Pro odeslání musíme mít váš souhlas se zpracováním údajů.',
    'message_success'     => 'Děkuji za zprávu.',
    'message_error'       => 'Něco se cestou pokazilo. Zkuste to prosím znovu — nebo mi napište přímo na ok@itwebtech.cz.',

    // OND-136: net-new copy blocky pro /kontakt redesign (plán §1).
    // Engineer (B2) tyto klíče napojí v `resources/views/pages/contact.blade.php`.

    // Trust signal hero — „he-it's-a-person".
    'hero' => [
        // Plán §3.1 hero — page-mark, upline, italic display heading, subline.
        // OND-135 cleanup (2026-05-14): page_mark_index odebrán — agency-
        // portfolio artefakt per CEO PR #78 precedent (home).
        'page_mark_label' => 'KONTAKT',
        'upline'          => 'Píšete přímo mně.',
        'heading_html'    => 'Žádné CRM,<br>žádné call centrum — <em>jen Ondřej</em>.',
        'eyebrow'      => 'Píšete přímo mně',
        'heading'      => 'Píšete přímo mně, Ondřejovi.',
        'subline'      => 'Vaši zprávu si přečtu osobně a odepíšu obvykle do druhého pracovního dne.',
        'photo_alt'    => 'Ondřej Kriška — autor a kontaktní osoba',
        'role_label'   => 'Vývojář, autor webu, jediný kontakt',
    ],

    // 3-step „Co se stane potom" — snižuje obavu z odeslání formuláře.
    'next_steps' => [
        'eyebrow' => 'Co se stane potom',
        'heading' => 'Tři kroky — žádný marketingový trychtýř.',
        'steps'   => [
            [
                'title' => 'Odpovím do 24 hodin',
                'text'  => 'Dorazí vám e-mail ode mě osobně, ne automatická potvrzovací zpráva. Pokud budu na cestách, ozvu se nejpozději druhý pracovní den.',
            ],
            [
                'title' => 'Dohodneme 30 minut hovoru',
                'text'  => 'Krátký telefonát nebo videohovor — zjistíme, jestli má spolupráce smysl. Bez prezentace, bez slidů, bez prodejního tlaku.',
            ],
            [
                'title' => 'Dostanete písemnou nabídku',
                'text'  => 'Do týdne pošlu specifikaci s rozsahem, termínem a přesnou cenou. Co bude ve specifikaci, bude i na faktuře.',
            ],
        ],
    ],

    // Thank-you state — zobrazí se po úspěšném odeslání místo formuláře.
    'thank_you' => [
        'heading'  => 'Hotovo, zpráva dorazila.',
        'subline'  => 'Děkuji. Přečtu si ji osobně a odepíšu nejpozději do druhého pracovního dne.',
        'next'     => 'Mezitím se můžete podívat na realizované projekty nebo si přečíst ceník.',
        'cta_projects' => 'Realizované projekty',
        'cta_price'    => 'Ceník',
    ],

    // Volitelné budget pole (sjednocené s home.inline_form a landing budgety).
    'budget_label'   => 'Orientační rozpočet (volitelné)',
    'budget_options' => [
        'Do 25 000 Kč',
        '25 000 až 55 000 Kč',
        '55 000 až 95 000 Kč',
        '95 000 Kč a více',
        'Zatím nevím — poradíte mi',
    ],

];
