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
 * a `extra` se nepřepisují: EN řádky existují z importu i s obrázky, oba
 * redliny je nechávají beze změny. Proto tady není `imagesFromCs()` jako
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
    private const NEW_EN_SLUGS = [
        4 => [
            'how-to-define-website-development-requirements',
            'how-to-prepare-for-a-new-website',
        ],
        6 => [
            'how-simple-web-application-can-save-your-business-millions',
            'when-a-custom-app-beats-a-spreadsheet',
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
     * V textech jsou typografické uvozovky a apostrofy (“ ” ’) a `€4,000`
     * s čárkou jako oddělovačem tisíců. Je to anglická konvence z redlinu,
     * ne překlep — nepřepisovat na rovné.
     *
     * @return array<int, array<string, string>>
     */
    private function articles(): array
    {
        return [

            // ----------------------------------------------------------------
            // 3 — Kolik stojí web → How much does a website cost
            // Slug `how-much-does-a-website-cost` zůstává — funkční SEO adresa.
            // Text zrcadlí aktuální CS/DE verzi odstavec po odstavci a čísla
            // bere z `lang/en/price.php` (Standard €2,200 · Custom from €3,800
            // · Starter €1,000). Když se ceny v lang změní, srovnat i tenhle text.
            // ----------------------------------------------------------------
            3 => [
                'title'       => 'What a custom website costs and what goes into the price',
                'description' => 'The price tiers I build websites in, what each one includes, and what pushes the price up. So you know up front whether I fit your budget.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Price is the first thing people ask me about, and it is the right question. But nobody can honestly give you a single number. A €1,000 website and an €8,000 website are two different things. So here it is straight: the tiers I work in, what is in them, and what pushes the price up.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
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
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>You know the price before I start working. Not when the invoice arrives.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
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
                    <p>One main thing per page. Call, fill in the form, download the price list, order. When a website is meant to do five things at once, it does none of them properly.</p>
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
                    <p>A logo, photos, texts, access to the domain and the hosting, a shop with products in some system. The more of it there is, the less has to be made from scratch.</p>
                    <h2>8. What your budget is</h2>
                    <p>I know nobody likes this question. I ask it so that I can tell you straight away whether I can do it for that price. If I cannot, I say so immediately and neither of us wastes time.</p>
                    <h2>9. When you need it by</h2>
                    <p>If you have a fixed date because of a trade fair or an opening, tell me right at the start. That is how I know whether I can make it.</p>
                    <h2>What happens next</h2>
                    <p>From your answers I write a specification. It says what I will build and a price that holds. Only then do you decide whether we go ahead. You sign nothing up front.</p>
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
                    <p>I will do this calculation for you free of charge on our first call. If it comes out that it is not worth it, I will tell you.</p>
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
                    <p>Try to answer one question. If someone who had never heard of you landed on your website tomorrow, would they understand within ten seconds what you do and whether it is for them? If not, that is where the problem is, and it makes no difference whether you have a website or not.</p>
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
                    <p><strong>5. The websites of the people you compete with look a class better.</strong> The customer compares you side by side whether you like it or not.</p>
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
                    <p><strong>The page addresses get thrown away.</strong> The new website has a different structure and the old addresses stop working. Search engines and links from other people’s websites suddenly lead nowhere. The fix is simple and it is done before launch: the old address has to redirect permanently to the new one. I want you to demand that from whoever builds your website.</p>
                    <p><strong>Only the look gets redone.</strong> The texts are copied across one to one, including the ones nobody understood. The website then looks new and works just as badly.</p>
                    <p><strong>Things that were working disappear.</strong> Sometimes the old website has a page that half the traffic goes to. Before anything is deleted, somebody needs to look at the statistics.</p>
                    <p><strong>Nobody carries the content over.</strong> References, photos of finished jobs, documents to download. There is usually more of it than anyone expects.</p>
                    <h2>How I approach a redesign</h2>
                    <p>First I look at what works on the old website and I keep that. Then we go through what the website is supposed to do and who it is supposed to say it to. Only after that do we deal with how it will look. I sort the page addresses out before launch, not after.</p>
                    <p>I write my own code, without ready-made plugins by other authors. Those are usually the reason a website falls apart after a while and has to be redone.</p>
                    HTML,
            ],

        ];
    }
}
