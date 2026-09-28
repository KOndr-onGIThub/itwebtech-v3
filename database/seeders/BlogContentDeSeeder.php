<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * OND-219 (navazuje na OND-217) — DE překlady blogových článků a DE slugy.
 *
 * Do OND-217 neměl ani jeden článek DE slug, takže /de/blog byl prázdný.
 * Texty dodal Content Writer v OND-218 (dokument `de-translations`), zdrojem
 * byla aktuální CS verze z BlogContentSeeder (OND-204), ne starý EN import.
 *
 * Proč seeder a ne úprava `database/sql/`: dumpy jsou jednorázový import staré
 * databáze a `EnsureArticlesSeededSeeder` je pouští jen do prázdné tabulky.
 * Na stagingu i produkci články existují, takže se změna dumpu nikdy
 * neprojeví. Obsah proto žije tady a aplikuje se dvěma cestami:
 *   - migrace 2026_09_16_120000_seed_de_blog_content.php → existující DB,
 *   - `EnsureArticlesSeededSeeder` po importu dumpů → čerstvá DB.
 *
 * Seeder je idempotentní: updaty jsou absolutní, slug se zakládá přes
 * updateOrInsert. Opakované spuštění nic nerozbije.
 *
 * Pozn. k cenám v článku 3: DE verze uvádí eura podle `lang/de/price.php`
 * (Spanne 3.500–8.000 €, Einstiegspreis ab 1.900 € — OND-389), ne přepočet korun
 * z CS verze. Když se čísla v `lang/de/price.php` změní, je potřeba srovnat
 * i tenhle text.
 *
 * EN překlady: od OND-275 jsou všechny publikované EN články v
 * `BlogContentEnSeeder` a kryjí se s přepsanou CS verzí — článek 3 a
 * titulek/description šestky přepsalo OND-267 (redline OND-262), články 4,
 * 10, 13 a tělo šestky OND-275 (redline OND-274). Ze starého SQL importu
 * už nežije žádná publikovaná EN verze.
 */
class BlogContentDeSeeder extends Seeder
{
    /**
     * DE slugy. Musí být unikátní napříč celou tabulkou `article_slugs`, ne jen
     * v rámci locale: `PageController::article()` dohledává slug bez ohledu na
     * locale a teprve pak kontroluje, jestli sedí. Kolize s cs/en slugem by
     * znamenala 301 na cizí článek.
     *
     * article_id => de slug
     */
    public const DE_SLUGS = [
        3   => 'was-kostet-eine-website',
        4   => 'vorbereitung-auf-die-neue-website',
        6   => 'wann-sich-eine-eigene-anwendung-lohnt',
        10  => 'braucht-ihre-firma-eine-website',
        13  => 'wann-website-relaunch-sinn-ergibt',
        // OND-432
        1   => 'domainnamen-finden',
        5   => 'was-ist-seo',
        7   => 'design-oder-inhalt',
        9   => 'keyword-recherche-schritt-fuer-schritt',
        11  => 'erfolgreiche-website-erstellen',
        12  => 'wettbewerbsanalyse-website',
    ];

    /**
     * Obrázková pole se nepřekládají — přebírají se z CS řádku, aby DE karta
     * ve výpisu a hero v detailu nebyly prázdné (`pages/blog.blade.php`
     * podmiňuje obrázek na `img_preview`, `pages/article.blade.php` na
     * `img_main`).
     *
     * Výjimka: když je sloupec přímo v `articles()[id]`, má přednost (OND-432,
     * články 1, 5, 7, 9, 11, 12 — obrázky s českým textem se v DE vynechávají).
     */
    private const IMAGE_COLUMNS = ['img_preview', 'img_main', 'img_mid', 'img_end'];

    public function run(): void
    {
        $this->note('[blog-content-de] nasazuji DE překlady blogu (OND-219)...');

        $translations = 0;

        foreach ($this->articles() as $articleId => $content) {
            if (! DB::table('articles')->where('id', $articleId)->exists()) {
                $this->note("[blog-content-de] POZOR: článek {$articleId} neexistuje, přeskakuji.");

                continue;
            }

            $this->upsertTranslation($articleId, $content);
            $this->upsertSlug($articleId, self::DE_SLUGS[$articleId]);
            $translations++;
        }

        $this->note("[blog-content-de] hotovo: {$translations} DE překladů a slugů.");
    }

    private function upsertTranslation(int $articleId, array $content): void
    {
        $row = DB::table('article_translations')
            ->where('article_id', $articleId)
            ->where('locale', 'de');

        $payload = $content + $this->imagesFromCs($articleId) + [
            'bonus'      => null,
            'extra'      => null,
            'active'     => 1,
            'updated_at' => now(),
        ];

        // Ne updateOrInsert: ten by při každém běhu přepsal i created_at.
        if ((clone $row)->exists()) {
            $row->update($payload);

            return;
        }

        DB::table('article_translations')->insert($payload + [
            'article_id' => $articleId,
            'locale'     => 'de',
            'created_at' => now(),
        ]);
    }

    /**
     * @return array<string, string|null>
     */
    private function imagesFromCs(int $articleId): array
    {
        $cs = DB::table('article_translations')
            ->where('article_id', $articleId)
            ->where('locale', 'cs')
            ->first(self::IMAGE_COLUMNS);

        $images = [];

        foreach (self::IMAGE_COLUMNS as $column) {
            $images[$column] = $cs->{$column} ?? null;
        }

        return $images;
    }

