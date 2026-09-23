@component('mail::message')
# Nová zpráva z kontaktního formuláře

Přišla nová zpráva z webu {{ config('app.name') }} (`/kontakt`).

| Údaj | Hodnota |
|---|---|
| Jméno | {{ $submission->name }} |
| E-mail | {{ $submission->email }} |
| Telefon | {{ $submission->tel ?? '—' }} |
| Předmět | {{ $submission->subject ?? '—' }} |
| Jazyk | {{ $submission->locale ?? '—' }} |
| Čas | {{ $submission->created_at?->format('d.m.Y H:i:s') ?? now()->format('d.m.Y H:i:s') }} |
| Lead ID | #{{ $submission->id }} |

**Zpráva:**

{{ $submission->message ?: '—' }}

@if (!empty($submission->attachments))
**Přílohy ({{ count($submission->attachments) }}):**

@foreach ($submission->attachments as $file)
@php
    $mb = $file['size'] / 1024 / 1024;
    $size = $mb >= 1 ? number_format($mb, 1, ',', ' ').' MB' : number_format($file['size'] / 1024, 0, ',', ' ').' kB';
@endphp
- {{ $file['name'] }} ({{ $size }}) — {{ ($file['mailed'] ?? false) ? 'přiloženo k tomuto e-mailu' : 'nevešlo se do e-mailu, leží na serveru: '.$file['path'] }}
@endforeach
@endif

@component('mail::subcopy')
Odpovědět můžeš přímo na tento e-mail (Reply-To je nastaven na adresu odesílatele).
Lead je zároveň uložen v databázi (`contact_submissions`), e-mail je jen notifikace.
@endcomponent
@endcomponent
