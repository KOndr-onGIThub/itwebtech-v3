<?php

namespace App\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * OND-280: Past na spamboty pro veřejné formuláře.
 *
 * Proč vůbec: 26. 7. přišly na `/poptavka` čtyři spamy ze dvou IP adres
 * (dvě zprávy na adresu). `throttle:8,1` klíčuje po IP a pustí osm za
 * minutu — tyhle boty se limitu ani nedotknou. Past na skryté pole je
 * jediné opatření, které by je chytilo.
 *
 * Jak: formulář nese pole, které je pro člověka neviditelné a nedosažitelné.
 * Vyplní ho jen program, který prochází HTML a cpe text do všeho, co najde.
 * Vyplněné pole = požadavek se **tiše** zahodí: návštěvník (tedy bot) dostane
 * normální úspěšnou odpověď, ale nic se neuloží ani neodešle. Bot se tím
 * nedozví, že ho past chytila, a nezačne hledat, jak ji obejít.
 */
final class Honeypot
{
    /**
     * Jméno skrytého pole.
     *
     * Záměrně to NENÍ nic, co umí doplnit automatické vyplňování prohlížeče
     * (jméno, e-mail, telefon, adresa, přezdívka). Kdyby prohlížeč pole sám
     * vyplnil, zahodili bychom poptávku od skutečného člověka — to je jediné
     * reálné riziko téhle změny.
     */
    public const FIELD = 'website_url';

    /**
     * Sklapla past? Když ano, rovnou to zaloguje.
     *
     * @param  string  $source  Odkud požadavek přišel — ať jde v logu poznat,
     *                          který formulář boti zkoušejí.
     */
    public static function tripped(Request $request, string $source): bool
    {
        if (blank($request->input(self::FIELD))) {
            return false;
        }

        // Loguje se i jméno a e-mail. Kdyby past někdy chytila člověka,
        // musí jít jeho poptávka dohledat v logu — ne zmizet beze stopy.
        Log::info('OND-280 honeypot: skryté pole vyplněné, požadavek se tiše zahazuje.', [
            'source'     => $source,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'name'       => $request->input('name'),
            'email'      => $request->input('email'),
            'trap_value' => mb_substr((string) $request->input(self::FIELD), 0, 200),
        ]);

        return true;
    }
}
