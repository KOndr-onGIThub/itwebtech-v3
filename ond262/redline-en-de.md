# OND-262 — Redline EN a DE mutací

**Zdroj nálezů:** `ond254/findings-langs.md` (audit OND-254, 22. 9. 2026).
**Zdroj starých znění:** dvě různá místa, podle toho, kde text žije — viz níž.
**Datum:** 22. 9. 2026.

**Ověření.** Každé „staré znění" v částech A3–A8, B2, B3 a E je ověřené skriptem proti
zdroji — lang a šablona proti `origin/staging` @ `a86a0ae`, DB proti staženému textu
produkce. Skript `build-redline.py` vedle tohoto souboru; poslední běh: **70 kontrol,
0 neshod**. Kromě starých znění kontroluje i tři tvrzení, o která se opírá zadání:
že všech 16 recenzí leží česky v `lang/en` i `lang/de`, že `duration` **není** ve
`portfolio_project_translations`, a že český text v řádku Duration je na 23 projektech.
Strojově čitelný výstup: `redline.json` (36 položek).

Části **A1** (slovník doby realizace), **A2** (recenze) a **C** (EN článek) v JSONu
nejsou — nejsou to náhrady 1:1, ale nové texty. Jejich zdrojem je tenhle soubor.

---

## Než začneš implementovat — tři věci, které mění zadání

### 1. Recenze nežijí v databázi, ale v `lang/*/testimonials.php`

