<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Admin Email
    |--------------------------------------------------------------------------
    |
    | E-mail jediného admin uživatele, který má přístup do Filament panelu
    | (`/admin`). Použito v `User::canAccessPanel()` a `AdminUserSeeder`.
    |
    */
    'email' => env('ADMIN_EMAIL'),

    /*
    |--------------------------------------------------------------------------
    | Default Author
    |--------------------------------------------------------------------------
    |
    | Výchozí jméno autora předvyplněné v `ArticleResource` form (pole „Autor").
    | Pokud není nastaveno, pole zůstává prázdné a editor jej musí vyplnit ručně.
    |
    */
    'author' => env('ADMIN_AUTHOR'),
];
