<?php

return [

    'meta' => [
        'title'       => 'Kontakt — Ondřej Kriška',
        'description' => 'Zavolejte nebo napište a já se ozvu zpět. Kontaktní formulář, telefon a adresa.',
    ],

    'subheading'          => 'Pomůžu vám',
    'heading'             => 'Ozvu se do 24 hodin v pracovní dny',
    // OND-201 (nález 5.9): „Ondřej Kriška, Česká republika" byl signál
    // anonymního dodavatele. Plná adresa a IČO jsou veřejné údaje, zvyšují
    // důvěru i lokální viditelnost. Zdroj: lang/cs/about.php („Kde sídlím").
    'address_label'       => 'Adresa',
    'address_name'        => 'Ondřej Kriška',
    'address_street'      => 'Dunajovská 116',
    'address_city'        => '691 81 Březí',
    'address_registration' => 'IČO 19231407, neplátce DPH',
    // OND-267 (E-1): popisek byl natvrdo v šabloně, takže i německá mutace
    // psala „E-mail" — německy se píše jen „E-Mail".
    'email_label'         => 'E-mail',
    'phone_label'         => 'Telefon',
    'hours_label'         => 'Dostupnost',
    'open_hours'          => 'Ozvu se do 24 hodin v pracovní dny. O víkendech a svátcích nedržím pohotovost, ale nic mi nezapadne.',
    'cta_consultation'    => 'Napište mi',

    'form_heading'        => 'Kontaktní formulář',
    'form_subheading'     => 'Získejte zdarma a nezávazně nabídku — nebo mi pošlete jakýkoli dotaz.',
    'name'                => 'Celé jméno',
    'email'               => 'Email',
    'tel'                 => 'Telefon (nepovinný)',
    'tel_hint'            => 'S číslem se ozvu rychleji.',
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
    // OND-256/8 — texty upload widgetu. Dřív byly natvrdo anglicky
    // v propech `x-form.file-drop`.
    'upload' => [
        'label'            => 'Přidat přílohy',
        'drag_text'        => '— nebo je sem přetáhněte',
        'browse'           => 'Vybrat soubory',
        'hint'             => 'PDF, DOC, DOCX, XLS, XLSX, JPG, PNG, ZIP…',
        'max_files'        => 'Max. 5 souborů',
        'max_size'         => 'celkem 20 MB',
        'remove'           => 'Odebrat',
        'error_too_many'   => 'Najednou lze přiložit nejvýš 5 souborů.',
        'error_too_large'  => 'Přílohy dohromady nesmí přesáhnout 20 MB.',
        // OND-264: hlášky ze serverové validace příloh.
        'error_per_file'   => 'Jeden soubor smí mít nejvýš :max MB.',
        'error_mime'       => 'Tenhle typ souboru poslat nejde. Povolené jsou: :types.',
        'error_failed'     => 'Přílohu se nepodařilo uložit. Zkuste to prosím znovu — nebo mi soubor pošlete na ok@ondraweb.cz.',
    ],

    'message_success'     => 'Děkuji za zprávu.',
    'message_error'       => 'Něco se cestou pokazilo. Zkuste to prosím znovu — nebo mi napište přímo na ok@ondraweb.cz.',

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
        'subline'      => 'Vaši zprávu si přečtu osobně. Ozvu se do 24 hodin v pracovní dny.',
        'photo_alt'    => 'Ondřej Kriška — autor a kontaktní osoba',
        'role_label'   => 'Vývojář, autor webu, jediný kontakt',
    ],

    // 3-step „Co se stane potom" — snižuje obavu z odeslání formuláře.
    'next_steps' => [
        'eyebrow' => 'Co se stane potom',
        'heading' => 'Tři kroky — žádný marketingový trychtýř.',
        'steps'   => [
            [
                'title' => 'Ozvu se do 24 hodin v pracovní dny',
                'text'  => 'Dorazí vám e-mail ode mě osobně, ne automatická potvrzovací zpráva. O víkendech a svátcích nedržím pohotovost — ozvu se první pracovní den.',
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
        'subline'  => 'Děkuji. Přečtu si ji osobně a ozvu se do 24 hodin v pracovní dny.',
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
