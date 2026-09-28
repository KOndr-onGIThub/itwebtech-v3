<?php

namespace App\Support;

use App\Models\Portfolio\PortfolioProject;
use Illuminate\Http\RedirectResponse;

/**
 * OND-455: starý slug případovky (itwebtech.cz, Framer ondraweb.cz) → dnešní
 * adresa. Mapa je v `config/redirects.php`.
 *
 * Trvalé 301 jen tehdy, když zamýšlená případovka je publikovaná. Jinak
 * dočasné 302 na náhradní cíl — prohlížeč si ho nezapamatuje a Google drží
 * starou adresu. Jakmile Ondřej případovku publikuje (stejný slug),
 * přesměrování se samo změní, bez zásahu do kódu.
 */
class LegacyProjectRedirect
{
    public static function for(string $oldSlug, string $locale): ?RedirectResponse
    {
        $map = config('redirects.project_slugs');

        if (! array_key_exists($oldSlug, $map)) {
            return null;
        }

        $candidates = (array) $map[$oldSlug];
        $status = 301;

        foreach ($candidates as $slug) {
            if (PortfolioProject::published()->where('slug', $slug)->exists()) {
                return redirect()->route("{$locale}.project", ['url' => $slug], $status);
            }
            $status = 302;
        }

        return redirect(lroute('projects', $locale), 302);
    }
}