Audit u P1-1 odhadl, že recenze budou v DB jako texty případovek. **Nejsou.** Žijí v
`lang/cs/testimonials.php`, `lang/en/testimonials.php` a `lang/de/testimonials.php` —
tři soubory, tři jazyky, ale všechny tři obsahují **doslova identický český text**
(v hlavičce souboru je to i napsané: „Testimonials are always shown in Czech regardless
of locale. Same file is used for cs/en/de."). Oprava je tedy čistě v `lang`, žádná
migrace. Část A2 níž.

### 2. „Duration" není jen PitArena a nejde opravit migrací dat

Audit u P1-2 jmenuje PitArenu. Prošel jsem **všech 24 projektů na produkci v EN i DE** a
český text v řádku Duration/Dauer je u **23 z nich** — jediná výjimka je
`pitarena-online-shop`, kde je pole prázdné. Kompletní seznam v části B1.

Zásadní zádrhel: **`duration` je sloupec na `portfolio_projects`, ne na
`portfolio_project_translations`** (ověřeno v
`database/migrations/2026_05_06_120000_create_portfolio_projects_table.php:24`). Je to
jedna hodnota pro všechny jazyky. Datová migrace ji tedy **nemůže** přeložit — ať do ní
zapíšeš cokoli, EN i DE dostane totéž co CS. Přejmenování z OND-261 („průběžně od 2023")
by problém nevyřešilo, jen vyměnilo jeden český řetězec za jiný.

**Doporučuju lang klíč, ne DB.** Přesně tak už je vyřešená „Kategorie" na témže řádku
(`projects.detail.category_label.<category>` — viz mapa polí v `ond261/redline-pripadovky-cs.md`).
Po zásahu z OND-261 zbude uzavřený slovník **pěti** hodnot, takže mapa v `lang` je
malá a úplná. Návrh znění v části A1. Mechanismus je rozhodnutí Engineera — pokud
zvolí přidání `duration` do translations tabulky, texty z části A1 platí beze změny,
jen se zapíšou jinam.

### 3. Vlna 1 (OND-256) v době měření nebyla na produkci

Ověřeno 22. 9. 2026: živá produkce nezná ani jeden řetězec „24 hours on business days" /
„24 Stunden an Arbeitstagen", který vlna 1 zavádí. Proto:

* **Stará znění v části A (lang) jsem bral z `origin/staging`** (commit `a86a0ae`,
  tj. po mergi vlny 1) — to je stav, proti kterému budeš implementovat.
* **Stará znění v části B (DB) jsem bral z produkce**, protože tam je zdroj pravdy.
  U DE blogu je jedna výjimka, kde je produkce zastaralá — označena v B3.

Slib reakční doby a upload widget na `/de/kontakt` **v tomhle dokumentu nejsou** — vlastní
je OND-256 a na stagingu už jsou hotové (ověřeno `lang/de/contact.php:42`
„Dateien hinzufügen", `lang/de/home.php:56` „Antwort innerhalb von 24 Stunden an
Arbeitstagen"). Tím padá i **P1-6 z findings-langs.md** (anglický upload widget na DE
kontaktu) — je hotový. Šesté P1 v tomhle dokumentu je proto nový EN slug, který zadání
OND-262 vede pod číslem 6.

---

# ČÁST A — texty v `lang/en` a `lang/de`

Sloupec „klíč" je cesta v poli daného souboru; číslo řádku je orientační a platí pro
`origin/staging` @ `a86a0ae`. Závazný je **doslovný starý řetězec**.

## A1 — Doba realizace (P1-2): slovník hodnot

Nové klíče, návrh `projects.detail.duration.*`. Pět hodnot pokryje všechny projekty,
které po OND-261 řádek Duration ponechají.

| Klíč | CS (po OND-261) | EN | DE | Projekty |
|---|---|---|---|---|
| `duration.ongoing_since` | `průběžně od :year` | `ongoing, since :year` | `laufend, seit :year` | pitarena (2023), yolk (2025) |
| `duration.weeks_few` | `několik týdnů` | `a few weeks` | `einige Wochen` | choccoboard, cyklocentrum, kemp-veselka, nove-interiery, strechy-zajic, vp-industry, zubni-provazek |
| `duration.months_few` | `několik měsíců` | `a few months` | `einige Monate` | frl-creator, picker |
| `duration.weeks_5` | `5 týdnů` | `5 weeks` | `5 Wochen` | barana |
| `duration.months_few_still_running` | `několik měsíců, aplikace běží dodnes` | `a few months; the app is still running` | `einige Monate, die Anwendung läuft bis heute` | hcms |

**Pozn. k PitAreně:** audit navrhoval EN „ongoing, 2023–present" / DE „laufend,
2023–heute". Mezitím OND-261 sjednotilo CS tvar na „průběžně od &lt;rok&gt;", takže EN/DE
srovnávám s ním — „ongoing, since 2023" / „laufend, seit 2023". Jinak by česká a cizí
mutace říkaly každá něco jiného.

Zbylých deset projektů má podle OND-261 (X2-4 až X2-13) řádek Duration **vypadnout** —
tím se český text z EN/DE odstraní sám a klíč pro ně netřeba.

## A2 — Recenze klientů (P1-1)

### A2a — Poznámka o překladu (nový klíč)

Audit správně chce u přeložených recenzí viditelně říct, že jde o překlad — jinak
návštěvník nedohledá originál na Google/Firmy.cz a autenticita se ztratí. Návrh: nový
klíč `home.testimonials.note`, vykreslený pod nadpisem sekce v
`resources/views/pages/home.blade.php` (sekce `#section-testimonials`, řádek ~462).

| Jazyk | Klíč | Nové znění |
|---|---|---|
| cs | `home.testimonials.note` | _(prázdné — v šabloně obalit `@if`, česky poznámka nedává smysl)_ |
| en | `home.testimonials.note` | `Translated from the Czech originals on Google, Firmy.cz and Facebook.` |
| de | `home.testimonials.note` | `Aus dem Tschechischen übersetzt — die Originale stehen auf Google, Firmy.cz und Facebook.` |

Proč „and Facebook" navíc oproti návrhu z auditu: jeden ze šestnácti zdrojů je Facebook
(Jana Veselá), takže dvojice Google/Firmy.cz by byla nepřesná.

### A2b — Texty recenzí

Soubor `lang/en/testimonials.php` a `lang/de/testimonials.php`, klíč `items[N].text`
a `items[N].role`. Pole `name`, `company`, `image`, `source` zůstávají beze změny
(vlastní jména a značky se nepřekládají).

Na homepage se vykresluje **šest** recenzí (výběr v `home.blade.php:44–47`), ale
pořadí se přepíná feature flagem `site.features.show_toyota_testimonial` a při vypnutí
naskakuje sedmá (Peter Vidlička). Překládám proto **všech šestnáct**, aby přepnutí
flagu nebo pozdější přeskládání nevrátilo češtinu.

Pořadí a číslování odpovídá pořadí v souboru. Na homepage se dnes zobrazují položky
**12, 8, 13, 9, 10 a 11** v tomhle pořadí (Baudyš, Toman, Holcmann, Jaskmanická,
Štěpánek, Pešice); při vypnutém Toyota flagu nastoupí místo Baudyše položka **6**
(Vidlička).

---

**1. Radka Láníková Ouředníková** — MAKOplast s.r.o.

| Pole | Staré (cs, ve všech třech souborech) | EN | DE |
|---|---|---|---|
| `role` | `jednatelka` | `managing director` | `Geschäftsführerin` |
| `text` | `Skvělá spolupráce, profesionální přístup, web hotový včas a podle představ. Doporučuji pana Krišku všem, kdo chtějí kvalitní webové stránky.` | `Great collaboration, a professional approach, the site finished on time and the way we imagined it. I recommend Mr Kriška to anyone who wants a website done properly.` | `Hervorragende Zusammenarbeit, professionelles Vorgehen, die Website war pünktlich fertig und genau so, wie wir sie uns vorgestellt hatten. Ich empfehle Herrn Kriška allen, die eine ordentliche Website wollen.` |

**2. Aleš Horký** — ExHot

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `výrobky z nerezu` | `stainless steel products` | `Edelstahlprodukte` |
| `text` | `Ondřeje Krišku bych rozhodně doporučil pro jeho vynalézavý a neotřelý styl práce, jdoucí ruku v ruce s flexibilním a profesionálním přístupem k zákazníkovi.` | `I would definitely recommend Ondřej Kriška for his inventive, fresh way of working, which goes hand in hand with a flexible and professional attitude towards the customer.` | `Ondřej Kriška ist klar zu empfehlen — für seine erfinderische, unverbrauchte Arbeitsweise, die mit einem flexiblen und professionellen Umgang mit dem Kunden Hand in Hand geht.` |

**3. Michal Cvrček** — Cyklocentrum Březí

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `spolumajitel` | `co-owner` | `Mitinhaber` |
| `text` | `Perfektní spolupráce. Výborné nápady a přístup. Rychlost, vstřícnost, ochota, profesionalita. Vřele doporučuji.` | `Perfect collaboration. Excellent ideas and approach. Fast, helpful, willing, professional. Warmly recommended.` | `Perfekte Zusammenarbeit. Ausgezeichnete Ideen und Herangehensweise. Schnell, entgegenkommend, hilfsbereit, professionell. Wärmstens empfohlen.` |

**4. Adéla Polášková** — Mušov21 restaurace

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `spolumajitelka` | `co-owner` | `Mitinhaberin` |
| `text` | `Děkuji Ondrovi za skvělou spolupráci v rámci zpracování loga tak, aby mohlo být použito na firemní textil. Vše proběhlo rychle, precizně a během několika hodin. Určitě doporučuji.` | `Thank you to Ondra for the great work on adapting our logo so it could be used on company clothing. It was done quickly, precisely and within a few hours. I can definitely recommend him.` | `Danke an Ondra für die großartige Arbeit am Logo, damit es auf Firmentextilien verwendet werden kann. Alles lief schnell, präzise und innerhalb weniger Stunden. Klare Empfehlung.` |

**5. Jana Veselá** — Kemp Veselka

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `provozovatelka kempu` | `campsite operator` | `Betreiberin des Campingplatzes` |
| `text` | `100% spokojenost s vytvořením našich webových stránek. Krásný a funkční web. Můžeme vřele doporučit.` | `100% satisfied with how our website was built. Beautiful and it works. We can warmly recommend him.` | `100 % zufrieden mit der Erstellung unserer Website. Schön und funktional. Wir können ihn wärmstens empfehlen.` |

**6. Peter Vidlička** — Yolk studio

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `co-founder` | `co-founder` _(beze změny)_ | `Mitgründer` |
| `text` | `Ondra je velice spolehlivy a sikovny vyvojar s kterym nam vzdy hodne dobre spolupracovalo.` | `Ondra is a very reliable and skilled developer; cooperation has always gone well.` | `Ondra ist ein sehr zuverlässiger und geschickter Entwickler, die Zusammenarbeit lief immer sehr gut.` |

> Tyhle dvě věty **nevymýšlej znovu** — přesně takhle už jsou přeložené v DB na
> `/en/projects/yolk` a `/de/projekte/yolk`. Opsal jsem je, aby web neměl dvě různé
> anglické verze jednoho výroku.

**7. Magda Pernicová Novotná** — Realiťačky v akci

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `makléřka` | `estate agent` | `Immobilienmaklerin` |
| `text` | `Profesionální, ale zároveň lidský a trpělivý přístup. Pan Kriška opravdu naslouchal mým potřebám a následně tyto informace zpracoval až do mé úplné spokojenosti. Vřele doporučuji.` | `Professional and at the same time human and patient. Mr Kriška really listened to what I needed and then turned it into something I am completely happy with. Warmly recommended.` | `Professionell und zugleich menschlich und geduldig. Herr Kriška hat mir wirklich zugehört und daraus etwas gemacht, mit dem ich rundum zufrieden bin. Wärmste Empfehlung.` |

> Stejný výrok se na homepage objevuje ještě jednou, v kroku 01 sekce „Jak pracuju" —
> viz A3-4 a A4-2. Obě místa musí mít **stejné** znění.

**8. Rostislav Toman** — Tradiční výroba pralinek, s.r.o.

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `Manager` | `manager` | `Manager` _(beze změny)_ |
| `text` | `Oceňuji vysokou odbornost a profesionalitu. Postupnými kroky jsme odladili očekávání, realitu a na základě rad i optimalizaci toku dat. Zažil jsem předčená očekávání v praxi. Z mé strany jednoznačně doporučení na spolupráci.` | `I appreciate the high level of expertise and professionalism. Step by step we brought expectations and reality together, and on his advice we optimised the data flow as well. I got to see expectations exceeded in practice. From my side, a clear recommendation.` | `Ich schätze die hohe Fachkompetenz und Professionalität. Schritt für Schritt haben wir Erwartungen und Realität in Einklang gebracht und auf seinen Rat hin auch den Datenfluss optimiert. Ich habe in der Praxis erlebt, wie Erwartungen übertroffen werden. Von meiner Seite eine klare Empfehlung.` |

**9. Hana Jaskmanická** — VP INDUSTRY

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `výkonná ředitelka` | `Executive Director` | `geschäftsführende Direktorin` |
| `text` | `Chtěli jsme mít pro naši firmu kvalitní a odlišné webové stránky. Díky individuálnímu přístupu, flexibilitě a profesionalitě odpovídá výsledek našim představám. Vřele doporučuji.` | `We wanted a website for our company that was good and different from the rest. Thanks to an individual approach, flexibility and professionalism, the result matches what we had in mind. Warmly recommended.` | `Wir wollten für unsere Firma eine gute Website, die sich von anderen unterscheidet. Durch die individuelle Herangehensweise, Flexibilität und Professionalität entspricht das Ergebnis genau unseren Vorstellungen. Wärmste Empfehlung.` |

**10. Ing. Ivo Štěpánek** — J. K. fire and safety consulting

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `podnikatel v oblasti BOZP` | `occupational safety consultant` | `Unternehmer im Arbeitsschutz` |
| `text` | `Služby pana Ondřeje Krišky vřele doporučuji. Jedná rychle a efektivně. Byl to pro mě velký rozdíl mezi předchozím IT dodavatelem. Je dobře, že v této zemi máme i takové odborníky.` | `I warmly recommend Ondřej Kriška's services. He acts fast and efficiently. For me it was a big difference compared with my previous IT supplier. It is good that this country has specialists like him.` | `Die Dienste von Ondřej Kriška empfehle ich wärmstens. Er handelt schnell und effizient. Für mich war das ein großer Unterschied zum vorherigen IT-Dienstleister. Gut, dass es in diesem Land solche Fachleute gibt.` |

**11. Václav Pešice** — Upstyle systems

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `software developer` | `software developer` _(beze změny)_ | `Softwareentwickler` |
| `text` | `S Ondrou je skvělá spolupráce. Vždy se snaží udělat pro klienty maximum. Odvedl perfektní práci. Rozhodně má moje doporučení.` | `Working with Ondra is great. He always tries to do the most he can for his clients. He did a perfect job. He definitely has my recommendation.` | `Die Zusammenarbeit mit Ondra ist großartig. Er versucht immer, das Maximum für seine Kunden herauszuholen. Er hat perfekte Arbeit geleistet. Er hat definitiv meine Empfehlung.` |

**12. Pavel Baudyš** — Toyota

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `ředitel řízení výroby, montáže a logistiky` | `Director of Manufacturing, Assembly & Logistics` | `Direktor Produktion, Montage & Logistik` |
| `badge` | `Z mého působení v Toyotě` | `From my years at Toyota` | `Aus meiner Zeit bei Toyota` |
| `text` | `S potěšením mohu poskytnout tuto referenci pro Ondřeje Krišku, který pracoval v naší společnosti Toyota 18 let. Jednou z nejsilnějších stránek Ondry je velká chuť rozvíjet se, což je viditelné na jeho výsledcích.` | `It is my pleasure to give this reference for Ondřej Kriška, who worked at our company Toyota for 18 years. One of Ondra's greatest strengths is a real appetite to keep developing, and it shows in his results.` | `Ich gebe diese Referenz für Ondřej Kriška gerne ab. Er hat 18 Jahre in unserem Unternehmen Toyota gearbeitet. Eine seiner größten Stärken ist der echte Wille, sich weiterzuentwickeln — das sieht man an seinen Ergebnissen.` |

> `badge` je dnes taky jen česky a překlad v souboru chybí úplně (`items[11].badge`,
> `items[15].badge`). Bez něj by na EN/DE svítilo „Z mého působení v Toyotě" nad
> přeloženou recenzí.

**13. Stanislav Holcmann** — Pitbike Aréna

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `majitel` | `owner` | `Inhaber` |
| `text` | `Tenhle web meister dělá stránky pro nás a můžu jenom vřele doporučit. Výborná komunikace, kvalitně odvedená práce, spoustu inovativních, praktických nápadů.` | `This web meister builds our sites and I can only recommend him warmly. Excellent communication, quality work, plenty of inventive, practical ideas.` | `Dieser Web-Meister baut unsere Seiten und ich kann ihn nur wärmstens empfehlen. Ausgezeichnete Kommunikation, saubere Arbeit, jede Menge erfinderische, praktische Ideen.` |

> „web meister" je autorův vtip v originále (německé slovo v české větě), ne překlep —
> nechávám ho v obou mutacích, protože v němčině i angličtině funguje stejně.

**14. Lukas Srnák** — Elektro Srnák

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `podnikatel` | `sole trader` | `Selbstständiger` |
| `text` | `Rychlost, ochota, vstřícnost. Naprosto perfektní přístup a jednání. Mohu vřele doporučit.` | `Fast, willing, helpful. An absolutely perfect approach and dealings. I can warmly recommend him.` | `Schnell, hilfsbereit, entgegenkommend. Ein absolut perfekter Umgang. Ich kann ihn wärmstens empfehlen.` |

**15. Jaroslav Zajíc** — Střechy Zajíc

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `živnostník` | `sole trader` | `Selbstständiger` |
| `text` | `Webové stránky vypadají skvěle a jejich ovládání je intuitivní. Díky proaktivnímu přístupu a odborným radám byl celý proces snadný. Určitě se obrátím znovu. Doporučuji.` | `The website looks great and it is intuitive to operate. Thanks to a proactive approach and expert advice, the whole process was easy. I will definitely come back. Recommended.` | `Die Website sieht großartig aus und lässt sich intuitiv bedienen. Durch das proaktive Vorgehen und die fachliche Beratung war der ganze Prozess einfach. Ich komme sicher wieder. Empfehlenswert.` |

**16. Jan Stybor** — Toyota

| Pole | Staré | EN | DE |
|---|---|---|---|
| `role` | `vedoucí projektového oddělení` | `Head of Project Department` | `Leiter der Projektabteilung` |
| `badge` | `Z mého působení v Toyotě` | `From my years at Toyota` | `Aus meiner Zeit bei Toyota` |
| `text` | `Oceňuji profesionální přístup k práci. Při vývoji aplikace důsledně analyzuje stav a chce poznat současné procesy. Shromažďuje požadavky od zákazníků a zjišťuje vize pro budoucnost. Připraví plán, na základě kterého se zákazníkem dohodne na klíčových milnících.` | `I appreciate his professional approach to the work. When developing an application he analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading. Then he puts together a plan and agrees the key milestones with the client.` | `Ich schätze seine professionelle Arbeitsweise. Bei der Entwicklung einer Anwendung analysiert er die Ausgangslage gründlich und will die bestehenden Prozesse wirklich verstehen. Er sammelt die Anforderungen der Nutzer und fragt nach, wohin es gehen soll. Daraus macht er einen Plan und stimmt die wichtigsten Meilensteine mit dem Kunden ab.` |

> Stejný výrok je i v kroku 02 sekce „Jak pracuju" — viz A4-3. Musí sedět.

## A3 — DE homepage a ceník (P1-3, P1-5, P2-1, P2-2, P2-4, P2-6)

| # | URL | Soubor a klíč | Staré znění (doslovně z `origin/staging`) | Nové znění | Proč |
|---|---|---|---|---|---|
| A3-1 | /de/ | `lang/de/home.php` → `how_i_work.steps[0].text` (ř. 130) | `Ich beginne mit einem Gespräch, nicht mit einem Formular. Ich muss Ihr Unternehmen, Ihre Kunden und verstehen, was die Website wirklich tun soll — Kontakte bringen, ein Produkt verkaufen oder Vertrauen aufbauen.` | `Ich beginne mit einem Gespräch, nicht mit einem Formular. Ich muss Ihr Unternehmen und Ihre Kunden verstehen — und wissen, was die Website wirklich leisten soll: Kontakte bringen, ein Produkt verkaufen oder Vertrauen aufbauen.` | **P1-3.** Vyšinutí z vazby: sloveso „verstehen" je vklíněné doprostřed výčtu. Rodilý mluvčí o větu zakopne přesně v kroku, který slibuje pečlivost. |
| A3-2 | /de/preisliste | `lang/de/price.php` → `note` (ř. 89) | `Kein Umsatzsteuerpflichtiger — die genannten Preise sind endgültig, es kommt nichts hinzu.` | `Ich bin nicht umsatzsteuerpflichtig — die genannten Preise sind Endpreise, es kommt keine Mehrwertsteuer hinzu.` | **P1-5.** Doslovný překlad „nejsem plátce DPH". Substantivum „Umsatzsteuerpflichtiger" se takhle nepoužívá a věta je bez podmětu. Vlastní web si navíc odporuje: `lang/de/contact.php:19` už má správné „nicht umsatzsteuerpflichtig". |
| A3-3 | /de/ | `lang/de/home.php` → `showcase.intro` (ř. 74) | `Das sind live Projekte, die Sie sich sofort ansehen können. Klicken Sie sich durch.` | `Das sind Live-Projekte, die Sie sich sofort ansehen können. Klicken Sie sich durch.` | **P2-1.** „live" jako nesklonné adjektivum před substantivem není němčina; správné je kompozitum. Tlačítko o kus níž má „Live-Website öffnen" správně — nekonzistence uvnitř jedné sekce. |
| A3-4 | /de/ | `lang/de/home.php` → `services.primary.eshop.bullets[]` (ř. 247) | `Anbindung an Buchhaltung, Spediteure und Zahlungsanbieter` | `Anbindung an Buchhaltung, Versanddienstleister und Zahlungsanbieter` | **P2-2.** „Spediteur" je kamionová/nákladní spedice, ne Zásilkovna nebo DHL pro e-shop. EN má na témže místě správně „couriers". |
| A3-5 | /de/ | `lang/de/home.php` → `problems.lead` (ř. 105) | `… Sie sprechen direkt mit mir — von der ersten Nachricht über den Launch und darüber hinaus.` | `… Sie sprechen direkt mit mir — von der ersten Nachricht bis zum Launch und darüber hinaus.` | **P2-4.** Vazba „von A über B hinaus" neexistuje. Tatáž stránka o kus níž (`faq`, ř. 400) má správně „Von der ersten Nachricht bis zum Launch". |
| A3-6 | /de/ | `lang/de/home.php` → `why_me.bio` (ř. 302) | `… Ich arbeite allein: Sie sprechen direkt mit mir, von der ersten Beratung über den Launch hinaus.` | `… Ich arbeite allein: Sie sprechen direkt mit mir — von der ersten Beratung bis zum Launch und darüber hinaus.` | **P2-4, druhý výskyt.** Navíc useknutá věta (chybí „bis"). |
| A3-7 | /de/ | `lang/de/home.php` → `services.primary.aplikace.description` (ř. 235) | `Interne Systeme, Kundenportale und Verwaltungstools, gebaut auf der Art, wie Ihr Betrieb tatsächlich funktioniert.` | `Interne Systeme, Kundenportale und Verwaltungstools — zugeschnitten auf die Art, wie Ihr Betrieb tatsächlich arbeitet.` | **P2-6.** „gebaut auf der Art" je kalk z „postavené na tom, jak…" a německy nefunguje. |

## A4 — Strojově znějící citace klientů (P2-3)

Klientské citace jsou důvěryhodnostní jádro stránky — strojový tón je tam nejdražší.
Znění **musí sedět s A2b**, protože jde o tytéž výroky na dvou místech webu.

| # | URL | Soubor a klíč | Staré znění | Nové znění | Proč |
|---|---|---|---|---|---|
| A4-1 | /en/ | `lang/en/home.php` → `problems.items[0].quote_text` (ř. 114) | `This is not the case where other would-be web designers just fill templates with data for outrageous fees.` | `Unlike the would-be web designers who just pour your content into a template and charge outrageous fees.` | Doslovný překlad „To není případ, kdy…"; „fill templates with data" je čechismus. |
| A4-2 | /de/ | `lang/de/home.php` → `problems.items[0].quote_text` (ř. 112) | `Das ist nicht der Fall, in dem andere Hobbyentwickler einfach Vorlagen mit Daten für überhöhte Preise füllen.` | `Ganz anders als die Möchtegern-Webdesigner, die für überhöhte Preise einfach Vorlagen mit Inhalten befüllen.` | Tentýž kalk v němčině; „Hobbyentwickler" navíc posouvá význam (amatér vs. samozvaný designér). |
| A4-3 | /de/ | `lang/de/home.php` → `how_i_work.steps[0].quote_text` (ř. 131) | `Er hörte wirklich meinen Bedürfnissen zu und verarbeitete diese Informationen bis zu meiner vollständigen Zufriedenheit.` | `Er hat mir wirklich zugehört und daraus etwas gemacht, mit dem ich rundum zufrieden bin.` | „verarbeitete diese Informationen" zní jako protokol, ne jako člověk. |
| A4-4 | /de/ | `lang/de/home.php` → `how_i_work.steps[1].quote_text` (ř. 138) | `Er analysiert gründlich den Zustand und möchte die aktuellen Prozesse kennenlernen. Er sammelt Anforderungen von Kunden und erkundigt sich nach Zukunftsvisionen.` | `Er analysiert die Ausgangslage gründlich und will die bestehenden Prozesse wirklich verstehen. Er sammelt die Anforderungen der Nutzer und fragt nach, wohin es gehen soll.` | Slovosled + „Zustand" bez atributu; „Zukunftsvisionen" je nabubřelé. |

**EN protějšky A4-3 a A4-4** audit samostatně nepojmenoval, protože nejsou vadné — jsou
jen formulované jinak než tytéž výroky v recenzích (A2b, položky 7 a 16). Po A2 by web
měl dvě různé anglické verze jedné věty na jedné stránce, takže je srovnávám:

| # | Soubor a klíč | Staré znění | Nové EN znění |
|---|---|---|---|
| A4-5 | `lang/en/home.php` → `how_i_work.steps[0].quote_text` (ř. 133) | `He truly listened to my needs and then turned them into something I was completely satisfied with.` | `He really listened to what I needed and then turned it into something I am completely happy with.` |
| A4-6 | `lang/en/home.php` → `how_i_work.steps[1].quote_text` (ř. 140) | `He rigorously analyses the situation and wants to understand current processes. He collects requirements from clients and explores visions for the future.` | `He analyses the starting position thoroughly and wants to understand the existing processes. He gathers requirements from users and asks where things are heading.` |

**Funkce, které jsem v A2b nevymýšlel znovu.** `lang/{en,de}/home.php` už obsahuje
přeložené `quote_author` u tří lidí; překlad v `role` musí sedět s ním, jinak stránka
uvede u jednoho člověka dvě různé pozice:

| Člověk | Existující `quote_author` | Použito v A2b |
|---|---|---|
| Pavel Baudyš | en ř. 192 `Director of Manufacturing, Assembly & Logistics` · de ř. 190 `Direktor Produktion, Montage & Logistik` | totéž |
| Jan Stybor | en ř. 141 `Head of Project Department` · de ř. 139 `Leiter der Projektabteilung` | totéž |
| Hana Jaskmanická | en ř. 440 `Executive Director` | totéž (de vlastní překlad zatím nemá) |

Zkrácené pull-quoty u Baudyše (en ř. 191 / de ř. 189) a Jaskmanické (en ř. 439 /
de ř. 438) jsou **v pořádku a nechávám je být** — jsou to záměrně zkrácené výseče, ne
jiný překlad. Jen si po zásahu do A2b ověř, že si s plným zněním neodporují.

## A5 — Formát měny (P2-7)

| # | URL | Soubor a klíč | Staré | Nové | Proč |
|---|---|---|---|---|---|
| A5-1 | /de/preisliste | `lang/de/price.php` → `addons.items[0].price` (ř. 119) | `ab €180 / Mo.` | `ab 180 € / Monat` | Na téže stránce mají hlavní tarify správný německý zápis „2.200 €" (symbol za číslem). Doplňkové služby používají anglický zápis se symbolem před číslem — nekonzistence uvnitř jedné stránky. „Mo." navíc není běžná německá zkratka. |
| A5-2 | /de/preisliste | `lang/de/price.php` → `addons.items[1].price` (ř. 124) | `ab €400 / Mo.` | `ab 400 € / Monat` | Totéž. |
| A5-3 | /de/preisliste | `lang/de/price.php` → `addons.items[3].price` (ř. 134) | `ab €190` | `ab 190 €` | Totéž. |

EN zápis (`€2,200`, `from €180 / mo.`) je konzistentní a **nechávám ho být**. Druhá půlka
P2-7 se týká EN blogu — řeší ji kompletní přepis v části C.

## A6 — Nadsazené tvrzení o úspoře (P2-8)

| # | URL | Soubor a klíč | Staré | Nové | Proč |
|---|---|---|---|---|---|
| A6-1 | /en/price | `lang/en/price.php` → `guarantees.items[0].text` (ř. 96) | `No WordPress, no third-party plugins. Save thousands per year compared to WordPress — no monthly updates and no security patching costs.` | `No WordPress, no third-party plugins. Save hundreds of euros a year compared to WordPress — no monthly updates and no security patching bills.` | Kalk z „ušetříte tisíce korun ročně". V eurovém kontextu „thousands" bez jednotky slibuje tisíce eur ročně jen za údržbu WP — napadnutelné. |
| A6-2 | /de/preisliste | `lang/de/price.php` → `guarantees.items[0].text` (ř. 96) | `Kein WordPress, keine Drittanbieter-Plugins. Sparen Sie jährlich Tausende gegenüber WordPress — keine monatlichen Updates und keine Kosten für Sicherheits-Patches.` | `Kein WordPress, keine Drittanbieter-Plugins. Sparen Sie jedes Jahr mehrere hundert Euro gegenüber WordPress — keine monatlichen Updates und keine Kosten für Sicherheits-Patches.` | Totéž v němčině. |

## A7 — Právní formulace v EN formuláři (P2-9)

| # | URL | Soubor a klíč | Staré | Nové | Proč |
|---|---|---|---|---|---|
| A7-1 | /en/contact | `lang/en/contact.php` → `agree` (ř. 33) | `I agree to the transfer of personal data in accordance with the ` | `I agree to the processing of my personal data in accordance with the ` | Právně jde o zpracování, ne předání. DE verze to má správně („Verarbeitung"), EN homepage taky („you agree to processing") — chybný je jen kontaktní formulář. Pozor na **koncovou mezeru** v řetězci, za ním se skládá odkaz. |

## A8 — DE zásady ochrany údajů (P2-10)

| # | URL | Soubor a klíč | Staré | Nové | Proč |
|---|---|---|---|---|---|
| A8-1 | /de/datenschutz | `lang/de/privacy.php` (ř. 34) | `<p>Diese Seite informiert Sie über unsere Richtlinien bezüglich der Erhebung, Verwendung und Weitergabe personenbezogener Daten bei der Nutzung unseres Dienstes …` | `<p>Auf dieser Seite erkläre ich, welche personenbezogenen Daten ich erhebe, wie ich sie verwende und an wen ich sie weitergebe, wenn Sie meine Website nutzen …` | Jediná DE stránka, kde mluví „firma v množném čísle" (20× wir/unser proti 3× ich) — láme ich-formu celého webu a odporuje vlastnímu hero („Was ich erhebe, warum ich es erhebe und wie ich es schütze."). |
| A8-2 | /de/datenschutz | `lang/de/privacy.php` (ř. 37) | `<p>Wir erheben verschiedene Arten von Informationen für verschiedene Zwecke, um Ihnen unseren Dienst bereitzustellen und zu verbessern.</p>` | `<p>Ich erhebe verschiedene Arten von Informationen zu verschiedenen Zwecken, um die Website bereitzustellen und zu verbessern.</p>` | Totéž. |
| A8-3 | /de/datenschutz | `lang/de/privacy.php` (ř. 46) | `<li>Adresse, Bundesland, Postleitzahl, Stadt</li>` | `<li>Adresse, PLZ und Ort</li>` | „Bundesland" je přeložené americké „State" z původní anglické šablony — u českého živnostníka působí cize a stejně se nesbírá. |
| A8-4 | /de/datenschutz | `lang/de/privacy.php` (ř. 67) | `<p>Die Sicherheit Ihrer Daten ist uns wichtig. Obwohl wir uns bemühen, kommerziell akzeptable Mittel zum Schutz Ihrer personenbezogenen Daten einzusetzen, …` | `<p>Die Sicherheit Ihrer Daten ist mir wichtig. Ich schütze sie nach dem Stand der Technik, …` | Ich-forma + „kommerziell akzeptable Mittel" je doslovný kalk z „commercially acceptable means", německy nic neříká. |
| A8-5 | /de/datenschutz | `lang/de/privacy.php` (ř. 75) | `<p>Unser Dienst richtet sich nicht an Personen unter 18 Jahren. Wir erheben wissentlich keine personenbezogenen Daten von Personen unter 18 Jahren.</p>` | `<p>Meine Website richtet sich nicht an Personen unter 18 Jahren. Ich erhebe wissentlich keine personenbezogenen Daten von Personen unter 18 Jahren.</p>` | Ich-forma. |

> **Pozor — tohle je jen jazyková oprava, ne právní revize.** V souboru zbývá dalších
> ~9 výskytů „wir/unser", které je potřeba projet stejným pravidlem (ich-forma, „meine
> Website" místo „unser Dienst"). Audit u P2-10 zároveň uvádí, že tělo stránky
> neobsahuje formální DSGVO náležitosti (právní tituly dle čl. 6, výčet práv subjektu,
> Clarity) a **právníkem to ověřené není**. Doplnění obsahu není copy práce — patří na
> samostatnou kartu nebo právníkovi. Já opravuju jen to, co je jazykově vadné.

---

# ČÁST B — texty v databázi

Stará znění jsou **z produkce** (`https://itwebtech.ondrejkriska.cz`, staženo 22. 9. 2026),
protože tam je zdroj pravdy. Vzor migrace + past s `PortfolioSeeder` jsou popsané
v `ond261/redline-pripadovky-cs.md`.

## B1 — Doba realizace: rozsah zásahu

**Nic z toho není oprava textu v DB** — viz bod 2 v úvodu. Tabulka slouží k tomu, aby
bylo vidět, kolik stránek se to týká a která hodnota kam patří po zásahu z OND-261.

| Projekt (EN slug / DE slug) | `portfolio_projects.duration` dnes | Po OND-261 | Klíč z A1 |
|---|---|---|---|
| pitarena | `průběžně 2023–dnes` | `průběžně od 2023` | `ongoing_since` (2023) |
| yolk | `průběžná spolupráce` | `průběžně od 2025` | `ongoing_since` (2025) |
| hcms | `několik měsíců (2021), provoz dodnes` | `několik měsíců, aplikace běží dodnes` | `months_few_still_running` |
| barana | `5 týdnů` | beze změny | `weeks_5` |
| choccoboard | `několik týdnů` | beze změny | `weeks_few` |
| cyklocentrum | `několik týdnů` | beze změny | `weeks_few` |
| kemp-veselka | `několik týdnů` | beze změny | `weeks_few` |
| nove-interiery | `několik týdnů` | beze změny | `weeks_few` |
| strechy-zajic | `několik týdnů` | beze změny | `weeks_few` |
| vp-industry | `několik týdnů` | beze změny | `weeks_few` |
| zubni-provazek | `několik týdnů` | beze změny | `weeks_few` |
| frl-creator | `několik měsíců` | beze změny | `months_few` |
| picker | `několik měsíců` | beze změny | `months_few` |
| article-motorkari-cz / artikel-motorkari-cz | `krátká spolupráce` | _řádek vypustit_ | — |
| attention-grabbing-animation / aufmerksamkeits-animation | `krátká produkce` | _řádek vypustit_ | — |
| elektro-srnak | `kratší realizace` | _řádek vypustit_ | — |
| excel-tools | `průběžně` | _řádek vypustit_ | — |
| josefopa | `říjen 2024` | _řádek vypustit_ | — |
| logo-realitacky | `leden 2024` | _řádek vypustit_ | — |
| pitarena-outdoor-sign / pitarena-werbeschild | `krátká zakázka` | _řádek vypustit_ | — |
| realitacky-v-akci | `září 2024` | _řádek vypustit_ | — |
| vanspedition | `kratší realizace` | _řádek vypustit_ | — |
| video-pitbike-akademie | `krátká produkce` | _řádek vypustit_ | — |
| pitarena-online-shop / pitarena-onlineshop | _(prázdné)_ | beze změny | — |

## B2 — Podtitul „Geschäftstreffen" (P3 z auditu, ale je to prodejní věta)

| URL | Tabulka a sloupec | Staré | Nové | Proč |
|---|---|---|---|---|
| /de/projekte/nove-interiery | `portfolio_project_translations.subtitle`, `locale='de'` | `Eine Website, die wie ein erstes Geschäftstreffen wirkt` | `Eine Website, die wie ein erstes Verkaufsgespräch wirkt` | „Geschäftstreffen" je neutrální obchodní schůzka (třeba s dodavatelem). EN má na témže místě „the first sales meeting" — tedy prodejní rozhovor. Věta se objevuje i na kartách „Weitere Projekte" u jiných projektů. |

## B3 — DE blog: „Ich bin kein Umsatzsteuerpflichtiger" (P1-5, druhý výskyt)

| URL | Umístění | Staré | Nové |
|---|---|---|---|
| /de/blog/was-kostet-eine-website | `article_translations.content_1`, `article_id=3`, `locale='de'` | `<p>Ich bin kein Umsatzsteuerpflichtiger. Der Preis, den ich Ihnen nenne, ist endgültig. Was genau in den einzelnen Stufen steckt, steht aufgeschlüsselt in der <a href="/de/preisliste">Preisliste</a>.</p>` | `<p>Ich bin nicht umsatzsteuerpflichtig. Der Preis, den ich Ihnen nenne, ist ein Endpreis — es kommt keine Mehrwertsteuer hinzu. Was genau in den einzelnen Stufen steckt, steht aufgeschlüsselt in der <a href="/de/preisliste">Preisliste</a>.</p>` |

**Engineer — dvě místa, ne jedno.** Text článku žije v DB *i* v
`database/seeders/BlogContentDeSeeder.php` (metoda `articles()`, klíč `3 => content_1`).
Migrace opraví existující DB, seeder opraví čerstvou. Když se upraví jen jedno, čerstvý
seed vrátí starý text.

**Pozor na starou verzi na produkci:** produkce dnes v témž odstavci ještě obsahuje
`Ich melde mich innerhalb von zwei Werktagen`, zatímco staging už má
`innerhalb von 24 Stunden an Arbeitstagen` (vlna 1, OND-256, migrace
`2026_09_22_100000_ond256_reakcni_doba_v_clancich.php`). Migrace z tohohle redlinu musí
běžet **po** ní a nesmí se o tuhle větu opírat — sahej jen na větu o DPH.

## B4 — Typografie německých uvozovek (P3, plošně)

Stejný vzor jako Q1 v OND-261: otevírací uvozovka je správná německá `„` (U+201E),
zavírací je rovná palcová `"` (U+0022) místo `"` (U+201C).

Naměřeno 22. 9. 2026:

* **`lang/de/*.php`:** 24× `„`, z toho 21 dvojic končí rovnou uvozovkou. Soubory:
  `blog.php` (1), `home.php` (6), `projects.php` (5), `contact.php` (1), `price.php` (2),
  `privacy.php` (2 + další uvnitř HTML).
* **DB, DE mutace:** 26 dvojic na detailech projektů a v blogu
  (`aufmerksamkeits-animation`, `barana`, `choccoboard`, `cyklocentrum` 3×,
  `excel-tools`, `frl-creator`, `kemp-veselka`, `nove-interiery` 2×, `picker`,
  `pitarena` 2×, `realitacky-v-akci`, `strechy-zajic`, `vanspedition`, `yolk`,
  `zubni-provazek` 3×, blog `vorbereitung-auf-die-neue-website` 3×, `de-preisliste` 1×).

**Pravidlo pro záměnu:** nahradit `"` za `"` **pouze tehdy, když je ve stejném řetězci
před ní `„`**. Plošná záměna všech `"` by rozbila HTML atributy uvnitř `content_*` polí
a odkazů v `lang/de/privacy.php`.

Kontrola po nasazení: v DE textech nula výskytů `"` v pozici zavírací uvozovky.

---

# ČÁST C — EN článek „How much does a website cost" (P1-4)

**Rozsah:** kompletní náhrada obsahu, ne oprava. Důvody z auditu (`our comprehensive
guide` jako jediné „our" na celém webu, rozbitá hlavička „2. How it influences the Price
of a Website", nedokončená věta „…for electricians cost elektro-srnak .", čechismus
„With websites from me ,", emotikon „;)", cenová pásma 1 750–4 500 € proti ceníku) nejdou
opravit záplatami — ten článek je starší text s jinou osnovou než CS a DE verze.

Text níž je **anglická verze aktuálního CS/DE článku**, se strukturou Standard / Custom /
Starter a s čísly z `lang/en/price.php` (Standard €2,200 · Custom from €3,800 ·
Starter €1,000). Zrcadlí DE článek odstavec po odstavci, takže EN a DE čtenář dostanou
tentýž obsah.

**Kde to žije:** `article_translations`, `article_id = 3`, `locale = 'en'`. Slug
`how-much-does-a-website-cost` zůstává — je to funkční SEO adresa a nic v ní nelže.

**Engineer:** EN obsah dosud nikdy nebyl v seederu (je ze starého SQL importu
`database/sql/article_translations.sql`, který `EnsureArticlesSeededSeeder` pouští jen do
prázdné tabulky). Vzor je `BlogContentDeSeeder` + migrace
`2026_09_16_120000_seed_de_blog_content.php`; potřeba je jejich anglický protějšek, jinak
se změna na čerstvé DB neprojeví.

---

### `title`

```
What a custom website costs and what goes into the price
```

### `description` (meta popis, max 500 znaků)

```
The price tiers I build websites in, what each one includes, and what pushes the price up. So you know up front whether I fit your budget.
```

### `perex`

```html
<blockquote><p>Price is the first thing people ask me about, and it is the right question. But nobody can honestly give you a single number. A €1,000 website and an €8,000 website are two different things. So here it is straight: the tiers I work in, what is in them, and what pushes the price up.</p></blockquote>
```

### `content_1`

```html
<h2>Why I do not have one number</h2>
<p>A website is not something off a shelf. When you write to me that you want a website, all I know so far is that you want a website. I do not know how many pages it should have. I do not know whether you want to manage the content yourself. I do not know whether you need to sell through it, or whether it has to work in English too. Every one of those moves the price.</p>
<p>Here is how I do it. First we go through what you need. Then I write you a specification that says in black and white what I will build and for how much. That price holds. The invoice at the end matches the specification from the start. If you decide along the way that you want something extra, I tell you the price first and you decide.</p>
<h2>What you are actually paying for</h2>
<p>You are paying for my time and for what I know how to do with it. You are not buying a template licence, and you are not buying the hours of a salesperson who sold you the site and then disappeared. I work alone, so there is no agency overhead in the price and no coordinator forwarding me your emails.</p>
<p>I write websites in my own code. I do not assemble them from page builders and third-party plugins that need constant updating and eventually break. That costs more at the start and less over time, because there is nothing for you to repair.</p>
<h2>The three tiers I work in</h2>
<p><strong>Standard — €2,200.</strong> A custom website of up to twelve pages. It comes with simple content management, so you change texts, photos or references yourself. Another language version is possible. This is what most companies order.</p>
<p><strong>Custom — from €3,800.</strong> An online shop, a booking system or a custom application. The scope is not fixed in advance; the price follows from what the site has to do and which systems it connects to.</p>
<p><strong>Starter — €1,000.</strong> The exception, not the normal way in. A presentation site of up to five pages for a sole trader for whom a bigger scope makes no sense.</p>
<p>I am not registered for VAT. The price I quote you is the final price. What exactly each tier includes is broken down on the <a href="/en/price">pricing page</a>.</p>
```

### `content_mid`

```html
<blockquote><p>You know the price before I start working. Not when the invoice arrives.</p></blockquote>
```

### `content_2`

```html
<h2>What pushes the price up</h2>
<ul>
<li><strong>A connection to a system you already use in the company.</strong> Stock, accounting, bookings. The more two systems have to understand each other, the more work it is.</li>
<li><strong>More languages.</strong> It is not just translating text. It is another version of the whole website that someone has to maintain.</li>
<li><strong>Content that does not exist yet.</strong> If you have neither photos nor texts, they have to be made. We agree in advance what you supply and what I do, so there is no surprise on the invoice.</li>
<li><strong>Scope that grows as we go.</strong> That is why I write the specification. So we both know where the line is.</li>
</ul>
<h2>Why I am not the cheapest</h2>
<p>Because I do not want to be. A template site for a few hundred euros makes sense if all you need is a business card on the internet. At that price, go ahead and have one — I will tell you so straight and I will not try to change your mind.</p>
<p>I build websites for companies that actually use the site in their business and want it done properly. For the difference you get a solution built around the way your company works, and code that belongs to you. It is not locked up with me, and it is not locked up with a platform you could not leave.</p>
<h2>When not to buy a website from me</h2>
<p>When your budget is under €800. When you need the site in a week. When you only want to fix an existing WordPress. I do none of those, and it is better you know now than after two meetings.</p>
<h2>How you get to an exact price</h2>
<p>Write and tell me what you need. Briefly is fine. I will get back to you within 24 hours on business days and we will go through it. If it turns out that I can help, you get a specification with a specific price. If not, I will say so and I will not push anything on you.</p>
```

### `bonus`, `extra`, obrázková pole

`null` / beze změny — stejně jako u DE verze (`BlogContentDeSeeder` řádky 87–88,
obrázky se přebírají z CS řádku).

### Co tím padá

| Nález z auditu | Řeší |
|---|---|
| `Dive into our comprehensive guide…` — jediné „our" na webu | nový `perex` a `description` |
| `2. How it influences the Price of a Website` — rozbitá hlavička | osnova nahrazena |
| `…for electricians cost elektro-srnak .` — nedokončená věta s vloženým odkazem | odstavec odstraněn |
| `With websites from me , you won't face this issue.` — čechismus | odstavec odstraněn |
| `Keep your websites uncluttered ;)` — emotikon | odstraněno |
| `from 1,750 to 4,500 €`, `from a few hundred to 1,500 €` — pásma mimo ceník | nahrazeno třemi stupni z `lang/en/price.php` |
| `cost f<strong>rom 750 to 7,500 €</strong>` — bold začíná uvnitř slova | odstavec odstraněn |
| `I will get back to you within two working days` v závěru | sjednoceno na slib z OND-256 |

---

# ČÁST D — nový EN slug (P1-6)

Článek `article_id = 6`. Dnes: `/en/blog/how-simple-web-application-can-save-your-business-millions`.

CS i DE už tohle framování opustily: CS má nový slug `kdy-se-vyplati-aplikace-na-miru`
(`BlogContentSeeder::NEW_CS_SLUGS`), DE `wann-sich-eine-eigene-anwendung-lohnt`.
EN zůstalo samo se starými „miliony".

| Co | Staré | Nové |
|---|---|---|
| slug (`article_slugs.slug`, `article_id=6`, `locale='en'`) | `how-simple-web-application-can-save-your-business-millions` | `when-a-custom-app-beats-a-spreadsheet` |

**Proč tenhle tvar:** odpovídá skutečnému obsahu i CS/DE titulku („Kdy se firmě vyplatí
aplikace na míru místo tabulky v Excelu" / „Wann sich eine eigene Anwendung statt Excel
lohnt"), je krátký, čitelný a trefuje reálný search intent (`custom app vs spreadsheet`,
`when to replace excel with an app`). Neslibuje číslo, které text sám nedokládá.

**301 se nemusí programovat.** Mechanismus už existuje: starý slug zůstane v
`article_slugs` jako `active = 0` a `PageController::article()` na něj udělá 301 — přesně
tak, jak to proběhlo u CS slugů v OND-204 (viz komentář u `NEW_CS_SLUGS`:
„Starý slug zůstane v `article_slugs` jako neaktivní (audit + 301 lookup), nový se stane
kanonickým."). Nový slug musí být unikátní **napříč celou tabulkou**, ne jen v rámci
locale — `when-a-custom-app-beats-a-spreadsheet` se s ničím nekryje (ověřeno proti
`database/sql/article_slugs.sql` a proti všem 15 živým slugům).

### Doprovodný titulek a perex — jinak si stránka odporuje

Slug sám nestačí: H1 článku dnes zní `How a simple web application can save your business
millions` a perex slibuje `A web application can save your business millions`. Nová adresa
by mluvila proti nadpisu hned pod ní. Minimální doprovod:

| Pole | Staré | Nové |
|---|---|---|
| `article_translations.title` (`article_id=6`, `locale='en'`) | `How a simple web application can save your business millions` | `When a custom app is worth it instead of a spreadsheet` |
| `article_translations.description` | `Tired of dealing with manual processes in your business? From human error to inefficient resource allocation, it's time to let go of the old ways and embrace the future. A web application can save your business millions by streamlining communications, automating tasks, and allocating resources efficiently.` | `I spent eighteen years in Toyota logistics writing applications for the shop floor. Here is how you can tell that a spreadsheet has stopped being enough for your company.` |

> **Tělo článku 6 tímhle opravené není.** Zůstává starý strojový překlad (Amazon,
> Facebook, Shopify, „$3.7 billion in 2022"), který s CS/DE verzí nemá společného skoro
> nic. Kompletní přepis je mimo zadání OND-262 — viz „Co zůstává otevřené".

---

# ČÁST E — zbylá dvě P2 a drobnosti P3

E-5 a E-6 jsou **P2** z auditu (P2-11 a P2-5) — jsou v „Hotovo když". Zbytek je P3,
tedy nad rámec zadání; znění dodávám, ať se to nemusí psát znovu.

| # | URL | Kde | Staré | Nové | Proč |
|---|---|---|---|---|---|
| E-1 | /de/kontakt | `resources/views/pages/contact.blade.php:91` — **napevno v šabloně** | `<dt>E-mail</dt>` | lang klíč; cs `E-mail`, en `Email`, de `E-Mail` | Německy se píše jen „E-Mail"; formulář o kus výš to má správně („E-Mail-Adresse"), nadpis bloku ne. Dnes je řetězec natvrdo v šabloně, takže je stejný ve všech třech jazycích — Engineer musí nejdřív založit klíč. |
| E-2 | /en/ | `lang/en/home.php` → `projects.items[].outcome` (ř. 211) | `… the client reports a noticeably stronger brand credibility.` | `… the client reports noticeably stronger brand credibility.` | Nepočitatelné „credibility" člen nebere. |
| E-3 | /de/ | `lang/de/home.php` → `craft.facts[].text` (ř. 358) | `… Deshalb ragt nichts heraus — und deshalb können Sie es unten selbst ausprobieren.` | `… Deshalb wirkt nichts fehl am Platz — und deshalb können Sie es unten selbst ausprobieren.` | Kalk z „nic nevyčnívá". „herausragen" znamená v němčině spíš „vynikat", tedy opak zamýšleného. |
| E-4 | /en/about | `lang/en/about.php` → `story.items[].text` (ř. 24) | `Programming was more enjoyable to me than anything else.` | `I enjoyed programming more than anything else.` | Doslovný překlad „bavilo mě to víc než cokoli jiného"; anglicky se to říká činně. |
| E-5 | /de/kontakt | `lang/de/contact.php` → `hero.upline` (ř. 62), `hero.eyebrow` (ř. 64), `hero.heading` (ř. 65) | `Sie schreiben direkt mir.` / `Sie schreiben direkt mir` / `Sie schreiben direkt mir, Ondřej.` | `Sie schreiben mir direkt.` / `Sie schreiben mir direkt` / `Sie schreiben direkt an mich, Ondřej.` | **P2-11.** Příznakový slovosled (kalk „píšete přímo mně"). U nadpisu s oslovením je přirozenější „an mich". Tři klíče, tři výskyty — opravit všechny, jinak vznikne nekonzistence uvnitř jedné stránky. |
| E-6 | /de/kontakt | `lang/de/contact.php` → `steps.items[1].title` (ř. 80) | `Wir vereinbaren 30 Minuten Gespräch` | `Wir vereinbaren ein 30-minütiges Gespräch` | **P2-5.** Chybí člen — gramaticky vadné, zní telegraficky. |
| E-7 | /de/kontakt | `lang/de/contact.php` → `address_registration` (ř. 19) | `Unternehmens-ID 19231407, nicht umsatzsteuerpflichtig` | `IČO (tschechische Unternehmens-ID) 19231407, nicht umsatzsteuerpflichtig` | Německý klient si podle „IČO" najde firmu v českém rejstříku; samotné „Unternehmens-ID" je nedohledatelné. |
| E-8 | DE plošně | `lang/de/*.php` — 21 výskytů: `blog.php` (4), `home.php` (3), `layout.php` (1), `price.php` (4), `projects.php` (9) | `Webseite` / `Webseiten` | `Website` / `Websites` | Web míchá obojí — 63× „Website" proti 21× „Webseite" v týchž kontextech. „Webseite" je striktně vzato *jedna podstránka*, ne celý web. Týká se i DB (badge kategorie „Webseite" na detailech projektů a v „Weitere Projekte"). |
| E-9 | /en/price | `lang/en/price.php` → `note` (ř. 89) | `Not VAT-registered — these prices are final, nothing is added.` | `I am not registered for VAT — the prices above are final, no VAT is added.` | Audit flagoval jen DE variantu (A3-2), ale EN má tutéž bezpodmětou stavbu. Když se mění DE, ať jsou obě mutace v ich-formě stejně. **Volitelné.** |

---

# Co zůstává otevřené

1. **Zbývající čtyři EN články jsou pořád starý strojový překlad.** Opravuju jen ten
   o ceně (P1-4) a titulek + perex u aplikace vs. Excel (P1-6). Beze změny zůstávají
   `does-your-business-need-a-website`, `how-to-define-website-development-requirements`,
   `website-redesign-reasons-signals-and-how-to-do-it` a **tělo**
   `how-simple-web-application-can-save-your-business-millions`. CS i DE verze těchhle
   článků byly přepsané (OND-204, OND-218/219), EN ne — je to výslovně známý dluh,
   zapsaný v hlavičce `BlogContentDeSeeder`: „EN překlady jsou pořád ze starého importu
   a s přepsanou CS verzí se nekryjí — mimo rozsah tohoto issue." Rozsah: čtyři články
   po ~3 500 znacích. Stačí slovo v komentáři a připravím je stejným způsobem jako
   článek v části C.
2. **EN slug článku 4** (`how-to-define-website-development-requirements`) drží staré
   framování „definovat požadavky", které CS opustilo (`jak-se-pripravit-na-novy-web`)
   i DE (`vorbereitung-auf-die-neue-website`). Souvisí s bodem 1 — má smysl řešit až
   s přepisem těla.
3. **Mechanismus pro `duration`** (lang klíč vs. sloupec v translations) je rozhodnutí
   Engineera, ne copy. Texty z A1 platí pro obě varianty.
4. **DSGVO obsah na /de/datenschutz** — A8 opravuje jazyk, ne právní náležitosti.
   Právní posouzení nikdo nedělal.
