@component('mail::message')
# Bezpečnostní upozornění

Na admin login (`/admin`) byl detekován neobvyklý počet neúspěšných pokusů.

| Údaj | Hodnota |
|---|---|
| IP adresa | `{{ $ip }}` |
| Pokusy | **{{ $attempts }}** za posledních {{ $windowMinutes }} min |
| Pokoušený e-mail | {{ $attemptedEmail ?? '—' }} |
| User-Agent | {{ \Illuminate\Support\Str::limit($userAgent, 200) }} |
| Čas | {{ now()->format('d.m.Y H:i:s') }} |

Pokud to nejsi ty, zvaž:

- změnu hesla,
- aktivaci/regeneraci 2FA recovery kódů,
- omezení přístupu na `/admin` přes Cloudflare WAF rule (IP allowlist).

@component('mail::subcopy')
Tento e-mail byl odeslán automaticky aplikací {{ config('app.name') }}.
@endcomponent
@endcomponent
