@component('mail::message')


Děkujeme za vaši objednávku na webu **{{ config('app.url') }}**.
<br>
<br>

Vaše objednávka zahrnuje:
@component('mail::panel')
@if($licence)
licence: <b>{{ $licence == "licence-Moravia" ? "NE" : $licence }}</b><br>
@endif
@if($zavod)
Vybrané závody:<br>
@foreach ($zavod as $item)
<b>{{ $item }}</b><br>
@endforeach
@endif
@if($sezona != 'cela-sezona-NE')
Sezóna: <b>{{ $sezona }}</b>
@endif
<br>
@if($startovni_cislo)
Startovní číslo: 
<b>{{ $startovni_cislo }}</b><br>
<i>Startovní čísla budou procházet kontrolou. Pokud si někdo v dané kategorii zvolil stejné číslo jako vy dříve, budeme vás kontaktovat pro výběr jiného čísla.</i>
@endif

@endcomponent

<br><br>

Údaje k platbě:
@component('mail::panel')
číslo účtu pro platbu: <b>131-3683530257/0100</b>
<br><br>
částka k úhradě: <b>{{ $cost }} ,-Kč</b>
<br><br>
variabilní symbol: <b>{{ $varSymbol }}</b>
<br><br>
datum splatnosti: <b>{{ now()->addDays(10)->format('d.m.Y') }}</b>
<br><b>* Nebo den před konáním závodu, ke kterému se objednávka vztahuje - podle toho co nastane dřív. Zaplatit lze také v hotovosti na místě.</b>
@if ($dve_splatky)
<br><br>
**Zvolili jste platbu na dvě splátky. Pokyny pro druhou splátku vám zašleme zhruba 1 měsíc před prvním závodem druhé části sezóny.**
@endif
<br>
@endcomponent
<br><br>

Neodpovídejte prosím na tento email. Jedná se o automaticky generovanou zprávu.
<br>
Pokud máte jakýkoli dotaz, neváhejte se na nás obrátit prostřednictvím kontaktního emailu <a href="mailto:ycf-cup@pitarena.cz">ycf-cup@pitarena.cz</a>
<br><br>
Děkujeme za vaši objednávku.<br>
Za Pitarénu a YCF CUP objednávky zprostředkovává: <br>
Ondřej Kriška <br>
<a href="https://itwebtech.cz">ITWebTech.cz</a><br>

@endcomponent