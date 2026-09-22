<?php

/*
|--------------------------------------------------------------------------
| Validation Language Lines (CS)
|--------------------------------------------------------------------------
|
| Czech translations for Laravel validation messages. Drives error strings
| shown by `Request::validate()`, e.g. the home inline lead form. Pokud
| přidáš nové pravidlo do controlleru a chybí tady klíč, Laravel spadne
| zpět na anglické messages — proto je seznam pravidel kompletní.
|
*/

return [

    'accepted'             => 'Pole :attribute musí být potvrzené.',
    'accepted_if'          => 'Pole :attribute musí být potvrzené, pokud :other má hodnotu :value.',
    'active_url'           => ':Attribute není platná URL adresa.',
    'after'                => ':Attribute musí být datum po :date.',
    'after_or_equal'       => ':Attribute musí být datum nejdříve :date.',
    'alpha'                => ':Attribute smí obsahovat pouze písmena.',
    'alpha_dash'           => ':Attribute smí obsahovat pouze písmena, čísla, pomlčky a podtržítka.',
    'alpha_num'            => ':Attribute smí obsahovat pouze písmena a čísla.',
    'array'                => ':Attribute musí být pole.',
    'ascii'                => ':Attribute smí obsahovat pouze jednobajtové alfanumerické znaky a symboly.',
    'before'                => ':Attribute musí být datum před :date.',
    'before_or_equal'       => ':Attribute musí být datum nejpozději :date.',
    'between'               => [
        'array'   => ':Attribute musí mít mezi :min až :max položek.',
        'file'    => ':Attribute musí být mezi :min a :max kilobyty.',
        'numeric' => ':Attribute musí být mezi :min a :max.',
        'string'  => ':Attribute musí mít mezi :min a :max znaky.',
    ],
    'boolean'               => ':Attribute musí být true nebo false.',
    'can'                   => ':Attribute obsahuje neoprávněnou hodnotu.',
    'confirmed'             => 'Potvrzení pole :attribute se neshoduje.',
    'contains'              => 'Pole :attribute postrádá požadovanou hodnotu.',
    'current_password'      => 'Nesprávné heslo.',
    'date'                  => ':Attribute musí být platné datum.',
    'date_equals'           => ':Attribute musí být datum shodné s :date.',
    'date_format'           => ':Attribute musí odpovídat formátu :format.',
    'decimal'               => ':Attribute musí mít :decimal desetinných míst.',
    'declined'              => 'Pole :attribute musí být odmítnuté.',
    'declined_if'           => 'Pole :attribute musí být odmítnuté, pokud :other má hodnotu :value.',
    'different'             => ':Attribute a :other se musí lišit.',
    'digits'                => ':Attribute musí mít :digits číslic.',
    'digits_between'        => ':Attribute musí mít :min až :max číslic.',
    'dimensions'            => ':Attribute má neplatné rozměry obrázku.',
    'distinct'              => ':Attribute má duplicitní hodnotu.',
    'doesnt_end_with'       => ':Attribute nesmí končit jednou z hodnot: :values.',
    'doesnt_start_with'     => ':Attribute nesmí začínat jednou z hodnot: :values.',
    'email'                 => ':Attribute musí být platná e-mailová adresa.',
    'ends_with'             => ':Attribute musí končit jednou z hodnot: :values.',
    'enum'                  => 'Vybraná hodnota :attribute je neplatná.',
    'exists'                => 'Vybraná hodnota :attribute je neplatná.',
    'extensions'            => ':Attribute musí mít jednu z přípon: :values.',
    'file'                  => ':Attribute musí být soubor.',
    'filled'                => 'Pole :attribute musí mít hodnotu.',
    'gt'                    => [
        'array'   => ':Attribute musí mít více než :value položek.',
        'file'    => ':Attribute musí být větší než :value kilobytů.',
        'numeric' => ':Attribute musí být větší než :value.',
        'string'  => ':Attribute musí mít více než :value znaků.',
    ],
    'gte'                   => [
        'array'   => ':Attribute musí mít alespoň :value položek.',
        'file'    => ':Attribute musí být alespoň :value kilobytů.',
        'numeric' => ':Attribute musí být alespoň :value.',
        'string'  => ':Attribute musí mít alespoň :value znaků.',
    ],
    'hex_color'             => ':Attribute musí být platná hexadecimální barva.',
    'image'                 => ':Attribute musí být obrázek.',
    'in'                    => 'Vybraná hodnota :attribute je neplatná.',
    'in_array'              => 'Pole :attribute musí existovat v :other.',
    'integer'               => ':Attribute musí být celé číslo.',
    'ip'                    => ':Attribute musí být platná IP adresa.',
    'ipv4'                  => ':Attribute musí být platná IPv4 adresa.',
    'ipv6'                  => ':Attribute musí být platná IPv6 adresa.',
    'json'                  => ':Attribute musí být platný JSON řetězec.',
    'list'                  => ':Attribute musí být seznam.',
    'lowercase'             => ':Attribute musí být malými písmeny.',
    'lt'                    => [
        'array'   => ':Attribute musí mít méně než :value položek.',
        'file'    => ':Attribute musí být menší než :value kilobytů.',
        'numeric' => ':Attribute musí být menší než :value.',
        'string'  => ':Attribute musí mít méně než :value znaků.',
    ],
    'lte'                   => [
        'array'   => ':Attribute nesmí mít více než :value položek.',
        'file'    => ':Attribute nesmí být větší než :value kilobytů.',
        'numeric' => ':Attribute nesmí být větší než :value.',
        'string'  => ':Attribute nesmí mít více než :value znaků.',
    ],
    'mac_address'           => ':Attribute musí být platná MAC adresa.',
    'max'                   => [
        'array'   => ':Attribute nesmí mít více než :max položek.',
        'file'    => ':Attribute nesmí být větší než :max kilobytů.',
        'numeric' => ':Attribute nesmí být větší než :max.',
        'string'  => ':Attribute nesmí mít více než :max znaků.',
    ],
    'max_digits'            => ':Attribute nesmí mít více než :max číslic.',
    'mimes'                 => ':Attribute musí být soubor typu: :values.',
    'mimetypes'             => ':Attribute musí být soubor typu: :values.',
    'min'                   => [
        'array'   => ':Attribute musí mít alespoň :min položek.',
        'file'    => ':Attribute musí být alespoň :min kilobytů.',
        'numeric' => ':Attribute musí být alespoň :min.',
        'string'  => ':Attribute musí mít alespoň :min znaků.',
    ],
    'min_digits'            => ':Attribute musí mít alespoň :min číslic.',
    'missing'               => 'Pole :attribute musí chybět.',
    'missing_if'            => 'Pole :attribute musí chybět, pokud :other má hodnotu :value.',
    'missing_unless'        => 'Pole :attribute musí chybět, pokud :other nemá hodnotu :value.',
    'missing_with'          => 'Pole :attribute musí chybět, je-li přítomno :values.',
    'missing_with_all'      => 'Pole :attribute musí chybět, jsou-li přítomny všechny hodnoty :values.',
    'multiple_of'           => ':Attribute musí být násobkem :value.',
    'not_in'                => 'Vybraná hodnota :attribute je neplatná.',
    'not_regex'             => 'Formát :attribute je neplatný.',
    'numeric'               => ':Attribute musí být číslo.',
    'password'              => [
        'letters'        => ':Attribute musí obsahovat alespoň jedno písmeno.',
        'mixed'          => ':Attribute musí obsahovat alespoň jedno velké a jedno malé písmeno.',
        'numbers'        => ':Attribute musí obsahovat alespoň jednu číslici.',
        'symbols'        => ':Attribute musí obsahovat alespoň jeden symbol.',
        'uncompromised'  => 'Zadaný :attribute se objevil v úniku dat. Zvolte prosím jiný :attribute.',
    ],
    'present'               => 'Pole :attribute musí být přítomno.',
    'present_if'            => 'Pole :attribute musí být přítomno, pokud :other má hodnotu :value.',
    'present_unless'        => 'Pole :attribute musí být přítomno, pokud :other nemá hodnotu :value.',
    'present_with'          => 'Pole :attribute musí být přítomno, je-li přítomno :values.',
    'present_with_all'      => 'Pole :attribute musí být přítomno, jsou-li přítomny všechny hodnoty :values.',
    'prohibited'            => 'Pole :attribute je zakázané.',
    'prohibited_if'         => 'Pole :attribute je zakázané, pokud :other má hodnotu :value.',
    'prohibited_if_accepted' => 'Pole :attribute je zakázané, pokud je :other přijato.',
    'prohibited_if_declined' => 'Pole :attribute je zakázané, pokud je :other odmítnuto.',
    'prohibited_unless'     => 'Pole :attribute je zakázané, pokud :other není v :values.',
    'prohibits'             => 'Pole :attribute zakazuje přítomnost :other.',
    'regex'                 => 'Formát :attribute je neplatný.',
    'required'              => 'Pole :attribute je povinné.',
    'required_array_keys'   => 'Pole :attribute musí obsahovat položky: :values.',
    'required_if'           => 'Pole :attribute je povinné, pokud :other má hodnotu :value.',
    'required_if_accepted'  => 'Pole :attribute je povinné, pokud je :other přijato.',
    'required_if_declined'  => 'Pole :attribute je povinné, pokud je :other odmítnuto.',
    'required_unless'       => 'Pole :attribute je povinné, pokud :other nemá hodnotu :values.',
    'required_with'         => 'Pole :attribute je povinné, je-li přítomno :values.',
    'required_with_all'     => 'Pole :attribute je povinné, jsou-li přítomny všechny hodnoty :values.',
    'required_without'      => 'Pole :attribute je povinné, není-li přítomno :values.',
    'required_without_all'  => 'Pole :attribute je povinné, není-li přítomna žádná z hodnot :values.',
    'same'                  => ':Attribute a :other se musí shodovat.',
    'size'                  => [
        'array'   => ':Attribute musí obsahovat :size položek.',
        'file'    => ':Attribute musí mít :size kilobytů.',
        'numeric' => ':Attribute musí být :size.',
        'string'  => ':Attribute musí mít :size znaků.',
    ],
    'starts_with'           => ':Attribute musí začínat jednou z hodnot: :values.',
    'string'                => ':Attribute musí být řetězec.',
    'timezone'              => ':Attribute musí být platná časová zóna.',
    'unique'                => ':Attribute již byl/a obsazen/a.',
    'uploaded'              => ':Attribute se nepodařilo nahrát.',
    'uppercase'             => ':Attribute musí být velkými písmeny.',
    'url'                   => ':Attribute musí být platná URL adresa.',
    'ulid'                  => ':Attribute musí být platné ULID.',
    'uuid'                  => ':Attribute musí být platné UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | Hezky pojmenované :attribute placeholdery pro formulářová pole.
    | Pokud přidáš nový form, doplň sem klíč pro každé pole.
    */

    'attributes' => [
        'name'    => 'jméno',
        'email'   => 'e-mail',
        'phone'   => 'telefon',
        'tel'     => 'telefon',
        'subject' => 'předmět',
        'message' => 'zpráva',
        'company' => 'firma',
        'budget'  => 'rozpočet',
        'gdpr'    => 'souhlas se zpracováním osobních údajů',
    ],

];
