@props([
    'sectors' => [],
    'counts'  => [],
    'target'  => 'portfolio-grid',
])

{{-- ============================================================
     OND-470 — filtr jako věta podle záměru návštěvníka:
     „Potřebuju [web] pro [výrobu a logistiku].“ Nahradil záložky
     Vše / Weby / Aplikace / Ostatní (vítězná varianta V1 z OND-471).

     Dvě nativní `<select>` uvnitř věty: klávesnice, čtečka i výběr
     na mobilu fungují samy od sebe. Viditelné slovo je zrcadlo
     vybrané volby; select leží průhledně přes něj.

     Nic se neschovává. Shody se přesunou nahoru (FLIP, jednorázově
     při změně, žádný výpočet na snímek), zbytek zůstane pod
     předělem „Další projekty“ ztlumený a klikací. Pořadí se mění
     v DOM, ne přes CSS `order` — pořadí tabulátoru sedí s očima.
     Ovládání je v resources/js/projekty.js.

     Bez JS je věta skrytá (CSS pod `html.js`, žádný posun po načtení)
     a mřížka je celá v původním pořadí. Stav se píše do URL
     (`?co=&pro=`), takže návrat z detailu i sdílený odkaz ukáže
     stejný výběr. Mřížka (`#{{ $target }}`) patří stránce, karty
     nesou `data-category` a `data-sectors`.
     ============================================================ --}}
@php
    $whatKeys = array_values(array_filter(
        ['all', 'website', 'application', 'other'],
        fn ($k) => $k === 'all' || ($counts[$k] ?? 0) > 0
    ));
    $forKeys = array_merge(['all'], array_keys($sectors));
    $total = $counts['all'] ?? 0;
    $i18n = [
        'count' => __('projects.catalog.count'),
        'match' => __('projects.catalog.sentence.match'),
        'none'  => __('projects.catalog.sentence.none'),
    ];
    // OND-478 — prototyp nápovědy pro první návštěvu: ?napoveda=a|b|c.
    // Bez parametru zůstává dnešní ukázka věty (pro srovnání).
    $hint = in_array(request()->query('napoveda'), ['a', 'b', 'c'], true) ? request()->query('napoveda') : 'demo';
@endphp

<form class="pd-intent" data-intent
      aria-label="{{ __('projects.catalog.sentence.aria') }}"
      data-total="{{ $total }}"
      data-target="{{ $target }}"
      data-hint="{{ $hint }}"
      data-i18n="{{ json_encode($i18n, JSON_UNESCAPED_UNICODE) }}">
    <p class="pd-intent__sentence">
        <span>{{ __('projects.catalog.sentence.lead') }}</span>
        <span class="pd-intent__slot">
            <label class="sr-only" for="intent-co">{{ __('projects.catalog.sentence.what_label') }}</label>
            <select id="intent-co" name="co" class="pd-intent__select" aria-controls="{{ $target }}">
                @foreach ($whatKeys as $key)
                    <option value="{{ $key }}">{{ __('projects.catalog.sentence.what.' . $key) }}</option>
                @endforeach
            </select>
            <span class="pd-intent__value" aria-hidden="true">{{ __('projects.catalog.sentence.what.all') }}</span>
        </span>
        <span>{{ __('projects.catalog.sentence.joiner') }}</span>
        <span class="pd-intent__slot">
            <label class="sr-only" for="intent-pro">{{ __('projects.catalog.sentence.for_label') }}</label>
            <select id="intent-pro" name="pro" class="pd-intent__select" aria-controls="{{ $target }}">
                @foreach ($forKeys as $key)
                    <option value="{{ $key }}">{{ __('projects.catalog.sentence.for.' . $key) }}</option>
                @endforeach
            </select>
            <span class="pd-intent__value" aria-hidden="true">{{ __('projects.catalog.sentence.for.all') }}</span>{{-- Tečka uvnitř slotu: za inline-blokem by se u dlouhé volby (de, mobil) zalomila na samostatný řádek. --}}<span class="pd-intent__dot">.</span>
        </span>
    </p>
    <p class="pd-intent__status">
        <span role="status" data-intent-status>{{ str_replace(':n', $total, $i18n['count']['other']) }}</span>
        <a href="{{ lroute('contact') }}" class="pd-case__live" data-intent-cta hidden>{{ __('projects.catalog.cta') }} &rarr;</a>
        @if ($hint === 'b')
            {{-- B: tichý řádek pod větou. Čtečka ho nečte — selecty mají vlastní popisky. --}}
            <span class="pd-intent__line" data-intent-hint aria-hidden="true">{{ __('projects.catalog.sentence.hint.line') }}</span>
        @endif
    </p>
    @if ($hint === 'a')
        {{-- A: bublina nad prvním slovem. Absolutně, nic neodsune. --}}
        <span class="pd-intent__bubble" data-intent-hint aria-hidden="true" hidden>{{ __('projects.catalog.sentence.hint.bubble') }}</span>
    @elseif ($hint === 'c')
        {{-- C: ruka ukáže klepnutí na první slovo a zůstane u něj. --}}
        <span class="pd-intent__hand" data-intent-hint aria-hidden="true" hidden>
            <svg viewBox="0 0 24 24" width="40" height="40"><path d="M9 11.5V4.2a1.7 1.7 0 0 1 3.4 0v6.3l.1-1.2a1.6 1.6 0 0 1 3.2.2v1.3a1.6 1.6 0 0 1 3.2.3v1.1a1.5 1.5 0 0 1 3 .3v4.3c0 3.4-2.6 6.2-6 6.2h-1.6a6 6 0 0 1-4.6-2.2l-3.6-4.4a1.6 1.6 0 0 1 2.4-2.1z"/></svg>
        </span>
    @endif
</form>
