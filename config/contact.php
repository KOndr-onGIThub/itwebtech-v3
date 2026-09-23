<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Contact details
    |--------------------------------------------------------------------------
    | Values used across the public site (contact page, footer).
    | Phone is optional — if empty, no phone link is rendered anywhere.
    */

    // E-mail address that receives contact-form submissions.
    'to' => env('CONTACT_TO', 'ok@ondraweb.cz'),

    // Public phone number. Rendered as a clickable tel: link only when set.
    'phone' => env('CONTACT_PHONE', '+420 728 697 712'),

    /*
    |--------------------------------------------------------------------------
    | Přílohy kontaktního formuláře (OND-264)
    |--------------------------------------------------------------------------
    | Jediný zdroj pravdy pro limity příloh. Čte je server (validace v
    | ContactController) i prohlížeč (x-data u komponenty form.file-drop),
    | aby se nemohly rozejít. Texty limitů pro uživatele žijí v jazykových
    | souborech contact.php pod klíči upload.max_files a upload.max_size.
    */

    'uploads' => [
        // Kolik souborů smí jedno odeslání nést.
        'max_files' => 5,

        // Strop na jeden soubor.
        'max_file_mb' => 10,

        // Strop na všechny přílohy dohromady (to, co slibuje widget).
        'max_total_mb' => 20,

        // Povolené přípony (rule `mimes:` i atribut `accept`).
        'extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png', 'webp', 'zip'],

        // Disk, kam se přílohy ukládají (neveřejný).
        'disk' => 'local',

        // Kolik MB příloh se ještě vejde do notifikačního e-mailu. Co se
        // nevejde, zůstane na disku a e-mail to vypíše i s cestou — nikdy
        // se nezahodí potichu.
        'mail_budget_mb' => 15,
    ],

];
