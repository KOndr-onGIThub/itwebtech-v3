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
];
