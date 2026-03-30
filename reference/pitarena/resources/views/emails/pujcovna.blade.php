@component('mail::message')

Vážený zákazníku,

děkujeme Vám za vaši rezervaci

Budeme vás co nejdříve kontaktovat, abychom si potvrdili termín a další detaily.


Bezpečně jsme od vás přijali tyto údaje:

@component('mail::panel')
jmeno: 
{{ $name }}
<br>
<br>
email: 
{{ $email }}
<br>
<br>
telefon: 
{{ $phone }}
<br>
<br>
zvolený datum a čas: 
{{ $date->format('j. n. Y H:i') }}
<br>
<br>
moto: 
{{ $model }}
<br>
<br>
zpráva:<br>
{{ $message }}
@endcomponent

<br>
<i>Na email web@pitarena neodpovídejte, jde o automaticky generovanou zprávu webové aplikace.</i>
<br><br>
V případě potřeby nás kontaktujte na tel.: +420 728 697 712, nebo na <a href="mailto:shop@pitarena.cz">shop@pitarena.cz</a>
<br><br>

Děkujeme<br>
{{ config('app.name') }}<br>
Překračuj své limity, objevuj vítězství!<br>
{{ config('app.url') }}
@endcomponent