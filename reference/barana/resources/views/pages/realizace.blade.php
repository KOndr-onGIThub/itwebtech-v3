@extends('layouts.app')

@section('title', 'Realizace — galerie dokončených projektů | BARANA')
@section('description', 'Prohlédněte si naše dokončené projekty — bioklimatické pergoly, hliníkové brány a ploty v Jihomoravském kraji. Fotky, parametry a reference zákazníků.')

@section('content')

{{-- R-01 Intro + filtry --}}
<section class="section-wrapper--sm bg-warm-white border-b border-gray-100">
    <div class="container-site">
        <div class="flex flex-col lg:flex-row lg:items-end justify-between gap-6" data-reveal>
            <div>
                <span class="section-eyebrow">Naše práce</span>
                <h1 class="section-title">Realizace</h1>
                <p class="section-sub max-w-xl">Každý projekt je jiný. Prohlédněte si, co jsme postavili — a nechte se inspirovat pro svůj vlastní.</p>
            </div>
        </div>
    </div>
</section>

{{-- R-02 Grid realizací s filtrem --}}
<section class="section-wrapper bg-warm-white"
    x-data="{
        filter: 'vse',
        openProject: null,
        projects: @js($projects),
    }"
    x-effect="if (openProject) $nextTick(() => window.dispatchEvent(new CustomEvent('glightbox:refresh')))"
    @keydown.escape.window="openProject = null"
>

    {{-- Filter bar --}}
    <div class="container-site mb-10" data-reveal>
        <div class="flex flex-wrap gap-2" role="group" aria-label="Filtrovat realizace">
            @foreach (['vse' => 'Všechny projekty', 'pergoly' => 'Pergoly', 'brany-ploty' => 'Brány a ploty', 'kombinace' => 'Kombinace'] as $key => $label)
                <button
                    @click="filter = '{{ $key }}'"
                    :class="filter === '{{ $key }}'
                        ? 'bg-sage text-white shadow-sm'
                        : 'bg-white text-body border border-gray-200 hover:border-sage hover:text-sage'"
                    class="px-5 py-2 rounded-full text-sm font-semibold transition-all duration-200"
                    :aria-pressed="filter === '{{ $key }}'"
                >
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Project grid --}}
    <div class="container-site">
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ($projects as $project)
                <div
                    x-show="filter === 'vse' || filter === '{{ $project['category'] }}'"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 scale-95"
                    x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="project-card"
                    @click="openProject = projects.find(p => p.id === {{ $project['id'] }})"
                    role="button"
                    tabindex="0"
                    @keydown.enter="openProject = projects.find(p => p.id === {{ $project['id'] }})"
                    :aria-label="'Otevřít projekt: {{ $project['title'] }}'"
                >
                    <div class="overflow-hidden">
                        <x-responsive-image
                            path="{{ $project['images'][0] }}"
                            alt="{{ $project['title'] }}"
                            class-picture="block w-full"
                            class-img="project-card__img"
                            sizes="(min-width: 1024px) 33vw, (min-width: 640px) 50vw, 100vw"
                            loading="lazy"
                        />
                    </div>
                    <div class="project-card__body">
                        <span class="badge badge--sage mb-2">
                            @php
                                $catLabels = ['pergoly' => 'Pergola', 'brany-ploty' => 'Brána / plot', 'kombinace' => 'Kombinace'];
                            @endphp
                            {{ $catLabels[$project['category']] ?? $project['category'] }}
                        </span>
                        <h3 class="project-card__title">{{ $project['title'] }}</h3>
                        <p class="project-card__desc">{{ $project['desc'] }}</p>
                        <span class="inline-flex items-center gap-1.5 text-sage text-sm font-semibold mt-3">
                            Zobrazit detail
                            <x-icon.arrow-right class="w-4 h-4" />
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- R-03 Project modal --}}
    <div
        x-show="openProject !== null"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-anthracite/70 backdrop-blur-sm"
        @click.self="openProject = null"
        role="dialog"
        aria-modal="true"
        x-bind:aria-label="openProject ? 'Detail projektu: ' + openProject.title : ''"
    >
        <div
            x-show="openProject !== null"
            x-transition:enter="transition ease-out duration-250"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-cloak
            class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-y-auto"
        >
            {{-- Modal header --}}
            <div class="sticky top-0 bg-white border-b border-gray-100 flex items-center justify-between px-6 py-4 z-10">
                <h2 class="font-bold text-heading text-lg" x-text="openProject?.title"></h2>
                <button
                    @click="openProject = null"
                    class="p-2 rounded-xl hover:bg-gray-100 transition-colors text-muted hover:text-heading"
                    aria-label="Zavřít"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/>
                    </svg>
                </button>
            </div>

            {{-- Modal body --}}
            <div class="p-6">
                {{-- Fotky --}}
                <template x-if="openProject">
                    <div class="grid grid-cols-2 gap-3 mb-6">
                        <template x-for="(img, i) in openProject.images" :key="i">
                            <a
                                :href="window.sharedImages?.[img]?.at(-1) ?? '/img/' + img"
                                data-glightbox
                                :data-gallery="'modal-' + openProject.id"
                                class="gallery-item rounded-xl overflow-hidden"
                                :class="i === 0 ? 'col-span-2 aspect-video' : 'aspect-4/3'"
                            >
                                <img
                                    :src="window.sharedImages?.[img]?.[5] ?? '/img/' + img"
                                    :alt="openProject.title"
                                    class="gallery-item__img"
                                    loading="lazy"
                                >
                                <div class="gallery-item__overlay" aria-hidden="true">
                                    <div class="gallery-item__icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/><line x1="11" y1="8" x2="11" y2="14"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </template>

                {{-- Popis --}}
                <p class="text-body leading-relaxed mb-6" x-text="openProject?.desc"></p>

                {{-- Parametry --}}
                <template x-if="openProject?.params">
                    <div class="bg-warm-white rounded-xl p-5 mb-6">
                        <h3 class="font-bold text-heading text-sm mb-3">Parametry projektu</h3>
                        <dl class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm">
                            <template x-for="[key, val] in Object.entries(openProject.params)" :key="key">
                                <div class="flex flex-col">
                                    <dt class="text-muted text-xs uppercase tracking-wider" x-text="key"></dt>
                                    <dd class="font-semibold text-heading mt-0.5" x-text="val"></dd>
                                </div>
                            </template>
                        </dl>
                    </div>
                </template>

                {{-- Citace zákazníka --}}
                <template x-if="openProject?.quote">
                    <blockquote class="border-l-4 border-gold pl-5 italic text-body text-sm leading-relaxed mb-6">
                        <p x-text="'„' + openProject.quote + '\u201c'"></p>
                        <footer class="mt-2 text-xs text-muted font-semibold not-italic" x-text="'— ' + openProject.client"></footer>
                    </blockquote>
                </template>

                <a href="{{ route('kontakt') }}" class="btn btn-primary w-full justify-center">
                    Chci podobný projekt
                    <x-icon.arrow-right class="w-4 h-4" />
                </a>
            </div>
        </div>
    </div>

</section>

{{-- R-04 CTA --}}
<x-sections.cta-band
    eyebrow="Váš projekt"
    title="Líbí se vám naše práce?"
    subtitle="Každý projekt začíná nezávaznou poptávkou. Řekněte nám o svém záměru — zbytek zařídíme my."
    btn-label="Poptejte vlastní projekt"
    btn-route="kontakt"
/>

@endsection
