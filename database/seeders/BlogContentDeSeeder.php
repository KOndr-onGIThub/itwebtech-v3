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
 * (Standard 2.200 € · Custom ab 3.800 € · Starter 1.000 €), ne přepočet korun
 * z CS verze. Když se čísla v `lang/de/price.php` změní, je potřeba srovnat
 * i tenhle text.
 *
 * EN překlady jsou pořád ze starého importu a s přepsanou CS verzí se nekryjí
 * — mimo rozsah tohoto issue.
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
    private const DE_SLUGS = [
        3   => 'was-kostet-eine-website',
        4   => 'vorbereitung-auf-die-neue-website',
        6   => 'wann-sich-eine-eigene-anwendung-lohnt',
        10  => 'braucht-ihre-firma-eine-website',
        13  => 'wann-website-relaunch-sinn-ergibt',
    ];

    /**
     * Obrázková pole se nepřekládají — přebírají se z CS řádku, aby DE karta
     * ve výpisu a hero v detailu nebyly prázdné (`pages/blog.blade.php`
     * podmiňuje obrázek na `img_preview`, `pages/article.blade.php` na
     * `img_main`).
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
     * DE texty z dokumentu `de-translations` (OND-218).
     *
     * @return array<int, array<string, string>>
     */
    private function articles(): array
    {
        return [

            // ----------------------------------------------------------------
            // 3 — Kolik stojí web → Was kostet eine Website
            // cs kolik-stoji-webove-stranky → de was-kostet-eine-website
            // ----------------------------------------------------------------
            3 => [
                'title'       => 'Was eine Website kostet und woraus sich der Preis ergibt',
                'description' => 'In welchen Preisstufen ich Websites baue, was darin enthalten ist und was den Preis nach oben treibt. Damit Sie vorher wissen, ob wir zusammenpassen.',
                'perex'       => <<<'HTML'
                    <blockquote><p>Der Preis ist die erste Frage, die mir Leute stellen, und das ist richtig so. Eine einzige Zahl kann Ihnen aber niemand seriös nennen. Eine Website für 1.000 € und eine für 8.000 € sind zwei verschiedene Dinge. Deshalb schreibe ich offen, in welchen Stufen ich arbeite, was darin steckt und was den Preis nach oben schiebt.</p></blockquote>
                    HTML,
                'content_1'   => <<<'HTML'
                    <h2>Warum ich keine einzige Zahl habe</h2>
                    <p>Eine Website ist keine Ware aus dem Regal. Wenn Sie mir schreiben, dass Sie eine Website wollen, weiß ich bis dahin nur, dass Sie eine Website wollen. Ich weiß nicht, wie viele Seiten sie haben soll. Ich weiß nicht, ob Sie die Inhalte selbst pflegen wollen. Ich weiß nicht, ob Sie darüber verkaufen müssen oder ob sie auch auf Englisch funktionieren soll. Jede dieser Fragen bewegt den Preis.</p>
                    <p>Ich mache es so: Zuerst gehen wir durch, was Sie brauchen. Dann schreibe ich Ihnen eine Spezifikation, in der schwarz auf weiß steht, was ich baue und zu welchem Preis. Dieser Preis gilt. Die Rechnung am Ende entspricht der Spezifikation vom Anfang. Wenn Sie unterwegs merken, dass Sie etwas zusätzlich wollen, nenne ich Ihnen den Preis vorher und Sie entscheiden.</p>
                    <h2>Wofür Sie eigentlich bezahlen</h2>
                    <p>Sie bezahlen meine Zeit und das, was ich damit anzufangen weiß. Sie kaufen keine Lizenz für ein Template und keine Stunden eines Vertrieblers, der Ihnen die Website verkauft hat und dann verschwunden ist. Ich arbeite allein, im Preis stecken also kein Agentur-Overhead und kein Koordinator, der mir Ihre E-Mails weiterleitet.</p>
                    <p>Websites schreibe ich mit eigenem Code. Ich baue sie nicht aus Baukästen und fremden Plug-ins zusammen, die ständig aktualisiert werden müssen und irgendwann kaputtgehen. Das ist am Anfang teurer und mit der Zeit günstiger, weil Sie nichts zu reparieren haben.</p>
                    <h2>Drei Stufen, in denen ich arbeite</h2>
                    <p><strong>Standard — 2.200 €.</strong> Eine Website nach Maß bis zwölf Seiten. Mit einer einfachen Inhaltsverwaltung, sodass Sie Texte, Fotos oder Referenzen selbst ändern. Eine weitere Sprachversion ist möglich. Das bestellen die meisten Firmen.</p>
                    <p><strong>Custom — ab 3.800 €.</strong> Onlineshop, Reservierungssystem oder eine Anwendung nach Maß. Der Umfang steht nicht vorher fest, der Preis ergibt sich daraus, was die Website können muss und an welche Systeme sie angebunden wird.</p>
                    <p><strong>Starter — 1.000 €.</strong> Die Ausnahme, nicht der normale Einstieg. Eine Präsentation bis fünf Seiten für Selbstständige, bei denen ein größerer Umfang keinen Sinn ergibt.</p>
                    <p>Ich bin kein Umsatzsteuerpflichtiger. Der Preis, den ich Ihnen nenne, ist endgültig. Was genau in den einzelnen Stufen steckt, steht aufgeschlüsselt in der <a href="/de/preisliste">Preisliste</a>.</p>
                    HTML,
                'content_mid' => <<<'HTML'
                    <blockquote><p>Sie kennen den Preis, bevor ich anfange zu arbeiten. Nicht erst auf der Rechnung.</p></blockquote>
                    HTML,
                'content_2'   => <<<'HTML'
                    <h2>Was den Preis nach oben treibt</h2>
                    <ul>
                    <li><strong>Anbindung an ein System, das Sie in der Firma schon nutzen.</strong> Lager, Buchhaltung, Reservierungen. Je mehr sich zwei Systeme verstehen müssen, desto mehr Arbeit ist es.</li>
                    <li><strong>Weitere Sprachen.</strong> Das ist nicht nur eine Textübersetzung. Es ist eine weitere Version der ganzen Website, die jemand pflegen muss.</li>
                    <li><strong>Inhalte, die es noch nicht gibt.</strong> Wenn Sie weder Fotos noch Texte haben, müssen sie erst entstehen. Wir klären vorher, was Sie beisteuern und was ich — damit es auf der Rechnung keine Überraschung gibt.</li>
                    <li><strong>Ein Umfang, der unterwegs wächst.</strong> Deshalb schreibe ich die Spezifikation. Damit wir beide wissen, wo die Grenze liegt.</li>
                    </ul>
                    <h2>Warum ich nicht der Günstigste bin</h2>
                    <p>Weil ich es nicht sein will. Eine Website aus dem Template für ein paar hundert Euro ergibt Sinn, wenn Sie nur eine Visitenkarte im Internet brauchen. Zu dem Preis können Sie sie ruhig haben, das sage ich Ihnen geradeheraus und werde Sie nicht umstimmen.</p>
                    <p>Ich baue Websites für Firmen, die ihre Website im Geschäft tatsächlich benutzen und sie ordentlich haben wollen. Für den Unterschied bekommen Sie eine Lösung, die darauf zugeschnitten ist, wie Ihre Firma arbeitet, und Code, der Ihnen gehört. Er ist weder bei mir eingesperrt noch bei einer Plattform, von der Sie nicht mehr wegkämen.</p>
                    <h2>Wann Sie keine Website bei mir kaufen sollten</h2>
                    <p>Wenn Ihr Budget unter 800 € liegt. Wenn Sie die Website in einer Woche brauchen. Wenn Sie nur ein bestehendes WordPress reparieren wollen. Nichts davon mache ich, und es ist besser, Sie wissen es jetzt als nach zwei Terminen.</p>
                    <h2>Wie Sie zum genauen Preis kommen</h2>
                    <p>Schreiben Sie mir, was Sie brauchen. Ruhig kurz. Ich melde mich innerhalb von zwei Werktagen und wir gehen es durch. Wenn dabei herauskommt, dass ich Ihnen helfen kann, bekommen Sie eine Spezifikation mit einem konkreten Preis. Wenn nicht, sage ich es Ihnen und dränge Ihnen nichts auf.</p>
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
                    <p>Eine Hauptsache pro Seite. Anrufen, das Formular ausfüllen, die Preisliste herunterladen, bestellen. Wenn eine Website fünf Dinge gleichzeitig tun soll, macht sie keines davon richtig.</p>
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
                    <p>Logo, Fotos, Texte, Zugänge zu Domain und Hosting, einen Shop mit Produkten in irgendeinem System. Je mehr davon vorhanden ist, desto weniger muss erst hergestellt werden.</p>
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
                    <p>Versuchen Sie, eine Frage zu beantworten. Wenn morgen ein Mensch auf Ihre Website käme, der noch nie von Ihnen gehört hat — würde er in zehn Sekunden verstehen, was Sie machen und ob es etwas für ihn ist? Wenn nicht, liegt dort das Problem, und es spielt keine Rolle, ob Sie eine Website haben oder nicht.</p>
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
                    <p><strong>5. Die Websites Ihrer Mitbewerber sehen eine Klasse besser aus.</strong> Der Kunde vergleicht Sie nebeneinander, ob Sie wollen oder nicht.</p>
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
                    <p><strong>Die Seitenadressen werden weggeworfen.</strong> Die neue Website hat eine andere Struktur und die alten Adressen funktionieren nicht mehr. Suchmaschinen und Links von fremden Websites führen plötzlich ins Leere. Die Lösung ist einfach und wird vor dem Start gemacht: Die alte Adresse muss dauerhaft auf die neue weiterleiten. Ich möchte, dass Sie das von jedem einfordern, der Ihnen die Website baut.</p>
                    <p><strong>Es wird nur das Aussehen erneuert.</strong> Die Texte werden eins zu eins übernommen, auch die, die niemand verstanden hat. Die Website sieht dann neu aus und funktioniert genauso schlecht.</p>
                    <p><strong>Dinge verschwinden, die funktioniert haben.</strong> Manchmal hat die alte Website eine Seite, auf die die Hälfte des Traffics kommt. Bevor etwas gelöscht wird, muss man in die Statistik schauen.</p>
                    <p><strong>Niemand übernimmt die Inhalte.</strong> Referenzen, Fotos von Umsetzungen, Dokumente zum Herunterladen. Es ist meistens mehr, als man erwartet.</p>
                    <h2>Wie ich an einen Relaunch herangehe</h2>
                    <p>Zuerst schaue ich, was auf der alten Website funktioniert, und das behalte ich. Dann gehen wir durch, was die Website tun soll und wem sie es sagen soll. Erst danach geht es darum, wie sie aussehen wird. Die Seitenadressen regle ich vor dem Start, nicht danach.</p>
                    <p>Den Code schreibe ich selbst, ohne fertige Plug-ins fremder Autoren. Genau die sind meistens der Grund, warum eine Website nach einiger Zeit auseinanderfällt und neu gemacht werden muss.</p>
                    HTML,
            ],

        ];
    }
}
