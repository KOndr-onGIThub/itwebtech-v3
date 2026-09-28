<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-267 + OND-275 (audit OND-254, redliny OND-262 a OND-274) — EN obsah blogu.
 *
 * Anglické překlady dosud nebyly v žádném seederu: pocházejí ze starého SQL
 * importu `database/sql/article_translations.sql`, který `EnsureArticlesSeededSeeder`
 * pouští jen do prázdné tabulky. Na stagingu i produkci články existují, takže
 * se změna dumpu nikdy neprojeví — a na čerstvé DB by se naopak neprojevila
 * migrace. Obsah proto žije tady a aplikuje se dvěma cestami, stejně jako
 * u DE (`BlogContentDeSeeder`):
 *   - migrace 2026_09_23_100300_ond267_en_blog_obsah.php (OND-267)
 *     a 2026_09_23_100400_ond275_en_blog_obsah.php (OND-275) → existující DB,
 *   - `EnsureArticlesSeededSeeder` po importu dumpů → čerstvá DB.
 *
 * Rozsah je od OND-275 kompletní — všech pět publikovaných EN článků tady má
 * aktuální znění a se starým importem se už nepotkají:
 *   - článek 3 (cena) — část C redlinu OND-262,
 *   - článek 6 (aplikace vs. Excel) — titulek, description a slug z části D
 *     redlinu OND-262, perex a tělo z části A4 redlinu OND-274,
 *   - články 4, 10 a 13 — části A2, A1 a A3 redlinu OND-274, včetně nového
 *     slugu čtyřky.
 *
 * Obrázková pole (`img_preview`, `img_main`, `img_mid`, `img_end`), `bonus`
 * a `extra` se u článků 3, 4, 6, 10, 13 nepřepisují: EN řádky existují
 * z importu i s obrázky, oba redliny je nechávají beze změny. Články 1, 5, 7,
 * 9, 11, 12 (OND-432) je v `articles()` nesou, protože se u nich mění. Proto tady není `imagesFromCs()` jako
 * v DE seederu — ten zakládal řádky, které do té doby vůbec neexistovaly.
 *
 * Seeder je idempotentní: updaty jsou absolutní, slug se zakládá přes
 * updateOrInsert. Opakované spuštění nic nerozbije.
 */
class BlogContentEnSeeder extends Seeder
{
    /**
     * Nové EN slugy. Musí být unikátní napříč celou tabulkou `article_slugs`,
     * ne jen v rámci locale: `PageController::article()` dohledává slug bez
     * ohledu na locale a teprve pak kontroluje, jestli sedí.
     *
     * 301 se neprogramuje — starý slug zůstane jako `active = 0` a controller
     * na něj přesměruje, přesně jak to proběhlo u CS slugů v OND-204.
     *
     * article_id => [starý slug, nový slug]
     */
    public const NEW_EN_SLUGS = [
        4 => [
            'how-to-define-website-development-requirements',
            'how-to-prepare-for-a-new-website',
        ],
        6 => [
            'how-simple-web-application-can-save-your-business-millions',
            'when-a-custom-app-beats-a-spreadsheet',
        ],
        // OND-432: anglicky se hledá „keyword research“, titulek to nese.
        9 => [
            'how-to-do-keyword-analysis-step-by-step',
            'how-to-do-keyword-research-step-by-step',
        ],
        // OND-432: starý slug byl dlouhý a na nový titulek nesedí.
        12 => [
            'basic-step-for-successful-web-design-competitive-analysis',
            'website-competitor-analysis',
        ],
    ];

    /**
     * Články, u kterých se `bonus` a `extra` mažou.
     *
     * Redline OND-274 počítal s tím, že tyhle dva sloupce zůstanou beze změny
     * „stejně jako u DE". U DE ale prázdné jsou — `BlogContentDeSeeder` je
     * zakládá jako `null` — a prázdné jsou i u CS po OND-204. Jen EN řádky
     * v nich pořád mají bloky ze starého strojového importu a `bonus`
     * `pages/article.blade.php` vykresluje hned pod textem článku. Bez
     * tohohle kroku by pod novým zněním zůstalo přesně to, co tabulky
     * „Co tím padá" odepisují:
     *   - 6: závěr se slibem „a web app can save your business millions“ —
     *     tedy to, kvůli čemu se měnil titulek i slug,
     *   - 10: „How a Website Can Transform Your Business“ s vymyšlenými
     *     modelovými příběhy a redakčním „we“ v `extra`,
     *   - 13: „Examples of successful redesigns“ — Crazy Egg a nedoložené
     *     statistiky se zdrojem `davidkoci.cz`, tedy konkurenční web.
     * Čtyřka je v seznamu pro jistotu, prázdná je už dneska.
     *
     * `extra` se na webu nevykresluje (žije jen v modelu a ve Filamentu),
     * maže se kvůli paritě s CS/DE a aby se odtud text nevrátil zpátky.
     *
     * Článek 3 je v seznamu na základě verdiktu z OND-287: jeho `bonus` není
     * strojový překlad, ale poznámka o transparentnosti cen s odkazem na
     * `itwebtech.cz/projects/elektro-srnak`, která zbyla po předchozí verzi
     * textu. Přepsané tělo (část C redlinu OND-262) mluví o ceně „Custom —
     * from €3,800", blok pod ním posílá čtenáře na web za 7 000 Kč a bere
     * pointu závěrečnému CTA. CS ani DE mutace ten motiv nemají, takže
     * smazáním se mutace naopak srovnají. V `extra` je navíc v anglické větě
     * odkaz na český ceník.
     */
    private const CLEARED_BLOCKS = [3, 4, 6, 10, 13];

