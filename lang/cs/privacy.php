<?php

return [

    'meta' => [
        'title'       => 'Zásady ochrany osobních údajů — Ondřej Kriška',
        'description' => 'Informace o zpracování osobních údajů na webu ondraweb.cz.',
    ],

    'heading' => 'Zásady ochrany osobních údajů',

    // OND-130 P2 iter 8 — plán §3.1 page-mark hero + TL;DR (plain-language summary).
    // OND-135 cleanup (2026-05-14): page_mark_index odebrán per sitewide
    // precedent (PR #78/#80/#82/#83).
    'hero' => [
        'page_mark_label' => 'OCHRANA OSOBNÍCH ÚDAJŮ',
        'upline'          => 'Stručně, srozumitelně, GDPR.',
        'heading_html'    => 'Vaše data jsou <em>vaše</em>.',
        'subline'         => 'Co sbíráme, proč to sbíráme a jak to ochráním. Bez právničtiny.',
    ],

    'tldr' => [
        'eyebrow' => 'V kostce',
        'items'   => [
            'Sbírám jen údaje, které mi sami pošlete (formulář, e-mail, telefon).',
            'Nepředávám je třetím stranám pro marketing — slouží jen k odpovědi na poptávku.',
            'Webová analytika je anonymní (GA4 bez reklamních cookies, Clarity).',
            'Kdykoli můžete požádat o výmaz: ok@ondraweb.cz.',
        ],
    ],

    // OND-284 — tělo stránky přepsáno do ich-formy (nález auditu OND-254).
    // Doplněn Microsoft Clarity, přílohy, IP/user agent, seznam příjemců,
    // právní základ a práva subjektu; odstraněno, co web nedělá.
    // Konflikt s OND-266 (oprava uvozovek) vyřešen ve prospěch tohoto znění —
    // odstavce, které OND-266 opravoval, tenhle přepis ruší celé.
    'content' =>'<p>Účinné od 23. září 2026</p>

<p>Tenhle web provozuju já, Ondřej Kriška — Dunajovská 116, 691 81 Březí, IČO 19231407. Nemám tým ani obchodní oddělení, takže jsem zároveň jediný, kdo se k vašim údajům dostane. Napsat mi můžete kdykoli na <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a>.</p>

<p>Níž se dočtete, co o vás vím, odkud to mám, proč si to nechávám a jak to kdykoli zastavíte.</p>

<h2>Co o vás vím</h2>

<h3>Co mi pošlete sami</h3>
<p>Když vyplníte některý z formulářů na webu nebo mi rovnou napíšete, mám o vás jen to, co jste sami vyplnili:</p>
<ul>
<li><strong>jméno a e-mail</strong> — bez nich vám nemám jak odpovědět,</li>
<li><strong>telefon, firmu a orientační rozpočet</strong> — nepovinné; telefon vyplňte jen tehdy, když chcete, abych volal,</li>
<li><strong>předmět a text zprávy</strong> — tedy to, co mi sami napíšete,</li>
<li><strong>přílohy</strong>, pokud je připojíte — nejvýš 5 souborů, každý do 10 MB a dohromady do 20 MB.</li>
</ul>
<p>Nic dalšího si o vás nedohledávám a data o vás nikde nekupuju. Fakturační údaje po vás chci až ve chvíli, kdy se domluvíme na spolupráci a mám vám vystavit fakturu.</p>

<h3>Co se uloží samo</h3>
<p>Ke každé odeslané zprávě se přidá vaše IP adresa a údaj o tom, jaký používáte prohlížeč. Nečtu to a nedělám z toho žádné profily — je to jen stopa pro případ, že by někdo formulář zneužil k rozesílání spamu.</p>
<p>Každá zpráva se zároveň uloží u mě v databázi, ne jen odešle e-mailem. Je to proto, abych o vaši poptávku nepřišel, kdyby zlobilo doručování pošty.</p>

<h3>Co měří analytika</h3>
<p>Dokud v liště dole neklepnete na „Přijmout vše“, neměří se nic. Když souhlas dáte, zapnou se dva nástroje:</p>
<ul>
<li><strong>Google Analytics 4</strong> — anonymní statistika návštěvnosti: kolik lidí na web přijde, odkud a které stránky je zajímají.</li>
<li><strong>Microsoft Clarity</strong> — heatmapy a nahrávky relací: anonymizovaný záznam pohybu kurzoru a kliků, ze kterého poznám, kde na webu lidi bloudí. Obsah stránky je v nahrávce maskovaný, takže co píšete do formuláře, v ní nevidím.</li>
</ul>
<p>Reklamní cookies nepoužívám. V Google Analytics zůstávají reklamní souhlasy <strong>ad_storage</strong>, <strong>ad_user_data</strong> a <strong>ad_personalization</strong> trvale na hodnotě <strong>denied</strong> — nejde o přepínač, na který bych mohl omylem sáhnout. Když v liště kliknete na „Odmítnout“, měřicí skripty se vůbec nenačtou. Přesný výčet cookies i dob jejich platnosti je na stránce <a href="/cookies">Cookies a měření</a>.</p>

<h2>Proč si to nechávám</h2>
<ul>
<li><strong>Abych vám odpověděl.</strong> To je celý důvod, proč na webu formulář je.</li>
<li><strong>Abych vám mohl vystavit fakturu</strong>, pokud se domluvíme na spolupráci.</li>
<li><strong>Abych formulář ubránil před spamem</strong> — k tomu slouží ta IP adresa.</li>
<li><strong>Abych věděl, co na webu funguje</strong> — k tomu je analytika, a jen když ji povolíte.</li>
</ul>
<p>K ničemu jinému vaše údaje nepoužívám. Nepředávám je dál pro marketing, neprodávám je a nepřidávám vás do žádného newsletteru — žádný nemám.</p>

<h3>Ať to má i tu formální stránku</h3>
<ul>
<li>Zprávu z formuláře zpracovávám proto, abych mohl <strong>jednat o spolupráci</strong>, o kterou jste mě požádali (čl. 6 odst. 1 písm. b GDPR), a na základě <strong>souhlasu</strong>, který zaškrtáváte u formuláře (čl. 6 odst. 1 písm. a).</li>
<li>Faktury a smlouvy si nechávám proto, že mi to <strong>ukládá zákon</strong> (čl. 6 odst. 1 písm. c).</li>
<li>IP adresu u odeslané zprávy držím na základě <strong>oprávněného zájmu</strong> ubránit formulář před zneužitím (čl. 6 odst. 1 písm. f).</li>
<li>Analytiku zapínám jen s <strong>vaším souhlasem</strong> (čl. 6 odst. 1 písm. a), a ten můžete kdykoli vzít zpět.</li>
</ul>

<h2>Jak dlouho to u mě zůstane</h2>
<ul>
<li><strong>Poptávka, ze které nakonec nic nebylo</strong> — nechávám si ji, dokud se k ní ještě může někdo z nás vrátit. Jakmile je jasné, že z toho nic nebude, mažu ji. A když mi napíšete, smažu ji hned.</li>
<li><strong>Poptávka, ze které vznikla spolupráce</strong> — zůstává u projektu, dokud projekt běží, a pak po dobu, po kterou se oba můžeme potřebovat vrátit k tomu, co bylo dohodnuté.</li>
<li><strong>Faktury a účetní doklady</strong> — tady nerozhoduju já, dobu určuje zákon o účetnictví. Dokud neuplyne, nemůžu je smazat ani na vaši žádost.</li>
<li><strong>Přílohy z formuláře</strong> — leží na neveřejné části serveru, nejsou dostupné přes žádný odkaz a platí pro ně totéž co pro poptávku, ke které patří.</li>
<li><strong>Souhlas s měřením</strong> — „Přijmout vše“ si prohlížeč pamatuje 365 dnů, „Odmítnout“ 180 dnů. Pak se lišta zeptá znovu.</li>
<li><strong>Cookies analytiky</strong> — Google Analytics standardně 2 roky, Microsoft Clarity podle nastavení Microsoftu, typicky rok.</li>
</ul>

<h2>Kdo se k vašim údajům ještě dostane</h2>
<p>Neprodávám je a nepředávám je nikomu pro marketing. Technicky se k nim ale dostane pár firem, na jejichž službách web a moje pošta stojí:</p>
<ul>
<li><strong>Hetzner</strong> (Německo) — server, na kterém web i databáze běží.</li>
<li><strong>Seznam.cz</strong> — provozuje schránku ok@ondraweb.cz, do které mi poptávky chodí.</li>
<li><strong>Google</strong> — Google Analytics, a jen pokud měření povolíte.</li>
<li><strong>Microsoft</strong> — Clarity, a jen pokud měření povolíte.</li>
</ul>
<p>Google i Microsoft zpracovávají část dat mimo Evropskou unii. Pokud vám to vadí, klepněte v liště na „Odmítnout“ — ani jeden z nich se pak k vám nedostane.</p>

<h2>Co s tím můžete udělat</h2>
<p>Jsou to vaše data, takže rozhodujete vy. Kdykoli mi napište na <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a> a chtějte, abych:</p>
<ul>
<li>vám řekl, co o vás mám — sepíšu to a pošlu,</li>
<li>opravil, co je u vás špatně,</li>
<li>smazal, co o vás mám — kromě toho, co si musím nechat kvůli účetnictví,</li>
<li>omezil zpracování nebo abych přestal, když proti němu vznesete námitku,</li>
<li>vám vaše údaje poslal ve formátu, se kterým odejdete jinam.</li>
</ul>
<p>Ozvu se do 30 dnů, obvykle mnohem dřív. Nechci po vás žádný formulář ani ověřený podpis — stačí e-mail z adresy, ze které jste mi psali.</p>
<p>Souhlas s měřením odvoláte jedním klepnutím na stránce <a href="/cookies">Cookies a měření</a> nebo smazáním cookies v prohlížeči.</p>
<p>Kdybyste měli pocit, že s vašimi údaji zacházím špatně, napište mi prosím první — většinou půjde o nedorozumění, které spravím obratem. Když to nespravím, můžete si stěžovat u Úřadu pro ochranu osobních údajů (uoou.cz).</p>

<h2>Jak to mám zabezpečené</h2>
<p>Web běží na HTTPS, přílohy leží mimo veřejnou část serveru a do databáze se dostanu jen já. Slibovat vám, že se nikdy nic nestane, by bylo hloupé — to vám nikdo poctivě neslíbí. Slíbit vám ale můžu, že kdyby se něco stalo a bylo to pro vás riziko, dozvíte se to ode mě, a ne z novin.</p>

<h2>Děti</h2>
<p>Weby dělám pro firmy a podnikatele, na děti tenhle web necílí a vědomě nesbírám údaje od nikoho mladšího 18 let. Kdyby mi je dítě přesto poslalo, napište mi a smažu je.</p>

<h2>Když tenhle text změním</h2>
<p>Zásady čas od času upravím — třeba když na webu přibude nový nástroj. Nová verze se objeví tady a nahoře se změní datum účinnosti. Pokud půjde o změnu, která se vás reálně dotkne, zeptám se znovu na souhlas.</p>

<h2>Kontakt</h2>
<p>Ondřej Kriška, Dunajovská 116, 691 81 Březí, IČO 19231407<br>E-mail: <a href="mailto:ok@ondraweb.cz">ok@ondraweb.cz</a></p>
<p>Pověřence pro ochranu osobních údajů nemám — na to je moje podnikání příliš malé. Píšete přímo mně.</p>',

];
