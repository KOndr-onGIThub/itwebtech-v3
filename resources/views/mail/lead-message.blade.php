@component('mail::message')
# Nová poptávka z webu

Přišla nová poptávka z webu {{ config('app.name') }}.

| Údaj | Hodnota |
|---|---|
| Jméno | {{ $lead->name }} |
| Firma | {{ $lead->company ?? '—' }} |
| E-mail | {{ $lead->email }} |
| Telefon | {{ $lead->phone ?? '—' }} |
| Rozpočet | {{ $lead->budget ?? '—' }} |
| Zdroj | {{ $lead->source ?? '—' }} |
| Čas | {{ $lead->created_at?->format('d.m.Y H:i:s') ?? now()->format('d.m.Y H:i:s') }} |
| Lead ID | #{{ $lead->id }} |

**Zpráva:**

{{ $lead->message ?: '—' }}

@component('mail::subcopy')
Odpovědět můžeš přímo na tento e-mail (Reply-To je nastaven na adresu odesílatele).
Poptávka je zároveň uložená v databázi (`landing_leads`), e-mail je jen notifikace.
@endcomponent
@endcomponent
