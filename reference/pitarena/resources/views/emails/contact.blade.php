@component('mail::message')


    Zpráva z kontaktního formuláře aplikace **{{ config('app.name') }}** na (`{{ config('app.url') }}`).

    Obsah zprávy:

@component('mail::panel')
zadane jmeno: 
{{ $name }}
<br>
<br>
zadany email: 
{{ $email }}
<br>
<br>
zadany telefon: 
{{ $phone }}
<br>
<br>
zprava:<br>
{{ $message }}
@endcomponent




Thanks,<br>
{{ config('app.name') }}
@endcomponent