    public function run(): void
    {
        $this->note('[blog-content-en] nasazuji EN texty blogu (OND-267 + OND-275)...');

        foreach ($this->articles() as $articleId => $content) {
            $updated = DB::table('article_translations')
                ->where('article_id', $articleId)
                ->where('locale', 'en')
                ->update($content + ['updated_at' => now()]);

            if ($updated === 0) {
                $this->note("[blog-content-en] POZOR: EN překlad článku {$articleId} nenalezen, přeskakuji.");
            }
        }

        DB::table('article_translations')
            ->where('locale', 'en')
            ->whereIn('article_id', self::CLEARED_BLOCKS)
            ->where(function ($query) {
                $query->whereNotNull('bonus')->orWhereNotNull('extra');
            })
            ->update(['bonus' => null, 'extra' => null, 'updated_at' => now()]);

        foreach (self::NEW_EN_SLUGS as $articleId => [, $newSlug]) {
            if (! DB::table('articles')->where('id', $articleId)->exists()) {
                continue;
            }

            DB::table('article_slugs')
                ->where('article_id', $articleId)
                ->where('locale', 'en')
                ->where('slug', '!=', $newSlug)
                ->update(['active' => 0, 'updated_at' => now()]);

            DB::table('article_slugs')->updateOrInsert(
                ['slug' => $newSlug, 'locale' => 'en'],
                [
                    'article_id' => $articleId,
                    'active'     => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }

        $this->note('[blog-content-en] hotovo.');
    }

    private function note(string $message): void
    {
        $this->command?->info($message);
    }

    /**
     * EN texty z redlinů `ond262/redline-en-de.md` (části C a D) a
     * `ond274/redline-en-blog.md` (části A1–A4) na větvi OND-259.
     *
     * Obrázková pole se nepřepisují — EN řádky je už mají z importu a oba
     * redliny u nich výslovně říkají „beze změny".
     *
     * Články 1, 5, 7, 9, 11, 12 jsou z dokumentu `clanky-final-en` (OND-430)
     * s redliny R1 z `review-preklady` (OND-431) a obrázková pole nesou,
     * viz OND-432 níž.
     *
     * V textech jsou typografické uvozovky a apostrofy (“ ” ’) a `€4,000`
     * s čárkou jako oddělovačem tisíců. Je to anglická konvence z redlinu,
     * ne překlep — nepřepisovat na rovné.
     *
     * @return array<int, array<string, string|null>>
     */
    public function articles(): array
    {
        return [

            // ----------------------------------------------------------------
            // 3 — Kolik stojí web → How much does a website cost
            // Slug `how-much-does-a-website-cost` zůstává — funkční SEO adresa.
            // Text zrcadlí aktuální CS/DE verzi odstavec po odstavci a čísla
            // bere z `lang/en/price.php` (rozpětí €3,500–€8,000, vstupní cena
            // from €1,900 — OND-389). Když se ceny v lang změní, srovnat i tenhle text.
            // ----------------------------------------------------------------
            3 => [
                'title'       => 'What a custom website costs and what goes into the price',
                'description' => 'What a website costs with me, what the price includes, and what moves it up or down. So you know up front whether I fit your budget.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Price is the first thing people ask me about, and it is the right question. But nobody can honestly give you a single number. A €1,900 website and an €8,000 website are two different things. So here it is straight: what a website costs with me, what is in the price, and what moves it up or down.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Why I do not have one number</h2>
                    <p>A website is not something off a shelf. When you write to me that you want a website, all I know so far is that you want a website. I do not know how many pages it should have. I do not know whether you want to manage the content yourself. I do not know whether you need to sell through it, or whether it has to work in English too. Every one of those moves the price.</p>
                    <p>Here is how I do it. First we go through what you need. Then I write you a specification that says in black and white what I will build and for how much. That price holds. The invoice at the end matches the specification from the start. If you decide along the way that you want something extra, I tell you the price first and you decide.</p>
                    <h2>What you are actually paying for</h2>
                    <p>You are paying for my time and for what I know how to do with it. You are not buying a template licence, and you are not buying the hours of a salesperson who sold you the site and then disappeared. I work alone, so there is no agency overhead in the price and no coordinator forwarding me your emails.</p>
                    <p>I write websites in my own code. I do not assemble them from page builders and third-party plugins that need constant updating and eventually break. That costs more at the start and less over time, because there is nothing for you to repair.</p>
                    <h2>What it comes to with me</h2>
                    <p>Most projects land between €3,500 and €8,000. The smallest thing I build is a simple presentation site, from €1,900. What yours will cost depends mainly on how much work it takes for the site to do its job.</p>
                    <p><strong>Presentation site — so customers can check you out.</strong> Who you are, what you do and how to reach you. For sole traders and small businesses for whom a bigger scope makes no sense. It will be fast, it will work properly on a phone, and no link to your enquiry form will be broken. Do not expect it to start bringing in work on its own — that takes more work than the smallest scope allows. But it will be done properly.</p>
                    <p><strong>Business site — so customers see why it should be you.</strong> More services, more languages, references and a blog. A custom website with simple content management, so you change texts, photos or references yourself. This is what most companies order.</p>
                    <p><strong>E-shops and applications — so the system does the work for you.</strong> An online shop, a booking system or a custom application. The scope is not fixed in advance; the price follows from what the system has to do and which systems it connects to.</p>
                    <p>The number of pages does not decide the price. What matters is how many different kinds of page the site needs. A blog is one kind: I build it once, and it makes no difference whether it ends up with one article or a hundred. Coming up with and writing those articles is separate work, though. The same goes for a product catalogue or references. So a one-page site can cost more than a five-page one if it has more to explain and do.</p>
                    <p>I am not registered for VAT. The price I quote you is the final price. What exactly each level includes is broken down on the <a href="/en/price">pricing page</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>You know the price before I start working. Not when the invoice arrives.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>What pushes the price up</h2>
                    <ul>
                    <li><strong>More services or products that need explaining clearly.</strong> Each one has to be thought through and described so that customers understand it.</li>
                    <li><strong>A connection to a system you already use in the company.</strong> Stock, accounting, bookings. The more two systems have to understand each other, the more work it is.</li>
                    <li><strong>More languages.</strong> It is not just translating text. It is another version of the whole website that someone has to maintain.</li>
                    <li><strong>Photos that do not exist yet.</strong> If you do not have them, they have to be taken or bought. We agree in advance what you supply and what I do, so there is no surprise on the invoice. I write the copy myself from your answers; that is part of every website. I write about why content matters more than looks in the article <a href="/en/blog/which-is-more-important-design-or-content">Design or content?</a></li>
                    <li><strong>Scope that grows as we go.</strong> That is why I write the specification. So we both know where the line is.</li>
                    </ul>
                    <h2>What brings the price down</h2>
                    <ul>
                    <li><strong>Photos you already have in good quality.</strong> Nothing needs to be shot or bought.</li>
                    <li><strong>One person on your side who makes the decisions.</strong> We do not wait for five people to sign off.</li>
                    <li><strong>Complete, quick answers to my questions.</strong> I write the copy from them, and the sooner I have them in full, the less time goes on follow-up questions.</li>
                    <li><strong>One language.</strong> One version of the website to build and maintain.</li>
                    <li><strong>You fill in the content yourself.</strong> I show you how, and you put the texts and photos on the site instead of me.</li>
                    </ul>
                    <h2>Why I am not the cheapest</h2>
                    <p>Because I do not want to be. A template site for a few hundred euros makes sense if all you need is a business card on the internet. At that price, go ahead and have one — I will tell you so straight and I will not try to change your mind.</p>
                    <p>I build websites for companies that actually use the site in their business and want it done properly. For the difference you get a solution built around the way your company works, and code that belongs to you. It is not locked up with me, and it is not locked up with a platform you could not leave.</p>
                    <h2>When not to buy a website from me</h2>
                    <p>When you need the site in a week. When you only want to fix an existing WordPress. I do neither, and it is better you know now than after two meetings.</p>
                    <h2>How you get to an exact price</h2>
                    <p>Write and tell me what you need. Briefly is fine. I will get back to you by the next business day and we will go through it. If it turns out that I can help, we agree on a specification with a specific price. If not, I will say so and I will not push anything on you.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 4 — Co si připravit, než oslovíte vývojáře (část A2 redlinu).
            // Kompletní náhrada strojového překladu + nový slug
            // `how-to-prepare-for-a-new-website` (část B) — starý
            // `how-to-define-website-development-requirements` sliboval metodiku
            // sběru požadavků, kterou článek nedodává; CS i DE tohle framování
            // opustily už dřív.
            // ----------------------------------------------------------------
            4 => [
                'title'       => 'What to prepare before you contact a web developer',
                'description' => 'Nine questions we will have to answer anyway. If you go through them in advance, we both save time and you get a more accurate price the first time round.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Almost nobody writes to me with a finished brief, and that is fine. Asking is what I am here for. But if you go through the questions below in advance, we cut the whole round trip by a few weeks and you get a more accurate price straight away.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>You do not need an answer to everything</h2>
                    <p>This article is not a test. It is a list of things I am going to ask about anyway. If you know the answer, write it to me straight away. If you do not, write “I do not know” and we will go through it together. That is a perfectly legitimate answer and I hear it often.</p>
                    <h2>1. What the website should do for your company</h2>
                    <p>Should it bring in enquiries? Save you phone calls because people read the answers for themselves? Sell? Or just exist so that nobody rules you out of a tender? Those are all valid goals, but they lead to three different websites.</p>
                    <h2>2. Who it is for</h2>
                    <p>The owner of a small company, a buyer at a large one and an end customer all read completely differently. The more specifically you describe who calls you today, the better I can write the page structure.</p>
                    <h2>3. What a person should do on the website</h2>
                    <p>One main thing per page. Call, fill in the form, download the price list, order. When a website is meant to do five things at once, it does none of them properly. I have written down what else decides whether a website works in the article <a href="/en/blog/how-to-create-a-successful-website">How to create a successful website</a>.</p>
                    <h2>4. What people do not know about you and should</h2>
                    <p>This is the most valuable thing you can give me. Usually it is something you say in meetings over and over and it is missing from the website.</p>
                    <h2>5. What does not work on your current website</h2>
                    <p>If you already have a website, write to me specifically about what annoys you on it. Can it not be edited? Can it not be found? Does it look old? Does nothing come through it?</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>The worst brief is “make it look nice”. The best one is “this specific thing annoys me”.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>6. Who will manage the content</h2>
                    <p>If you want to change texts and photos yourself, I will build you simple content management and show you how it works. If you do not, we do not have to build it and you save money. Either is fine, I just need to know in advance.</p>
                    <h2>7. What you already have</h2>
                    <p>A logo, photos, access to the domain and the hosting, a shop with products in some system. The more of it there is, the less has to be made from scratch. You do not have to write the texts; I write them from your answers to these questions. If you have a finished <a href="/en/blog/website-competitor-analysis">competitor analysis</a> or <a href="/en/blog/how-to-do-keyword-research-step-by-step">keyword research</a>, send me that too.</p>
                    <h2>8. What your budget is</h2>
                    <p>I know nobody likes this question. I ask it so that I can tell you straight away whether I can do it for that price. If I cannot, I say so immediately and neither of us wastes time.</p>
                    <h2>9. When you need it by</h2>
                    <p>If you have a fixed date because of a trade fair or an opening, tell me right at the start. That is how I know whether I can make it.</p>
                    <h2>What happens next</h2>
                    <p>From your answers I write a specification. It says what I will build and a price that holds. You decide on the build only once the finished specification is in front of you.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 6 — Aplikace vs. Excel.
            // Titulek, description a slug `when-a-custom-app-beats-a-spreadsheet`
            // nasadilo OND-267 (část D redlinu OND-262). Tady je zbytek: perex
            // a tělo (část A4). Bez perexu by pod novým titulkem zůstal starý
            // slib „a web app can save your business millions“.
            // ----------------------------------------------------------------
            6 => [
                'title'       => 'When a custom app is worth it instead of a spreadsheet',
                'description' => 'I spent eighteen years in Toyota logistics writing applications for the shop floor. Here is how you can tell that a spreadsheet has stopped being enough for your company.',
                'perex'       => <<<'HTML'
                    <blockquote><p>I worked at Toyota for eighteen years. I started as a labourer in logistics and I finished as a senior specialist in the project team. In that time I saw plenty of processes running on spreadsheets and paper, and I replaced a few of them with an application. Here is how you can tell that you are at that point too.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>The spreadsheet is not the enemy</h2>
                    <p>Excel is an excellent tool and plenty of companies can run on it for years without trouble. I am not going to talk you into needing an application. Most of the time you do not.</p>
                    <p>The problem starts the moment several people work in the spreadsheet at once, the moment something gets printed from it that people then work to, and the moment a mistake in it costs money. That is where the question starts to make sense.</p>
                    <h2>Five signs the spreadsheet has hit its limit</h2>
                    <ol>
                    <li><strong>There are several versions of the same spreadsheet</strong> and nobody knows exactly which one is the valid one.</li>
                    <li><strong>Somebody retypes data from one system into another.</strong> By hand, every day, over and over.</li>
                    <li><strong>When that person is off sick, the work stops.</strong> The process hangs on one person and their spreadsheet.</li>
                    <li><strong>Mistakes are found late.</strong> A typo in a cell only shows up at the customer.</li>
                    <li><strong>Nobody can say where things stand right now.</strong> The number has to be assembled from five files.</li>
                    </ol>
                    <p>If one of those fits, leave it be. If three or more fit, it is worth doing the sums.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>You do not need an application because it is modern. You need one when the manual work costs you more than building it does.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>What I did at Toyota</h2>
                    <p>My job was to make logistics more efficient in step with assembly. In production you cannot afford for the line to stop, so every change has to be thought through in advance and tested.</p>
                    <p>I programmed a web application there called TSM that replaced part of the manual work in logistics. Over time I put several more applications into operation.</p>
                    <p>None of it was about spectacular technology. It was about finding the place where time was being wasted and removing that place. I think the same way today when I build a custom application for a company.</p>
                    <h2>How to do the sums yourself</h2>
                    <p>Take an activity that is done by hand. How many minutes a day does it take? How often a month does a mistake happen with it, and what does that mistake cost? Multiply it by twelve months. If you end up with a number in the thousands of euros a year, a custom application pays for itself in a few years and after that it only saves. If you end up with a few hundred euros, leave it alone and buy yourself a decent spreadsheet instead.</p>
                    <p>I can give you a rough estimate as early as our intro call. If it comes out that it is not worth it, I will tell you.</p>
                    <h2>What a custom application is and what it is not</h2>
                    <p>It is a program built around exactly how your company works. Records, orders, planning, reports. It runs in a browser, so you install nothing and you can get to it from your phone too.</p>
                    <p>It is not an off-the-shelf system that you have to adapt to. That is the main difference, and also the reason it costs more than a monthly subscription to something in a box.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 10 — Potřebuje firma web (část A1 redlinu).
            // Kompletní náhrada obsahu. Slug `does-your-business-need-a-website`
            // zůstává — sedí na nový titulek a je to funkční SEO adresa.
            // ----------------------------------------------------------------
            10 => [
                'title'       => 'Does your business need a website? Sometimes not',
                'description' => 'I am not impartial about this — I build websites. Even so, there are situations where a website will not help you. Here is which ones and what to do instead.',
                'perex'       => <<<'HTML'
                    <blockquote><p>I make my living building websites, so I am not writing this impartially and I will not pretend otherwise. Even so, I know cases where a website does not help a company and the money is better spent elsewhere. Here it is straight: which ones.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>When you really do not need a website</h2>
                    <p><strong>You are fully booked and the work comes from referrals.</strong> If you have months of work ahead of you and you would turn new enquiries down anyway, a website will bring you nothing right now. Come back to it when you want to grow or to change the kind of work you take.</p>
                    <p><strong>You sell to one large customer.</strong> If your company stands on two long-term contracts, a website is a business card, not a sales channel. A simple page with contact details is enough, and you do not need to spend €4,000 on it.</p>
                    <p><strong>There is nobody to pick up the phone.</strong> A website that brings in enquiries nobody answers is worse than no website at all. The customer remembers that you did not get back to them.</p>
                    <p><strong>You are looking for a miracle.</strong> A website is a tool, not a solution. If the company is not clear about what it sells and to whom, a website will not fix that. It will only write it in bigger letters.</p>
                    <h2>When a website does make sense</h2>
                    <p><strong>People check you out before they call.</strong> Almost everyone does this now. If all they find is a directory listing from 2019, they mark you down.</p>
                    <p><strong>You keep explaining the same thing.</strong> If you repeat in every meeting how the work goes and what is included in the price, the website explains it for you. You then talk to people who already know.</p>
                    <p><strong>Your competitors look better than they work.</strong> That is uncomfortable, but it decides.</p>
                    <p><strong>You want different work from the work you have.</strong> A website is the cheapest way to show that you do bigger and more demanding jobs too.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>A website will not win the work for you. But it says up front what you otherwise explain again in every meeting.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>What a website cannot do</h2>
                    <p>I will not promise you how many enquiries it will bring. I have no influence over what the demand in your field looks like, what your prices are, or how fast you answer. Anyone who promises you that number is guessing.</p>
                    <p>What I can influence is the work I deliver. That the website is fast and understandable, that it looks good on a phone, and that nothing falls apart on it in two years.</p>
                    <h2>Before you decide</h2>
                    <p>Try to answer one question. If someone who had never heard of you landed on your website tomorrow, would they understand within ten seconds what you do and whether it is for them? If not, that is where the problem is, and it makes no difference whether you have a website or not. I write about what else a website has to get right to work in the article <a href="/en/blog/how-to-create-a-successful-website">How to create a successful website</a>.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 13 — Redesign webu (část A3 redlinu).
            // Kompletní náhrada obsahu. Slug
            // `website-redesign-reasons-signals-and-how-to-do-it` zůstává: pořád
            // popisuje, co v článku je, a adresa je zaběhlá.
            // ----------------------------------------------------------------
            13 => [
                'title'       => 'When a website redesign is worth it and when it is money wasted',
                'description' => 'Five reasons a website redesign makes sense and three reasons it does not. Plus what to watch out for so that your traffic does not drop after the relaunch.',
                'perex'       => <<<'HTML'
                    <blockquote><p>A redesign usually comes up the moment somebody stops liking the website. That is the weakest reason I know. Here is when redoing a website makes sense, when it does not, and what most often goes wrong along the way.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Five reasons a redesign makes sense</h2>
                    <p><strong>1. The website cannot be maintained.</strong> Changing a phone number means writing to somebody who gets back to you in a week. That on its own is worth a redesign.</p>
                    <p><strong>2. It is unusable on a phone.</strong> Most people look at websites on a phone these days. If they have to zoom in and scroll sideways, they leave.</p>
                    <p><strong>3. The website is falling apart or going down.</strong> Typically with page builders assembled from plugins by different authors. One update and the order form stops working.</p>
                    <p><strong>4. The company has changed.</strong> You do something different, you want to sell something different, you are in a different price bracket. The website stayed where you were five years ago.</p>
                    <p><strong>5. The websites of the people you compete with look a class better.</strong> The customer compares you side by side whether you like it or not. I describe how to take a proper look at them in the article on <a href="/en/blog/website-competitor-analysis">competitor analysis</a>.</p>
                    <h2>Three situations where you should keep your money</h2>
                    <p><strong>The website is two years old and it works.</strong> Age on its own is not a reason. If it can be maintained, it is fast and people find what they need on it, leave it alone.</p>
                    <p><strong>You do not like it, but your customers do not mind it.</strong> Your taste and your customer’s taste are not the same thing. Before you put a few thousand euros into it, ask five clients what they were missing on the website.</p>
                    <p><strong>The real problem is somewhere else.</strong> If enquiries are not coming because you are three classes more expensive than everyone around you and you explain that nowhere, a new look will not fix it.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>A redesign is work on the content and the structure. The look is what follows from it.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>What most often goes wrong in a redesign</h2>
                    <p><strong>The page addresses get thrown away.</strong> The new website has a different structure and the old addresses stop working. Search engines and links from other people’s websites suddenly lead nowhere. The fix is simple and it is done before launch: the old address has to redirect permanently to the new one. I want you to demand that from whoever builds your website. I explain why search positions are so valuable in the article <a href="/en/blog/what-is-seo-and-why-is-it-so-important">What is SEO</a>.</p>
                    <p><strong>Only the look gets redone.</strong> The texts are copied across one to one, including the ones nobody understood. The website then looks new and works just as badly. I write about why content matters more than looks in the article <a href="/en/blog/which-is-more-important-design-or-content">Design or content?</a></p>
                    <p><strong>Things that were working disappear.</strong> Sometimes the old website has a page that half the traffic goes to. Before anything is deleted, somebody needs to look at the statistics.</p>
                    <p><strong>Nobody carries the content over.</strong> References, photos of finished jobs, documents to download. There is usually more of it than anyone expects.</p>
                    <h2>How I approach a redesign</h2>
                    <p>First I look at what works on the old website and I keep that. Then we go through what the website is supposed to do and who it is supposed to say it to. Only after that do we deal with how it will look. I sort the page addresses out before launch, not after.</p>
                    <p>I write my own code, without ready-made plugins by other authors. Those are usually the reason a website falls apart after a while and has to be redone.</p>
                    HTML,
            ],

            // ================================================================
            // OND-432 — články 1, 5, 7, 9, 11, 12 (dokument `clanky-final-en` na OND-430
            // + redliny `review-preklady` z OND-431). Na rozdíl od článků výš nesou
            // i obrázková pole a `bonus`/`extra` = null: starý import v nich měl
            // bloky, které by se vykreslily pod novým textem.
            // Obrázky s českým textem se v EN vynechávají (findom, casti_domeny, u dvanáctky
            // i hlavní obrázek a náhled). img_preview a img_main ostatních článků se nemění.
            // ================================================================

            // ----------------------------------------------------------------
            // 1 — How to choose a domain name (slug how-to-choose-the-perfect-domain-name, beze změny)
            // ----------------------------------------------------------------
            1 => [
                'title'       => 'How to choose a domain name people will remember',
                'description' => 'How to choose a domain that is easy to spell and remember. Ten rules for picking a domain name, what it costs and what to check before you register.',
                'perex'       => <<<'HTML'
                    <blockquote><p>You choose a domain once and then put it on invoices, business cards and under every email for years. You can change it later, but it costs work and some of the people who already know you. I have written down how to go about choosing a domain name, what to check before you register it and what I have run into with clients myself.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>In short: how to choose a domain</h2>
                    <ol>
                    <li><strong>It fits who you are.</strong> Your company name, your own name, or what you do.</li>
                    <li><strong>Short.</strong> Ideally 8 to 12 characters, without digits or hyphens.</li>
                    <li><strong>Memorable the first time.</strong> No abbreviations that mean nothing to anyone.</li>
                    <li><strong>Easy to type after hearing it.</strong> You say it once and the other person writes it down correctly.</li>
                    <li><strong>A word from your field only if it fits.</strong> It will not move you up in search results.</li>
                    <li><strong>Room to grow.</strong> Do not tie yourself to one product or one town.</li>
                    <li><strong>Available and free of awkward associations.</strong> Check that nobody has it and type it into a search engine.</li>
                    <li><strong>No bad history.</strong> Look at what used to run on it.</li>
                    <li><strong>On your country’s ending</strong> if you sell in one country.</li>
                    <li><strong>Nobody else’s trade mark.</strong> It saves you a dispute.</li>
                    </ol>
                    <p>Below I go through each point, with examples from practice.</p>
                    <h2>What is a domain name and do you even need one?</h2>
                    <p>A domain name, or domain for short, is the address a person types into the browser to get to your website. Computers on the internet find each other by numeric IP addresses. A domain is the name people remember instead. For example <em>mycompany.com</em>.</p>
                    <p>A domain is worth having even if you do not have a website. Email alone is reason enough. The address <em>info@mycompany.com</em> comes across differently from <em>mycompany@gmail.com</em>. You choose the part before the @ yourself, so every employee can have their own mailbox and all of them carry the company name.</p>
                    <p>Do you need a website, or are a domain and email enough for now? I cover that in the article <a href="/en/blog/does-your-business-need-a-website">Does your business need a website?</a></p>
                    <h2>1. How to choose a domain name that fits your business</h2>
                    <p><strong>A small local business</strong> can afford a domain that says what it does. If you rent out boats, <em>onthewater.com</em> tells a stranger at first glance what it is about.</p>
                    <p><strong>A company that wants to grow</strong> is in a different position. The domain <em>beds.com</em> is great until you also start selling tables and garden furniture. Then it holds you back. That is why larger companies usually build on a brand, not on a description of what they sell.</p>
                    <p><strong>If you work on your own</strong>, consider your own name, for example <em>janesmith.com</em>. A brand built on a name is much easier to grow than one built on an impersonal company name. The name just has to be available and easy to spell. I come to that in point 4.</p>
                    <p>For every option, do at least a quick <a href="/en/blog/website-competitor-analysis">check of your competitors</a>. You do not want people to confuse you with someone who does the same thing two streets away.</p>
                    <p>If you are only just setting up the company, choose the name and the domain together. Check that the domain is free before you register the company name and have a logo made.</p>
                    <h2>2. Domain length and allowed characters</h2>
                    <p>The shorter, the better. Technically a domain can be up to 63 characters long, but ideally it fits into 8 to 12. A domain like that fits on a business card and is easy to say over the phone.</p>
                    <p>The safe choice is <strong>the letters a–z, the digits 0–9 and the hyphen</strong>. A hyphen cannot be at the start or at the end. Many endings also allow letters with accents, but people rarely type them and not every system handles them well. Capital letters make no difference. <em>ONDRAWEB.cz</em> and <em>ondraweb.cz</em> take you to the same place.</p>
                    <p><strong>Avoid digits</strong> unless they are part of the brand. With <em>3builders.com</em>, nobody knows whether to type the digit or <em>threebuilders.com</em>.</p>
                    <p><strong>Hyphens are a leftover.</strong> People used them to separate words because a domain cannot contain a space. Today words are written together, and a hyphen is just one more thing you have to spell out on the phone.</p>
                    <h2>3. Choosing a domain name that is easy to remember</h2>
                    <p>A domain should stick after someone hears it once. No complicated words and no spelling traps.</p>
                    <p>I come across this with abbreviations. A company has a long name and wants a domain made of its initials. To the company, those letters mean something. To the customer, they are six random characters.</p>
                    <p>On one website that I did not build, but where I did updates and security checks, the domain is exactly that kind of abbreviation. I worked on it repeatedly, and I still had trouble remembering it.</p>
                    <h2>4. A domain name that is easy to type</h2>
                    <p>People often do not see your domain written down. They hear it. On the phone, at a trade fair, from a friend. It has to be possible to type it after hearing it once.</p>
                    <ul>
                    <li><strong>Words that sound the same.</strong> Is it <em>4</em>, <em>for</em> or <em>four</em>? <em>2</em>, <em>to</em> or <em>two</em>? And names can be spelled several ways: <em>Smith</em> or <em>Smyth</em>, <em>Philips</em> or <em>Phillips</em>.</li>
                    <li><strong>Characters that get mixed up.</strong> Zero and the letter O, capital I and small L. On a business card they look almost the same.</li>
                    <li><strong>Double letters where two words meet.</strong> In <em>westtravel.com</em>, some people leave out one T.</li>
                    </ul>
                    <p><strong>Do a test.</strong> Say the domain out loud once to someone who does not know it and have them type it. If they have to ask, or they type it differently, the domain has failed. Better to find out now than after a thousand printed flyers.</p>
                    <h2>5. Consider keywords in the domain</h2>
                    <p>A word from your field in the domain can help. Not in the search engine, where it no longer moves you up today. It helps the person who sees the domain in the search results and knows straight away what they will find on the website. I explain what really decides in search in the article <a href="/en/blog/what-is-seo-and-why-is-it-so-important">What is SEO</a>.</p>
                    <p>An estate agent in Leeds might have <em>leedshomes.com</em>. Only then it is hard to move beyond Leeds. That is the next point.</p>
                    <p>Do not overdo it. Three keywords in a row look like spam. And simple domains like that are usually taken long ago anyway.</p>
                    <h2>6. Think about the future</h2>
                    <p>A company may grow into new products or another town. The domain should not stand in its way. If you add water skiing to your boat rental, <em>onthewater.com</em> still fits. <em>boatrental.com</em> no longer does.</p>
                    <p>You can change the domain later. The old address must redirect permanently to the new one, though, otherwise you lose links and search positions. I write about what to watch for with a change like that in the article on <a href="/en/blog/website-redesign-reasons-signals-and-how-to-do-it">website redesign</a>.</p>
                    <h2>7. Check availability</h2>
                    <p>Any registrar, the company you buy domains from, will tell you at no cost whether a domain is free. For <em>.com</em> and other generic endings, <a href="https://lookup.icann.org/" target="_blank" rel="noopener">ICANN Lookup</a> also shows whether a domain is registered and through which registrar.</p>
                    <p><strong>If it is taken</strong>, look at what is on it. Is someone else’s website running there? Look for a different name. Is it empty or for sale? You can try approaching the holder, but then they set the price. You can add a word or a town to a taken name, but make sure people will not confuse you with whoever has the original domain.</p>
                    <p>While you are at it, check whether the same name is free on the social networks you use.</p>
                    <p><strong>Then type the domain into a search engine.</strong> Sometimes you find out that the internet links the word with something you do not want. When I was coming up with a brand for clients in finance and real estate, the name <em>findom</em> made the shortlist. Then I typed it into Google. It is the name of a fetish.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>You recognise a good domain by the fact that you never have to spell it out for anyone.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>8. Check the domain’s history</h2>
                    <p>A domain that is free today may have a past. If spam or a scam shop used to run on it, search engines may remember. You can see what used to be on it for free in the internet archive <a href="https://web.archive.org/" target="_blank" rel="noopener">web.archive.org</a>. Just enter the domain.</p>
                    <h2>9. Consider registering more than one ending</h2>
                    <p>The ending is the part after the last dot. There are two kinds:</p>
                    <ul>
                    <li><strong>country endings</strong>, tied to a country or region: <em>.co.uk</em>, <em>.ie</em>, <em>.de</em>, <em>.eu</em>,</li>
                    <li><strong>generic endings</strong>, not tied to any country: <em>.com</em>, <em>.net</em>, <em>.info</em>.</li>
                    </ul>
                    <p>If you sell mainly in one country, take its ending. Customers there know it and trust it. If you sell internationally, <strong>.com</strong> is the safe choice. If you are planning a version of the website for another country, register that country’s ending too. And if you are building a brand on the name, it is worth buying the <em>.com</em> as well, and the <em>.eu</em> if you are based in the EU, so that a competitor or a speculator cannot take them. It is a small yearly cost for peace of mind.</p>
                    <h2>10. Check that the name is not a registered trade mark</h2>
                    <p>If the domain contains someone else’s trade mark, you can end up in a dispute with its owner over the domain and the name. Before you buy the domain, search for the name in <a href="https://www.tmdn.org/tmview/" target="_blank" rel="noopener">TMview</a>, which covers the EU trade mark office and many national ones, and in the <a href="https://branddb.wipo.int/" target="_blank" rel="noopener">Global Brand Database</a> of the World Intellectual Property Organization.</p>
                    <h2>How much does a domain cost?</h2>
                    <p>A <em>.com</em> domain costs roughly 10 to 25 dollars a year at the common registrars. Country endings are priced differently in each country. The first year is often cheaper as a promotion and the renewal then costs more. So compare the renewal price, not the registration price. Many endings, including <em>.com</em>, can be paid up to ten years ahead.</p>
                    <p>With the Business site, the domain and hosting for the first year are included in the price. You will find what each option includes in the <a href="/en/price">pricing</a>.</p>
                    <p>Good names are a market of their own. When a holder does not renew a domain, it becomes available again, and good names are quickly picked up by domain traders. A short name that fits a field can then cost many times the normal price.</p>
                    <h2>Who the domain should be registered to</h2>
                    <p>A domain belongs to whoever is recorded as the <strong>registrant</strong>. Not to whoever paid for it, and not to whoever built your website. If a developer or an agency registers it for you, insist that your company is the registrant. Otherwise you need their consent for every change, and above all when you want to leave them. Your registrar will tell you who the registrant of your domain is. Public lookups usually hide the holder’s details for privacy reasons.</p>
                    <p>Keep an eye on the expiry date too. If you do not renew in time, the website and email stop working. After a grace period, which differs by ending and by registrar, the domain is released and anyone can register it.</p>
                    <p>Access to the domain is one of the things worth having to hand before you start on a new website. The full list is in the article <a href="/en/blog/how-to-prepare-for-a-new-website">What to prepare before you contact a web developer</a>.</p>
                    <h2>Parts of a domain and how to spot a fake link</h2>
                    <p>Finally, for those who want to understand what they see in the address bar. It is most useful when you need to spot a fraudulent link.</p>
                    <p>A domain is read from right to left. Take <code>blog.mysite.com</code>:</p>
                    <ul>
                    <li><strong>The top-level domain</strong> (TLD) is the ending, here <code>com</code>.</li>
                    <li><strong>The second-level domain</strong> is the name you register and pay for, here <code>mysite</code>. This is what you are choosing.</li>
                    <li><strong>A subdomain</strong> is everything to the left of it, here <code>blog</code>. The holder creates subdomains without any further registration. For example <code>shop.mysite.com</code> for an online shop.</li>
                    </ul>
                    <p><strong>You can tell who a domain belongs to from the name right before the ending.</strong> The address <code>google.anything.com</code> does not belong to Google but to whoever owns <code>anything.com</code>. Most fraudulent emails rely on exactly this.</p>
                    <p>A full web address (URL) has more parts. Take <code>https://www.blog.mysite.com/article-about-domains?page=2#sectionB</code>:</p>
                    <ul>
                    <li><strong>Protocol</strong> <code>https</code>. The S means the connection is encrypted. Do not enter passwords or payment details on a site without it. You do not have to type it, browsers try it on their own these days.</li>
                    <li><strong><code>www</code></strong> is just a subdomain that websites once used after the World Wide Web. You do not have to type it. The website should still run on only one version and redirect the other to it, otherwise a search engine may see it twice.</li>
                    <li><strong>The path</strong> <code>/article-about-domains</code> leads to a specific page or file on the website.</li>
                    <li><strong>The parameter</strong> <code>?page=2</code> passes extra information to the website, here the page number.</li>
                    <li><strong>The anchor</strong> <code>#sectionB</code> points to a specific place on the page.</li>
                    </ul>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 5 — What is SEO (slug what-is-seo-and-why-is-it-so-important, beze změny)
            // ----------------------------------------------------------------
            5 => [
                'title'       => 'What is SEO and why it matters: explained without jargon',
                'description' => 'What SEO is, how it works and what you can do yourself. Technical SEO, content and backlinks explained for business owners, no promises of first place.',
                'perex'       => <<<'HTML'
                    <blockquote><p>SEO stands for search engine optimisation. It is about people finding you on Google at the moment they are looking for what you offer. I have written down what SEO is made of, what you can do yourself and when not to trust the person selling it to you.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>What is SEO</h2>
                    <p>SEO (<em>Search Engine Optimisation</em>) is work on a website and around it that helps its pages show up higher in the unpaid search results. Unpaid because, unlike with ads, you do not pay for every click that brings a visitor.</p>
                    <p>In most of Europe and the English-speaking world, that mostly means Google. Bing comes a distant second. Both judge websites in a similar way, but not the same, so a page can rank differently in each.</p>
                    <h2>Why SEO matters</h2>
                    <p>Whoever comes to you from a search engine was looking for something. They did not arrive by accident from an ad they wanted to skip. When they type “carpenter Bristol” and find you, they are one step closer to calling you.</p>
                    <p>Search traffic also does not switch off when you stop paying. An ad ends with the last paid click. A well-written page can bring visits for years.</p>
                    <p>I will not promise you, though, that SEO brings customers. It depends on how many people look for your service, how strong your competition is and what your website tells them when they arrive. And whoever promises you first place on Google is promising something they do not control. The order of the results is decided by the search engine, nobody else.</p>
                    <h2>The three parts of SEO</h2>
                    <p>SEO is usually divided into three areas. Technical, content (on-page) and external (off-page). Each answers a different question.</p>
                    <h2>1. Technical SEO: can the search engine read your website?</h2>
                    <p>A search engine does not read a website with eyes but with a program called a crawler. Technical SEO makes sure the crawler reaches every page and understands it.</p>
                    <ul>
                    <li><strong>Loading speed.</strong> People leave slow websites and the search engine notices. <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a> from Google shows you for free how you are doing.</li>
                    <li><strong>Phone.</strong> Google judges a website by how it looks and works on a phone. If it is awkward to use there, you lose out in the results too.</li>
                    <li><strong>Security.</strong> The website has to run on encrypted <code>https</code>.</li>
                    <li><strong>Page addresses.</strong> Each page has one address. When an address changes, the old one must redirect permanently to the new one.</li>
                    <li><strong>Search engine tools.</strong> Add your website to <a href="https://search.google.com/search-console/about" target="_blank" rel="noopener">Google Search Console</a> and <a href="https://www.bing.com/webmasters" target="_blank" rel="noopener">Bing Webmaster Tools</a>. Both are free. They show whether the search engine reports errors on the website and which searches you appear for.</li>
                    </ul>
                    <p>The technical part has to be done by whoever builds the website. If it is missing, the rest of the SEO work goes to waste. With me, technical SEO is included even in the smallest website.</p>
                    <h2>2. On-page SEO: does the page answer what people are looking for?</h2>
                    <p>On-page is everything that sits directly on the page. The text, headings, the page title, the description in the search results, images and links between your pages.</p>
                    <ul>
                    <li><strong>One page, one topic.</strong> If people should find you for “bathroom renovation” and for “tiling”, you need two pages. Not one page with everything on it.</li>
                    <li><strong>Your customers’ words, not yours.</strong> A company writes “sanitary installations”, the customer searches for “replace toilet”. You find out which words people really use from <a href="/en/blog/how-to-do-keyword-research-step-by-step">keyword research</a>.</li>
                    <li><strong>The topic belongs in the heading, but above all in the text below it.</strong> A word in the heading is not enough for the search engine. It wants to see that the text really deals with the topic.</li>
                    <li><strong>Page title and description.</strong> That is what a person sees in the search results. It decides whether they click on you or on your neighbour.</li>
                    <li><strong>Links between your pages.</strong> They show the crawler and the reader what belongs together.</li>
                    </ul>
                    <p>A word from your field in the domain will not move you up. I write more about that in the article on <a href="/en/blog/how-to-choose-the-perfect-domain-name">choosing a domain name</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>SEO is not a trick to fool the search engine. It is the work of making a page really answer what a person is looking for.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>3. Off-page SEO: do others trust your website?</h2>
                    <p>Off-page is everything outside your website. Above all <strong>backlinks</strong>, meaning links from other websites to yours. The search engine treats them as recommendations. One link from an industry website or the local paper carries more weight than a hundred links from directories nobody reads.</p>
                    <p><strong>Business profiles and reviews</strong> belong here too. If you work in a particular place, set up a free <a href="https://www.google.com/business/" target="_blank" rel="noopener">Google Business Profile</a> and a listing in <a href="https://www.bing.com/forbusiness/" target="_blank" rel="noopener">Bing Places</a>. For searches like “plumber Leeds”, they often show up before the websites themselves.</p>
                    <p>Social networks do not affect positions directly. They only help indirectly. When content gets shared, more people see it and some of them may link to it.</p>
                    <h2>How to earn backlinks honestly</h2>
                    <ul>
                    <li><strong>Write something worth linking to.</strong> A guide, a price overview, an answer to a question nobody in your field has properly explained.</li>
                    <li><strong>Partners and suppliers.</strong> Companies you work with can mention you on their website. Often you only have to ask.</li>
                    <li><strong>Industry and local websites.</strong> Associations, chambers of commerce, events you support, the local newsletter.</li>
                    <li><strong>An article on someone else’s website.</strong> An industry magazine or blog is often glad to publish a useful text from an expert, with a link to the author.</li>
                    <li><strong>Your competitors’ links.</strong> Look at who links to your competitors. You will often find a directory or an association where you are the only one missing. I describe how to look at the competition in the article on <a href="/en/blog/website-competitor-analysis">competitor analysis</a>.</li>
                    </ul>
                    <p>Packages like “100 links for 50 euros” will not help you. Search engines recognise links like that and, in the worse case, penalise the website for them. Google says so explicitly in its <a href="https://developers.google.com/search/docs/essentials/spam-policies" target="_blank" rel="noopener">spam policies</a>.</p>
                    <h2>SEO in practice: where to start</h2>
                    <ol>
                    <li><strong>Add your website to Search Console and Bing Webmaster Tools.</strong> You will see what you appear for and whether the search engine reports any errors.</li>
                    <li><strong>Find out what people search for.</strong> Write down the searches, how often they are searched and how hard it will be to rank for them.</li>
                    <li><strong>Give each important search one page.</strong> And write it so that it really answers it.</li>
                    <li><strong>Add content your customers look for.</strong> The questions you hear on the phone over and over are often the best topics for articles.</li>
                    <li><strong>Watch what happens.</strong> Positions move slowly. You will see results in months, not weeks.</li>
                    </ol>
                    <p>Be careful when you redo your website. If page addresses change and the old ones are not redirected, you lose positions you spent years building. I write about what to watch for in the article on <a href="/en/blog/website-redesign-reasons-signals-and-how-to-do-it">website redesign</a>.</p>
                    <h2>Can you do it yourself?</h2>
                    <p>The basics, yes. Adding the website to the search engine tools, writing proper page titles and answering your customers’ questions in your texts is something anyone who knows their field can do. Google itself offers a complete introduction in its <a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide" target="_blank" rel="noopener">SEO Starter Guide</a>.</p>
                    <p>Keyword research, content strategy and performance monitoring take time and paid tools. I offer that as an additional service. You will find the price in the <a href="/en/price">pricing</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 7 — Design or content? (slug which-is-more-important-design-or-content, beze změny)
            // ----------------------------------------------------------------
            7 => [
                'title'       => 'Design or content? Which matters more on a website',
                'description' => 'Which matters more on a website, design or content? Why I start with content, what design does and what it means for your new website.',
                'perex'       => <<<'HTML'
                    <blockquote><p>The usual answer to this question is “both are important”. That is true, but it tells you nothing. My answer is more specific: content comes first and design serves it. Here is why, and what it means when you are planning a new website.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>The short answer: content</h2>
                    <p>Design is the way content is shown. Without content it has nothing to show. If you do not know what the website should say, it can look as good as you like and the visitor still will not know whether you are the right people for them.</p>
                    <p>By content I do not just mean text. It is everything the website communicates. What you offer, to whom, at what price, how working with you goes, examples of your work, references, photos.</p>
                    <h2>What content does</h2>
                    <ul>
                    <li><strong>It answers questions.</strong> The visitor came with a question. If they find the answer on the website, they get in touch. If not, they move on.</li>
                    <li><strong>It builds trust.</strong> Specific references and examples of your work convince people more than the best graphics.</li>
                    <li><strong>It brings people from search engines.</strong> A search engine reads text, not colours. A nice design will not get you onto the first page of Google. More on that in the article <a href="/en/blog/what-is-seo-and-why-is-it-so-important">What is SEO and why it matters</a>.</li>
                    </ul>
                    <h2>What design does</h2>
                    <p>Design is not decoration you can do without, though. It has its own job.</p>
                    <ul>
                    <li><strong>First impression.</strong> Before a person starts reading, they decide within a few seconds whether the website looks trustworthy. An outdated or broken look puts them off before they get to the content.</li>
                    <li><strong>Readability.</strong> Headings, paragraphs and white space decide whether a text can be read or only skipped.</li>
                    <li><strong>Guidance.</strong> Good design shows what matters and where to click next.</li>
                    <li><strong>Phone.</strong> About half of people now look at websites on a phone. Design has to work there too, not just on a large monitor.</li>
                    </ul>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Design makes content readable, but it will not say for you what you offer and why someone should choose you.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>Why I start with content</h2>
                    <p>When I build a new website, the client and I first go through what the website should do and who it should say it to. Only then do I deal with how it will look. The other way round does not work. A design drawn without content assumes a heading of two lines and a paragraph of three. Then the real text arrives and does not fit.</p>
                    <p>It is the same with a redesign. A new look with the old texts looks new but works just as badly. More on that in the article on <a href="/en/blog/website-redesign-reasons-signals-and-how-to-do-it">website redesign</a>.</p>
                    <h2>What it means for your new website</h2>
                    <ul>
                    <li><strong>Start thinking about content right away.</strong> What do people not know about you and should? What do they keep asking? That is the basis of the texts. More questions are in the article <a href="/en/blog/how-to-prepare-for-a-new-website">What to prepare before you contact a web developer</a>.</li>
                    <li><strong>Include photos in the budget.</strong> If you do not have them, they have to be taken or bought. I write the texts for you from your answers. What pushes the price up or down is in the article <a href="/en/blog/how-much-does-a-website-cost">What a custom website costs</a>.</li>
                    <li><strong>Choose the design to suit the content, not the other way round.</strong> A website you like at another company was designed for their texts. Not for yours.</li>
                    </ul>
                    <p>A nice website that says nothing helps nobody. A useful website that looks old loses people before they read it. You need both, just in the right order. I write about what else a website has to get right to work in the article <a href="/en/blog/how-to-create-a-successful-website">How to create a successful website</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 9 — Keyword research (nový slug how-to-do-keyword-research-step-by-step)
            // ----------------------------------------------------------------
            9 => [
                'title'       => 'How to do keyword research step by step',
                'description' => 'How to do keyword research: where to collect searches, which tools are free, how to sort the keywords and how to turn them into the pages of your website.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Keyword research is a list of what people type into a search engine when they are looking for what you offer. Without it, a website gets built around the way you talk about your field, not the way your customers do. I describe a process you can manage yourself with an ordinary spreadsheet and free tools.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>What keywords are and what the research is for</h2>
                    <p>Keywords are the words and phrases people type into Google or Bing. “Bespoke kitchens”, “bespoke kitchen prices”, “kitchen fitter York”. Each of them tells you what the person wants and how close they are to buying.</p>
                    <p>The research tells you three things:</p>
                    <ul>
                    <li><strong>How your customers talk about your field.</strong> Often differently from you.</li>
                    <li><strong>What they search for most.</strong> And what almost nobody searches for, even though it seems important to you.</li>
                    <li><strong>Which pages your website should have.</strong> Each group of searches needs its own page.</li>
                    </ul>
                    <p>At the end you have a spreadsheet. It decides which pages the website will have, what they will be called and what the articles will be about. That is why the research is worth doing before a new website is built, not after.</p>
                    <p>I will show the whole process on one example, a maker of bespoke kitchens.</p>
                    <h2>1. Collect the searches</h2>
                    <p>Write down everything a customer might search for. Do not sort anything or throw anything out yet.</p>
                    <ul>
                    <li><strong>Your services and products.</strong> The way you name them and the way your customers name them.</li>
                    <li><strong>Questions you hear on the phone.</strong> “How much does it cost?”, “How long does it take?”, “Can you fit it in a small flat?”</li>
                    <li><strong>Competitors’ websites.</strong> How they name their services and their page headings.</li>
                    <li><strong>Forums and social networks</strong> where your customers ask questions. Watch the age. A discussion from ten years ago will not tell you much about today.</li>
                    </ul>
                    <p>The result is a plain list, one phrase per row. Any spreadsheet will do, I use Excel. How many phrases you end up with depends on the field. A broad field like cosmetics gives you hundreds, a company with one product a few dozen.</p>
                    <h2>2. Expand the list with tools and add search volume</h2>
                    <p>Now you add phrases you did not think of yourself and, for each one, the <strong>search volume</strong>. That is roughly how many times a month people search for it.</p>
                    <p><strong>Google Keyword Planner.</strong> It is part of <a href="https://ads.google.com/home/tools/keyword-planner/" target="_blank" rel="noopener">Google Ads</a> and it is free, you just need an account. From your phrases it suggests more and shows the search volume on Google. If you are not currently running ads in Google Ads, you will only see the volume as a range, for example 100 to 1,000. For a first orientation that is enough.</p>
                    <p><strong>Google Trends.</strong> <a href="https://trends.google.com/trends/" target="_blank" rel="noopener">Google Trends</a> is free and needs no account. It does not show how many people search, only how interest changes over time. That helps with seasonal things. Do people search for summer holidays most in April, or already in January? That tells you when an article needs to be ready.</p>
                    <p><strong>Autocomplete.</strong> Start typing into the search engine and it suggests how people finish the phrase. Type “bespoke kitchens” and you will see what people add to it. By hand it is slow, but you rule out what does not belong to you as you go. Paid SEO tools can pull out hundreds of suggestions at once.</p>
                    <p>In the spreadsheet, make one column for the search volume. If some phrases have no volume, keep the ones that make sense to you and just mark them.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Write your website around what your customers type into the search engine, not around how you talk about your field.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>3. Clean up and sort</h2>
                    <p>The list is now long and messy. First remove duplicates, typos and everything that has nothing to do with you. A maker of bespoke kitchens does not need “IKEA kitchen assembly instructions”.</p>
                    <p>Then sort the phrases by <strong>what the person wants</strong>:</p>
                    <ul>
                    <li><strong>Informational searches.</strong> They want to find something out. “How to choose a worktop”. Articles answer these.</li>
                    <li><strong>Buying searches.</strong> They want to buy or order. “Bespoke kitchen prices”. Service and product pages answer these.</li>
                    <li><strong>Local searches.</strong> They are looking for someone nearby. “Kitchen fitter York”. The contact page and your Google Business Profile answer these.</li>
                    </ul>
                    <p>Finally, group the phrases that mean the same thing. “Bespoke kitchen prices” and “how much does a bespoke kitchen cost” are one question. They belong on one page.</p>
                    <h2>4. Set priorities</h2>
                    <p>Not every keyword is worth the work. For each group, note:</p>
                    <ul>
                    <li><strong>Search volume.</strong> How many people search for it.</li>
                    <li><strong>How closely it relates to what you sell.</strong> A hundred people looking for exactly your service are worth more than ten thousand looking for something similar.</li>
                    <li><strong>Competition.</strong> Type the search into Google and see who is on the first page. Big portals and online shops are hard to overtake. Weak or outdated pages are your chance.</li>
                    <li><strong>Season.</strong> When in the year the search peaks.</li>
                    </ul>
                    <p>High, medium and low is enough for the priority. The searches most worth it are the ones directly related to what you sell that nobody has a proper page for yet.</p>
                    <h2>5. Assign groups to pages</h2>
                    <p>This step matters most. Assign each group of searches one page of the website. Either one that already exists or one that will be created.</p>
                    <p>If two pages end up on one group, they will compete with each other and neither will rank high. If a group has no page, you know what is missing from the website. The structure of a new website is then put together from this spreadsheet. It is one of the things worth having to hand before you start writing a <a href="/en/blog/how-to-prepare-for-a-new-website">brief for a developer</a>.</p>
                    <h2>What to do with the research next</h2>
                    <ul>
                    <li><strong>Update your existing pages.</strong> The page title, heading and text, using the words people really use.</li>
                    <li><strong>Add the missing pages and articles.</strong> High priority first.</li>
                    <li><strong>Track the results.</strong> In Google Search Console and Bing Webmaster Tools you will see which searches the website appears for and how many people click.</li>
                    <li><strong>Repeat the research from time to time.</strong> What people search for changes, and so does the competition.</li>
                    </ul>
                    <p>Keywords are only one part of SEO. I explain what else decides whether the search engine shows you in the article <a href="/en/blog/what-is-seo-and-why-is-it-so-important">What is SEO and why it matters</a>. I go into who you are competing with on the first page in the article on <a href="/en/blog/website-competitor-analysis">competitor analysis</a>.</p>
                    <p>I also do keyword research as part of my SEO service. You will find the price in the <a href="/en/price">pricing</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 11 — How to create a successful website (slug how-to-create-a-successful-website, beze změny)
            // ----------------------------------------------------------------
            11 => [
                'title'       => 'How to create a successful website: what really decides',
                'description' => 'How to create a successful website: a clear message, trust, one call to action, phones, search engines and upkeep. Ten things that decide whether it works.',
                'perex'       => <<<'HTML'
                    <blockquote><p>A successful website is not the prettiest one. It is a website where a person quickly understands what you do, trusts you and knows how to get in touch. I will not promise you how much work a website will bring. But I have written down ten things that have to work on a website for it to stand a chance at all.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>First decide what the website should do</h2>
                    <p>You cannot measure a website’s success until you know what you want from it. Should it bring in enquiries? Sell? Save you phone calls because people read the answers for themselves? Or is it enough that nobody rules you out when choosing a supplier? Each of those goals leads to a different website. What they have in common: the website should turn visitors into customers.</p>
                    <p>Just as important is who the website is for. Those are the two questions I ask every client first.</p>
                    <h2>1. Within ten seconds it must be clear what you do</h2>
                    <p>When someone comes to your website for the first time, they decide within a few seconds whether to stay. The heading “Welcome to the website of XY Ltd” tells them nothing. The heading “We build timber-frame houses in the Peak District, turnkey in eight months” tells them whether they are in the right place.</p>
                    <p>A specific sentence always beats a general one.</p>
                    <h2>2. Write in your customer’s words</h2>
                    <p>A website written in industry jargon will not be understood by the customer or shown by the search engine. A company writes “complete heating solutions”, the customer searches for “boiler replacement”. I describe how to find out which words people use to search for your field in the article <a href="/en/blog/how-to-do-keyword-research-step-by-step">How to do keyword research</a>.</p>
                    <p>Write briefly and to the point. A paragraph of three sentences, a heading that says what is below it. People do not read websites, they scan them. They stop at what interests them.</p>
                    <h2>3. One main call to action per page</h2>
                    <p>Every page should have one main thing you want from the visitor. Call, fill in a form, order. If you offer them four buttons of equal weight, they often choose none.</p>
                    <p>Keep forms as short as possible. Name, contact details and a message are usually enough. Every extra field is a reason not to fill it in. For an online shop, that goes double for the checkout.</p>
                    <h2>4. Trust before the question of money</h2>
                    <p>A person who does not know you needs a reason to trust you. What helps most:</p>
                    <ul>
                    <li><strong>references with a name and a company</strong>, not an anonymous “satisfied customer”,</li>
                    <li><strong>examples of your work</strong> with a description of what you solved and how,</li>
                    <li><strong>your face and name</strong>, so it is clear who they will be dealing with,</li>
                    <li><strong>reviews outside your website</strong>, on Google or on review sites in your field.</li>
                    </ul>
                    <p>General phrases like “we are reliable and professional” convince nobody. Anyone can write them.</p>
                    <h2>5. Design that carries the content</h2>
                    <p>Design has two jobs. To make a good first impression and to make reading easy. A readable font, enough contrast, enough space around the text, good photos of your own instead of stock photos. And the same style everywhere, so the website feels like one whole.</p>
                    <p>I answered whether design or content matters more in a separate article, <a href="/en/blog/which-is-more-important-design-or-content">Design or content?</a></p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>A successful website is not the prettiest one, but the one where a person quickly understands what you do and knows how to get in touch.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>6. Navigation nobody gets lost in</h2>
                    <ul>
                    <li><strong>Few items in the menu</strong>, named the way the customer understands them. “Prices”, not “Investment”.</li>
                    <li><strong>Contact from every page.</strong> Nobody should have to look for it.</li>
                    <li><strong>The same controls everywhere.</strong> Menu and buttons in the same place on every page.</li>
                    <li><strong>A response to every action.</strong> After sending a form, it must be clear that it was sent and what happens next.</li>
                    </ul>
                    <p>Only large websites and online shops need a search box. A smaller website should be clear enough not to need one.</p>
                    <h2>7. Phones and speed</h2>
                    <p>About half of people now look at websites on a phone. It is not enough for the website to display there. Buttons have to be easy to hit with a thumb, forms have to be fillable without zooming, and the page has to load quickly even on mobile data. <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a> shows you for free how your website is doing.</p>
                    <h2>8. So that people find you</h2>
                    <p>A website nobody finds cannot be successful. The basis is a technically clean website, one page per topic and texts that answer what people search for. I explain what else belongs to it in the article <a href="/en/blog/what-is-seo-and-why-is-it-so-important">What is SEO and why it matters</a>.</p>
                    <h2>9. Security</h2>
                    <ul>
                    <li><strong>An encrypted <code>https</code> connection.</strong> Without it, the browser marks the website as not secure.</li>
                    <li><strong>Updates.</strong> Websites assembled from ready-made systems and third-party plugins must be updated regularly, otherwise they become an easy target. The fewer third-party plugins, the fewer worries.</li>
                    <li><strong>Strong passwords</strong> and admin access only for those who really need it.</li>
                    <li><strong>Backups.</strong> When something goes wrong, the website must be quick to restore.</li>
                    </ul>
                    <h2>10. Do not abandon the website after launch</h2>
                    <p>The work does not end at launch. Measure where people come from, on which pages they leave and how many of them get in touch. Google Analytics or a simpler tool like Plausible is enough. Without data, you improve the website blind.</p>
                    <p>And keep the content up to date. Old prices, discontinued services or the latest news from three years ago make it look as if the company has closed. I write about when upkeep is no longer enough and it is time to redo the website in the article on <a href="/en/blog/website-redesign-reasons-signals-and-how-to-do-it">website redesign</a>.</p>
                    <h2>Where to start</h2>
                    <p>A developer cannot sort out most of the things on this list alone. They need to know from you what the website should do, who it is for and what people do not know about you. I have written down the questions it is good to have answers to in advance in the article <a href="/en/blog/how-to-prepare-for-a-new-website">What to prepare before you contact a web developer</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 12 — Website competitor analysis (nový slug website-competitor-analysis)
            // ----------------------------------------------------------------
            12 => [
                'title'       => 'Website competitor analysis: how to do it before a new site',
                'description' => 'How to do a website competitor analysis: who to look at, what to note on their websites and how to tell what should set your new website apart.',
                'img_preview' => null,
                'img_main'    => null,
                'perex'       => <<<'HTML'
                    <blockquote><p>A customer does not see you on your own online. They see you next to three other companies that do the same thing, and they choose between you. A competitor analysis is a proper look at those three companies before you start building a new website. I write about what to look at, how to note it down and what to do with it afterwards.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Why do a competitor analysis</h2>
                    <p>Without one, a website is built to the owner’s taste and to what they liked elsewhere. With one, you get a website that holds its own in the customer’s eyes next to the ones they compare you with. The analysis shows you:</p>
                    <ul>
                    <li><strong>What customers in your field expect.</strong> If all three competitors have prices on their website, their absence on yours will be noticed.</li>
                    <li><strong>Where the gap is.</strong> What nobody explains properly, what people ask about and cannot find an answer to anywhere.</li>
                    <li><strong>Which mistakes to avoid.</strong> A form that does not work, a website unusable on a phone, no clear idea of what the company actually does.</li>
                    <li><strong>What you can rank for in search and what you cannot.</strong> If big portals have taken the first page for your main search, you need a different approach than a fight over the same word.</li>
                    </ul>
                    <p>The analysis is not for copying. If your website looks and talks the same as three others, the customer has nothing to choose by. You are looking for what sets you apart.</p>
                    <h2>Who to look at</h2>
                    <p>Three to five companies are enough. Choose from two sources:</p>
                    <ul>
                    <li><strong>Who is in the search results.</strong> Type the searches you want to be found for into Google. For example “bespoke kitchens York”. Whoever is on the first page is your competition online, even if you have never heard of them.</li>
                    <li><strong>Who your customers compare you with.</strong> Who do they mention when they are deciding? Who did you last lose a job to? That is your competition in business.</li>
                    </ul>
                    <p>The two groups often differ. Both matter.</p>
                    <h2>What to note on their websites</h2>
                    <p><strong>The offer and how they describe it.</strong> What exactly they offer, in which words, and whether you understand from the home page within ten seconds what they do and for whom.</p>
                    <p><strong>What they tell the customer and what they do not.</strong> Prices or at least a price range, how working with them goes, lead times, guarantees. What is missing on their side is where you can do better.</p>
                    <p><strong>Trust.</strong> References with names, examples of work, photos of people, reviews. Are they specific or general?</p>
                    <p><strong>Website structure.</strong> Which pages they have, what the menu items are called and how many clicks it takes to find the contact details.</p>
                    <p><strong>Call to action.</strong> What does the website want from you? Call, fill in a form, download a price list? And how easy is it?</p>
                    <p><strong>Phone and speed.</strong> Go through their websites on a phone. <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a> measures the speed for free.</p>
                    <p><strong>What they appear for in search.</strong> Which searches they are visible for and which pages and articles they have for them. Paid tools give exact figures. To start with, it is enough to try the searches from your <a href="/en/blog/how-to-do-keyword-research-step-by-step">keyword research</a> by hand.</p>
                    <p><strong>Reviews outside their website.</strong> On their own website everyone picks the best ones. More interesting is what people write about them on Google, on review sites or on social networks. What they praise and what they complain about.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>A competitor analysis does not look for what to copy, but for what sets you apart.</p></blockquote>
                    HTML,
                'img_mid'     => 'analyza_konkurence.webp',
                'img_mid_alt' => 'Charts and tables spread out on a desk',
                'content_2'   => <<<'HTML'
                    <h2>How to note down the results</h2>
                    <p>One spreadsheet works best. Competitors in the columns, the points you are tracking in the rows. At the end, three short lists:</p>
                    <ul>
                    <li><strong>What everyone has.</strong> That is the standard in your field. You cannot do without it either.</li>
                    <li><strong>What someone does well.</strong> Inspiration, not a template.</li>
                    <li><strong>What nobody has.</strong> That is your opportunity.</li>
                    </ul>
                    <p>You do not need more. It is not about a hundred-page report, but about one page you can make decisions from.</p>
                    <h2>How to use the analysis when designing the website</h2>
                    <ul>
                    <li><strong>Content.</strong> Answer the questions your competitors do not. Prices, how working with you goes, frequently asked questions.</li>
                    <li><strong>Structure.</strong> Have the pages customers in your field expect. And on top of that, the ones that set you apart.</li>
                    <li><strong>Message.</strong> If everyone writes “quality and reliability”, write something the customer can check.</li>
                    <li><strong>Search.</strong> Searches where your competitors have weak or outdated pages are where you stand a chance. I explain why that matters in the article <a href="/en/blog/what-is-seo-and-why-is-it-so-important">What is SEO and why it matters</a>.</li>
                    <li><strong>Features.</strong> A calculator, bookings, documents to download. If your competitors do not have them and they would help the customer, you are ahead.</li>
                    </ul>
                    <p>Attach the finished analysis to your brief. It saves the developer a lot of questions and you get a more accurate quote. You will find what else belongs in a brief in the article <a href="/en/blog/how-to-prepare-for-a-new-website">What to prepare before you contact a web developer</a>.</p>
                    <p>It is just as worth looking at your competitors before a redesign. If the websites of the people you compete with look a class better, that is one of the good reasons to redo your website. I go into when it makes sense and when it does not in the article on <a href="/en/blog/website-redesign-reasons-signals-and-how-to-do-it">website redesign</a>.</p>
                    <h2>How often to repeat the analysis</h2>
                    <p>The market changes. New competitors arrive, old ones redo their websites or start writing articles. It is enough to take another look once a year and compare it with last year’s spreadsheet.</p>
                    <p>And if you are still choosing a company name or a domain, do a quick version of the analysis now. You do not want people to confuse you with someone who does the same thing. I write more about that in the article on <a href="/en/blog/how-to-choose-the-perfect-domain-name">choosing a domain name</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

        ];
    }
}
