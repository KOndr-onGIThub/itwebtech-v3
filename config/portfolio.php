<?php

/*
|--------------------------------------------------------------------------
| Obory realizací (OND-471)
|--------------------------------------------------------------------------
| „Pro koho“ ve větě nad přehledem /projekty (`?v=1`). Štítky oboru
| v DB má jen polovina projektů, proto ruční mapa slugů. Projekt může
| být ve dvou oborech. Nepublikované slugy se ignorují; obor bez
| jediného publikovaného projektu se nevykreslí.
| Popisky jsou v lang/{cs,en,de}/projects.php → `sentence.for.*`.
*/

return [

    'sectors' => [
        'remeslo' => ['strechy-zajic', 'josefopa', 'elektro-srnak', 'nove-interiery', 'barana'],
        'vyroba'  => ['hcms', 'picker', 'frl-creator', 'excel-tools', 'choccoboard', 'vp-industry', 'vanspedition'],
        'sluzby'  => ['zubni-provazek', 'realitacky-v-akci', 'kemp-veselka', 'yolk'],
        'sport'   => ['pitarena', 'pitarena-eshop', 'clanek-motorkari-cz', 'pitarena-cedule', 'cyklocentrum', 'kemp-veselka'],
    ],

];
