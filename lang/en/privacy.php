<?php

return [

    'meta' => [
        'title'       => 'Privacy Policy — Ondřej Kriška',
        'description' => 'Information about the processing of personal data on ondraweb.cz.',
    ],

    'heading' => 'Privacy Policy',

    // OND-130 P2 iter 8 — plán §3.1 page-mark hero + TL;DR.
    // OND-135 cleanup (2026-05-14): page_mark_index removed per sitewide
    // precedent (PR #78/#80/#82/#83).
    'hero' => [
        'page_mark_label' => 'PRIVACY POLICY',
        'upline'          => 'Plain English, GDPR-grade.',
        'heading_html'    => 'Your data is <em>yours</em>.',
        'subline'         => 'What I collect, why I collect it, and how I protect it. No legalese.',
    ],

    'tldr' => [
        'eyebrow' => 'In short',
        'items'   => [
            'I only collect data you send me (form, email, phone).',
            'I never share it with third parties for marketing — it is used only to reply to your inquiry.',
            'Web analytics is anonymous (GA4 without ad cookies, Clarity).',
            'You can request deletion any time: ok@ondraweb.cz.',
        ],
    ],

    // OND-284 — body rewritten into first person (audit finding OND-254).
    // Added Microsoft Clarity, attachments, IP/user agent, recipients,
    // legal basis and data subject rights; removed what the site does not do.
    'content' =>'<p>Effective from 23 September 2026</p>

<p>This website is run by me, Ondřej Kriška — Dunajovská 116, 691 81 Březí, Czech Republic, company ID 19231407. I have no team and no sales department, which also means I am the only person who ever sees your data. You can write to me any time at <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>.</p>

<p>Below you will find what I know about you, where I got it, why I keep it, and how you can stop it at any time.</p>

<h2>What I know about you</h2>

<h3>What you send me yourself</h3>
<p>When you fill in one of the forms on this site, or simply email me, all I have is what you filled in yourself:</p>
<ul>
<li><strong>name and email</strong> — without them I have no way to reply,</li>
<li><strong>phone, company and rough budget</strong> — optional; fill in your phone only if you want me to call,</li>
<li><strong>subject and message</strong> — that is, whatever you write to me,</li>
<li><strong>attachments</strong>, if you add any — up to 5 files, each under 10 MB and 20 MB in total.</li>
</ul>
<p>I do not look up anything else about you and I do not buy data about you anywhere. I only ask for billing details once we agree to work together and I need to invoice you.</p>

<h3>What gets stored automatically</h3>
<p>Your IP address and a note about which browser you use are attached to every message sent. I do not read it and I do not build profiles from it — it is only a trace in case someone abuses the form to send spam.</p>
<p>Every message is also stored in my database, not just sent by email. That is so your inquiry does not get lost if mail delivery plays up.</p>

<h3>What analytics measure</h3>
<p>Until you click “Accept all” in the bar at the bottom, nothing is measured. Once you do consent, two tools switch on:</p>
<ul>
<li><strong>Google Analytics 4</strong> — anonymous traffic statistics: how many people come to the site, where from, and which pages interest them.</li>
<li><strong>Microsoft Clarity</strong> — heatmaps and session recordings: an anonymised recording of cursor movement and clicks that shows me where people get lost on the site. Page content is masked in the recording, so I cannot see what you type into a form.</li>
</ul>
<p>I do not use advertising cookies. In Google Analytics the advertising consents <strong>ad_storage</strong>, <strong>ad_user_data</strong> and <strong>ad_personalization</strong> stay permanently set to <strong>denied</strong> — it is not a switch I could flip by accident. If you click “Decline” in the bar, the measurement scripts are never loaded at all. The exact list of cookies and how long they last is on the <a href="/en/cookies">Cookies and analytics</a> page.</p>

<h2>Why I keep it</h2>
<ul>
<li><strong>To reply to you.</strong> That is the whole reason the form is on the site.</li>
<li><strong>To invoice you</strong>, if we agree to work together.</li>
<li><strong>To protect the form from spam</strong> — that is what the IP address is for.</li>
<li><strong>To know what works on the site</strong> — that is what analytics are for, and only if you allow them.</li>
</ul>
<p>I do not use your data for anything else. I do not pass it on for marketing, I do not sell it, and I do not add you to any newsletter — I do not have one.</p>

<h3>And now the formal side of it</h3>
<ul>
<li>I process your message so that I can <strong>take steps towards the cooperation</strong> you asked me about (Art. 6(1)(b) GDPR) and on the basis of the <strong>consent</strong> you tick next to the form (Art. 6(1)(a)).</li>
<li>I keep invoices and contracts because the <strong>law requires me to</strong> (Art. 6(1)(c)).</li>
<li>I hold the IP address attached to a message on the basis of my <strong>legitimate interest</strong> in protecting the form from abuse (Art. 6(1)(f)).</li>
<li>I switch analytics on only with <strong>your consent</strong> (Art. 6(1)(a)), and you can withdraw it at any time.</li>
</ul>

<h2>How long it stays with me</h2>
<ul>
<li><strong>An inquiry that came to nothing</strong> — I keep it for as long as either of us might still come back to it. Once it is clear nothing will come of it, I delete it. And if you write to me, I delete it straight away.</li>
<li><strong>An inquiry that turned into a project</strong> — it stays with the project for as long as the project runs, and then for as long as either of us might need to go back to what was agreed.</li>
<li><strong>Invoices and accounting records</strong> — this one is not up to me, the period is set by Czech accounting law. Until it runs out, I cannot delete them even at your request.</li>
<li><strong>Form attachments</strong> — they sit on the non-public part of the server, are not reachable through any link, and the same rules apply to them as to the inquiry they belong to.</li>
<li><strong>Your measurement consent</strong> — your browser remembers “Accept all” for 365 days and “Decline” for 180 days. After that the bar asks again.</li>
<li><strong>Analytics cookies</strong> — Google Analytics 2 years by default, Microsoft Clarity according to Microsoft\'s settings, typically a year.</li>
</ul>

<h2>Who else gets to your data</h2>
<p>I do not sell it and I do not pass it to anyone for marketing. A handful of companies do have technical access to it, because the site and my mailbox run on their services:</p>
<ul>
<li><strong>Hetzner</strong> (Germany) — the server the site and the database run on.</li>
<li><strong>Seznam.cz</strong> — runs the ok@ondraweb.cz mailbox that your inquiries arrive in.</li>
<li><strong>Google</strong> — Google Analytics, and only if you allow measurement.</li>
<li><strong>Microsoft</strong> — Clarity, and only if you allow measurement.</li>
</ul>
<p>Google and Microsoft process some of the data outside the European Union. If you are not comfortable with that, click “Decline” in the bar — neither of them will reach you then.</p>

<h2>What you can do about it</h2>
<p>It is your data, so you decide. Write to me at <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a> any time and ask me to:</p>
<ul>
<li>tell you what I hold about you — I will write it up and send it,</li>
<li>correct anything that is wrong,</li>
<li>delete what I hold about you — except what I have to keep for accounting,</li>
<li>restrict the processing, or stop it if you object to it,</li>
<li>send you your data in a format you can take elsewhere.</li>
</ul>
<p>I will get back to you within 30 days, usually much sooner. I will not ask you for a form or a certified signature — an email from the address you wrote to me from is enough.</p>
<p>You can withdraw your measurement consent with one click on the <a href="/en/cookies">Cookies and analytics</a> page, or by clearing cookies in your browser.</p>
<p>If you ever feel I am handling your data badly, please write to me first — it will usually be a misunderstanding I can fix right away. If I do not fix it, you can complain to the Czech Data Protection Authority (uoou.cz) or to the supervisory authority in your own country.</p>

<h2>How I keep it safe</h2>
<p>The site runs over HTTPS, attachments sit outside the public part of the server, and I am the only one with access to the database. Promising you that nothing will ever go wrong would be silly — nobody can honestly promise that. What I can promise is that if something did go wrong and it put you at risk, you would hear it from me and not from the news.</p>

<h2>Children</h2>
<p>I build websites for companies and sole traders. This site is not aimed at children and I do not knowingly collect data from anyone under 18. If a child did send me their data anyway, write to me and I will delete it.</p>

<h2>When I change this text</h2>
<p>I will update these terms from time to time — for instance when a new tool is added to the site. The new version appears here and the effective date at the top changes. If it is a change that genuinely affects you, I will ask for your consent again.</p>

<h2>Contact</h2>
<p>Ondřej Kriška, Dunajovská 116, 691 81 Březí, Czech Republic, company ID 19231407<br>Email: <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a></p>
<p>I do not have a data protection officer — my business is far too small for that. You are writing directly to me.</p>',

];
