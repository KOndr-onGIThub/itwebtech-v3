<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-267 (audit OND-254, redline OND-262) — EN obsah blogu.
 *
 * Anglické překlady dosud nebyly v žádném seederu: pocházejí ze starého SQL
 * importu `database/sql/article_translations.sql`, který `EnsureArticlesSeededSeeder`
 * pouští jen do prázdné tabulky. Na stagingu i produkci články existují, takže
 * se změna dumpu nikdy neprojeví — a na čerstvé DB by se naopak neprojevila
 * migrace. Obsah proto žije tady a aplikuje se dvěma cestami, stejně jako
 * u DE (`BlogContentDeSeeder`):
 *   - migrace 2026_09_23_100300_ond267_en_blog_obsah.php → existující DB,
 *   - `EnsureArticlesSeededSeeder` po importu dumpů → čerstvá DB.
 *
 * Rozsah je záměrně užší než u DE:
 *   - článek 3 (cena) — kompletní nový text, část C redlinu,
 *   - článek 6 (aplikace vs. Excel) — jen titulek, perex a nový slug (část D);
 *     tělo zůstává starý strojový překlad a řeší ho OND-275.
 * Zbylé tři EN články jsou pořád ze starého importu — taky OND-275.
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
        6 => [
            'how-simple-web-application-can-save-your-business-millions',
            'when-a-custom-app-beats-a-spreadsheet',
        ],
    ];

    public function run(): void
    {
        $this->note('[blog-content-en] nasazuji EN texty blogu (OND-267)...');

        foreach ($this->articles() as $articleId => $content) {
            $updated = DB::table('article_translations')
                ->where('article_id', $articleId)
                ->where('locale', 'en')
                ->update($content + ['updated_at' => now()]);

            if ($updated === 0) {
                $this->note("[blog-content-en] POZOR: EN překlad článku {$articleId} nenalezen, přeskakuji.");
            }
        }

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
     * EN texty z redlinu `ond262/redline-en-de.md` (části C a D).
     *
     * Obrázková pole se nepřepisují — EN řádky je už mají z importu a část C
     * u nich výslovně říká „beze změny".
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
            // 6 — Aplikace vs. Excel (část D redlinu).
            // Mění se jen titulek a perex; bez nich by nová adresa
            // `when-a-custom-app-beats-a-spreadsheet` mluvila proti nadpisu
            // pod sebou. Tělo článku je pořád starý strojový překlad — OND-275.
            // ----------------------------------------------------------------
            6 => [
                'title'       => 'When a custom app is worth it instead of a spreadsheet',
                'description' => 'I spent eighteen years in Toyota logistics writing applications for the shop floor. Here is how you can tell that a spreadsheet has stopped being enough for your company.',
            ],
        ];
    }
}
