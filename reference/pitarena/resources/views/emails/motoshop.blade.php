@component('mail::message')

Vážený zákazníku,

děkujeme Vám za poptávku na **pitbike {{ $model }}**

Budeme vás co nejdříve kontaktovat, abychom vám potvrdili detaily.


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
model: 
{{ $model }}
<br>
<br>
zprava:<br>
{{ $message }}
@endcomponent

<br>
<i>Na email web@pitarena neodpovídejte, jde o automaticky generovanou zprávu webové aplikace.</i>
<br><br>
V případě nejasností nás kontaktujte na tel.: +420 728 697 712, nebo na <a href="mailto:shop@pitarena.cz">shop@pitarena.cz</a>
<br><br>

Děkujeme<br>
{{ config('app.name') }}<br>
Překračuj limity, objevuj vítězství!<br>
{{ config('app.url') }}
@endcomponent