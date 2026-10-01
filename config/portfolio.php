<?php

/*
|--------------------------------------------------------------------------
| Obory realizací (OND-470, návrh OND-471)
|--------------------------------------------------------------------------
| „Pro koho“ ve větě nad přehledem /projekty. Štítky oboru
| v DB má jen polovina projektů, proto ruční mapa slugů. Projekt může
| být ve dvou oborech. Nepublikované slugy se ignorují; obor bez
| jediného publikovaného projektu se nevykreslí.
| Popisky jsou v lang/{cs,en,de}/projects.php → `catalog.sentence.for.*`.
| Nový projekt = doplnit jeho slug sem, jinak ho věta najde jen podle druhu.
*/

return [

    'sectors' => [
        'remeslo' => ['strechy-zajic', 'josefopa', 'elektro-srnak', 'nove-interiery', 'barana'],
        'vyroba'  => ['hcms', 'picker', 'frl-creator', 'excel-tools', 'choccoboard', 'vp-industry', 'vanspedition'],
        'sluzby'  => ['zubni-provazek', 'realitacky-v-akci', 'kemp-veselka', 'yolk', 'vinarstvi-antos'],
        'sport'   => ['pitarena', 'pitarena-eshop', 'clanek-motorkari-cz', 'pitarena-cedule', 'cyklocentrum', 'kemp-veselka'],
    ],

];
