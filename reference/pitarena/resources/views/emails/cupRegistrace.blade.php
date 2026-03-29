@component('mail::message')


Děkujeme za vaši registraci na webu **{{ config('app.url') }}**.
<br>
<br>
Registrace bude dokončena **uhrazením platby** (údaje k platbě naleznete níže).

Vaše objednávka zahrnuje:
@component('mail::panel')
licence: <b>{{ $licence == "licence-Moravia" ? "NE" : $licence }}</b><br>
@if($zavod)
Vybrané závody:<br>
@foreach ($zavod as $item)
<b>{{ $item }}</b><br>
@endforeach
@else
Sezóna: <b>{{ $sezona }}</b>
@endif
<br>
Kategorie:
@foreach (json_decode($category_name) as $item)
<b>{{ $item->name }}</b>
@endforeach
<br>
Startovní číslo: 
@if($startovni_cislo)
<b>{{ $startovni_cislo }}</b><br>
<i>Startovní čísla budou procházet kontrolou. Pokud si někdo v dané kategorii zvolil stejné číslo jako vy dříve, budeme vás kontaktovat pro výběr jiného čísla.</i>
@else
<b>Nepřiřazeno</b><br>
<i>Startovní číslo si z dostupných vyberete v den závodu na místě.</i>
@endif

@endcomponent

<br><br>
@php
    $amount = (float) ($zaplatit ?? 0);
    $allowCashPayment = now()->gte(
        now()->copy()->startOfYear()->addMonths(3) // 1. dubna aktuálního roku
    );
@endphp

Údaje k platbě:
@component('mail::panel')

@if ($amount > 0)
QR kód pro platbu:<br><br>
@if(!empty($hasQr) && ($qr_mime ?? null) === 'image/png')
    <img src="cid:{{ $qrCid }}" width="200" height="200" alt="QR kód pro platbu">
    <br><i>* Pokud se QR kód nezobrazuje, je i v příloze jako <b>qr.png</b>.</i>
@else
    <i>QR kód se nepodařilo vygenerovat – použijte prosím údaje níže.</i>
@endif
<br><br>
číslo účtu pro platbu: <b>131-3683530257/0100</b>
<br><br>
částka k úhradě: <b>{{ $zaplatit }} ,-Kč</b>
<br><br>
variabilní symbol: <b>{{ $var_symbol }}</b>
<br><br>
datum splatnosti: <b>{{ now()->addDays(7)->format('d.m.Y') }} *</b>
@if($allowCashPayment)
<br><i>* Nebo den před konáním závodu, ke kterému se registrace vztahuje - podle toho co nastane dřív.</i>
<br><i>Registrace provedené méně než 24 h. před závodem lze zaplatit v hotovosti na místě.</i>
@endif
@if ($dve_splatky)
<br><br>
**Zvolili jste platbu na dvě splátky. Pokyny pro druhou splátku vám zašleme zhruba 1 měsíc před prvním závodem druhé části sezóny.**
@endif
@else
Neobjednali jste nic co by bylo potřeba nyní platit.
@endif
<br>
@endcomponent
<br><br>

Vaše zadané údaje:
@component('mail::panel')
jméno jezdce:  <b>{{ $jmeno_jezdce }}</b>
<br>
fakturační jmeno: <b>{{ $fakturacni_jmeno ? $fakturacni_jmeno : 'stejné jako jméno jezdce' }}</b>
<br>
Název firmy: <b>{{ $firm_name ? $firm_name : 'nezadáno' }}</b>
<br>
IČO:  <b>{{ $firm_ico ? $firm_ico : 'nezadáno' }}</b>
<br>
<br>
email: <b>{{ $email_jezdce }}</b>
<br>
<br>
telefon: <b>{{ $telefon_jezdce }}</b>
<br>
<br>
poznámka:<br>
{{ $poznamka }}
@endcomponent
<br><br>

Neodpovídejte prosím na tento email. Jedná se o automaticky generovanou zprávu.
<br>
Pokud máte jakýkoli dotaz, neváhejte se na nás obrátit prostřednictvím kontaktního emailu <a href="mailto:ycf-cup@pitarena.cz">ycf-cup@pitarena.cz</a>
<br><br>
Děkujeme za vaši registraci.<br>
Za Pitarénu a YCF CUP registrace zprostředkovává: <br>
Ondřej Kriška <br>
<a href="https://itwebtech.cz">ITWebTech.cz</a><br>

@endcomponent