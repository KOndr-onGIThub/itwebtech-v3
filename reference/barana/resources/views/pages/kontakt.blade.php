@extends('layouts.app')

@section('title', 'Kontakt — BARANA s.r.o. | Jihomoravský kraj')
@section('description', 'Kontaktujte BARANA s.r.o. Poptejte bioklimatickou pergolu, bránu nebo plot. Volejte +420 123 456 789 nebo vyplňte formulář — odpovídáme do 24 hodin.')
@section('hide_prefooter') true @endsection

@section('content')

<div class="site-main-inner">

    {{-- Nadpis --}}
    <section class="section-wrapper--sm bg-warm-white border-b border-gray-100">
        <div class="container-site">
            <div class="max-w-2xl" data-reveal>
                <span class="section-eyebrow">Jsme tu pro vás</span>
                <h1 class="section-title">Kontakt</h1>
                <p class="section-sub">Poptávka je vždy zdarma a nezávazná. Odpovídáme do 24 hodin v pracovní dny — obvykle dřív.</p>
            </div>
        </div>
    </section>

    {{-- Hlavní sekce: formulář + přímé kontakty --}}
    <section class="section-wrapper bg-warm-white">
        <div class="container-site">
            <div class="grid lg:grid-cols-3 gap-10 lg:gap-14">

                {{-- K-01 Formulář (2/3) --}}
                <div class="lg:col-span-2" data-reveal="fade-left">
                    <h2 class="text-xl font-bold text-heading mb-6">Napište nám</h2>

                    <form
                        x-data="contactForm"
                        @submit.prevent="submit"
                        action="{{ route('kontakt.submit') }}"
                        method="POST"
                        enctype="multipart/form-data"
                        novalidate
                    >
                        @csrf

                        <div class="grid sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label for="jmeno" class="form-label">Jméno a příjmení <span class="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    id="jmeno"
                                    name="jmeno"
                                    class="form-input"
                                    placeholder="Jana Nováková"
                                    required
                                    autocomplete="name"
                                >
                            </div>
                            <div>
                                <label for="email" class="form-label">E-mail <span class="text-red-500">*</span></label>
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-input"
                                    placeholder="jana@example.cz"
                                    required
                                    autocomplete="email"
                                >
                            </div>
                            <div>
                                <label for="telefon" class="form-label">Telefon <span class="text-red-500">*</span></label>
                                <input
                                    type="tel"
                                    id="telefon"
                                    name="telefon"
                                    class="form-input"
                                    placeholder="+420 777 888 999"
                                    required
                                    autocomplete="tel"
                                >
                            </div>
                            <div>
                                <label for="typ_poptavky" class="form-label">Co vás zajímá</label>
                                <select id="typ_poptavky" name="typ_poptavky" class="form-input">
                                    <option value="">Vyberte…</option>
                                    <option value="Bioklimatická pergola">Bioklimatická pergola</option>
                                    <option value="Hliníková brána">Hliníková brána</option>
                                    <option value="Hliníkový plot">Hliníkový plot</option>
                                    <option value="Kompletní řešení (pergola + brána / plot)">Kompletní řešení (pergola + brána / plot)</option>
                                    <option value="Jiné">Jiné</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="zprava" class="form-label">Vaše zpráva</label>
                            <textarea
                                id="zprava"
                                name="zprava"
                                rows="5"
                                class="form-input resize-none"
                                placeholder="Popište váš záměr — přibližné rozměry, lokalita, co plánujete. Čím víc napíšete, tím lépe vám poradíme."
                            ></textarea>
                        </div>

                        <div class="mb-6">
                            <label class="form-label">Přílohy (volitelné)</label>
                            <x-form.file-drop
                                name="soubory[]"
                                label="Přidat soubory"
                                hint="Fotky místa, situační plán, PDF výkres…"
                                accept=".pdf,.doc,.docx,.jpg,.jpeg,.png,.webp,.zip"
                                drag-text="nebo přetáhněte sem"
                                max-files="Max. 5 souborů"
                                max-size="celkem 20 MB"
                            />
                        </div>

                        <button
                            type="submit"
                            class="btn btn-primary btn-lg w-full sm:w-auto"
                            :disabled="loading"
                            :class="{ 'opacity-70 cursor-not-allowed': loading }"
                        >
                            <span x-show="!loading">Odeslat poptávku</span>
                            <span x-show="loading" x-cloak>Odesílám…</span>
                            <x-icon.arrow-right class="w-5 h-5 shrink-0" />
                        </button>

                        <p class="text-muted text-xs mt-3">
                            Odesláním souhlasíte se zpracováním osobních údajů dle naší
                            <a href="{{ route('ochrana-osobnich-udaju') }}" class="underline hover:text-sage" target="_blank">Ochrany osobních údajů</a>.
                        </p>
                    </form>
                </div>

                {{-- K-02 Přímé kontakty (1/3) --}}
                <div class="space-y-5" data-reveal="fade-right">

                    <h2 class="text-xl font-bold text-heading">Přímý kontakt</h2>

                    <div class="card p-6 border-l-4 border-gold space-y-5">

                        <a href="tel:+420123456789" class="flex items-start gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage shrink-0 contact-icon" style="animation-delay: 0s">
                                <x-icon.phone class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-0.5">Telefon</p>
                                <p class="font-bold text-heading group-hover:text-sage transition-colors">+420 123 456 789</p>
                                <p class="text-xs text-muted">Po–Pá 8:00–17:00</p>
                            </div>
                        </a>

                        <div class="border-t border-gray-100"></div>

                        <a href="mailto:info@barana.cz" class="flex items-start gap-4 group">
                            <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage shrink-0 contact-icon" style="animation-delay: 0.9s">
                                <x-icon.mail class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-0.5">E-mail</p>
                                <p class="font-bold text-heading group-hover:text-sage transition-colors">info@barana.cz</p>
                                <p class="text-xs text-muted">Odpovídáme do 24 h</p>
                            </div>
                        </a>

                        <div class="border-t border-gray-100"></div>

                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-sage-light flex items-center justify-center text-sage shrink-0 contact-icon" style="animation-delay: 1.8s">
                                <x-icon.map-pin-house class="w-5 h-5" />
                            </div>
                            <div>
                                <p class="text-xs text-muted font-semibold uppercase tracking-wider mb-0.5">Adresa</p>
                                <p class="font-bold text-heading">BARANA s.r.o.</p>
                                <p class="text-sm text-body">Hlavní 49<br>69181 Březí</p>
                                <p class="text-xs text-muted mt-1">IČO: 24568341</p>
                            </div>
                        </div>

                    </div>

                    {{-- Provozní doba --}}
                    <div class="card p-5">
                        <h3 class="font-bold text-heading text-sm mb-3 flex items-center gap-2">
                            <x-icon.clock class="w-4 h-4 text-sage" />
                            Provozní doba
                        </h3>
                        <dl class="space-y-1.5 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-muted">Pondělí – Pátek</dt>
                                <dd class="font-semibold text-heading">8:00 – 17:00</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted">Sobota</dt>
                                <dd class="font-semibold text-heading">Po dohodě</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-muted">Neděle</dt>
                                <dd class="text-muted">Zavřeno</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- K-04 Sociální sítě --}}
                    <div class="flex gap-3">
                        <a href="#" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-body hover:border-sage hover:text-sage transition-colors">
                            <x-icon.facebook class="w-4 h-4" />
                            Facebook
                        </a>
                        <a href="#" class="flex items-center gap-2 px-4 py-2.5 rounded-xl border border-gray-200 text-sm font-semibold text-body hover:border-sage hover:text-sage transition-colors">
                            <x-icon.instagram class="w-4 h-4" />
                            Instagram
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </section>

    {{-- K-03 Mapa --}}
    <section class="bg-warm-white">
        <div class="container-site section-wrapper--sm">
            <h2 class="text-lg font-bold text-heading mb-5">Kde nás najdete</h2>
            <a
                href="https://maps.google.com/?q=Hlavní+49,+69181+Březí"
                target="_blank"
                rel="noopener"
                aria-label="Otevřít BARANA s.r.o., Hlavní 49, Březí v Google Maps"
                class="group block rounded-2xl overflow-hidden border border-gray-200 shadow-sm"
            >
                <div class="relative flex items-center justify-center h-95 map-grid-bg">

                    {{-- Jemné zlaté záření uprostřed --}}
                    <div class="absolute inset-0 pointer-events-none map-glow"></div>

                    {{-- Informační karta --}}
                    <div class="relative z-10 text-center px-8 py-9 rounded-2xl max-w-xs w-full mx-4 map-info-card">

                        {{-- Pin ikona --}}
                        <div class="w-14 h-14 rounded-full flex items-center justify-center mx-auto mb-5 map-pin-ring">
                            <x-icon.map-pin-house class="w-7 h-7 text-gold" />
                        </div>

                        {{-- Název a adresa --}}
                        <p class="font-bold text-lg mb-1 leading-tight text-(--color-heading)">BARANA s.r.o.</p>
                        <p class="text-sm mb-6 text-body">Hlavní 49, 691 81 Březí</p>

                        {{-- CTA tlačítko --}}
                        <span class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm bg-gold text-(--bg-page)">
                            Otevřít v Google Maps
                            <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                            </svg>
                        </span>

                    </div>
                </div>
            </a>
        </div>
    </section>

    {{-- K-05 Mini FAQ --}}
    <section class="section-wrapper--sm bg-warm-white">
        <div class="container-site max-w-3xl">
            <h2 class="text-xl font-bold text-heading mb-6" data-reveal>Nejčastější dotazy před prvním kontaktem</h2>
            <x-faq-accordion :items="[
                [
                    'question' => 'Jak rychle se mi ozvete?',
                    'answer'   => 'Do 24 hodin v pracovní dny — obvykle dřív.',
                ],
                [
                    'question' => 'Kolik přibližně stojí pergola nebo brána?',
                    'answer'   => 'Cena závisí na rozměrech, provedení a doplňcích — nedá se říct paušálně. Konkrétní nabídku zpracujeme po zaměření, a to zdarma. Přibližnou představu o ceně vám ale rádi dáme už při prvním kontaktu, jakmile nám sdělíte základní rozměry a vaše představy.',
                ],
                [
                    'question' => 'Jak dlouho trvá výroba a montáž?',
                    'answer'   => 'Od podpisu smlouvy počítejte obvykle 6–10 týdnů na výrobu. Samotná montáž pak trvá 1–3 dny podle rozsahu projektu. Na začátku sezóny se termíny obsazují rychle — doporučujeme nenechávat plánování na poslední chvíli.',
                ],
                [
                    'question' => 'Potřebuji na pergolu nebo plot stavební povolení?',
                    'answer'   => 'Záleží na rozměrech a lokalitě. Menší pergoly a ploty povolení nevyžadují, větší stavby nebo objekty v CHKO ano. Při zaměření vám poradíme, co váš konkrétní projekt vyžaduje — podmínky v Jihomoravském kraji dobře známe.',
                ],
                [
                    'question' => 'Co musím mít připravené já a co zajistíte vy?',
                    'answer'   => 'My se postaráme o návrh, výrobu i montáž. Od vás potřebujeme zpevněnou plochu (beton nebo dlažba) a v případě elektrických doplňků — pohon brány, osvětlení — přivedenou elektřinu na místo. Vše ostatní je na nás.',
                ],
            ]" />
        </div>
    </section>

</div>

@endsection
