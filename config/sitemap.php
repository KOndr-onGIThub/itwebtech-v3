<?php

return [
    'cache_ttl' => env('SITEMAP_CACHE_TTL', 600), // 10 min
    'cache_key' => 'sitemap.xml',
    'defaults' => [
        'priority' => [
            'home'     => 1.0,
            'projects' => 0.9,
            'project'  => 0.8,
            'blog'     => 0.7,
            'article'  => 0.6,
            'contact'  => 0.6,
            'price'    => 0.6,
            'privacy'  => 0.4,
            'fallback' => 0.5,
        ],
        'changefreq' => [
            'home'     => 'weekly',
            'projects' => 'weekly',
            'project'  => 'monthly',
            'blog'     => 'weekly',
            'article'  => 'monthly',
            'fallback' => 'monthly',
        ],
    ],
];
