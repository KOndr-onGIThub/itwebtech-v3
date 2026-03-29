@component('mail::message')


    Zpráva z kontaktního formuláře aplikace **{{ config('app.name') }}** na (`{{ config('app.url') }}`).

    Obsah zprávy:

@component('mail::panel')
zadany email:<br>
{{ $email }}
<br>
zadane jmeno:<br>
{{ $name }}
<br>
zadany telefon:<br>
{{ $tel }}
<br>

zajem o web? - {{ $web ? 'ANO' : 'NE' }}<br>
zajem o app? - {{ $app ? 'ANO' : 'NE' }}<br>
zajem o Eshop? - {{ $eshop ? 'ANO' : 'NE' }}<br>
zajem o Ostatní? - {{ $other ? 'ANO' : 'NE' }}<br>

vybraná cenová kategorie<br>
{{ $selected_price_option }}

zprava:<br>
{{ $message }}
@endcomponent




Thanks,<br>
{{ config('app.name') }}
@endcomponent

