@props([
    'project',
    'locale' => null,
    'eager'  => false,
    // OND-438: náhled a titulek nesou jméno pro přechod do detailu. V „Další
    // projekty" na detailu vypnuté — jinak by se karty spárovaly s kartami
    // stránky, ze které člověk přišel, a při přechodu odletěly dolů z obrazovky.
    'transition' => true,
])

{{-- ============================================================
     OND-399 — `.pd-work`: jedna realizace v mřížce `.pd-works`.
     Nahrazuje `x-portfolio.card` na /projekty i v „Další projekty"
     na detailu (tam beze změny, jen jiný počet položek).

     Celá karta je jeden odkaz. Žádné „Zobrazit projekt" pod každou
     kartou: 21× stejná výzva je šum, a že se na náhled kliká, ví
     každý (Jakob). Afordanci nese rám náhledu (acid při najetí)
     a podtržení titulku.
     ============================================================ --}}
@php
    $locale ??= app()->getLocale();
    /** @var \App\Models\Portfolio\PortfolioProject $project */
    $t = $project->translation($locale);
    $title = $t?->title ?? $project->client_name ?? $project->slug;

    // Věta pod titulkem: subtitle → fallback zkrácené summary (jako dřív karta).
    $text = $t?->subtitle ?: ($t?->summary ? \Illuminate\Support\Str::limit($t->summary, 110) : null);

    // Náhled: OND-449 (B-06) — tentýž soubor jako lead detailu, CSS z něj ořízne 16:10 shora.
    $hero = portfolio_card_thumbnail($project->screenshots ?? collect());

    $category = __('projects.detail.category_label.' . $project->category);
    if (str_starts_with($category, 'projects.detail.category_label.')) {
        $category = ucfirst($project->category);
    }

    // „Komu": jen značka klienta (před „ — ", „ (" nebo čárkou), bez jména kontaktní
    // osoby — to patří na detail. A jen když ji titulek už neříká (shoda
    // prvního slova, nebo značka stojí v titulku): „Střechy Zajíc · Střechy
    // Zajíc" je dvakrát totéž, „HCMS … · Toyota Kolín" je informace.
    $brand = trim(preg_split('/\s+(?:—|–|\()|,/u', (string) $project->client_name)[0] ?? '');
    $firstWord = fn (string $s) => mb_strtolower(preg_split('/[\s,]+/u', trim($s))[0] ?? '');
    $showBrand = $brand !== ''
        && $firstWord($brand) !== $firstWord($title)
        && ! str_contains(mb_strtolower($title), mb_strtolower($brand));

    $thumbAlt = $hero?->translation()?->alt
        ?: __('projects.card.thumbnail_alt', ['project' => $title]);
@endphp

<article class="pd-work" data-category="{{ $project->category }}" {{ $attributes }}>
    <a href="{{ $project->detailUrl($locale) }}" class="pd-work__link"
       aria-label="{{ $title }} — {{ __('projects.view_project') }}"
       data-analytics="project_card_click"
       data-analytics-props='{"slug":"{{ $project->slug }}"}'>
        <div class="pd-work__visual"@if ($transition) style="view-transition-name: {{ project_transition_name($project->slug, 'img') }}"@endif>
            @if ($hero)
                <x-portfolio.screenshot
                    :path="$hero->path"
                    :alt="$thumbAlt"
                    sizes="(max-width: 639px) 38vw, (max-width: 1023px) 50vw, 400px"
                    loading="{{ $eager ? 'eager' : 'lazy' }}"
                    fetchpriority="{{ $eager ? 'high' : null }}"
                />
            @endif
        </div>
        <p class="pd-work__meta">
            {{ $category }}@if ($project->year) · {{ $project->year }}@endif @if ($showBrand)· {{ $brand }}@endif
        </p>
        <h3 class="pd-work__title"@if ($transition) style="view-transition-name: {{ project_transition_name($project->slug, 'title') }}"@endif>{{ $title }}</h3>
        @if ($text)
            <p class="pd-work__text">{{ $text }}</p>
        @endif
    </a>
</article>
