<?php

return [

    'meta' => [
        'title'       => 'Datenschutzerklärung — Ondřej Kriška',
        'description' => 'Informationen zur Verarbeitung personenbezogener Daten auf ondraweb.cz.',
    ],

    'heading' => 'Datenschutzerklärung',

    // OND-130 P2 iter 8 — Plan §3.1 Page-Mark Hero + TL;DR.
    // OND-135 Bereinigung (2026-05-14): page_mark_index entfernt per
    // Sitewide-Präzedenzfall (PR #78/#80/#82/#83).
    'hero' => [
        'page_mark_label' => 'DATENSCHUTZ',
        'upline'          => 'Klar, verständlich, DSGVO-konform.',
        'heading_html'    => 'Ihre Daten gehören <em>Ihnen</em>.',
        'subline'         => 'Was ich erhebe, warum ich es erhebe und wie ich es schütze. Ohne Juristensprache.',
    ],

    'tldr' => [
        'eyebrow' => 'Kurz gesagt',
        'items'   => [
            'Ich erhebe nur Daten, die Sie mir selbst übermitteln (Formular, E-Mail, Telefon).',
            'Ich gebe sie nicht an Dritte für Marketing weiter — sie dienen nur zur Antwort auf Ihre Anfrage.',
            'Webanalyse ist anonym (GA4 ohne Werbe-Cookies, Clarity).',
            'Sie können jederzeit Löschung beantragen: ok@ondraweb.cz.',
        ],
    ],

    // OND-284 — Text in die Ich-Form umgeschrieben (Audit-Befund OND-254).
    // Ergänzt: Microsoft Clarity, Anhänge, IP/User-Agent, Empfänger,
    // Rechtsgrundlage und Betroffenenrechte; entfernt, was die Website nicht tut.
    // Konflikt mit OND-266 (Anführungszeichen) zugunsten dieser Fassung gelöst —
    // die von OND-266 korrigierten Absätze entfallen hier vollständig.
    'content' =>'<p>Gültig ab 23. September 2026</p>

<p>Diese Website betreibe ich, Ondřej Kriška — Dunajovská 116, 691 81 Březí, Tschechien, Ident.-Nr. 19231407. Ich habe weder ein Team noch eine Vertriebsabteilung, und damit bin ich auch der Einzige, der Ihre Daten je zu sehen bekommt. Schreiben Sie mir jederzeit an <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>.</p>

<p>Unten lesen Sie, was ich über Sie weiß, woher ich es habe, warum ich es behalte und wie Sie das jederzeit stoppen.</p>

<h2>Was ich über Sie weiß</h2>

<h3>Was Sie mir selbst schicken</h3>
<p>Wenn Sie eines der Formulare auf dieser Website ausfüllen oder mir direkt schreiben, habe ich nur das, was Sie selbst eingetragen haben:</p>
<ul>
<li><strong>Name und E-Mail</strong> — ohne sie kann ich Ihnen nicht antworten,</li>
<li><strong>Telefon, Firma und ungefähres Budget</strong> — freiwillig; die Telefonnummer tragen Sie nur ein, wenn ich anrufen soll,</li>
<li><strong>Betreff und Nachricht</strong> — also das, was Sie mir schreiben,</li>
<li><strong>Anhänge</strong>, falls Sie welche anfügen — höchstens 5 Dateien, jede bis 10 MB und zusammen bis 20 MB.</li>
</ul>
<p>Mehr recherchiere ich nicht über Sie und ich kaufe nirgendwo Daten über Sie zu. Nach Rechnungsdaten frage ich erst, wenn wir uns auf eine Zusammenarbeit geeinigt haben und ich Ihnen eine Rechnung stellen soll.</p>

<h3>Was automatisch gespeichert wird</h3>
<p>Zu jeder abgeschickten Nachricht kommen Ihre IP-Adresse und die Angabe, welchen Browser Sie verwenden. Ich lese das nicht und erstelle daraus keine Profile — es ist nur eine Spur für den Fall, dass jemand das Formular zum Spam-Versand missbraucht.</p>
<p>Jede Nachricht wird außerdem in meiner Datenbank gespeichert und nicht nur per E-Mail verschickt. So geht Ihre Anfrage nicht verloren, wenn die Zustellung der Post einmal klemmt.</p>

<h3>Was die Analyse misst</h3>
<p>Solange Sie im Banner unten nicht auf „Alle akzeptieren“ klicken, wird nichts gemessen. Wenn Sie einwilligen, schalten sich zwei Werkzeuge ein:</p>
<ul>
<li><strong>Google Analytics 4</strong> — anonyme Besucherstatistik: wie viele Menschen auf die Website kommen, woher und welche Seiten sie interessieren.</li>
<li><strong>Microsoft Clarity</strong> — Heatmaps und Sitzungsaufzeichnungen: eine anonymisierte Aufzeichnung von Mausbewegungen und Klicks, an der ich erkenne, wo sich Besucher auf der Website verirren. Der Seiteninhalt ist in der Aufzeichnung maskiert, ich sehe also nicht, was Sie ins Formular tippen.</li>
</ul>
<p>Werbe-Cookies verwende ich nicht. In Google Analytics bleiben die Werbe-Einwilligungen <strong>ad_storage</strong>, <strong>ad_user_data</strong> und <strong>ad_personalization</strong> dauerhaft auf <strong>denied</strong> — das ist kein Schalter, den ich versehentlich umlegen könnte. Wenn Sie im Banner auf „Ablehnen“ klicken, werden die Mess-Skripte gar nicht erst geladen. Die genaue Liste der Cookies und ihrer Laufzeiten finden Sie auf der Seite <a href="/de/cookies">Cookies und Messung</a>.</p>

<h2>Warum ich es behalte</h2>
<ul>
<li><strong>Um Ihnen zu antworten.</strong> Das ist der ganze Grund, warum das Formular auf der Website steht.</li>
<li><strong>Um Ihnen eine Rechnung stellen zu können</strong>, falls wir zusammenarbeiten.</li>
<li><strong>Um das Formular vor Spam zu schützen</strong> — dafür ist die IP-Adresse da.</li>
<li><strong>Um zu wissen, was auf der Website funktioniert</strong> — dafür ist die Analyse da, und nur wenn Sie sie erlauben.</li>
</ul>
<p>Für etwas anderes verwende ich Ihre Daten nicht. Ich gebe sie nicht für Marketing weiter, verkaufe sie nicht und trage Sie in keinen Newsletter ein — ich habe keinen.</p>

<h3>Und jetzt die formale Seite</h3>
<ul>
<li>Ihre Nachricht verarbeite ich, um die <strong>Zusammenarbeit anbahnen</strong> zu können, nach der Sie gefragt haben (Art. 6 Abs. 1 lit. b DSGVO), und auf Grundlage der <strong>Einwilligung</strong>, die Sie neben dem Formular ankreuzen (Art. 6 Abs. 1 lit. a).</li>
<li>Rechnungen und Verträge bewahre ich auf, weil das <strong>Gesetz</strong> es mir vorschreibt (Art. 6 Abs. 1 lit. c).</li>
<li>Die IP-Adresse zu einer abgeschickten Nachricht halte ich auf Grundlage meines <strong>berechtigten Interesses</strong>, das Formular vor Missbrauch zu schützen (Art. 6 Abs. 1 lit. f).</li>
<li>Die Analyse schalte ich nur mit <strong>Ihrer Einwilligung</strong> ein (Art. 6 Abs. 1 lit. a), und die können Sie jederzeit widerrufen.</li>
</ul>

<h2>Wie lange es bei mir bleibt</h2>
<ul>
<li><strong>Eine Anfrage, aus der nichts wurde</strong> — die behalte ich, solange einer von uns beiden noch darauf zurückkommen könnte. Sobald klar ist, dass nichts daraus wird, lösche ich sie. Und wenn Sie mir schreiben, lösche ich sie sofort.</li>
<li><strong>Eine Anfrage, aus der ein Projekt wurde</strong> — die bleibt beim Projekt, solange das Projekt läuft, und danach so lange, wie einer von uns auf das Vereinbarte zurückkommen könnte.</li>
<li><strong>Rechnungen und Buchhaltungsbelege</strong> — hier entscheide nicht ich, die Frist gibt das tschechische Buchhaltungsgesetz vor. Bis sie abgelaufen ist, kann ich sie auch auf Ihren Wunsch nicht löschen.</li>
<li><strong>Anhänge aus dem Formular</strong> — sie liegen im nicht öffentlichen Teil des Servers, sind über keinen Link erreichbar, und für sie gilt dasselbe wie für die Anfrage, zu der sie gehören.</li>
<li><strong>Ihre Einwilligung zur Messung</strong> — „Alle akzeptieren“ merkt sich Ihr Browser 365 Tage, „Ablehnen“ 180 Tage. Danach fragt das Banner erneut.</li>
<li><strong>Analyse-Cookies</strong> — Google Analytics standardmäßig 2 Jahre, Microsoft Clarity nach den Einstellungen von Microsoft, typischerweise ein Jahr.</li>
</ul>

<h2>Wer sonst noch an Ihre Daten kommt</h2>
<p>Ich verkaufe sie nicht und gebe sie an niemanden für Marketing weiter. Technischen Zugang haben allerdings einige Unternehmen, auf deren Diensten die Website und mein Postfach laufen:</p>
<ul>
<li><strong>Hetzner</strong> (Deutschland) — der Server, auf dem Website und Datenbank laufen.</li>
<li><strong>Seznam.cz</strong> — betreibt das Postfach ok@ondraweb.cz, in dem Ihre Anfragen ankommen.</li>
<li><strong>Google</strong> — Google Analytics, und nur wenn Sie die Messung erlauben.</li>
<li><strong>Microsoft</strong> — Clarity, und nur wenn Sie die Messung erlauben.</li>
</ul>
<p>Google und Microsoft verarbeiten einen Teil der Daten außerhalb der Europäischen Union. Wenn Ihnen das nicht recht ist, klicken Sie im Banner auf „Ablehnen“ — dann kommt keiner von beiden an Sie heran.</p>

<h2>Was Sie dagegen tun können</h2>
<p>Es sind Ihre Daten, also entscheiden Sie. Schreiben Sie mir jederzeit an <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a> und verlangen Sie, dass ich:</p>
<ul>
<li>Ihnen sage, was ich über Sie habe — ich stelle es zusammen und schicke es,</li>
<li>korrigiere, was bei Ihnen falsch ist,</li>
<li>lösche, was ich über Sie habe — außer dem, was ich für die Buchhaltung behalten muss,</li>
<li>die Verarbeitung einschränke oder sie beende, wenn Sie Widerspruch einlegen,</li>
<li>Ihnen Ihre Daten in einem Format schicke, mit dem Sie zu jemand anderem gehen können.</li>
</ul>
<p>Ich melde mich innerhalb von 30 Tagen, meist deutlich früher. Ich verlange von Ihnen kein Formular und keine beglaubigte Unterschrift — eine E-Mail von der Adresse, von der aus Sie mir geschrieben haben, genügt.</p>
<p>Ihre Einwilligung zur Messung widerrufen Sie mit einem Klick auf der Seite <a href="/de/cookies">Cookies und Messung</a> oder indem Sie die Cookies im Browser löschen.</p>
<p>Sollten Sie das Gefühl haben, dass ich mit Ihren Daten schlecht umgehe, schreiben Sie mir bitte zuerst — meist ist es ein Missverständnis, das ich sofort ausräume. Wenn ich es nicht ausräume, können Sie sich beim tschechischen Amt für Datenschutz (uoou.cz) oder bei der Aufsichtsbehörde in Ihrem Land beschweren.</p>

<h2>Wie ich es absichere</h2>
<p>Die Website läuft über HTTPS, Anhänge liegen außerhalb des öffentlichen Teils des Servers, und an die Datenbank komme nur ich. Ihnen zu versprechen, dass nie etwas passiert, wäre albern — das verspricht Ihnen niemand ehrlich. Versprechen kann ich Ihnen aber: Wenn etwas passieren sollte und es ein Risiko für Sie wäre, erfahren Sie es von mir und nicht aus den Nachrichten.</p>

<h2>Kinder</h2>
<p>Ich baue Websites für Firmen und Selbstständige. Diese Website richtet sich nicht an Kinder, und ich erhebe wissentlich keine Daten von Personen unter 18 Jahren. Sollte ein Kind mir seine Daten trotzdem schicken, schreiben Sie mir und ich lösche sie.</p>

<h2>Wenn ich diesen Text ändere</h2>
<p>Diese Erklärung passe ich von Zeit zu Zeit an — etwa wenn auf der Website ein neues Werkzeug dazukommt. Die neue Fassung erscheint hier und oben ändert sich das Datum. Geht es um eine Änderung, die Sie wirklich betrifft, frage ich erneut nach Ihrer Einwilligung.</p>

<h2>Kontakt</h2>
<p>Ondřej Kriška, Dunajovská 116, 691 81 Březí, Tschechien, Ident.-Nr. 19231407<br>E-Mail: <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a></p>
<p>Einen Datenschutzbeauftragten habe ich nicht — dafür ist mein Betrieb viel zu klein. Sie schreiben direkt an mich.</p>',

];
