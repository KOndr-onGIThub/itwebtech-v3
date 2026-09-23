<?php

return [

    'meta' => [
        'title'       => 'Cookies and consent — ondraweb.cz',
        'description' => 'Information about the cookies and analytics tools I use on ondraweb.cz, and how to revoke your consent at any time.',
    ],

    'hero' => [
        'page_mark_label' => 'COOKIES & ANALYTICS',
        'upline'          => 'No ad cookies. No data selling.',
        'heading_html'    => 'What I measure and <em>why</em> I do it.',
        'subline'         => 'Anonymous traffic statistics — so I know what works. No ad targeting, no third-party brokers.',
    ],

    'tldr' => [
        'eyebrow' => 'In short',
        'items'   => [
            'I only measure anonymous traffic (GA4) and anonymised heatmaps (Clarity).',
            'No advertising cookies or ad targeting — <code>ad_storage</code> is permanently <code>denied</code>.',
            'You can revoke your consent any time using the button below or by clearing cookies in your browser.',
        ],
    ],

    'intro' => 'This page summarises the cookies and analytics tools used on <strong>ondraweb.cz</strong>, what they are for, and how to revoke your consent to their use at any time.',

    'what_we_use' => [
        'heading' => 'What I use',
        'items'   => [
            '<strong>Google Analytics 4 (GA4)</strong> — anonymous traffic statistics that show how many people visit the site, where they come from, and which sections engage them.',
            '<strong>Microsoft Clarity</strong> — heatmaps and session recordings (with anonymised content) that help spot where visitors struggle to find what they are looking for.',
        ],
        'note'    => 'I do not use any advertising cookies or ad targeting. In GA4, the advertising consents (<code>ad_storage</code>, <code>ad_user_data</code>, <code>ad_personalization</code>) remain permanently set to <code>denied</code>.',
    ],

    'what_we_measure' => [
        'heading' => 'What I measure',
        'items'   => [
            'Traffic and sources (where visitors come from, how many pages they view, how long they stay).',
            'Interactions with primary CTAs — clicks on “Get a quote”, the phone number, opening the form, submitting an inquiry.',
            'Session recordings (Clarity) — anonymised video capture of cursor movement and clicks so I can spot places where visitors get lost.',
        ],
    ],

    'retention' => [
        'heading' => 'Retention period',
        'items'   => [
            '“Accept all” consent — stored in the browser (<code>localStorage</code>) for <strong>365 days</strong>, after which you will be asked again.',
            '“Decline” — stored for <strong>180 days</strong>; during this time the banner will not ask again and no analytics cookies are set.',
            'GA4 cookies (<code>_ga</code>, <code>_ga_*</code>) — typically 2 years (only if you grant consent).',
            'Microsoft Clarity cookies (<code>_clck</code>, <code>_clsk</code>, <code>MUID</code>, <code>CLID</code>) — as configured by Microsoft (typically 1 year).',
        ],
    ],

    'revoke' => [
        'heading'      => 'How to revoke consent',
        'description'  => 'If you want to revoke your consent, click the button below. It clears the stored consent and any GA / Clarity cookies, and after reloading the page the banner will reappear.',
        'button'       => 'Revoke consent and clear cookies',
        'manual'       => 'Alternatively, you can clear cookies for the <code>ondraweb.cz</code> domain manually in your browser settings.',
    ],

    'controller' => [
        'heading' => 'Data controller',
        'name'    => 'Ondřej Kriška – ONDRAWEB',
        'email_label' => 'Email',
        'see_privacy_html' => 'For more detailed information about personal-data processing, see the :link.',
        'see_privacy_link' => 'privacy policy',
    ],

];
