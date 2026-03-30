<?php

// URL sitemapy pro itwebtech.cz
$sitemapUrl = "https://itwebtech.cz/sitemap.xml";

// API klíč pro IndexNow
$apiKey = "d585558e964a434b972dba0630c83161";

// Načti sitemap.xml
$sitemapXml = file_get_contents($sitemapUrl);
if (!$sitemapXml) {
    die("Chyba při načítání sitemapy.");
}

// Parsuj XML a extrahuj URL
$xml = simplexml_load_string($sitemapXml);
$urls = [];
foreach ($xml->url as $url) {
    $urls[] = (string) $url->loc;
}

// Připrav data pro API IndexNow
$data = [
    "host" => "itwebtech.cz",
    "key" => $apiKey,
    "keyLocation" => "https://itwebtech.cz/d585558e964a434b972dba0630c83161.txt",
    "urlList" => $urls
];

// Odešli požadavek na API IndexNow
$ch = curl_init("https://api.indexnow.org/IndexNow");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

$response = curl_exec($ch);
curl_close($ch);

echo "Odpověď API: " . $response;
