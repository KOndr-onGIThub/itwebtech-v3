@component('mail::message')


    Zpráva z aplikace **{{ config('app.name') }}** na (`{{ config('app.url') }}`).

@component('mail::panel')
Na základě odeslané žádost o zobrazení nebo úpravu registrace pro YCF CUP, vám zasíláme ověřovací kód.
<br>
Tento kód zadejte pro zobrazení vašich záznamů na stejné stránce, kde jste o tento kód žádali.
<br><br>

Ověřovací kód je:<br>
<b>{{ $verificationCode }}</b>

<br><br>
Tento kód můžete použít k načtení nebo úpravě dat o vaší registraci neomezeně. Jeho platnost skončí pouze pokud si vygenerujete nový kód.

<br><br>
Pokud jste žádost o kód neodeslali vy, můžete tento email ignorovat. Nikdo neoprávněný se k vašim datům bez tohoto ověřovacího kódu nedostane.
@endcomponent




Překračuj své limity, objevuj vítězství!<br>
Tým PitArény a YCF-CUP<br>
@endcomponent