    private function upsertSlug(int $articleId, string $slug): void
    {
        // Kdyby v DE locale někdy vznikl jiný slug (Filament), zůstane jako
        // neaktivní záznam pro 301 lookup, kanonický bude ten náš.
        DB::table('article_slugs')
            ->where('article_id', $articleId)
            ->where('locale', 'de')
            ->where('slug', '!=', $slug)
            ->update(['active' => 0, 'updated_at' => now()]);

        DB::table('article_slugs')->updateOrInsert(
            ['slug' => $slug, 'locale' => 'de'],
            [
                'article_id' => $articleId,
                'active'     => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }

    private function note(string $message): void
    {
        $this->command?->info($message);
    }

    /**
     * DE texty z dokumentu `de-translations` (OND-218). Články 1, 5, 7, 9, 11
     * a 12 a věty s odkazy na ně ve 3, 4, 10 a 13 z dokumentu `clanky-final-de`
     * (OND-430) s redliny `review-preklady` (OND-431), nasazuje je OND-432.
     *
     * @return array<int, array<string, string|null>>
     */
    public function articles(): array
    {
        return [

            // ----------------------------------------------------------------
            // 3 — Kolik stojí web → Was kostet eine Website
            // cs kolik-stoji-webove-stranky → de was-kostet-eine-website
            // ----------------------------------------------------------------
            3 => [
                'title'       => 'Was eine Website kostet und woraus sich der Preis ergibt',
                'description' => 'Was eine Website bei mir kostet, was im Preis steckt und was ihn nach oben oder unten bewegt. Damit Sie vorher wissen, ob wir zusammenpassen.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Der Preis ist die erste Frage, die mir Leute stellen, und das ist richtig so. Eine einzige Zahl kann Ihnen aber niemand seriös nennen. Eine Website für 1.900 € und eine für 8.000 € sind zwei verschiedene Dinge. Deshalb schreibe ich offen, was eine Website bei mir kostet, was im Preis steckt und was ihn nach oben oder unten schiebt.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Warum ich keine einzige Zahl habe</h2>
                    <p>Eine Website ist keine Ware aus dem Regal. Wenn Sie mir schreiben, dass Sie eine Website wollen, weiß ich bis dahin nur, dass Sie eine Website wollen. Ich weiß nicht, wie viele Seiten sie haben soll. Ich weiß nicht, ob Sie die Inhalte selbst pflegen wollen. Ich weiß nicht, ob Sie darüber verkaufen müssen oder ob sie auch auf Englisch funktionieren soll. Jede dieser Fragen bewegt den Preis.</p>
                    <p>Ich mache es so: Zuerst gehen wir durch, was Sie brauchen. Dann schreibe ich Ihnen eine Spezifikation, in der schwarz auf weiß steht, was ich baue und zu welchem Preis. Dieser Preis gilt. Die Rechnung am Ende entspricht der Spezifikation vom Anfang. Wenn Sie unterwegs merken, dass Sie etwas zusätzlich wollen, nenne ich Ihnen den Preis vorher und Sie entscheiden.</p>
                    <h2>Wofür Sie eigentlich bezahlen</h2>
                    <p>Sie bezahlen meine Zeit und das, was ich damit anzufangen weiß. Sie kaufen keine Lizenz für ein Template und keine Stunden eines Vertrieblers, der Ihnen die Website verkauft hat und dann verschwunden ist. Ich arbeite allein, im Preis stecken also kein Agentur-Overhead und kein Koordinator, der mir Ihre E-Mails weiterleitet.</p>
                    <p>Websites schreibe ich mit eigenem Code. Ich baue sie nicht aus Baukästen und fremden Plug-ins zusammen, die ständig aktualisiert werden müssen und irgendwann kaputtgehen. Das ist am Anfang teurer und mit der Zeit günstiger, weil Sie nichts zu reparieren haben.</p>
                    <h2>Was es bei mir kostet</h2>
                    <p>Die meisten Projekte liegen zwischen 3.500 und 8.000 €. Das Kleinste, was ich baue, ist eine Präsentationswebsite mit bis zu fünf Seiten, ab 1.900 €. Was Ihre kostet, hängt vor allem vom Umfang ab.</p>
                    <p><strong>Präsentationswebsite — bis fünf Seiten.</strong> Für Selbstständige und kleine Unternehmen, bei denen ein größerer Umfang keinen Sinn ergibt. Sie wird schnell sein, auf dem Handy sauber funktionieren, und kein Link zum Anfrageformular wird ins Leere führen. Erwarten Sie nicht, dass sie von allein Aufträge bringt — dafür braucht es mehr Arbeit, als der kleinste Umfang zulässt. Aber sie wird ordentlich gemacht.</p>
                    <p><strong>Firmenwebsite — bis zwölf Seiten.</strong> Eine Website nach Maß mit einer einfachen Inhaltsverwaltung, sodass Sie Texte, Fotos oder Referenzen selbst ändern. Eine weitere Sprachversion ist möglich. Das bestellen die meisten Firmen.</p>
                    <p><strong>Individuell — ohne Umfangsgrenze.</strong> Onlineshop, Reservierungssystem oder eine Anwendung nach Maß. Der Umfang steht nicht vorher fest, der Preis ergibt sich daraus, was die Website können muss und an welche Systeme sie angebunden wird.</p>
                    <p>Ich bin nicht umsatzsteuerpflichtig. Der Preis, den ich Ihnen nenne, ist ein Endpreis — es kommt keine Mehrwertsteuer hinzu. Was genau jeweils enthalten ist, steht aufgeschlüsselt in der <a href="/de/preisliste">Preisliste</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Sie kennen den Preis, bevor ich anfange zu arbeiten. Nicht erst auf der Rechnung.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Was den Preis nach oben treibt</h2>
                    <ul>
                    <li><strong>Anbindung an ein System, das Sie in der Firma schon nutzen.</strong> Lager, Buchhaltung, Reservierungen. Je mehr sich zwei Systeme verstehen müssen, desto mehr Arbeit ist es.</li>
                    <li><strong>Weitere Sprachen.</strong> Das ist nicht nur eine Textübersetzung. Es ist eine weitere Version der ganzen Website, die jemand pflegen muss.</li>
                    <li><strong>Inhalte, die es noch nicht gibt.</strong> Wenn Sie weder Fotos noch Texte haben, müssen sie erst entstehen. Wir klären vorher, was Sie beisteuern und was ich — damit es auf der Rechnung keine Überraschung gibt. Warum der Inhalt wichtiger ist als das Aussehen, schreibe ich im Artikel <a href="/de/blog/design-oder-inhalt">Design oder Inhalt?</a></li>
                    <li><strong>Ein Umfang, der unterwegs wächst.</strong> Deshalb schreibe ich die Spezifikation. Damit wir beide wissen, wo die Grenze liegt.</li>
                    </ul>
                    <h2>Was den Preis senkt</h2>
                    <ul>
                    <li><strong>Texte und Fotos liegen bereit.</strong> Es muss nichts erst entstehen, und ich kann gleich mit dem Bau anfangen.</li>
                    <li><strong>Weniger Seiten.</strong> Weniger Arbeit, niedrigerer Preis.</li>
                    <li><strong>Eine Sprache.</strong> Eine Version der Website, die gebaut und gepflegt werden muss.</li>
                    <li><strong>Sie pflegen die Inhalte selbst.</strong> Ich zeige Ihnen, wie es geht, und Texte und Fotos stellen Sie statt mir auf die Website.</li>
                    </ul>
                    <h2>Warum ich nicht der Günstigste bin</h2>
                    <p>Weil ich es nicht sein will. Eine Website aus dem Template für ein paar hundert Euro ergibt Sinn, wenn Sie nur eine Visitenkarte im Internet brauchen. Zu dem Preis können Sie sie ruhig haben, das sage ich Ihnen geradeheraus und werde Sie nicht umstimmen.</p>
                    <p>Ich baue Websites für Firmen, die ihre Website im Geschäft tatsächlich benutzen und sie ordentlich haben wollen. Für den Unterschied bekommen Sie eine Lösung, die darauf zugeschnitten ist, wie Ihre Firma arbeitet, und Code, der Ihnen gehört. Er ist weder bei mir eingesperrt noch bei einer Plattform, von der Sie nicht mehr wegkämen.</p>
                    <h2>Wann Sie keine Website bei mir kaufen sollten</h2>
                    <p>Wenn Sie die Website in einer Woche brauchen. Wenn Sie nur ein bestehendes WordPress reparieren wollen. Beides mache ich nicht, und es ist besser, Sie wissen es jetzt als nach zwei Terminen.</p>
                    <h2>Wie Sie zum genauen Preis kommen</h2>
                    <p>Schreiben Sie mir, was Sie brauchen. Ruhig kurz. Ich melde mich spätestens am nächsten Arbeitstag und wir gehen es durch. Wenn dabei herauskommt, dass ich Ihnen helfen kann, bekommen Sie eine Spezifikation mit einem konkreten Preis. Wenn nicht, sage ich es Ihnen und dränge Ihnen nichts auf.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 4 — Příprava na nový web → Vorbereitung auf die neue Website
            // cs jak-se-pripravit-na-novy-web → de vorbereitung-auf-die-neue-website
            // ----------------------------------------------------------------
            4 => [
                'title'       => 'Neun Fragen, die Sie vor dem Website-Projekt klären',
                'description' => 'Neun Fragen, die wir ohnehin beantworten müssen. Wenn Sie sie vorher durchgehen, sparen wir beide Zeit und Sie bekommen einen genaueren Preis.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Fast niemand schreibt mir mit einem fertigen Briefing, und das ist in Ordnung. Dafür bin ich da, um nachzufragen. Wenn Sie die Fragen unten aber vorher durchgehen, verkürzen wir die ganze Runde um ein paar Wochen und Sie bekommen gleich beim ersten Mal einen genaueren Preis.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Sie müssen nicht auf alles eine Antwort haben</h2>
                    <p>Dieser Artikel ist kein Test. Es ist eine Liste von Dingen, nach denen ich ohnehin fragen werde. Wenn Sie die Antwort kennen, schreiben Sie sie mir gleich. Wenn nicht, schreiben Sie „weiß ich nicht" und wir gehen es zusammen durch. Das ist eine völlig legitime Antwort und ich höre sie oft.</p>
                    <h2>1. Was soll die Website für Ihre Firma tun</h2>
                    <p>Soll sie Anfragen bringen? Ihnen Telefonate sparen, weil die Leute die Antworten selbst lesen? Verkaufen? Oder einfach nur existieren, damit Sie bei einer Ausschreibung niemand aussortiert? Das sind alles gültige Ziele, aber sie führen zu drei verschiedenen Websites.</p>
                    <h2>2. Für wen ist sie gedacht</h2>
                    <p>Der Inhaber einer kleinen Firma, der Einkäufer eines Konzerns und der Endkunde lesen völlig unterschiedlich. Je konkreter Sie mir beschreiben, wer Sie heute anruft, desto besser kann ich die Seitenstruktur schreiben.</p>
                    <h2>3. Was soll der Mensch auf der Website tun</h2>
                    <p>Eine Hauptsache pro Seite. Anrufen, das Formular ausfüllen, die Preisliste herunterladen, bestellen. Wenn eine Website fünf Dinge gleichzeitig tun soll, macht sie keines davon richtig. Was sonst noch entscheidet, ob eine Website funktioniert, habe ich im Artikel <a href="/de/blog/erfolgreiche-website-erstellen">Erfolgreiche Website erstellen</a> aufgeschrieben.</p>
                    <h2>4. Was die Leute über Sie nicht wissen und wissen sollten</h2>
                    <p>Das ist das Wertvollste, was Sie mir geben können. Meistens ist es etwas, das Sie in Terminen immer wieder erzählen und das auf der Website fehlt.</p>
                    <h2>5. Was an der heutigen Website nicht funktioniert</h2>
                    <p>Wenn Sie schon eine Website haben, schreiben Sie mir konkret, was Sie daran stört. Lässt sie sich nicht ändern? Findet man sie nicht? Sieht sie alt aus? Kommt nichts darüber herein?</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Das schlechteste Briefing ist „machen Sie es schön". Das beste ist „genau das stört mich".</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>6. Wer wird die Inhalte pflegen</h2>
                    <p>Wenn Sie Texte und Fotos selbst ändern wollen, baue ich Ihnen eine einfache Inhaltsverwaltung ein und zeige Ihnen, wie es geht. Wenn nicht, müssen wir sie nicht bauen und Sie sparen. Beides ist in Ordnung, ich muss es nur vorher wissen.</p>
                    <h2>7. Was Sie schon haben</h2>
                    <p>Logo, Fotos, Texte, Zugänge zu Domain und Hosting, einen Shop mit Produkten in irgendeinem System. Je mehr davon vorhanden ist, desto weniger muss erst hergestellt werden. Wenn Sie eine fertige <a href="/de/blog/wettbewerbsanalyse-website">Wettbewerbsanalyse</a> oder <a href="/de/blog/keyword-recherche-schritt-fuer-schritt">Keyword-Recherche</a> haben, schicken Sie sie mir auch.</p>
                    <h2>8. Welches Budget haben Sie</h2>
                    <p>Ich weiß, diese Frage mag niemand. Ich stelle sie, um Ihnen gleich sagen zu können, ob ich es zu diesem Preis kann. Wenn nicht, sage ich es sofort und wir verlieren beide keine Zeit.</p>
                    <h2>9. Bis wann brauchen Sie es</h2>
                    <p>Wenn Sie wegen einer Messe oder einer Eröffnung einen festen Termin haben, nennen Sie ihn mir gleich. Daran erkenne ich, ob ich es schaffe.</p>
                    <h2>Was danach passiert</h2>
                    <p>Aus Ihren Antworten schreibe ich eine Spezifikation. Darin steht, was ich baue, und ein Preis, der gilt. Erst dann entscheiden Sie, ob wir es machen. Sie unterschreiben nichts im Voraus.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 6 — Aplikace na míru → Wann sich eine eigene Anwendung lohnt
            // cs kdy-se-vyplati-aplikace-na-miru → de wann-sich-eine-eigene-anwendung-lohnt
            // ----------------------------------------------------------------
            6 => [
                'title'       => 'Wann sich eine eigene Anwendung statt Excel lohnt',
                'description' => 'Achtzehn Jahre habe ich in der Toyota-Logistik gearbeitet und dort Anwendungen für den Betrieb geschrieben. Woran Sie merken, dass Excel nicht mehr reicht.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Achtzehn Jahre lang habe ich bei Toyota gearbeitet. Angefangen habe ich als Arbeiter in der Logistik, aufgehört als Senior-Spezialist im Projektteam. In der Zeit habe ich viele Prozesse gesehen, die auf Tabellen und Papier liefen, und ein paar davon habe ich durch eine Anwendung ersetzt. Ich schreibe, woran Sie merken, dass Sie auch an diesem Punkt sind.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Die Tabelle ist nicht der Feind</h2>
                    <p>Excel ist ein ausgezeichnetes Werkzeug und viele Firmen können damit jahrelang problemlos arbeiten. Ich will Ihnen nicht einreden, dass Sie eine Anwendung brauchen. Meistens brauchen Sie keine.</p>
                    <p>Das Problem beginnt in dem Moment, in dem mehrere Leute gleichzeitig mit der Tabelle arbeiten, in dem daraus etwas gedruckt wird, nach dem dann gearbeitet wird, und in dem ein Fehler darin Geld kostet. Ab da lohnt sich die Frage.</p>
                    <h2>Fünf Signale, dass die Tabelle an ihrer Grenze ist</h2>
                    <ol>
                    <li><strong>Es gibt mehrere Versionen derselben Tabelle</strong> und niemand weiß genau, welche die gültige ist.</li>
                    <li><strong>Jemand überträgt Daten von einem System ins andere.</strong> Von Hand, jeden Tag, immer wieder.</li>
                    <li><strong>Wenn dieser Mensch krank ist, steht die Arbeit.</strong> Der Prozess hängt an einer Person und ihrer Tabelle.</li>
                    <li><strong>Fehler fallen zu spät auf.</strong> Ein Zahlendreher in der Zelle zeigt sich erst beim Kunden.</li>
                    <li><strong>Niemand kann sagen, wie der Stand gerade ist.</strong> Die Zahl muss aus fünf Dateien zusammengesetzt werden.</li>
                    </ol>
                    <p>Wenn davon ein Punkt passt, lassen Sie es. Wenn drei oder mehr passen, lohnt es sich nachzurechnen.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Sie brauchen eine Anwendung nicht, weil sie modern ist. Sie brauchen sie, wenn die Handarbeit Sie mehr kostet als ihre Entwicklung.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Was ich bei Toyota gemacht habe</h2>
                    <p>Meine Aufgabe war es, die Logistik im Zusammenspiel mit der Montage effizienter zu machen. In der Produktion können Sie sich nicht leisten, dass das Band stehen bleibt, also muss jede Änderung vorher durchdacht und getestet werden.</p>
                    <p>Ich habe dort eine Webanwendung namens TSM programmiert, die einen Teil der Handarbeit in der Logistik ersetzt hat. Nach und nach habe ich noch mehrere weitere Anwendungen in den Betrieb gebracht.</p>
                    <p>Es ging dabei nicht um spektakuläre Technologie. Es ging darum, die Stelle zu finden, an der Zeit verschwendet wird, und diese Stelle zu beseitigen. Genauso denke ich auch heute, wenn ich für eine Firma eine Anwendung nach Maß baue.</p>
                    <h2>Wie Sie es selbst nachrechnen</h2>
                    <p>Nehmen Sie eine Tätigkeit, die von Hand erledigt wird. Wie viele Minuten am Tag kostet sie? Wie oft im Monat passiert dabei ein Fehler und was kostet dieser Fehler? Multiplizieren Sie es mit zwölf Monaten. Wenn eine Zahl im Bereich von mehreren tausend Euro pro Jahr herauskommt, rechnet sich eine Anwendung nach Maß in ein paar Jahren und spart danach nur noch. Wenn ein paar hundert Euro herauskommen, lassen Sie es und kaufen Sie sich lieber eine ordentliche Tabelle.</p>
                    <p>Diese Rechnung mache ich Ihnen beim ersten Gespräch kostenlos. Wenn dabei herauskommt, dass es sich nicht lohnt, sage ich es Ihnen.</p>
                    <h2>Was eine Anwendung nach Maß ist und was nicht</h2>
                    <p>Es ist ein Programm, das genau darauf gebaut ist, wie Ihre Firma arbeitet. Erfassung, Bestellungen, Planung, Auswertungen. Es läuft im Browser, Sie installieren also nichts und kommen auch vom Handy daran.</p>
                    <p>Es ist kein fertiges System, an das Sie sich anpassen müssen. Das ist der wesentliche Unterschied und auch der Grund, warum es mehr kostet als ein Monatsabo für eine Standardsoftware.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 10 — Potřebuje firma web → Braucht Ihre Firma eine Website
            // cs potrebuje-vase-firma-webovou-stranku → de braucht-ihre-firma-eine-website
            // ----------------------------------------------------------------
            10 => [
                'title'       => 'Braucht Ihre Firma eine Website? Manchmal nicht',
                'description' => 'Ich bin nicht unbefangen, ich baue Websites. Trotzdem gibt es Fälle, in denen eine Website nichts bringt. Welche das sind und was stattdessen hilft.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Ich verdiene mein Geld damit, Websites zu bauen, also schreibe ich diesen Artikel nicht unbefangen und tue auch nicht so. Trotzdem kenne ich Fälle, in denen eine Website einer Firma nicht hilft und das Geld besser angelegt wäre. Ich schreibe geradeheraus, welche das sind.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Wann Sie wirklich keine Website brauchen</h2>
                    <p><strong>Sie sind ausgelastet und die Aufträge kommen über Empfehlungen.</strong> Wenn Sie für Monate im Voraus Arbeit haben und neue Anfragen ohnehin ablehnen würden, bringt Ihnen eine Website jetzt nichts. Kommen Sie darauf zurück, wenn Sie wachsen oder die Art der Aufträge ändern wollen.</p>
                    <p><strong>Sie verkaufen an einen großen Abnehmer.</strong> Wenn Ihre Firma auf zwei langfristigen Verträgen steht, ist die Website eine Visitenkarte und kein Vertriebskanal. Eine einfache Seite mit Kontakten reicht, und dafür müssen Sie keine viertausend Euro ausgeben.</p>
                    <p><strong>Es ist niemand da, der ans Telefon geht.</strong> Eine Website, die Anfragen bringt, auf die niemand antwortet, ist schlimmer als gar keine Website. Der Kunde merkt sich, dass Sie sich nicht gemeldet haben.</p>
                    <p><strong>Sie suchen ein Wunder.</strong> Eine Website ist ein Werkzeug, keine Lösung. Wenn in der Firma nicht klar ist, was sie verkauft und an wen, repariert die Website das nicht. Sie schreibt es nur in größerer Schrift.</p>
                    <h2>Wann eine Website Sinn ergibt</h2>
                    <p><strong>Die Leute prüfen Sie, bevor sie anrufen.</strong> Das macht heute fast jeder. Wenn sie nur ein Branchenbuchprofil von 2019 finden, stufen sie Sie herunter.</p>
                    <p><strong>Sie erklären immer wieder dasselbe.</strong> Wenn Sie in jedem Termin wiederholen, wie die Zusammenarbeit abläuft und was im Preis enthalten ist, erklärt es die Website für Sie. Sie sprechen dann mit Leuten, die es schon wissen.</p>
                    <p><strong>Der Wettbewerb sieht besser aus, als er arbeitet.</strong> Das ist unangenehm, aber es entscheidet.</p>
                    <p><strong>Sie wollen andere Aufträge, als Sie heute haben.</strong> Eine Website ist der günstigste Weg zu zeigen, dass Sie auch größere und anspruchsvollere Dinge machen.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Eine Website holt die Aufträge nicht für Sie herein. Aber sie sagt vorab das, was Sie sonst in jedem Termin neu erklären.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Was eine Website nicht kann</h2>
                    <p>Ich verspreche Ihnen nicht, wie viele Anfragen sie bringt. Ich habe keinen Einfluss darauf, wie die Nachfrage in Ihrer Branche aussieht, welchen Preis Sie haben und wie schnell Sie antworten. Wer Ihnen diese Zahl verspricht, rät.</p>
                    <p>Was ich beeinflussen kann, ist die Arbeit, die ich abliefere. Dass die Website schnell und verständlich ist, auf dem Handy gut aussieht und dass in zwei Jahren nichts daran auseinanderfällt.</p>
                    <h2>Bevor Sie sich entscheiden</h2>
                    <p>Versuchen Sie, eine Frage zu beantworten. Wenn morgen ein Mensch auf Ihre Website käme, der noch nie von Ihnen gehört hat — würde er in zehn Sekunden verstehen, was Sie machen und ob es etwas für ihn ist? Wenn nicht, liegt dort das Problem, und es spielt keine Rolle, ob Sie eine Website haben oder nicht. Was eine Website sonst noch erfüllen muss, damit sie funktioniert, schreibe ich im Artikel <a href="/de/blog/erfolgreiche-website-erstellen">Erfolgreiche Website erstellen</a>.</p>
                    HTML,
            ],

            // ----------------------------------------------------------------
            // 13 — Redesign → Website-Relaunch
            // cs redesign-webovych-stranek-duvody-signaly-a-jak-na-to → de wann-website-relaunch-sinn-ergibt
            // ----------------------------------------------------------------
            13 => [
                'title'       => 'Wann ein Website-Relaunch Sinn ergibt und wann nicht',
                'description' => 'Fünf Gründe, warum ein Website-Relaunch Sinn ergibt, und drei, warum nicht. Dazu, worauf Sie achten, damit danach der Traffic nicht einbricht.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Über einen Relaunch wird meistens dann gesprochen, wenn die Website jemandem nicht mehr gefällt. Das ist der schwächste Grund, den ich kenne. Ich schreibe, wann es sich lohnt, eine Website neu zu machen, wann nicht, und was dabei am häufigsten schiefgeht.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Fünf Gründe, warum ein Relaunch Sinn ergibt</h2>
                    <p><strong>1. Die Website lässt sich nicht pflegen.</strong> Eine geänderte Telefonnummer bedeutet, jemandem zu schreiben, der sich in einer Woche meldet. Das allein ist einen Relaunch wert.</p>
                    <p><strong>2. Auf dem Handy ist sie unbrauchbar.</strong> Die meisten Menschen schauen heute vom Handy auf eine Website. Wenn sie dort zoomen und seitwärts schieben müssen, gehen sie weg.</p>
                    <p><strong>3. Die Website fällt auseinander oder stürzt ab.</strong> Typisch bei Baukästen, die aus Plug-ins verschiedener Autoren zusammengesteckt sind. Ein Update und das Bestellformular funktioniert nicht mehr.</p>
                    <p><strong>4. Die Firma hat sich verändert.</strong> Sie machen etwas anderes, wollen etwas anderes verkaufen, liegen in einer anderen Preisklasse. Die Website ist dort geblieben, wo Sie vor fünf Jahren waren.</p>
                    <p><strong>5. Die Websites Ihrer Mitbewerber sehen eine Klasse besser aus.</strong> Der Kunde vergleicht Sie nebeneinander, ob Sie wollen oder nicht. Wie Sie sich die Mitbewerber gründlich ansehen, beschreibe ich im Artikel über die <a href="/de/blog/wettbewerbsanalyse-website">Wettbewerbsanalyse</a>.</p>
                    <h2>Drei Situationen, in denen Sie das Geld behalten sollten</h2>
                    <p><strong>Die Website ist zwei Jahre alt und funktioniert.</strong> Alter allein ist kein Grund. Wenn sie sich pflegen lässt, schnell ist und die Leute darauf finden, was sie brauchen, lassen Sie sie in Ruhe.</p>
                    <p><strong>Sie gefällt Ihnen nicht, aber die Kunden stört sie nicht.</strong> Ihr Geschmack und der Geschmack Ihres Kunden sind nicht dasselbe. Bevor Sie ein paar tausend Euro hineinstecken, fragen Sie fünf Kunden, was ihnen auf der Website gefehlt hat.</p>
                    <p><strong>Das eigentliche Problem liegt woanders.</strong> Wenn keine Anfragen kommen, weil Sie drei Klassen teurer sind als die Umgebung und es nirgends erklären, repariert das ein neues Aussehen nicht.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Ein Relaunch ist Arbeit am Inhalt und an der Struktur. Das Aussehen ist erst die Folge.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Was bei einem Relaunch am häufigsten schiefgeht</h2>
                    <p><strong>Die Seitenadressen werden weggeworfen.</strong> Die neue Website hat eine andere Struktur und die alten Adressen funktionieren nicht mehr. Suchmaschinen und Links von fremden Websites führen plötzlich ins Leere. Die Lösung ist einfach und wird vor dem Start gemacht: Die alte Adresse muss dauerhaft auf die neue weiterleiten. Ich möchte, dass Sie das von jedem einfordern, der Ihnen die Website baut. Warum Positionen in der Suche so wertvoll sind, erkläre ich im Artikel <a href="/de/blog/was-ist-seo">Was ist SEO</a>.</p>
                    <p><strong>Es wird nur das Aussehen erneuert.</strong> Die Texte werden eins zu eins übernommen, auch die, die niemand verstanden hat. Die Website sieht dann neu aus und funktioniert genauso schlecht. Warum der Inhalt wichtiger ist als das Aussehen, schreibe ich im Artikel <a href="/de/blog/design-oder-inhalt">Design oder Inhalt?</a></p>
                    <p><strong>Dinge verschwinden, die funktioniert haben.</strong> Manchmal hat die alte Website eine Seite, auf die die Hälfte des Traffics kommt. Bevor etwas gelöscht wird, muss man in die Statistik schauen.</p>
                    <p><strong>Niemand übernimmt die Inhalte.</strong> Referenzen, Fotos von Umsetzungen, Dokumente zum Herunterladen. Es ist meistens mehr, als man erwartet.</p>
                    <h2>Wie ich an einen Relaunch herangehe</h2>
                    <p>Zuerst schaue ich, was auf der alten Website funktioniert, und das behalte ich. Dann gehen wir durch, was die Website tun soll und wem sie es sagen soll. Erst danach geht es darum, wie sie aussehen wird. Die Seitenadressen regle ich vor dem Start, nicht danach.</p>
                    <p>Den Code schreibe ich selbst, ohne fertige Plug-ins fremder Autoren. Genau die sind meistens der Grund, warum eine Website nach einiger Zeit auseinanderfällt und neu gemacht werden muss.</p>
                    HTML,
            ],

            // ================================================================
            // OND-432 — články 1, 5, 7, 9, 11, 12 (dokument `clanky-final-de` na OND-430
            // + redliny `review-preklady` z OND-431). Na rozdíl od článků výš nesou
            // i obrázková pole a `bonus`/`extra` = null: starý import v nich měl
            // bloky, které by se vykreslily pod novým textem.
            // DE řádky vznikají poprvé. Obrázky jsou vyjmenované, ne převzaté z CS (imagesFromCs):
            // ty s českým nebo anglickým textem se v DE vynechávají, u dvanáctky i náhled.
            // ================================================================

            // ----------------------------------------------------------------
            // 1 — Domainnamen finden (slug domainnamen-finden)
            // ----------------------------------------------------------------
            1 => [
                'title'       => 'Einen Domainnamen finden, den sich Ihre Kunden merken',
                'description' => 'Wie Sie eine Domain finden, die man leicht schreibt und sich merkt. Zehn Regeln für den Domainnamen, Kosten und was Sie vorher prüfen sollten.',
                'img_preview' => 'jak-vybrat-perfektni-domenove-jmeno_priview.jpg',
                'img_main'    => 'jak-vybrat-perfektni-domenove-jmeno.jpg',
                'perex'       => <<<'HTML'
                    <blockquote><p>Eine Domain wählen Sie einmal und schreiben sie dann jahrelang auf Rechnungen, Visitenkarten und unter jede E-Mail. Später ändern lässt sie sich, aber das kostet Arbeit und einen Teil der Leute, die Sie schon kennen. Ich habe aufgeschrieben, wie Sie bei der Wahl des Domainnamens vorgehen, was Sie vor der Registrierung prüfen sollten und worauf ich bei Kunden selbst gestoßen bin.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Kurz gesagt: so wählen Sie eine Domain</h2>
                    <ol>
                    <li><strong>Sie passt zu Ihnen.</strong> Firmenname, Ihr eigener Name oder das, was Sie tun.</li>
                    <li><strong>Kurz.</strong> Am besten 8 bis 12 Zeichen, ohne Ziffern und Bindestriche.</li>
                    <li><strong>Beim ersten Hören zu merken.</strong> Keine Abkürzungen, die niemandem etwas sagen.</li>
                    <li><strong>Nach Gehör schreibbar.</strong> Sie sagen sie einmal, und der andere schreibt sie richtig.</li>
                    <li><strong>Ein Branchenwort nur, wenn es passt.</strong> Im Suchergebnis bringt es Sie nicht nach oben.</li>
                    <li><strong>Mit Luft für die Zukunft.</strong> Binden Sie sich nicht an ein Produkt oder eine Stadt.</li>
                    <li><strong>Frei und ohne unangenehme Nebenbedeutung.</strong> Prüfen Sie, ob sie frei ist, und geben Sie sie in eine Suchmaschine ein.</li>
                    <li><strong>Ohne schlechte Vergangenheit.</strong> Schauen Sie nach, was früher darauf lief.</li>
                    <li><strong>Auf .de</strong>, wenn Sie in Deutschland verkaufen, oder auf der Endung Ihres Landes.</li>
                    <li><strong>Ohne fremde Marke.</strong> Das erspart Ihnen einen Streit.</li>
                    </ol>
                    <p>Jeden Punkt erkläre ich unten, mit Beispielen aus der Praxis.</p>
                    <h2>Was ist ein Domainname und brauchen Sie überhaupt einen?</h2>
                    <p>Der Domainname, kurz Domain, ist die Adresse, die man in den Browser tippt, um auf Ihre Website zu kommen. Computer finden sich im Internet über IP-Adressen aus Zahlen. Die Domain ist der Name, den sich ein Mensch stattdessen merken kann. Zum Beispiel <em>meinefirma.de</em>.</p>
                    <p>Eine Domain lohnt sich auch ohne Website. Schon wegen der E-Mail. Die Adresse <em>info@meinefirma.de</em> wirkt anders als <em>meinefirma@gmx.de</em>. Den Teil vor dem @ wählen Sie selbst, also kann jeder Mitarbeiter ein eigenes Postfach haben, und alle tragen den Firmennamen.</p>
                    <p>Ob Sie eine Website brauchen oder ob Ihnen Domain und E-Mail vorerst reichen, habe ich im Artikel <a href="/de/blog/braucht-ihre-firma-eine-website">Braucht Ihre Firma eine Website?</a> beschrieben.</p>
                    <h2>1. Einen Domainnamen wählen, der zu Ihrem Geschäft passt</h2>
                    <p><strong>Ein kleiner Betrieb vor Ort</strong> kann sich eine Domain leisten, die sagt, was er macht. Wenn Sie Boote verleihen, sagt <em>aufdemwasser.de</em> einem Fremden auf den ersten Blick, worum es geht.</p>
                    <p><strong>Eine Firma, die wachsen will,</strong> ist in einer anderen Lage. Die Domain <em>betten.de</em> ist großartig, bis Sie auch Tische und Gartenmöbel verkaufen. Dann fängt sie an zu bremsen. Größere Firmen bauen deshalb meistens auf eine Marke, nicht auf eine Beschreibung ihres Sortiments.</p>
                    <p><strong>Wenn Sie allein arbeiten</strong>, denken Sie über Ihren eigenen Namen nach, zum Beispiel <em>annaschneider.de</em>. Eine Marke, die auf einem Namen steht, baut sich viel leichter auf als ein unpersönlicher Firmenname. Nur muss der Name frei sein und sich schreiben lassen. Dazu komme ich in Punkt 4.</p>
                    <p>Machen Sie bei jeder Variante zumindest einen kurzen <a href="/de/blog/wettbewerbsanalyse-website">Blick auf den Wettbewerb</a>. Sie wollen nicht, dass man Sie mit jemandem verwechselt, der zwei Straßen weiter dasselbe macht.</p>
                    <p>Wenn Sie die Firma gerade erst gründen, wählen Sie Name und Domain zusammen. Prüfen Sie, ob die Domain frei ist, bevor Sie den Namen eintragen lassen und ein Logo in Auftrag geben.</p>
                    <h2>2. Länge der Domain und erlaubte Zeichen</h2>
                    <p>Je kürzer, desto besser. Technisch darf eine Domain bis zu 63 Zeichen lang sein, ideal sind aber 8 bis 12. So eine Domain passt auf die Visitenkarte und lässt sich am Telefon diktieren.</p>
                    <p>Bei .de sind <strong>die Buchstaben a–z, die Ziffern 0–9 und der Bindestrich</strong> erlaubt, dazu Umlaute wie ä, ö, ü und das ß. Der Bindestrich darf nicht am Anfang oder am Ende stehen. Wenn Sie eine Domain mit Umlaut nehmen, sichern Sie sich auch die Schreibweise mit ae, oe, ue. Viele tippen sie so, und nicht jedes System kommt mit Umlauten zurecht. Großbuchstaben spielen keine Rolle. <em>ONDRAWEB.cz</em> und <em>ondraweb.cz</em> führen an dieselbe Stelle.</p>
                    <p><strong>Vermeiden Sie Ziffern</strong>, wenn sie nicht zur Marke gehören. Bei <em>3maler.de</em> weiß niemand, ob er die Ziffer tippen soll oder <em>dreimaler.de</em>.</p>
                    <p><strong>Bindestriche sind ein Überbleibsel.</strong> Früher trennte man damit Wörter, weil eine Domain kein Leerzeichen enthalten kann. Heute schreibt man die Wörter zusammen, und der Bindestrich ist nur noch etwas, das Sie am Telefon extra buchstabieren müssen.</p>
                    <h2>3. Einen Domainnamen wählen, den man sich leicht merkt</h2>
                    <p>Eine Domain soll nach dem ersten Hören hängen bleiben. Ohne komplizierte Wörter und ohne Rechtschreibfallen.</p>
                    <p>Auf dieses Problem stoße ich bei Abkürzungen. Eine Firma hat einen langen Namen, oft einen englischen, und will eine Domain aus den Anfangsbuchstaben. Für sie bedeuten diese Buchstaben etwas. Für den Kunden sind es sechs zufällige Zeichen.</p>
                    <p>Auf einer Website, die ich nicht gebaut habe, an der ich aber Updates und Sicherheitsprüfungen gemacht habe, ist die Domain genau so eine Abkürzung. Ich habe mehrmals daran gearbeitet und trotzdem hatte ich Mühe, sie mir zu merken.</p>
                    <h2>4. Ein Domainname, der sich leicht schreiben lässt</h2>
                    <p>Ihre Domain sieht man oft nicht geschrieben, man hört sie. Am Telefon, auf der Messe, von Bekannten. Man muss sie nach Gehör schreiben können.</p>
                    <ul>
                    <li><strong>Namen mit mehreren Schreibweisen.</strong> <em>Meier</em>, <em>Maier</em>, <em>Mayer</em> oder <em>Meyer</em>? <em>Schmidt</em> oder <em>Schmitt</em>? Am Telefon klingen sie gleich.</li>
                    <li><strong>Zeichen, die man verwechselt.</strong> Null und der Buchstabe O, großes I und kleines L. Auf der Visitenkarte sehen sie fast gleich aus.</li>
                    <li><strong>Doppelte Buchstaben an der Wortgrenze.</strong> Bei <em>sporttaschen.de</em> lässt ein Teil der Leute ein T weg.</li>
                    </ul>
                    <p><strong>Machen Sie einen Test.</strong> Sagen Sie die Domain einmal laut jemandem, der sie nicht kennt, und lassen Sie ihn sie aufschreiben. Wenn er nachfragen muss oder sie anders schreibt, hat die Domain den Test nicht bestanden. Besser, Sie merken das jetzt als nach tausend gedruckten Flyern.</p>
                    <h2>5. Keywords in der Domain: ja oder nein?</h2>
                    <p>Ein Wort aus Ihrer Branche in der Domain kann helfen. Nicht in der Suchmaschine, dort bringt es heute keine besseren Positionen. Es hilft dem Menschen, der die Domain in den Suchergebnissen sieht und gleich weiß, was er auf der Website findet. Was in der Suche wirklich entscheidet, schreibe ich im Artikel <a href="/de/blog/was-ist-seo">Was ist SEO</a>.</p>
                    <p>Ein Makler in Leipzig kann <em>immobilienleipzig.de</em> haben. Nur kommt er aus Leipzig dann schwer heraus. Darum geht es im nächsten Punkt.</p>
                    <p>Übertreiben Sie es nicht. Drei Keywords hintereinander wirken wie Spam. Und solche einfachen Domains sind meistens ohnehin längst vergeben.</p>
                    <h2>6. Denken Sie an die Zukunft</h2>
                    <p>Eine Firma kann um neue Produkte oder in eine weitere Stadt wachsen. Die Domain sollte ihr dabei nicht im Weg stehen. Wenn Sie zum Bootsverleih später Wasserski dazunehmen, passt <em>aufdemwasser.de</em> immer noch. <em>bootsverleih.de</em> nicht mehr.</p>
                    <p>Die Domain lässt sich auch später ändern. Die alte Adresse muss aber dauerhaft auf die neue weiterleiten, sonst verlieren Sie Links und Positionen in der Suche. Worauf Sie bei so einer Änderung achten müssen, schreibe ich im Artikel über den <a href="/de/blog/wann-website-relaunch-sinn-ergibt">Website-Relaunch</a>.</p>
                    <h2>7. Prüfen Sie, ob sie frei ist</h2>
                    <p>Ob eine .de-Domain frei ist, prüfen Sie kostenlos in der <a href="https://www.denic.de/services/whois-service/" target="_blank" rel="noopener">Domainabfrage der DENIC</a>, die die Endung .de verwaltet. Dieselbe Suche haben auch die Anbieter, bei denen Sie die Domain kaufen.</p>
                    <p><strong>Wenn sie vergeben ist</strong>, schauen Sie, was darauf ist. Läuft dort eine fremde Website? Suchen Sie einen anderen Namen. Ist sie leer oder steht sie zum Verkauf? Sie können den Inhaber ansprechen, nur bestimmt dann er den Preis. An einen vergebenen Namen lässt sich ein Wort oder eine Stadt anhängen, aber prüfen Sie, dass man Sie nicht mit dem verwechselt, der die ursprüngliche Domain hat.</p>
                    <p>Wenn Sie schon suchen, schauen Sie gleich, ob derselbe Name auch in den sozialen Netzwerken frei ist, die Sie nutzen.</p>
                    <p><strong>Dann geben Sie die Domain in eine Suchmaschine ein.</strong> Manchmal stellen Sie fest, dass das Internet mit dem Wort etwas verbindet, das Sie nicht wollen. Als ich eine Marke für Kundinnen aus der Finanz- und Immobilienbranche entwickelt habe, kam der Name <em>findom</em> in die engere Wahl. Dann habe ich ihn bei Google eingegeben. Es ist die Bezeichnung für einen Fetisch.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Eine gute Domain erkennen Sie daran, dass Sie sie niemandem buchstabieren müssen.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>8. Prüfen Sie die Vergangenheit der Domain</h2>
                    <p>Eine Domain, die heute frei ist, kann eine Vergangenheit haben. Lief darauf früher Spam oder ein betrügerischer Shop, können sich Suchmaschinen daran erinnern. Was darauf war, sehen Sie kostenlos im Internetarchiv <a href="https://web.archive.org/" target="_blank" rel="noopener">web.archive.org</a>. Geben Sie dort einfach die Domain ein.</p>
                    <h2>9. Mehrere Endungen registrieren?</h2>
                    <p>Die Endung ist der Teil nach dem letzten Punkt. Es gibt zwei Arten:</p>
                    <ul>
                    <li><strong>Länderendungen</strong>, gebunden an ein Land oder eine Region: <em>.de</em>, <em>.at</em>, <em>.ch</em>, <em>.eu</em>,</li>
                    <li><strong>generische Endungen</strong>, ohne Bindung an ein Land: <em>.com</em>, <em>.net</em>, <em>.info</em>.</li>
                    </ul>
                    <p>Wenn Sie in Deutschland verkaufen, nehmen Sie <strong>.de</strong>. Deutsche Kunden kennen die Endung und vertrauen ihr. Wenn Sie eine österreichische oder Schweizer Version der Website planen, registrieren Sie auch <em>.at</em> oder <em>.ch</em>. Und wenn Sie auf dem Namen eine Marke aufbauen, lohnt es sich, auch <em>.com</em> und <em>.eu</em> zu kaufen, damit sie kein Wettbewerber und kein Spekulant belegt. Das sind geringe Kosten im Jahr, und Sie haben Ruhe.</p>
                    <h2>10. Prüfen Sie, ob der Name als Marke geschützt ist</h2>
                    <p>Enthält die Domain eine fremde Marke, kann es mit dem Inhaber Streit um die Domain und um den Namen geben. Bevor Sie die Domain kaufen, geben Sie den Namen im <a href="https://register.dpma.de/DPMAregister/marke/einsteiger" target="_blank" rel="noopener">DPMAregister</a> des Deutschen Patent- und Markenamts ein. Marken, die in der ganzen Europäischen Union gelten, finden Sie in der Datenbank <a href="https://www.tmdn.org/tmview/" target="_blank" rel="noopener">TMview</a>.</p>
                    <h2>Was kostet eine Domain?</h2>
                    <p>Eine .de-Domain kostet bei den großen Anbietern regulär etwa 10 bis 16 Euro im Jahr, bei günstigen Anbietern auch weniger. Das erste Jahr ist oft als Aktion billiger, die Verlängerung kostet dann mehr. Vergleichen Sie deshalb den Preis für die Verlängerung, nicht für die Registrierung.</p>
                    <p>Bei der Firmenwebsite sind Domain und Hosting für das erste Jahr im Preis enthalten. Was in welcher Variante enthalten ist, finden Sie in der <a href="/de/preisliste">Preisliste</a>.</p>
                    <p>Gute Namen sind ein eigener Markt. Verlängert ein Inhaber seine Domain nicht, wird sie wieder frei, und gute Namen schnappen sich Domainhändler schnell. Ein kurzer Name, der zu einer Branche passt, kostet dann ein Vielfaches des normalen Preises.</p>
                    <h2>Auf wen die Domain eingetragen sein sollte</h2>
                    <p>Die Domain gehört dem, der als <strong>Domaininhaber</strong> eingetragen ist. Nicht dem, der sie bezahlt hat, und nicht dem, der Ihnen die Website gebaut hat. Wenn ein Entwickler oder eine Agentur sie für Sie registriert, bestehen Sie darauf, dass Ihre Firma Inhaber ist. Sonst brauchen Sie seine Zustimmung bei jeder Änderung, und vor allem dann, wenn Sie zu jemand anderem wechseln wollen. Wer Inhaber Ihrer Domain ist, sagt Ihnen Ihr Anbieter. Die öffentliche Abfrage zeigt die Daten des Inhabers aus Datenschutzgründen meistens nicht.</p>
                    <p>Behalten Sie auch die Laufzeit im Blick. Wird die Domain nicht rechtzeitig verlängert, funktionieren Website und E-Mails nicht mehr. Nach einer Frist, die je nach Endung und Anbieter unterschiedlich lang ist, wird die Domain frei und jeder kann sie registrieren.</p>
                    <p>Die Zugänge zur Domain gehören zu den Dingen, die Sie griffbereit haben sollten, bevor Sie eine neue Website angehen. Die ganze Liste steht im Artikel <a href="/de/blog/vorbereitung-auf-die-neue-website">Neun Fragen, die Sie vor dem Website-Projekt klären</a>.</p>
                    <h2>Teile einer Domain und wie Sie einen gefälschten Link erkennen</h2>
                    <p>Zum Schluss für alle, die verstehen wollen, was in der Adresszeile steht. Am meisten hilft das, wenn Sie einen betrügerischen Link erkennen wollen.</p>
                    <p>Eine Domain liest man von rechts nach links. Nehmen wir <code>blog.meineseite.de</code>:</p>
                    <ul>
                    <li><strong>Die Top-Level-Domain</strong> (TLD) ist die Endung, hier <code>de</code>.</li>
                    <li><strong>Die Second-Level-Domain</strong> ist der Name, den Sie registrieren und bezahlen, hier <code>meineseite</code>. Genau den wählen Sie.</li>
                    <li><strong>Die Subdomain</strong> ist alles links davon, hier <code>blog</code>. Subdomains legt der Inhaber selbst an, ohne weitere Registrierung. Zum Beispiel <code>shop.meineseite.de</code> für den Onlineshop.</li>
                    </ul>
                    <p><strong>Wem eine Domain gehört, erkennen Sie am Namen direkt vor der Endung.</strong> Die Adresse <code>google.irgendwas.de</code> gehört nicht Google, sondern dem, dem <code>irgendwas.de</code> gehört. Genau darauf bauen die meisten betrügerischen E-Mails.</p>
                    <p>Eine vollständige Webadresse (URL) hat noch weitere Teile. Am Beispiel <code>https://www.blog.meineseite.de/artikel-ueber-domains?seite=2#abschnittB</code>:</p>
                    <ul>
                    <li><strong>Protokoll</strong> <code>https</code>. Das S bedeutet, dass die Verbindung verschlüsselt ist. Auf einer Website ohne S geben Sie keine Passwörter und keine Zahlungsdaten ein. Tippen müssen Sie es nicht, der Browser versucht es heute von selbst.</li>
                    <li><strong><code>www</code></strong> ist nur eine Subdomain, die Websites früher nach dem World Wide Web bekamen. Tippen müssen Sie sie nicht. Die Website sollte aber nur unter einer Variante laufen und die andere darauf weiterleiten, sonst sieht die Suchmaschine sie womöglich doppelt.</li>
                    <li><strong>Der Pfad</strong> <code>/artikel-ueber-domains</code> führt zu einer bestimmten Seite oder Datei auf der Website.</li>
                    <li><strong>Der Parameter</strong> <code>?seite=2</code> übergibt der Website eine zusätzliche Information, hier die Seitennummer.</li>
                    <li><strong>Der Anker</strong> <code>#abschnittB</code> verweist auf eine bestimmte Stelle der Seite.</li>
                    </ul>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 5 — Was ist SEO (slug was-ist-seo)
            // ----------------------------------------------------------------
            5 => [
                'title'       => 'Was ist SEO und warum ist es wichtig? Ohne Fachjargon',
                'description' => 'Was SEO ist, wie es funktioniert und was Sie selbst schaffen. Technisches SEO, Inhalte und Backlinks für Firmeninhaber, ohne Versprechen vom ersten Platz.',
                'img_preview' => 'co-je-seo-a-proc-je-tak-dulezite_preview.jpg',
                'img_main'    => 'co-je-seo-a-proc-je-tak-dulezite.jpg',
                'perex'       => <<<'HTML'
                    <blockquote><p>SEO steht für Suchmaschinenoptimierung. Es geht darum, dass die Leute Sie bei Google finden, wenn sie genau das suchen, was Sie anbieten. Ich habe aufgeschrieben, woraus SEO besteht, was Sie selbst schaffen und wann Sie dem nicht glauben sollten, der es Ihnen verkauft.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Was ist SEO</h2>
                    <p>SEO (vom englischen <em>Search Engine Optimization</em>) ist Arbeit an der Website und um sie herum, damit ihre Seiten in den unbezahlten Suchergebnissen weiter oben erscheinen. Unbezahlt deshalb, weil Sie für einen Besuch von dort nicht wie bei Werbung für jeden Klick zahlen.</p>
                    <p>In Deutschland heißt das vor allem Google. Bing folgt mit großem Abstand. Beide bewerten Websites ähnlich, aber nicht gleich, deshalb kann eine Seite in beiden unterschiedlich stehen.</p>
                    <h2>Warum SEO wichtig ist</h2>
                    <p>Wer über eine Suchmaschine zu Ihnen kommt, hat etwas gesucht. Er ist nicht aus Versehen über eine Anzeige gekommen, die er überspringen wollte. Wenn er „Schreiner München“ eintippt und Sie findet, ist er einen Schritt näher daran, Sie anzurufen.</p>
                    <p>Besucher aus der Suche verschwinden außerdem nicht, wenn Sie aufhören zu zahlen. Werbung endet mit dem letzten bezahlten Klick. Eine gut geschriebene Seite kann jahrelang Besucher bringen.</p>
                    <p>Ich verspreche Ihnen aber nicht, dass SEO Kunden bringt. Das hängt davon ab, wie viele Leute Ihre Leistung suchen, welche Konkurrenz Sie haben und was die Website ihnen sagt, wenn sie ankommen. Und wer Ihnen den ersten Platz bei Google verspricht, verspricht etwas, das er nicht in der Hand hat. Die Reihenfolge der Ergebnisse bestimmt die Suchmaschine, niemand sonst.</p>
                    <h2>Die drei Teile von SEO</h2>
                    <p>SEO wird meistens in drei Bereiche geteilt. Technisch, inhaltlich (On-Page) und extern (Off-Page). Jeder beantwortet eine andere Frage.</p>
                    <h2>1. Technisches SEO: Kann die Suchmaschine Ihre Website lesen?</h2>
                    <p>Eine Suchmaschine liest eine Website nicht mit den Augen, sondern mit einem Programm, dem Crawler. Technisches SEO sorgt dafür, dass der Crawler alle Seiten erreicht und sie versteht.</p>
                    <ul>
                    <li><strong>Ladezeit.</strong> Langsame Websites verlassen die Leute, und die Suchmaschine merkt das. Wie Sie dastehen, zeigt kostenlos <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a> von Google.</li>
                    <li><strong>Handy.</strong> Google bewertet eine Website danach, wie sie auf dem Handy aussieht und funktioniert. Lässt sie sich dort schlecht bedienen, verlieren Sie auch in den Ergebnissen.</li>
                    <li><strong>Sicherheit.</strong> Die Website muss über verschlüsseltes <code>https</code> laufen.</li>
                    <li><strong>Adressen der Seiten.</strong> Jede Seite hat eine Adresse. Ändert sich eine Adresse, muss die alte dauerhaft auf die neue weiterleiten.</li>
                    <li><strong>Werkzeuge der Suchmaschinen.</strong> Melden Sie die Website in der <a href="https://search.google.com/search-console/about?hl=de" target="_blank" rel="noopener">Google Search Console</a> und in den <a href="https://www.bing.com/webmasters" target="_blank" rel="noopener">Bing Webmaster Tools</a> an. Beide sind kostenlos. Sie zeigen, ob die Suchmaschine Fehler auf der Website meldet und bei welchen Suchanfragen Sie erscheinen.</li>
                    </ul>
                    <p>Den technischen Teil muss der machen, der die Website baut. Fehlt er, ist die übrige Arbeit an SEO umsonst. Bei mir ist technisches SEO schon bei der kleinsten Website im Preis enthalten.</p>
                    <h2>2. On-Page-SEO: Beantwortet die Seite, was die Leute suchen?</h2>
                    <p>On-Page ist alles, was direkt auf der Seite steht. Text, Überschriften, der Seitentitel, die Beschreibung in den Suchergebnissen, Bilder und Links zwischen Ihren Seiten.</p>
                    <ul>
                    <li><strong>Eine Seite, ein Thema.</strong> Wenn man Sie unter „Badsanierung“ und unter „Fliesen verlegen“ finden soll, brauchen Sie zwei Seiten. Nicht eine, auf der alles steht.</li>
                    <li><strong>Die Worte der Kunden, nicht Ihre.</strong> Die Firma schreibt „Sanitärinstallation“, der Kunde sucht „WC austauschen“. Welche Wörter die Leute wirklich benutzen, erfahren Sie aus der <a href="/de/blog/keyword-recherche-schritt-fuer-schritt">Keyword-Recherche</a>.</li>
                    <li><strong>Das Thema gehört in die Überschrift, vor allem aber in den Text darunter.</strong> Der Suchmaschine reicht ein Wort in der Überschrift nicht. Sie will sehen, dass der Text das Thema wirklich behandelt.</li>
                    <li><strong>Seitentitel und Beschreibung.</strong> Das sieht man in den Suchergebnissen. Es entscheidet, ob jemand auf Sie klickt oder auf den Nachbarn.</li>
                    <li><strong>Links zwischen Ihren Seiten.</strong> Sie zeigen dem Crawler und dem Leser, was zusammengehört.</li>
                    </ul>
                    <p>Ein Branchenwort in der Domain bringt Ihnen keine Positionen. Mehr dazu im Artikel über die <a href="/de/blog/domainnamen-finden">Wahl des Domainnamens</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>SEO ist kein Trick, um die Suchmaschine zu überlisten, sondern Arbeit daran, dass die Seite wirklich beantwortet, was jemand sucht.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>3. Off-Page-SEO: Vertrauen andere Ihrer Website?</h2>
                    <p>Off-Page ist alles außerhalb Ihrer Website. Vor allem <strong>Backlinks</strong>, also Links von anderen Websites auf Ihre. Die Suchmaschine nimmt sie als Empfehlung. Ein Link von einer Branchenseite oder aus der Lokalzeitung wiegt mehr als hundert Links aus Verzeichnissen, die niemand liest.</p>
                    <p>Dazu gehören auch <strong>Firmenprofile und Bewertungen</strong>. Wenn Sie an einem bestimmten Ort arbeiten, legen Sie kostenlos ein <a href="https://www.google.de/intl/de/business/" target="_blank" rel="noopener">Unternehmensprofil bei Google</a> und einen Eintrag bei <a href="https://www.bing.com/forbusiness/" target="_blank" rel="noopener">Bing Places</a> an. Bei Suchanfragen wie „Klempner Köln“ sind sie oft vor den Websites selbst zu sehen.</p>
                    <p>Soziale Netzwerke beeinflussen die Positionen nicht direkt. Sie helfen nur indirekt. Wenn Inhalte geteilt werden, sehen sie mehr Leute, und jemand davon verlinkt sie vielleicht.</p>
                    <h2>Wie Sie Backlinks ehrlich bekommen</h2>
                    <ul>
                    <li><strong>Schreiben Sie etwas, auf das sich ein Link lohnt.</strong> Eine Anleitung, eine Preisübersicht, eine Antwort auf eine Frage, die in Ihrer Branche noch niemand ordentlich erklärt hat.</li>
                    <li><strong>Partner und Lieferanten.</strong> Firmen, mit denen Sie zusammenarbeiten, können Sie auf ihrer Website nennen. Oft reicht es, darum zu bitten.</li>
                    <li><strong>Branchen- und lokale Websites.</strong> Verbände, Kammern, Veranstaltungen, die Sie unterstützen, das Gemeindeblatt.</li>
                    <li><strong>Ein Artikel auf einer fremden Website.</strong> Ein Fachmagazin oder Blog druckt oft gern einen nützlichen Text eines Fachmanns, mit Link zum Autor.</li>
                    <li><strong>Links der Konkurrenz.</strong> Schauen Sie, wer auf Ihre Mitbewerber verlinkt. Oft finden Sie ein Verzeichnis oder einen Verband, wo nur Sie fehlen. Wie Sie sich die Konkurrenz ansehen, beschreibe ich im Artikel über die <a href="/de/blog/wettbewerbsanalyse-website">Wettbewerbsanalyse</a>.</li>
                    </ul>
                    <p>Pakete wie „100 Links für 50 Euro“ helfen Ihnen nicht. Suchmaschinen erkennen solche Links und bestrafen die Website im schlimmeren Fall dafür. Google schreibt das ausdrücklich in seinen <a href="https://developers.google.com/search/docs/essentials/spam-policies?hl=de" target="_blank" rel="noopener">Spamrichtlinien</a>.</p>
                    <h2>SEO in der Praxis: wo anfangen</h2>
                    <ol>
                    <li><strong>Melden Sie die Website in der Search Console und den Bing Webmaster Tools an.</strong> Sie sehen, wofür Sie erscheinen und ob die Suchmaschine Fehler meldet.</li>
                    <li><strong>Finden Sie heraus, was die Leute suchen.</strong> Schreiben Sie die Suchanfragen auf, wie oft sie gesucht werden und wie schwer es wird, dafür nach oben zu kommen.</li>
                    <li><strong>Geben Sie jeder wichtigen Suchanfrage eine Seite.</strong> Und schreiben Sie sie so, dass sie die Anfrage wirklich beantwortet.</li>
                    <li><strong>Ergänzen Sie Inhalte, die Ihre Kunden suchen.</strong> Die Fragen, die Sie am Telefon immer wieder hören, sind oft die besten Themen für Artikel.</li>
                    <li><strong>Beobachten Sie, was passiert.</strong> Positionen bewegen sich langsam. Ergebnisse sehen Sie nach Monaten, nicht nach Wochen.</li>
                    </ol>
                    <p>Vorsicht beim Relaunch. Wenn sich die Adressen der Seiten ändern und die alten nicht weitergeleitet werden, verlieren Sie Positionen, die Sie jahrelang aufgebaut haben. Worauf Sie achten müssen, schreibe ich im Artikel über den <a href="/de/blog/wann-website-relaunch-sinn-ergibt">Website-Relaunch</a>.</p>
                    <h2>Schaffen Sie das selbst?</h2>
                    <p>Die Grundlagen ja. Die Website bei den Suchmaschinen anmelden, ordentliche Seitentitel schreiben und in den Texten die Fragen der Kunden beantworten schafft jeder, der sein Fach kennt. Eine zusammenhängende Einführung bietet Google selbst in seinem <a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide?hl=de" target="_blank" rel="noopener">SEO-Leitfaden für Einsteiger</a>.</p>
                    <p>Keyword-Analyse, Content-Strategie und Leistungsüberwachung brauchen dagegen Zeit und kostenpflichtige Werkzeuge. Das mache ich als zusätzliche Dienstleistung. Den Preis finden Sie in der <a href="/de/preisliste">Preisliste</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 7 — Design oder Inhalt? (slug design-oder-inhalt)
            // ----------------------------------------------------------------
            7 => [
                'title'       => 'Design oder Inhalt? Was auf einer Website wichtiger ist',
                'description' => 'Was ist auf einer Website wichtiger, Design oder Inhalt? Warum ich mit dem Inhalt anfange, was Design leistet und was das für Ihre neue Website heißt.',
                'img_preview' => 'co-je-dulezitejsi-design-nebo-obsah_preview.jpg',
                'img_main'    => 'co-je-dulezitejsi-design-nebo-obsah.jpg',
                'perex'       => <<<'HTML'
                    <blockquote><p>Auf diese Frage antwortet man meistens „beides ist wichtig“. Das stimmt, sagt Ihnen aber nichts. Meine Antwort ist konkreter: Der Inhalt kommt zuerst, und das Design dient ihm. Ich schreibe, warum, und was das heißt, wenn Sie eine neue Website planen.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Die kurze Antwort: der Inhalt</h2>
                    <p>Design ist die Art, wie der Inhalt gezeigt wird. Ohne Inhalt hat es nichts zu zeigen. Wenn Sie nicht wissen, was die Website sagen soll, kann sie noch so schön sein, und der Besucher erkennt trotzdem nicht, ob Sie die Richtigen für ihn sind.</p>
                    <p>Mit Inhalt meine ich nicht nur Text. Es ist alles, was die Website mitteilt. Was Sie anbieten, wem, zu welchem Preis, wie die Zusammenarbeit abläuft, Arbeitsbeispiele, Referenzen, Fotos.</p>
                    <h2>Was der Inhalt leistet</h2>
                    <ul>
                    <li><strong>Er beantwortet Fragen.</strong> Der Besucher kam mit einer Frage. Findet er auf der Website die Antwort, meldet er sich. Wenn nicht, geht er weiter.</li>
                    <li><strong>Er schafft Vertrauen.</strong> Konkrete Referenzen und Arbeitsbeispiele überzeugen mehr als die schönste Grafik.</li>
                    <li><strong>Er bringt Leute aus der Suchmaschine.</strong> Eine Suchmaschine liest Text, keine Farben. Schönes Design bringt Sie nicht auf die erste Seite bei Google. Mehr dazu im Artikel <a href="/de/blog/was-ist-seo">Was ist SEO und warum ist es wichtig?</a></li>
                    </ul>
                    <h2>Was Design leistet</h2>
                    <p>Design ist aber keine Verzierung, auf die man verzichten kann. Es hat seine eigene Aufgabe.</p>
                    <ul>
                    <li><strong>Der erste Eindruck.</strong> Bevor jemand zu lesen anfängt, entscheidet er in ein paar Sekunden, ob die Website vertrauenswürdig wirkt. Ein veraltetes oder kaputtes Aussehen schreckt ihn ab, bevor er zum Inhalt kommt.</li>
                    <li><strong>Lesbarkeit.</strong> Überschriften, Absätze und Abstände entscheiden, ob man einen Text lesen kann oder nur überspringt.</li>
                    <li><strong>Führung.</strong> Gutes Design zeigt, was wichtig ist und wohin man als Nächstes klickt.</li>
                    <li><strong>Handy.</strong> Etwa die Hälfte der Leute schaut heute vom Handy auf Websites. Das Design muss auch dort funktionieren, nicht nur auf dem großen Monitor.</li>
                    </ul>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Design macht den Inhalt lesbar, aber es sagt nicht für Sie, was Sie anbieten und warum man gerade Sie wählen sollte.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>Warum ich mit dem Inhalt anfange</h2>
                    <p>Wenn ich eine neue Website baue, gehen der Kunde und ich zuerst durch, was die Website tun soll und wem sie es sagen soll. Erst danach kümmere ich mich darum, wie sie aussieht. Umgekehrt funktioniert es nicht. Ein Design, das ohne Inhalt gezeichnet wird, rechnet mit einer Überschrift auf zwei Zeilen und einem Absatz auf drei. Dann kommt der echte Text und passt nicht hinein.</p>
                    <p>Genauso ist es beim Relaunch. Ein neues Aussehen mit den alten Texten sieht neu aus, funktioniert aber genauso schlecht. Mehr dazu im Artikel über den <a href="/de/blog/wann-website-relaunch-sinn-ergibt">Website-Relaunch</a>.</p>
                    <h2>Was das für Ihre neue Website heißt</h2>
                    <ul>
                    <li><strong>Denken Sie gleich über den Inhalt nach.</strong> Was wissen die Leute über Sie nicht und sollten es wissen? Wonach fragen sie immer wieder? Das ist die Grundlage der Texte. Weitere Fragen finden Sie im Artikel <a href="/de/blog/vorbereitung-auf-die-neue-website">Neun Fragen, die Sie vor dem Website-Projekt klären</a>.</li>
                    <li><strong>Rechnen Sie Texte und Fotos ins Budget ein.</strong> Wenn Sie sie nicht haben, müssen sie erst entstehen. Auch darum geht es im Artikel <a href="/de/blog/was-kostet-eine-website">Was eine Website kostet</a>.</li>
                    <li><strong>Wählen Sie das Design nach dem Inhalt, nicht umgekehrt.</strong> Eine Website, die Ihnen bei einer anderen Firma gefällt, wurde für deren Texte entworfen. Nicht für Ihre.</li>
                    </ul>
                    <p>Eine schöne Website, die nichts sagt, hilft niemandem. Eine nützliche Website, die alt aussieht, verliert die Leute, bevor sie sie lesen. Sie brauchen beides, nur in der richtigen Reihenfolge. Was eine Website sonst noch erfüllen muss, damit sie funktioniert, schreibe ich im Artikel <a href="/de/blog/erfolgreiche-website-erstellen">Erfolgreiche Website erstellen</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 9 — Keyword-Recherche (slug keyword-recherche-schritt-fuer-schritt)
            // ----------------------------------------------------------------
            9 => [
                'title'       => 'Keyword-Recherche Schritt für Schritt: so gehen Sie vor',
                'description' => 'Keyword-Recherche Schritt für Schritt: wo Sie Suchanfragen sammeln, welche Werkzeuge kostenlos sind, wie Sie Keywords sortieren und daraus Seiten ableiten.',
                'img_preview' => 'jak-na-analyzu-klicovych-slov-krok-za-krokem_preview.jpg',
                'img_main'    => 'jak-na-analyzu-klicovych-slov-krok-za-krokem.jpg',
                'perex'       => <<<'HTML'
                    <blockquote><p>Die Keyword-Recherche ist eine Liste dessen, was die Leute in die Suchmaschine tippen, wenn sie suchen, was Sie anbieten. Ohne sie entsteht die Website so, wie Sie über Ihre Branche sprechen, nicht Ihre Kunden. Ich beschreibe ein Vorgehen, das Sie selbst mit einer gewöhnlichen Tabelle und kostenlosen Werkzeugen schaffen.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Was Keywords sind und wozu die Recherche dient</h2>
                    <p>Keywords sind die Wörter und Wendungen, die Leute bei Google oder Bing eingeben. „Küche nach Maß“, „Küche nach Maß Preis“, „Küchenstudio Leipzig“. Jede davon verrät, was jemand will und wie nah er am Kauf ist.</p>
                    <p>Die Recherche sagt Ihnen drei Dinge:</p>
                    <ul>
                    <li><strong>Wie Kunden über Ihre Branche sprechen.</strong> Oft anders als Sie.</li>
                    <li><strong>Was sie am häufigsten suchen.</strong> Und was dagegen fast niemand sucht, auch wenn es Ihnen wichtig vorkommt.</li>
                    <li><strong>Welche Seiten Ihre Website haben soll.</strong> Jede Gruppe von Suchanfragen braucht ihre eigene Seite.</li>
                    </ul>
                    <p>Am Ende haben Sie eine Tabelle. Nach ihr entscheidet sich, welche Seiten die Website bekommt, wie sie heißen und worüber die Artikel sind. Deshalb lohnt sich die Recherche vor dem Bau einer neuen Website, nicht erst danach.</p>
                    <p>Das ganze Vorgehen zeige ich an einem Beispiel, einem Hersteller von Küchen nach Maß.</p>
                    <h2>1. Sammeln Sie Suchanfragen</h2>
                    <p>Schreiben Sie alles auf, was ein Kunde suchen könnte. Noch nichts sortieren und nichts streichen.</p>
                    <ul>
                    <li><strong>Ihre Leistungen und Produkte.</strong> So, wie Sie sie nennen, und so, wie Ihre Kunden sie nennen.</li>
                    <li><strong>Fragen, die Sie am Telefon hören.</strong> „Was kostet das?“, „Wie lange dauert das?“, „Passt das auch in eine kleine Wohnung?“</li>
                    <li><strong>Websites der Konkurrenz.</strong> Wie sie ihre Leistungen und die Überschriften ihrer Seiten benennen.</li>
                    <li><strong>Foren und soziale Netzwerke</strong>, in denen Ihre Kunden Fragen stellen. Achten Sie auf das Alter. Eine zehn Jahre alte Diskussion sagt wenig über heute.</li>
                    </ul>
                    <p>Das Ergebnis ist eine einfache Liste, pro Zeile eine Wendung. Dafür reicht jede Tabelle, ich nutze Excel. Wie viele Wendungen es werden, hängt von der Branche ab. Eine breite Branche wie Kosmetik bringt Hunderte, eine Firma mit einem Produkt ein paar Dutzend.</p>
                    <h2>2. Erweitern Sie die Liste mit Werkzeugen und ergänzen Sie das Suchvolumen</h2>
                    <p>Jetzt erweitern Sie die Liste um Wendungen, auf die Sie selbst nicht gekommen sind, und ergänzen zu jeder das <strong>Suchvolumen</strong>. Also wie oft sie im Monat ungefähr gesucht wird.</p>
                    <p><strong>Keyword-Planer von Google.</strong> Er ist Teil von <a href="https://ads.google.com/intl/de_de/home/tools/keyword-planner/" target="_blank" rel="noopener">Google Ads</a> und kostenlos, Sie brauchen nur ein Konto. Aus Ihren Wendungen schlägt er weitere vor und zeigt das Suchvolumen bei Google. Wenn Sie in Google Ads gerade keine Anzeigen schalten, sehen Sie das Suchvolumen nur als Spanne, etwa 100 bis 1.000. Für die erste Orientierung reicht das.</p>
                    <p><strong>Google Trends.</strong> <a href="https://trends.google.com/trends/" target="_blank" rel="noopener">Google Trends</a> ist kostenlos und braucht kein Konto. Es zeigt nicht, wie viele Leute suchen, sondern wie sich das Interesse über die Zeit ändert. Das hilft bei saisonalen Dingen. Suchen die Leute den Sommerurlaub am meisten im April oder schon im Januar? Daran sehen Sie, wann ein Artikel fertig sein muss.</p>
                    <p><strong>Autovervollständigung.</strong> Fangen Sie an, in die Suchmaschine zu tippen, und sie schlägt selbst vor, wie die Leute den Satz beenden. Sie tippen „Küche nach Maß“ und sehen, was die Leute dazuschreiben. Von Hand ist das langsam, aber Sie sortieren dabei gleich aus, was nicht zu Ihnen gehört. Hunderte Vorschläge auf einmal holen kostenpflichtige SEO-Werkzeuge heraus.</p>
                    <p>Legen Sie in der Tabelle eine Spalte für das Suchvolumen an. Finden Sie für manche Wendungen kein Suchvolumen, behalten Sie die, die für Sie Sinn ergeben, und markieren Sie sie nur.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Schreiben Sie die Website nach dem, was Ihre Kunden in die Suchmaschine tippen, nicht danach, wie Sie über Ihre Branche sprechen.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>3. Aufräumen und sortieren</h2>
                    <p>Die Liste ist jetzt lang und unordentlich. Streichen Sie zuerst Doppelte, Tippfehler und alles, was nichts mit Ihnen zu tun hat. Ein Hersteller von Küchen nach Maß braucht keine „IKEA Küche Aufbauanleitung“.</p>
                    <p>Dann sortieren Sie die Wendungen danach, <strong>was der Mensch will</strong>:</p>
                    <ul>
                    <li><strong>Informationssuchen.</strong> Er will etwas wissen. „Arbeitsplatte richtig auswählen“. Darauf antworten Artikel.</li>
                    <li><strong>Kaufsuchen.</strong> Er will kaufen oder bestellen. „Küche nach Maß Preis“. Darauf antworten Seiten zu Leistungen und Produkten.</li>
                    <li><strong>Lokale Suchen.</strong> Er sucht jemanden in der Nähe. „Küchenstudio Leipzig“. Darauf antworten die Kontaktseite und das Unternehmensprofil bei Google.</li>
                    </ul>
                    <p>Zum Schluss fassen Sie Wendungen zusammen, die dasselbe bedeuten. „Küche nach Maß Preis“ und „was kostet eine Küche nach Maß“ sind eine Frage. Sie gehören auf eine Seite.</p>
                    <h2>4. Legen Sie Prioritäten fest</h2>
                    <p>Nicht jedes Keyword ist die Arbeit wert. Notieren Sie zu jeder Gruppe:</p>
                    <ul>
                    <li><strong>Suchvolumen.</strong> Wie viele Leute danach suchen.</li>
                    <li><strong>Nähe zu dem, was Sie verkaufen.</strong> Hundert Leute, die genau Ihre Leistung suchen, sind mehr wert als zehntausend, die etwas Ähnliches suchen.</li>
                    <li><strong>Konkurrenz.</strong> Geben Sie die Suchanfrage bei Google ein und schauen Sie, wer auf der ersten Seite steht. Große Portale und Onlineshops überholt man schwer. Schwache oder veraltete Seiten sind Ihre Chance.</li>
                    <li><strong>Saison.</strong> Wann im Jahr die Anfrage am meisten gesucht wird.</li>
                    </ul>
                    <p>Als Priorität reichen hoch, mittel und niedrig. Am meisten lohnen sich Suchanfragen, die direkt mit dem zusammenhängen, was Sie verkaufen, und für die noch niemand eine ordentliche Seite hat.</p>
                    <h2>5. Ordnen Sie die Gruppen Seiten zu</h2>
                    <p>Dieser Schritt ist der wichtigste. Ordnen Sie jeder Gruppe von Suchanfragen eine Seite der Website zu. Entweder eine, die es schon gibt, oder eine, die entstehen wird.</p>
                    <p>Fallen auf eine Gruppe zwei Seiten, machen sie sich gegenseitig Konkurrenz, und keine kommt weit nach oben. Fällt auf eine Gruppe keine, wissen Sie, was auf der Website fehlt. Aus dieser Tabelle setzt sich dann die Struktur der neuen Website zusammen. Sie gehört zu den Dingen, die Sie griffbereit haben sollten, bevor Sie das <a href="/de/blog/vorbereitung-auf-die-neue-website">Briefing für den Entwickler</a> schreiben.</p>
                    <h2>Was Sie danach mit der Recherche machen</h2>
                    <ul>
                    <li><strong>Überarbeiten Sie bestehende Seiten.</strong> Seitentitel, Überschrift und Text nach den Wörtern, die die Leute wirklich benutzen.</li>
                    <li><strong>Ergänzen Sie fehlende Seiten und Artikel.</strong> Zuerst die mit hoher Priorität.</li>
                    <li><strong>Verfolgen Sie die Ergebnisse.</strong> In der Google Search Console und den Bing Webmaster Tools sehen Sie, bei welchen Suchanfragen die Website erscheint und wie viele Leute daraus klicken.</li>
                    <li><strong>Wiederholen Sie die Recherche ab und zu.</strong> Was die Leute suchen, ändert sich, und die Konkurrenz auch.</li>
                    </ul>
                    <p>Keywords sind nur ein Teil von SEO. Was sonst noch entscheidet, ob die Suchmaschine Sie zeigt, erkläre ich im Artikel <a href="/de/blog/was-ist-seo">Was ist SEO und warum ist es wichtig?</a> Mit wem Sie sich auf der ersten Seite messen, beschreibe ich im Artikel über die <a href="/de/blog/wettbewerbsanalyse-website">Wettbewerbsanalyse</a>.</p>
                    <p>Die Keyword-Recherche mache ich auch als Teil meiner SEO-Dienstleistung. Was sie kostet, finden Sie in der <a href="/de/preisliste">Preisliste</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 11 — Erfolgreiche Website erstellen (slug erfolgreiche-website-erstellen)
            // ----------------------------------------------------------------
            11 => [
                'title'       => 'Erfolgreiche Website erstellen: was wirklich zählt',
                'description' => 'Wie Sie eine erfolgreiche Website erstellen: klare Botschaft, Vertrauen, eine Aufforderung, Handy, Suche und Pflege. Zehn Dinge, die entscheiden.',
                'img_preview' => 'jak-vytvorit-uspesnou-webovou-stranku_preview.jpg',
                'img_main'    => 'jak-vytvorit-uspesnou-webovou-stranku.jpg',
                'perex'       => <<<'HTML'
                    <blockquote><p>Eine erfolgreiche Website ist nicht die schönste. Es ist eine Website, auf der man schnell versteht, was Sie machen, Ihnen vertraut und weiß, wie man sich meldet. Ich verspreche Ihnen nicht, wie viele Aufträge eine Website bringt. Aber ich habe zehn Dinge aufgeschrieben, die auf einer Website funktionieren müssen, damit sie überhaupt eine Chance hat.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Klären Sie zuerst, was die Website tun soll</h2>
                    <p>Den Erfolg einer Website kann man nicht messen, solange Sie nicht wissen, was Sie von ihr wollen. Soll sie Anfragen bringen? Verkaufen? Ihnen Telefonate sparen, weil die Leute die Antworten selbst lesen? Oder reicht es, dass Sie bei der Auswahl eines Anbieters niemand aussortiert? Jedes dieser Ziele führt zu einer anderen Website. Gemeinsam ist ihnen eines: Die Website soll Besucher zu Kunden machen.</p>
                    <p>Genauso wichtig ist, für wen die Website ist. Diese beiden Fragen stelle ich jedem Kunden als Erstes.</p>
                    <h2>1. In zehn Sekunden muss klar sein, was Sie machen</h2>
                    <p>Wenn jemand zum ersten Mal auf Ihre Website kommt, entscheidet er in wenigen Sekunden, ob er bleibt. Die Überschrift „Willkommen auf der Website der Firma XY“ sagt ihm nichts. Die Überschrift „Wir bauen Holzhäuser im Allgäu, schlüsselfertig in acht Monaten“ sagt ihm, ob er richtig ist.</p>
                    <p>Ein konkreter Satz ist immer besser als ein allgemeiner.</p>
                    <h2>2. Schreiben Sie mit den Worten Ihrer Kunden</h2>
                    <p>Eine Website in Fachsprache versteht der Kunde nicht, und die Suchmaschine zeigt sie nicht. Die Firma schreibt „ganzheitliche Heizungslösungen“, der Kunde sucht „Heizung tauschen“. Wie Sie herausfinden, mit welchen Wörtern die Leute Ihre Branche suchen, beschreibe ich im Artikel <a href="/de/blog/keyword-recherche-schritt-fuer-schritt">Keyword-Recherche Schritt für Schritt</a>.</p>
                    <p>Schreiben Sie kurz und sachlich. Ein Absatz mit drei Sätzen, eine Überschrift, die sagt, was darunter steht. Auf Websites lesen die Leute nicht, sie überfliegen. Sie bleiben bei dem hängen, was sie interessiert.</p>
                    <h2>3. Eine Hauptaufforderung pro Seite</h2>
                    <p>Jede Seite soll eine Hauptsache haben, die Sie vom Besucher wollen. Anrufen, ein Formular ausfüllen, bestellen. Bieten Sie ihm vier gleich wichtige Schaltflächen an, wählt er oft keine.</p>
                    <p>Halten Sie Formulare so kurz wie möglich. Name, Kontakt und Nachricht reichen meistens. Jedes zusätzliche Feld ist ein Grund, es nicht auszufüllen. Bei einem Onlineshop gilt das für die Kasse doppelt.</p>
                    <h2>4. Vertrauen, bevor es ums Geld geht</h2>
                    <p>Wer Sie nicht kennt, braucht einen Grund, Ihnen zu vertrauen. Am meisten hilft:</p>
                    <ul>
                    <li><strong>Referenzen mit Namen und Firma</strong>, nicht ein anonymer „zufriedener Kunde“,</li>
                    <li><strong>Arbeitsbeispiele</strong> mit einer Beschreibung, was Sie gelöst haben und wie,</li>
                    <li><strong>Ihr Gesicht und Ihr Name</strong>, damit klar ist, mit wem man es zu tun hat,</li>
                    <li><strong>Bewertungen außerhalb Ihrer Website</strong>, bei Google oder auf Bewertungsportalen Ihrer Branche.</li>
                    </ul>
                    <p>Allgemeine Sätze wie „wir sind zuverlässig und professionell“ überzeugen niemanden. Die schreibt jeder.</p>
                    <h2>5. Design, das den Inhalt trägt</h2>
                    <p>Design hat zwei Aufgaben. Einen guten ersten Eindruck machen und das Lesen leicht machen. Eine lesbare Schrift, genug Kontrast, genug Raum um den Text, gute eigene Fotos statt Bildern aus der Bilddatenbank. Und überall derselbe Stil, damit die Website wie aus einem Guss wirkt.</p>
                    <p>Ob Design oder Inhalt wichtiger ist, habe ich in einem eigenen Artikel beantwortet: <a href="/de/blog/design-oder-inhalt">Design oder Inhalt?</a></p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Eine erfolgreiche Website ist nicht die schönste, sondern die, auf der man schnell versteht, was Sie machen, und weiß, wie man sich bei Ihnen meldet.</p></blockquote>
                    HTML,
                'img_mid'     => null,
                'img_mid_alt' => null,
                'content_2'   => <<<'HTML'
                    <h2>6. Eine Navigation, in der sich niemand verirrt</h2>
                    <ul>
                    <li><strong>Wenige Menüpunkte</strong>, so benannt, wie der Kunde sie versteht. „Preise“, nicht „Investition“.</li>
                    <li><strong>Kontakt von jeder Seite aus.</strong> Niemand soll ihn suchen müssen.</li>
                    <li><strong>Überall dieselbe Bedienung.</strong> Menü und Schaltflächen auf jeder Seite an derselben Stelle.</li>
                    <li><strong>Eine Rückmeldung auf jede Aktion.</strong> Nach dem Absenden eines Formulars muss klar sein, dass es angekommen ist und was jetzt passiert.</li>
                    </ul>
                    <p>Eine Suche auf der Website brauchen nur große Websites und Onlineshops. Eine kleinere Website soll so übersichtlich sein, dass sie keine braucht.</p>
                    <h2>7. Handy und Geschwindigkeit</h2>
                    <p>Etwa die Hälfte der Leute schaut heute vom Handy auf Websites. Es reicht nicht, dass die Website dort angezeigt wird. Schaltflächen müssen sich mit dem Daumen treffen lassen, Formulare müssen sich ohne Vergrößern ausfüllen lassen, und die Seite muss auch über mobile Daten schnell laden. Wie Ihre Website dasteht, zeigt kostenlos <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a>.</p>
                    <h2>8. Damit man Sie findet</h2>
                    <p>Eine Website, die niemand findet, kann nicht erfolgreich sein. Die Grundlage ist eine technisch saubere Website, eine Seite pro Thema und Texte, die beantworten, was die Leute suchen. Was alles dazugehört, erkläre ich im Artikel <a href="/de/blog/was-ist-seo">Was ist SEO und warum ist es wichtig?</a></p>
                    <h2>9. Sicherheit</h2>
                    <ul>
                    <li><strong>Verschlüsselte Verbindung über <code>https</code>.</strong> Ohne sie markiert der Browser die Website als nicht sicher.</li>
                    <li><strong>Updates.</strong> Websites aus fertigen Systemen und fremden Plug-ins müssen regelmäßig aktualisiert werden, sonst werden sie zum leichten Ziel. Je weniger fremde Plug-ins, desto weniger Sorgen.</li>
                    <li><strong>Starke Passwörter</strong> und Zugang zur Verwaltung nur für die, die ihn wirklich brauchen.</li>
                    <li><strong>Backups.</strong> Wenn etwas schiefgeht, muss sich die Website schnell wiederherstellen lassen.</li>
                    </ul>
                    <h2>10. Lassen Sie die Website nach dem Start nicht allein</h2>
                    <p>Mit dem Start ist die Arbeit nicht vorbei. Messen Sie, woher die Leute kommen, auf welchen Seiten sie gehen und wie viele sich melden. Dafür reicht Google Analytics oder ein einfacheres Werkzeug wie Plausible. Ohne Daten verbessern Sie die Website im Blindflug.</p>
                    <p>Und halten Sie die Inhalte aktuell. Alte Preise, eingestellte Leistungen oder die letzte Neuigkeit von vor drei Jahren wirken, als gäbe es die Firma nicht mehr. Wann Pflege nicht mehr reicht und es Zeit für einen Relaunch ist, schreibe ich im Artikel über den <a href="/de/blog/wann-website-relaunch-sinn-ergibt">Website-Relaunch</a>.</p>
                    <h2>Wo Sie anfangen</h2>
                    <p>Die meisten Punkte dieser Liste kann ein Entwickler nicht allein lösen. Er muss von Ihnen wissen, was die Website tun soll, für wen sie ist und was die Leute über Sie nicht wissen. Die Fragen, auf die Sie vorher eine Antwort haben sollten, habe ich im Artikel <a href="/de/blog/vorbereitung-auf-die-neue-website">Neun Fragen, die Sie vor dem Website-Projekt klären</a> aufgeschrieben.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

            // ----------------------------------------------------------------
            // 12 — Wettbewerbsanalyse (slug wettbewerbsanalyse-website)
            // ----------------------------------------------------------------
            12 => [
                'title'       => 'Wettbewerbsanalyse für die Website: so gehen Sie vor',
                'description' => 'Wettbewerbsanalyse für Ihre Website: wen Sie beobachten, was Sie auf deren Websites notieren und womit sich Ihre neue Website abheben soll.',
                'img_preview' => null,
                'img_main'    => null,
                'perex'       => <<<'HTML'
                    <blockquote><p>Ein Kunde sieht Sie im Internet nicht allein. Er sieht Sie neben drei anderen Firmen, die dasselbe machen, und wählt zwischen Ihnen. Die Wettbewerbsanalyse ist ein gründlicher Blick auf diese drei Firmen, bevor Sie eine neue Website bauen. Ich schreibe, worauf Sie schauen, wie Sie es festhalten und was Sie danach damit machen.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Warum eine Wettbewerbsanalyse</h2>
                    <p>Ohne sie entsteht eine Website nach dem Geschmack des Inhabers und nach dem, was ihm anderswo gefallen hat. Mit ihr entsteht eine Website, die in den Augen des Kunden neben denen besteht, mit denen er Sie vergleicht. Die Analyse zeigt Ihnen:</p>
                    <ul>
                    <li><strong>Was der Kunde in der Branche erwartet.</strong> Wenn alle drei Mitbewerber Preise auf der Website haben, fällt es auf, wenn sie bei Ihnen fehlen.</li>
                    <li><strong>Wo die Lücke ist.</strong> Was niemand ordentlich erklärt, wonach die Leute fragen und nirgends eine Antwort finden.</li>
                    <li><strong>Welche Fehler Sie nicht machen sollten.</strong> Ein Formular, das nicht funktioniert, eine Website, die auf dem Handy unbrauchbar ist, unklar, was die Firma eigentlich macht.</li>
                    <li><strong>Wo Sie in der Suche nach oben kommen und wo nicht.</strong> Wenn große Portale die erste Seite für Ihre Hauptsuchanfrage belegen, brauchen Sie einen anderen Weg als den Kampf um dasselbe Wort.</li>
                    </ul>
                    <p>Die Analyse ist nicht zum Abschreiben da. Wenn Ihre Website aussieht und spricht wie drei andere, hat der Kunde nichts, wonach er wählen kann. Sie suchen, womit Sie sich abheben.</p>
                    <h2>Wen Sie beobachten</h2>
                    <p>Drei bis fünf Firmen reichen. Wählen Sie aus zwei Quellen:</p>
                    <ul>
                    <li><strong>Wer in der Suchmaschine steht.</strong> Geben Sie bei Google die Suchanfragen ein, unter denen Sie gefunden werden wollen. Zum Beispiel „Küche nach Maß Leipzig“. Wer auf der ersten Seite steht, ist Ihre Konkurrenz im Internet, auch wenn Sie von ihm vielleicht nichts wissen.</li>
                    <li><strong>Mit wem Ihre Kunden Sie vergleichen.</strong> Wen erwähnen sie, wenn sie sich entscheiden? An wen haben Sie zuletzt einen Auftrag verloren? Das ist die Konkurrenz im Geschäft.</li>
                    </ul>
                    <p>Beide Gruppen unterscheiden sich oft. Beide sind wichtig.</p>
                    <h2>Was Sie auf deren Websites notieren</h2>
                    <p><strong>Das Angebot und wie sie es beschreiben.</strong> Was genau sie anbieten, mit welchen Worten, und ob Sie auf der Startseite in zehn Sekunden verstehen, was sie machen und für wen.</p>
                    <p><strong>Was sie dem Kunden sagen und was nicht.</strong> Preis oder zumindest eine Preisspanne, Ablauf der Zusammenarbeit, Termine, Garantien. Was bei ihnen fehlt, ist die Stelle, an der Sie besser sein können.</p>
                    <p><strong>Vertrauen.</strong> Referenzen mit Namen, Arbeitsbeispiele, Fotos von Menschen, Bewertungen. Sind sie konkret oder allgemein?</p>
                    <p><strong>Struktur der Website.</strong> Welche Seiten sie haben, wie die Menüpunkte heißen und wie viele Klicks es dauert, bis Sie den Kontakt finden.</p>
                    <p><strong>Handlungsaufforderung.</strong> Was will die Website von Ihnen? Anrufen, ein Formular ausfüllen, eine Preisliste herunterladen? Und wie einfach ist das?</p>
                    <p><strong>Handy und Geschwindigkeit.</strong> Gehen Sie ihre Websites auf dem Handy durch. Die Geschwindigkeit misst kostenlos <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener">PageSpeed Insights</a>.</p>
                    <p><strong>Wofür sie in der Suche erscheinen.</strong> Bei welchen Suchanfragen sie zu sehen sind und welche Seiten und Artikel sie dafür haben. Genaue Zahlen liefern kostenpflichtige Werkzeuge. Für den Anfang reicht es, die Suchanfragen aus Ihrer <a href="/de/blog/keyword-recherche-schritt-fuer-schritt">Keyword-Recherche</a> von Hand auszuprobieren.</p>
                    <p><strong>Bewertungen außerhalb ihrer Website.</strong> Auf der eigenen Website sucht sich jeder die besten aus. Interessanter ist, was die Leute bei Google, auf Bewertungsportalen oder in sozialen Netzwerken über sie schreiben. Was sie loben und worüber sie sich beschweren.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Eine Wettbewerbsanalyse sucht nicht, was man abschreiben kann, sondern womit man sich abhebt.</p></blockquote>
                    HTML,
                'img_mid'     => 'analyza_konkurence.webp',
                'img_mid_alt' => 'Diagramme und Tabellen auf einem Tisch ausgebreitet',
                'content_2'   => <<<'HTML'
                    <h2>Wie Sie die Ergebnisse festhalten</h2>
                    <p>Am besten funktioniert eine einzige Tabelle. Mitbewerber in die Spalten, die beobachteten Punkte in die Zeilen. Dazu am Ende drei kurze Listen:</p>
                    <ul>
                    <li><strong>Was alle haben.</strong> Das ist der Standard der Branche. Darauf können auch Sie nicht verzichten.</li>
                    <li><strong>Was jemand gut macht.</strong> Inspiration, keine Vorlage.</li>
                    <li><strong>Was niemand hat.</strong> Hier liegt Ihre Chance.</li>
                    </ul>
                    <p>Mehr brauchen Sie nicht. Es geht nicht um einen Bericht mit hundert Seiten, sondern um eine Seite, nach der man entscheiden kann.</p>
                    <h2>Wie Sie die Analyse beim Entwurf der Website nutzen</h2>
                    <ul>
                    <li><strong>Inhalt.</strong> Beantworten Sie die Fragen, die die Konkurrenz nicht beantwortet. Preise, Ablauf der Zusammenarbeit, häufige Fragen.</li>
                    <li><strong>Struktur.</strong> Die Seiten, die der Kunde in der Branche erwartet, brauchen Sie auch. Und dazu die, mit denen Sie sich abheben.</li>
                    <li><strong>Botschaft.</strong> Wenn alle „Qualität und Zuverlässigkeit“ schreiben, schreiben Sie etwas, das der Kunde nachprüfen kann.</li>
                    <li><strong>Suche.</strong> Suchanfragen, bei denen die Konkurrenz schwache oder veraltete Seiten hat, sind die, bei denen Sie eine Chance haben. Warum das wichtig ist, erkläre ich im Artikel <a href="/de/blog/was-ist-seo">Was ist SEO und warum ist es wichtig?</a></li>
                    <li><strong>Funktionen.</strong> Rechner, Terminbuchung, Dokumente zum Herunterladen. Wenn die Konkurrenz sie nicht hat und sie dem Kunden helfen würden, haben Sie einen Vorsprung.</li>
                    </ul>
                    <p>Legen Sie die fertige Analyse Ihrem Briefing bei. Dem Entwickler erspart sie viele Fragen, und Sie bekommen ein genaueres Angebot. Was sonst noch ins Briefing gehört, finden Sie im Artikel <a href="/de/blog/vorbereitung-auf-die-neue-website">Neun Fragen, die Sie vor dem Website-Projekt klären</a>.</p>
                    <p>Genauso lohnt sich der Blick auf die Konkurrenz vor einem Relaunch. Wenn die Websites Ihrer Mitbewerber eine Klasse besser aussehen, ist das einer der guten Gründe für einen Relaunch. Wann er Sinn ergibt und wann nicht, beschreibe ich im Artikel über den <a href="/de/blog/wann-website-relaunch-sinn-ergibt">Website-Relaunch</a>.</p>
                    <h2>Wie oft Sie die Analyse wiederholen</h2>
                    <p>Der Markt ändert sich. Neue Mitbewerber kommen, alte machen ihre Website neu oder fangen an, Artikel zu schreiben. Es reicht, einmal im Jahr wieder hinzuschauen und mit der Tabelle vom letzten Mal zu vergleichen.</p>
                    <p>Und wenn Sie gerade erst einen Firmennamen oder eine Domain wählen, machen Sie schon jetzt eine kurze Version der Analyse. Sie wollen nicht, dass man Sie mit jemandem verwechselt, der dasselbe macht. Mehr dazu im Artikel über die <a href="/de/blog/domainnamen-finden">Wahl des Domainnamens</a>.</p>
                    HTML,
                'img_end'     => null,
                'img_end_alt' => null,
                'bonus'       => null,
                'extra'       => null,
            ],

        ];
    }
}
