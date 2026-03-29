<!DOCTYPE html>
<html lang="cs">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { font-family: 'Inter', Arial, Helvetica, sans-serif; background: #0C0B09; margin: 0; padding: 0; }
    .wrapper { max-width: 600px; margin: 0 auto; padding: 24px 16px; }

    /* Header */
    .header { background: #131210; padding: 36px 32px 30px; border-radius: 12px 12px 0 0; border: 1px solid rgba(212,168,83,0.18); border-bottom: none; text-align: center; }
    .logo { display: block; margin: 0 auto 22px; }
    .check-icon-wrap { margin-bottom: 16px; }
    .check-icon-cell { width: 48px; height: 48px; border-radius: 50%; background: rgba(212,168,83,0.12); border: 1.5px solid rgba(212,168,83,0.30); font-size: 20px; color: #D4A853; text-align: center; vertical-align: middle; }
    .header h1 { margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.025em; color: #EAE7E1; }
    .header p { margin: 8px 0 0; font-size: 14px; color: rgba(234,231,225,0.45); line-height: 1.6; }

    /* Body */
    .body { background: #1A1916; padding: 32px; border-radius: 0 0 12px 12px; border: 1px solid rgba(212,168,83,0.12); border-top: none; }
    .intro { font-size: 15px; color: #A09070; line-height: 1.7; margin-bottom: 28px; }
    .intro strong { color: #EAE7E1; font-weight: 600; }
    .section-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.12em; color: #625848; margin-bottom: 12px; }

    /* Summary table */
    .summary { background: #131210; border: 1px solid rgba(255,255,255,0.06); border-radius: 10px; overflow: hidden; margin-bottom: 28px; }
    .summary-row { border-bottom: 1px solid rgba(255,255,255,0.05); }
    .summary-row:last-child { border-bottom: none; }
    .summary-row td { padding: 11px 18px; vertical-align: top; }
    .summary-key { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #625848; white-space: nowrap; width: 100px; }
    .summary-val { font-size: 13px; color: #C8C4BC; line-height: 1.6; word-break: break-word; }
    .summary-val.message { white-space: pre-wrap; color: #A09070; }
    .badge { display: inline-block; background: rgba(212,168,83,0.10); color: #D4A853; font-size: 12px; font-weight: 600; padding: 3px 10px; border-radius: 6px; border: 1px solid rgba(212,168,83,0.22); letter-spacing: 0.01em; word-break: break-word; white-space: normal; }

    /* Steps */
    .steps { margin-bottom: 28px; }
    .step { margin-bottom: 12px; }
    .step td { vertical-align: middle; padding: 0; }
    .step-num-td { width: 42px; padding-right: 14px; text-align: center; }
    .step-num { width: 28px; height: 28px; border-radius: 50%; background: rgba(212,168,83,0.10); border: 1px solid rgba(212,168,83,0.22); color: #D4A853; font-size: 12px; font-weight: 700; text-align: center; vertical-align: middle; }
    .step-text { font-size: 14px; color: #A09070; line-height: 1.65; }
    .step-text strong { color: #C8C4BC; font-weight: 600; }

    .divider { border: none; border-top: 1px solid rgba(255,255,255,0.06); margin: 24px 0; }

    .contact-note { text-align: center; font-size: 13px; color: #625848; line-height: 1.7; }
    .contact-note a { color: #D4A853; text-decoration: none; font-weight: 500; }

    .footer { text-align: center; font-size: 12px; color: #7A6B5C; margin-top: 20px; line-height: 1.8; }
    .footer a { color: #9A8878; text-decoration: none; }
</style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <svg class="logo" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 220 48" width="147" height="32" aria-label="BARANA">
            <polygon points="18,8 34,36 2,36" fill="none" stroke="#D4A853" stroke-width="2.5" stroke-linejoin="round"/>
            <line x1="2" y1="36" x2="34" y2="36" stroke="#D4A853" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="10" y1="36" x2="10" y2="44" stroke="#D4A853" stroke-width="2.5" stroke-linecap="round"/>
            <line x1="26" y1="36" x2="26" y2="44" stroke="#D4A853" stroke-width="2.5" stroke-linecap="round"/>
            <text x="46" y="40" font-family="'Arial Black', Arial, sans-serif" font-size="34" font-weight="900" letter-spacing="-1" fill="#D4A853">BARANA</text>
        </svg>
        <table class="check-icon-wrap" cellpadding="0" cellspacing="0" style="margin: 0 auto 16px;">
            <tr><td class="check-icon-cell">✓</td></tr>
        </table>
        <h1>Poptávka přijata</h1>
        <p>Vaše zpráva nám dorazila. Ozveme se vám co nejdříve.</p>
    </div>

    <div class="body">

        <p class="intro">
            Dobrý den, <strong>{{ $data['jmeno'] }}</strong>,<br>
            děkujeme za vaši poptávku. Níže najdete shrnutí toho, co jste nám zaslali.
        </p>

        <div class="section-label">Vaše poptávka</div>
        <table class="summary" width="100%" cellpadding="0" cellspacing="0">
            @if (!empty($data['typ_poptavky']))
            <tr class="summary-row">
                <td class="summary-key">Typ</td>
                <td class="summary-val"><span class="badge">{{ $data['typ_poptavky'] }}</span></td>
            </tr>
            @endif
            @if (!empty($data['telefon']))
            <tr class="summary-row">
                <td class="summary-key">Telefon</td>
                <td class="summary-val">{{ $data['telefon'] }}</td>
            </tr>
            @endif
            <tr class="summary-row">
                <td class="summary-key">Zpráva</td>
                <td class="summary-val message">{{ $data['zprava'] }}</td>
            </tr>
        </table>

        <div class="section-label">Co se děje dál?</div>
        <div class="steps">
            <table class="step" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
                <tr>
                    <td class="step-num-td" valign="middle" width="42">
                        <table cellpadding="0" cellspacing="0" style="margin:0 auto;"><tr>
                            <td class="step-num" width="28" height="28" align="center" valign="middle">1</td>
                        </tr></table>
                    </td>
                    <td class="step-text" valign="middle"><strong>Projdeme vaši poptávku</strong> a posoudíme, jak vám můžeme nejlépe pomoct.</td>
                </tr>
            </table>
            <table class="step" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom:12px;">
                <tr>
                    <td class="step-num-td" valign="middle" width="42">
                        <table cellpadding="0" cellspacing="0" style="margin:0 auto;"><tr>
                            <td class="step-num" width="28" height="28" align="center" valign="middle">2</td>
                        </tr></table>
                    </td>
                    <td class="step-text" valign="middle"><strong>Ozveme se vám</strong> na tento e-mail nebo na telefon, který jste uvedli — zpravidla do 1–2 pracovních dnů.</td>
                </tr>
            </table>
            <table class="step" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                    <td class="step-num-td" valign="middle" width="42">
                        <table cellpadding="0" cellspacing="0" style="margin:0 auto;"><tr>
                            <td class="step-num" width="28" height="28" align="center" valign="middle">3</td>
                        </tr></table>
                    </td>
                    <td class="step-text" valign="middle"><strong>Domluvíme se na dalším postupu</strong> a připravíme pro vás konkrétní řešení.</td>
                </tr>
            </table>
        </div>

        <hr class="divider">

        <p class="contact-note">
            Máte otázku nebo chcete poptávku doplnit?<br>
            Odpovězte přímo na tento e-mail nebo nás kontaktujte na <a href="mailto:info@barana.cz">info@barana.cz</a>
        </p>

    </div>

    <div class="footer">
        <p>Tuto zprávu jste obdrželi, protože jste vyplnili formulář na <a href="https://barana.cz">barana.cz</a></p>
    </div>

</div>
</body>
</html>
