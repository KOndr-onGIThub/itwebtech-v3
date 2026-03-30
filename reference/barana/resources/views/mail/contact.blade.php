<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', Arial, Helvetica, sans-serif; background: #0C0B09; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }
    .header { background: #131210; padding: 28px 32px 26px; border-radius: 12px 12px 0 0; border: 1px solid rgba(212,168,83,0.18); border-bottom: none; }
    .logo { display: block; margin-bottom: 20px; }
    .header h1 { margin: 0; font-size: 21px; font-weight: 700; letter-spacing: -0.025em; color: #EAE7E1; line-height: 1.3; }
    .header-meta { margin: 6px 0 0; font-size: 12px; color: rgba(234,231,225,0.4); letter-spacing: 0.01em; }
    .body { background: #1A1916; padding: 32px; border-radius: 0 0 12px 12px; border: 1px solid rgba(212,168,83,0.12); border-top: none; }
    .field { margin-bottom: 22px; }
    .field-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #625848; margin-bottom: 5px; }
    .field-value { font-size: 15px; color: #C8C4BC; line-height: 1.65; }
    .message-box { background: #131210; border-left: 3px solid #D4A853; padding: 16px 20px; border-radius: 0 8px 8px 0; white-space: pre-wrap; word-break: break-word; font-size: 14px; color: #A09070; line-height: 1.7; }
    .divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 24px 0; }
    .badge { display: inline-block; background: rgba(212,168,83,0.10); color: #D4A853; font-size: 12px; font-weight: 600; padding: 3px 12px; border-radius: 99px; border: 1px solid rgba(212,168,83,0.22); letter-spacing: 0.02em; }
    .attachments { background: #131210; border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px 18px; margin-top: 20px; }
    .attachments-title { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #625848; margin-bottom: 10px; }
    .attachment-item { font-size: 13px; color: #A09070; padding: 4px 0; }
    .attachment-item::before { content: '📎 '; }
    .cta { margin: 28px 0 0; text-align: center; }
    .cta a { display: inline-block; background: #D4A853; color: #0C0B09; font-size: 14px; font-weight: 700; text-decoration: none; padding: 13px 34px; border-radius: 10px; letter-spacing: 0.01em; box-shadow: 0 4px 20px rgba(212,168,83,0.35); }
    .footer { text-align: center; font-size: 12px; color: #3A3028; margin-top: 20px; line-height: 1.8; }
    .footer a { color: #4A4035; text-decoration: none; }
</style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <!-- Logo: SVG s gold barvami — fallback: plain text v older clients -->
        <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 220 48" width="147" height="32" aria-label="BARANA">
            <polygon points="18,8 34,36 2,36" fill="none" stroke="#D4A853" stroke-width="2.5" stroke-linejoin="round"/>
            <line x1="2" y1="36" x2="34" y2="36" stroke="#D4A853" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="10" y1="36" x2="10" y2="44" stroke="#D4A853" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="26" y1="36" x2="26" y2="44" stroke="#D4A853" stroke-width="2.5" stroke-linecap="round"/>
            <text x="46" y="40" font-family="'Arial Black', Arial, sans-serif" font-size="34" font-weight="900" letter-spacing="-1" fill="#D4A853">BARANA</text>
        </svg>
        <h1>Nová poptávka</h1>
        <div class="header-meta">Přijato {{ now()->format('d. m. Y') }} v {{ now()->format('H:i') }}</div>
    </div>

    <div class="body">

        <div class="field">
            <div class="field-label">Jméno</div>
            <div class="field-value">{{ $data['jmeno'] }}</div>
        </div>

        <div class="field">
            <div class="field-label">E-mail</div>
            <div class="field-value">
                <a href="mailto:{{ $data['email'] }}" style="color:#D4A853; text-decoration:none;">{{ $data['email'] }}</a>
            </div>
        </div>

        @if (!empty($data['telefon']))
        <div class="field">
            <div class="field-label">Telefon</div>
            <div class="field-value">
                <a href="tel:{{ $data['telefon'] }}" style="color:#D4A853; text-decoration:none;">{{ $data['telefon'] }}</a>
            </div>
        </div>
        @endif

        @if (!empty($data['typ_poptavky']))
        <div class="field">
            <div class="field-label">Typ poptávky</div>
            <div class="field-value"><span class="badge">{{ $data['typ_poptavky'] }}</span></div>
        </div>
        @endif

        <hr class="divider">

        <div class="field">
            <div class="field-label">Zpráva</div>
            <div class="message-box">{{ $data['zprava'] }}</div>
        </div>

        @if (!empty($attachedFiles))
        <div class="attachments">
            <div class="attachments-title">Přílohy ({{ count($attachedFiles) }})</div>
            @foreach ($attachedFiles as $fileName)
            <div class="attachment-item">{{ $fileName }}</div>
            @endforeach
        </div>
        @endif

        <div class="cta">
            <a href="mailto:{{ $data['email'] }}?subject=Re%3A+Va%C5%A1e+popt%C3%A1vka+%E2%80%94+BARANA">
                Odpovědět na poptávku
            </a>
        </div>

    </div>

    <div class="footer">
        <p>Zpráva přišla z kontaktního formuláře na <a href="https://barana.cz">barana.cz</a></p>
        <p>Odpovězte přímo na tento e-mail — zpráva bude doručena odesílateli.</p>
    </div>

</div>
</body>
</html>
