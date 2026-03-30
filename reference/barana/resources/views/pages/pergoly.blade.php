@extends('layouts.app')

@section('title', 'Bioklimatické pergoly Brno a Jihomoravský kraj — na míru, s montáží | BARANA')
@section('description', 'Navrhujeme a montujeme prémiové hliníkové bioklimatické pergoly v Jihomoravském kraji. Lamely, LED osvětlení, pohony, stěny. Nezávazná poptávka zdarma.')

@section('content')

{{-- P-01 Hero --}}
<x-sections.hero
    eyebrow="Bioklimatické pergoly"
    title="Jiný způsob trávení času doma"
    subtitle="Ranní káva za deště, letní grilování bez slunce v očích, podzimní večery s teplem infrazářiče. Bioklimatická pergola mění terasu v plnohodnotný obývací pokoj pod otevřeným nebem."
    cta-primary-label="Poptejte nezávazně"
    cta-primary-route="kontakt"
    cta-secondary-label="Shlédnout realizace"
    cta-secondary-route="realizace"
    image-path="hero/pergola-pohori.webp"
    image-alt="Bioklimatická pergola BARANA — Jihomoravský kraj"
/>

{{-- P-02 Úvod: Co je bioklimatická pergola --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            <div data-reveal="fade-left">
                <span class="section-eyebrow">Co to vlastně je</span>
                <h2 class="section-title mb-6">Pergola, která ovládá klima</h2>
                <p class="text-body leading-relaxed mb-4">
                    Klasická pergola je pevná konstrukce — nedá se přizpůsobit počasí. Bioklimatická pergola je jiná: její střecha tvoří otočné hliníkové lamely, které nastavíte přesně tak, jak potřebujete.
                </p>
                <p class="text-body leading-relaxed mb-4">
                    Při slunci nakloníte lamely a stíníte. Při mrholení je zavřete a pokračujete venku dál. Přijde průvan — nastavíte větrání. Lamely reagují na váš pocit tepla, ne na předpověď počasí.
                </p>
                <p class="text-body leading-relaxed">
                    Výsledek? Terasa, kterou využijete od dubna do října. A s infratopením klidně celoročně.
                </p>
            </div>
            <div data-reveal="fade-right">
                <x-responsive-image
                    path="pergoly/intro-aerial.webp"
                    alt="Bioklimatická pergola jako součást moderního domu s bazénem — pohled z výšky"
                    class-picture="block w-full mb-6"
                    class-img="w-full aspect-video object-cover rounded-2xl shadow-md"
                    sizes="(min-width: 1024px) 50vw, 100vw"
                    loading="lazy"
                />
                <div class="grid grid-cols-2 gap-4">
                    <div class="card p-5 text-center">
                        <p class="text-3xl font-extrabold text-sage mb-1" data-counter>12 měsíců</p>
                        <p class="text-sm text-muted">s infratopením</p>
                    </div>
                    <div class="card p-5 text-center">
                        <p class="text-3xl font-extrabold text-sage mb-1" data-counter>135°</p>
                        <p class="text-sm text-muted">natočení lamel</p>
                    </div>
                    <div class="card p-5 text-center">
                        <p class="text-3xl font-extrabold text-sage mb-1" data-counter>110 km/h</p>
                        <p class="text-sm text-muted">odolnost větru</p>
                    </div>
                    <div class="card p-5 text-center">
                        <p class="text-3xl font-extrabold text-sage mb-1" data-counter>57 l/min</p>
                        <p class="text-sm text-muted">odvod dešťové vody</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- P-02b Srovnání materiálů --}}
<section class="section-wrapper--sm bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Proč hliník</span>
            <h2 class="section-title">Hliník vs. dřevo vs. WPC</h2>
            <p class="section-sub">Na fotografii vypadají všechny tři materiály dobře. V realitě se ale liší v tom, co vás čeká za 5, 10 a 20 let.</p>
        </div>

        <div class="overflow-x-auto rounded-2xl border border-gray-200 mt-4" data-reveal>
            <table class="tech-table compare-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Vlastnost</th>
                        <th class="text-center">
                            <span class="inline-flex flex-col items-center gap-0.5">
                                <span>Hliník</span>
                                <span class="text-[10px] font-semibold tracking-wider uppercase text-gold/70">BARANA</span>
                            </span>
                        </th>
                        <th class="text-center">Dřevo</th>
                        <th class="text-center">WPC / kompozit</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['Životnost',                  '30+ let',               '10–15 let',              '15–20 let'],
                        ['Údržba',                     'Žádná',                  'Nátěr každé 2 roky',     'Příležitostné čištění'],
                        ['Odolnost vlhkosti',          '✓ Plná',                '✗ Hnije, praská',         '~ Částečná'],
                        ['Odolnost větru',             'do 110 km/h',            'do 80 km/h',              'do 90 km/h'],
                        ['Automatické ovládání lamel', '✓ Standard',            '✗ Nelze',                 '✗ Nelze'],
                        ['Integrovaný odtok vody',     '✓ V profilech',         '✗ Není',                  '✗ Není'],
                        ['Záruka výrobce',             '5 let',                  '1–2 roky',                '2–3 roky'],
                        ['Náklady po 10 letech',       'Jen pořizovací cena',   'Pořizovací + 3–5× nátěr', 'Pořizovací + opravy'],
                    ] as [$prop, $alu, $wood, $wpc])
                        <tr>
                            <td class="font-medium text-heading">{{ $prop }}</td>
                            <td class="text-center">
                                @php
                                    $cls = str_starts_with($alu, '✓') ? 'compare-table__check'
                                         : (str_starts_with($alu, '✗') ? 'compare-table__cross' : '');
                                @endphp
                                <span class="{{ $cls }}">{{ $alu }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $cls = str_starts_with($wood, '✓') ? 'compare-table__check'
                                         : (str_starts_with($wood, '✗') ? 'compare-table__cross' : 'compare-table__partial');
                                @endphp
                                <span class="{{ $cls }}">{{ $wood }}</span>
                            </td>
                            <td class="text-center">
                                @php
                                    $cls = str_starts_with($wpc, '✓') ? 'compare-table__check'
                                         : (str_starts_with($wpc, '✗') ? 'compare-table__cross' : 'compare-table__partial');
                                @endphp
                                <span class="{{ $cls }}">{{ $wpc }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-center text-xs text-muted mt-4" data-reveal>Hodnoty platí pro standardní podmínky instalace v ČR. Životnost závisí na konkrétním produktu a péči.</p>
    </div>
</section>

{{-- P-02d Interaktivní demo otočných lamel --}}
<section class="section-wrapper bg-warm-white overflow-hidden">
    <div class="container-site">

        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Pro každé počasí</span>
            <h2 class="section-title">Pergola pro každé počasí</h2>
            <p class="section-sub">Počasí neovlivníte — ale pergola vám pomůže s každým z nich. Vyberte situaci a podívejte se, jak lamely reagují.</p>
        </div>

        <div
            x-data="pergolaDemo()"
            x-init="$nextTick(() => { initWeather(); initScrollTrigger(); })"
            class="mt-14 flex flex-col items-center gap-8"
        >

            {{-- Pergola 3D vizualizace --}}
            <div
                class="relative w-full select-none"
                style="max-width: 480px; perspective: 1000px; perspective-origin: 50% 20%;"
            >
                <div
                    class="relative mx-auto w-full rounded-2xl"
                    style="aspect-ratio: 3/2; transform-style: preserve-3d;"
                >
                    {{-- Obloha (viditelná přes otevřené lamely) --}}
                    <div
                        class="absolute inset-0 rounded-2xl pointer-events-none transition-opacity duration-300"
                        :style="'opacity:' + skyOpacity"
                        style="background: linear-gradient(180deg, #a8d9f4 0%, #62b4e0 60%, #4a9fd4 100%);"
                        aria-hidden="true"
                    ></div>

                    {{-- Hliníkový rám + lamely --}}
                    <div
                        class="absolute inset-0 rounded-2xl"
                        style="
                            border: 16px solid #2e2e2e;
                            box-shadow: 0 20px 60px rgba(0,0,0,0.35), inset 0 0 0 2px #555;
                            transform-style: preserve-3d;
                        "
                    >
                        {{-- 3D lamely (rotateX — zajišťuje 3D efekt) --}}
                        <div
                            class="absolute inset-0 flex flex-col"
                            style="padding: 5px; transform-style: preserve-3d;"
                            :style="'gap: ' + Math.round(Math.abs(Math.sin(angle * Math.PI / 180)) * 5) + 'px; padding: 5px; transform-style: preserve-3d;'"
                        >
                            <template x-for="idx in [0,1,2,3,4,5,6,7]" :key="idx">
                                <div class="relative flex-1" style="transform-style: preserve-3d;">
                                    <div
                                        class="absolute inset-0 rounded-[3px]"
                                        style="transform-origin: center 50%; will-change: transform; backface-visibility: visible;"
                                        :style="'transform: rotateX(' + (-angle) + 'deg);' +
                                                'transition: transform ' + (animating ? '0.45s cubic-bezier(0.4,0,0.2,1)' : '0s') + ';' +
                                                'background:' + getSlatGradient(idx) + ';' +
                                                'box-shadow: 0 2px 4px rgba(0,0,0,0.28);'"
                                    ></div>
                                </div>
                            </template>
                        </div>

                        {{--
                            Hrana lamely (záchranný overlay):
                            Tenké proužky viditelné pouze blízko 90° — přesně kdy
                            3D element mizí. Opacity se řídí přes edgeOpacity getter.
                            Není v preserve-3d kontextu → vždy renderuje jako 2D vrstva.
                        --}}
                        <div
                            class="absolute inset-0 flex flex-col pointer-events-none"
                            style="padding: 5px; transition: opacity 0.08s linear;"
                            :style="'opacity:' + edgeOpacity + '; gap: ' + Math.round(Math.abs(Math.sin(angle * Math.PI / 180)) * 5) + 'px;'"
                            aria-hidden="true"
                        >
                            <template x-for="n in [0,1,2,3,4,5,6,7]" :key="'e'+n">
                                <div class="flex-1 relative">
                                    <div
                                        class="absolute inset-x-0.75 rounded-sm"
                                        style="top: calc(50% - 1.5px); height: 3px; background: #4a4a4a; box-shadow: 0 1px 2px rgba(0,0,0,0.5);"
                                    ></div>
                                </div>
                            </template>
                        </div>

                        {{-- Pravá stěna rámu (3D hloubka) --}}
                        <div
                            class="absolute top-0 -right-3.5] rounded-r-xl pointer-events-none"
                            style="width: 14px; height: 100%; background: #1a1a1a; transform: rotateY(90deg); transform-origin: left center;"
                            aria-hidden="true"
                        ></div>
                        {{-- Spodní stěna rámu (3D hloubka) --}}
                        <div
                            class="absolute -bottom-3 left-0 rounded-b-xl pointer-events-none"
                            style="height: 12px; width: 100%; background: #222; transform: rotateX(-90deg); transform-origin: top center;"
                            aria-hidden="true"
                        ></div>
                    </div>

                </div>

            {{-- Počasí: canvas overlay --}}
            <canvas
                x-ref="weatherCanvas"
                class="absolute inset-0 w-full h-full pointer-events-none rounded-2xl"
                style="z-index: 20;"
                aria-hidden="true"
            ></canvas>

            {{-- D: Situační popis přímo na vizualizaci --}}
            <div class="absolute bottom-4 left-1/2 -translate-x-1/2 pointer-events-none" style="z-index: 30;">
                <span
                    class="flex items-center gap-2 px-4 py-1.5 rounded-full text-sm font-semibold whitespace-nowrap backdrop-blur-sm transition-all duration-300"
                    :class="activePreset.badge"
                    style="background: rgba(0,0,0,0.5); color: white;"
                >
                    <span class="shrink-0 w-4 h-4 flex items-center justify-center">
                        <span x-show="activePreset.angle === 0"   x-cloak><x-icon.cloud-rain-wind class="w-4 h-4" /></span>
                        <span x-show="activePreset.angle === 45"  x-cloak><x-icon.cloud-sun       class="w-4 h-4" /></span>
                        <span x-show="activePreset.angle === 90"  x-cloak><x-icon.wind            class="w-4 h-4" /></span>
                        <span x-show="activePreset.angle === 135" x-cloak><x-icon.sun             class="w-4 h-4" /></span>
                    </span>
                    <span x-text="activePreset.desc"></span>
                </span>
            </div>
            </div>

            {{-- B: 4 weather karty — horizontální layout, Lucide ikony --}}
            <div class="relative z-10 grid grid-cols-2 gap-3 w-full" style="max-width: 480px;">

                @foreach ([
                    ['angle' => 0,   'label' => 'Déšť',    'desc' => 'Plná ochrana před deštěm',    'icon' => 'cloud-rain-wind'],
                    ['angle' => 45,  'label' => 'Polostín', 'desc' => 'Filtrované, příjemné světlo', 'icon' => 'cloud-sun'],
                    ['angle' => 90,  'label' => 'Větrání',  'desc' => 'Maximální průchod vzduchu',   'icon' => 'wind'],
                    ['angle' => 135, 'label' => 'Slunce',   'desc' => 'Výhled na otevřené nebe',     'icon' => 'sun'],
                ] as $card)
                <button
                    @click="stopAutoDemo(); animating = true; angle = {{ $card['angle'] }}"
                    @mousemove="e => { const r = $el.getBoundingClientRect(); $el.style.backgroundImage = `radial-gradient(circle at ${e.clientX - r.left}px ${e.clientY - r.top}px, rgba(212,168,83,0.18) 0%, transparent 65%)`; }"
                    @mouseleave="$el.style.backgroundImage = ''"
                    class="flex items-center gap-3 p-3.5 rounded-2xl border-2 text-left cursor-pointer transition-all duration-200 hover:scale-[1.03] active:scale-[0.97] focus:outline-none focus-visible:ring-2 focus-visible:ring-[#D4A853]"
                    :class="activePreset.angle === {{ $card['angle'] }}
                        ? 'border-[#D4A853] bg-[#D4A853]/10 shadow-sm'
                        : 'border-gray-200 bg-white hover:border-[#D4A853]/60'"
                >
                    <div class="shrink-0 transition-colors duration-200" :class="activePreset.angle === {{ $card['angle'] }} ? 'text-[#D4A853]' : 'text-gray-400'">
                        <x-dynamic-component :component="'icon.' . $card['icon']" class="w-6 h-6" />
                    </div>
                    <p class="font-bold text-heading text-sm leading-tight">{{ $card['label'] }}</p>
                </button>
                @endforeach

            </div>

            {{-- Sekundární: slider pro jemné doladění --}}
            <div class="relative z-10 w-full" style="max-width: 480px;">
                <p class="text-xs text-muted text-center mb-2">
                    nebo nastavte ručně —
                    <span class="font-semibold text-heading tabular-nums" x-text="angle + '°'"></span>
                </p>
                <input
                    type="range"
                    x-model.number="angle"
                    @pointerdown="stopAutoDemo(); animating = false"
                    @pointerup="animating = true"
                    min="0" max="135" step="1"
                    class="w-full h-2 rounded-full cursor-pointer"
                    style="accent-color: #D4A853;"
                    aria-label="Sklon lamely"
                >
            </div>

        </div>
    </div>
</section>

<script>
function pergolaDemo() {
    return {
        angle: 0,
        animating: true,
        _autoDemoActive: false,

        presets: [
            { icon: '🌧', label: 'Déšť',    desc: 'Plná ochrana před deštěm',     angle: 0,   badge: 'bg-blue-100 text-blue-700'    },
            { icon: '⛅', label: 'Polostín', desc: 'Filtrované, příjemné světlo',  angle: 45,  badge: 'bg-amber-100 text-amber-700'  },
            { icon: '💨', label: 'Větrání',  desc: 'Maximální průchod vzduchu',    angle: 90,  badge: 'bg-emerald-100 text-emerald-700' },
            { icon: '☀️', label: 'Slunce',   desc: 'Výhled na otevřené nebe',      angle: 135, badge: 'bg-orange-100 text-orange-700' },
        ],

        get activePreset() {
            const diffs = this.presets.map(p => Math.abs(p.angle - this.angle));
            return this.presets[diffs.indexOf(Math.min(...diffs))];
        },

        get skyOpacity() {
            const t = this.angle * Math.PI / 180;
            return Math.pow(Math.abs(Math.sin(t)), 0.6) * 0.95;
        },

        get edgeOpacity() {
            const t = this.angle * Math.PI / 180;
            return Math.max(0, 1 - Math.abs(Math.cos(t)) * 8);
        },

        // C: Auto-demo — při načtení stránky jednou projede všechny stavy
        async _runAutoDemo() {
            this._autoDemoActive = true;
            const stages = [
                { angle: 0,   pause: 1200 },
                { angle: 45,  pause: 1600 },
                { angle: 90,  pause: 1600 },
                { angle: 135, pause: 1600 },
                { angle: 0,   pause: 0    },
            ];
            await new Promise(r => setTimeout(r, 800));
            for (const s of stages) {
                if (!this._autoDemoActive) return;
                this.animating = true;
                this.angle = s.angle;
                if (s.pause) await new Promise(r => setTimeout(r, s.pause));
            }
            this._autoDemoActive = false;
        },

        stopAutoDemo() {
            this._autoDemoActive = false;
        },

        // Spustí auto-demo až když je sekce viditelná ve viewportu (≥ 40 %)
        initScrollTrigger() {
            const observer = new IntersectionObserver((entries) => {
                if (entries[0].isIntersecting) {
                    this._runAutoDemo();
                    observer.disconnect(); // jen jednou
                }
            }, { threshold: 0.4 });
            observer.observe(this.$el);
        },

        getSlatGradient(idx) {
            const t = this.angle * Math.PI / 180;
            const cosA = Math.cos(t);
            const face = Math.abs(cosA);
            const isFront = cosA >= 0;
            const hi = isFront ? Math.round(50 + face * 38) : Math.round(28 + face * 14);
            const lo = isFront ? Math.round(28 + face * 18) : Math.round(18 + face * 8);
            const v  = (idx % 2 === 0) ? 3 : 0;
            return `linear-gradient(180deg, hsl(215,6%,${hi+v}%) 0%, hsl(215,6%,${lo+v}%) 100%)`;
        },

        // ─── Počasí ───────────────────────────────────────────────────────

        _raf: null,

        // Intenzita každého efektu podle aktuálního úhlu lamel
        _clamp: (v) => Math.max(0, Math.min(1, v)),
        get rainI() { return this._clamp(1 - this.angle / 25); },
        get windI() { return this._clamp(1 - Math.abs(this.angle - 90) / 22); },
        get sunI()  { return this._clamp((this.angle - 105) / 30); },

        _rnd(lo, hi) { return lo + Math.random() * (hi - lo); },

        initWeather() {
            const canvas = this.$refs.weatherCanvas;
            if (!canvas) return;

            // Nastav canvas na skutečnou velikost elementu
            const resize = () => {
                canvas.width  = canvas.offsetWidth;
                canvas.height = canvas.offsetHeight;
            };
            resize();
            window.addEventListener('resize', resize);

            const W = () => canvas.width;
            const H = () => canvas.height;

            // Déšť – kapky
            const drops = Array.from({ length: 65 }, () => ({
                x: this._rnd(0, 500), y: this._rnd(-150, 300),
                vy: this._rnd(5, 10), len: this._rnd(12, 26),
                a: this._rnd(0.25, 0.6),
            }));

            // Vítr – horizontální proužky
            const winds = Array.from({ length: 25 }, () => ({
                x: this._rnd(0, 500), y: this._rnd(30, 270),
                vx: this._rnd(1.5, 3.5), len: this._rnd(20, 55),
                a: this._rnd(0.08, 0.22), vy: this._rnd(-0.2, 0.2),
            }));

            // Slunce – zlaté prachové částice
            const motes = Array.from({ length: 45 }, () => ({
                x: this._rnd(0, 500), y: this._rnd(0, 300),
                vx: this._rnd(-0.3, 0.3), vy: this._rnd(-0.8, -0.2),
                r: this._rnd(0.8, 2.4), maxA: this._rnd(0.25, 0.65),
                phase: this._rnd(0, Math.PI * 2),
            }));

            const ctx = canvas.getContext('2d');

            const loop = () => {
                const w = W(), h = H();
                ctx.clearRect(0, 0, w, h);

                const ri = this.rainI;
                const wi = this.windI;
                const si = this.sunI;

                // ── Déšť ──────────────────────────────────────────────────
                if (ri > 0.01) {
                    const roofY = h * 0.20;
                    const open  = this.angle > 70;
                    ctx.save();
                    ctx.lineWidth = 1.2;
                    drops.forEach(d => {
                        d.y += d.vy;
                        d.x -= 1.6; // náklon větrem
                        if (!open && d.y > roofY) {
                            // Kapka dopadla na zavřenou pergolu
                            d.y = this._rnd(-60, -10);
                            d.x = this._rnd(0, w);
                        } else if (d.y > h || d.x < -20) {
                            d.y = this._rnd(-60, -10);
                            d.x = this._rnd(0, w);
                        }
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(175,212,242,${d.a * ri})`;
                        ctx.moveTo(d.x, d.y);
                        ctx.lineTo(d.x + 5, d.y - d.len);
                        ctx.stroke();
                    });
                    ctx.restore();
                }

                // ── Vítr ──────────────────────────────────────────────────
                if (wi > 0.01) {
                    ctx.save();
                    ctx.lineWidth = 1;
                    winds.forEach(p => {
                        p.x += p.vx;
                        p.y += p.vy;
                        if (p.x > w + p.len) {
                            p.x = -p.len;
                            p.y = this._rnd(h * 0.1, h * 0.9);
                        }
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(200,225,248,${p.a * wi})`;
                        ctx.moveTo(p.x, p.y);
                        ctx.lineTo(p.x - p.len, p.y - p.vy * 6);
                        ctx.stroke();
                    });
                    ctx.restore();
                }

                // ── Slunce ────────────────────────────────────────────────
                if (si > 0.01) {
                    const t  = Date.now() / 1000;
                    const sx = w * 0.82, sy = h * 0.11, sr = w * 0.065;

                    // Záře kolem slunce
                    const grd = ctx.createRadialGradient(sx, sy, 0, sx, sy, sr * 3.5);
                    grd.addColorStop(0,   `rgba(255,235,90,${0.7 * si})`);
                    grd.addColorStop(0.5, `rgba(255,210,40,${0.3 * si})`);
                    grd.addColorStop(1,   'rgba(255,200,0,0)');
                    ctx.beginPath(); ctx.fillStyle = grd;
                    ctx.arc(sx, sy, sr * 3.5, 0, Math.PI * 2); ctx.fill();

                    // Jádro slunce
                    ctx.beginPath();
                    ctx.fillStyle = `rgba(255,250,180,${0.95 * si})`;
                    ctx.arc(sx, sy, sr, 0, Math.PI * 2); ctx.fill();

                    // Paprsky
                    for (let i = 0; i < 8; i++) {
                        const ra = (i / 8) * Math.PI * 2 + t * 0.25;
                        ctx.beginPath();
                        ctx.strokeStyle = `rgba(255,235,100,${0.4 * si})`;
                        ctx.lineWidth = 1.8;
                        ctx.moveTo(sx + Math.cos(ra) * sr * 1.4, sy + Math.sin(ra) * sr * 1.4);
                        ctx.lineTo(sx + Math.cos(ra) * sr * 2.3, sy + Math.sin(ra) * sr * 2.3);
                        ctx.stroke();
                    }

                    // Zlatý prach ve světle
                    ctx.save();
                    motes.forEach(m => {
                        m.x += m.vx; m.y += m.vy;
                        if (m.y < -5 || m.x < -5 || m.x > w + 5) {
                            m.x = this._rnd(0, w); m.y = h + 5;
                        }
                        const a = m.maxA * (0.5 + 0.5 * Math.sin(t * 1.1 + m.phase));
                        ctx.beginPath();
                        ctx.fillStyle = `rgba(255,220,80,${a * si})`;
                        ctx.arc(m.x, m.y, m.r, 0, Math.PI * 2);
                        ctx.fill();
                    });
                    ctx.restore();
                }

                this._raf = requestAnimationFrame(loop);
            };
            loop();
        },
    };
}
</script>

{{-- P-03 Funkce a výhody --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Co všechno umí</span>
            <h2 class="section-title">Funkce, které oceníte každý den</h2>
            <p class="section-sub">Pergola není jen střecha. Je to systém, který se přizpůsobí vašemu životu.</p>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5 mt-12" data-reveal-group>
            <x-benefit-card
                icon="align-justify"
                title="Otočné lamely"
                desc="Hliníkové lamely se natáčejí od 0° do 135° — od plného stínění po maximální větrání. Dostupné ve dvou provedeních: SLIM (trapézový profil) a Standard (plochý profil)."
            />
            <x-benefit-card
                icon="shield"
                title="Odolnost větru"
                desc="Prémiový hliník odolá nárazu větru až 110 km/h. Bouřka vás nepřekvapí — pergola to zvládne i bez vás doma."
            />
            <x-benefit-card
                icon="lightbulb"
                title="LED osvětlení"
                desc="Integrované LED pásky v profilech lamel. Žádné kabely viditelně, žádné žárovky navíc. Večery pod pergolou dostaly nový rozměr."
            />
            <x-benefit-card
                icon="flame"
                title="Infratopení"
                desc="Montáž infrazářiče do konstrukce je příprava na podzim a jaro. Terasa využitelná od března do listopadu — bez kompromisů."
            />
            <x-benefit-card
                icon="columns-3"
                title="Boční stěny a žaluzie"
                desc="Zip systém, skleněné stěny nebo hliníkové lamely. Uzavřete pergolu podle potřeby — chrání před větrem, hmyzem i pohledy sousedů."
            />
            <x-benefit-card
                icon="remote"
                title="Motorové ovládání"
                desc="Dálkový ovladač, dotykový panel nebo chytrá aplikace. Zavřít pergolu před bouřkou zvládnete z gauče nebo z práce."
            />
            <x-benefit-card
                icon="palette"
                title="Výběr barev"
                desc="12 standardních barev ze vzorníku RAL, další odstíny na vyžádání. Pergola ladí s fasádou, plotem i dalšími prvky — vše v jednom stylu."
            />
            <x-benefit-card
                icon="ruler"
                title="Rozměry na míru"
                desc="Standardní rozměry? Neznáme je. Jeden segment do 6×4 m (profil 120 mm) nebo 7×5 m (profil 140 mm). Atypické rozměry řešíme individuálně."
            />
        </div>
    </div>
</section>

{{-- P-03b Vizuální showcase — pergola v různých situacích --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Pergola v každém světle</span>
            <h2 class="section-title">Každý den jiná nálada</h2>
            <p class="section-sub">Uzavřete střechu při dešti, vychutnejte si večer za sklem, rozsvěťte LED pro noční posezení — pergola se přizpůsobí každé chvíli.</p>
        </div>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-12" data-reveal-group>
            <x-responsive-image
                path="pergoly/nalada-dest.jpg"
                alt="Pergola s uzavřenou střechou chrání před deštěm"
                class-picture="block w-full"
                class-img="w-full aspect-[4/3] object-cover rounded-xl"
                sizes="(min-width: 1024px) 25vw, 50vw"
                loading="lazy"
            />
            <x-responsive-image
                path="pergoly/nalada-vecer-sklo.jpg"
                alt="Pergola s prosklenými bočními stěnami za soumraku"
                class-picture="block w-full"
                class-img="w-full aspect-[4/3] object-cover rounded-xl"
                sizes="(min-width: 1024px) 25vw, 50vw"
                loading="lazy"
            />
            <x-responsive-image
                path="pergoly/nalada-led-noc.jpg"
                alt="Pergola s LED osvětlením — večerní atmosféra"
                class-picture="block w-full"
                class-img="w-full aspect-[4/3] object-cover rounded-xl"
                sizes="(min-width: 1024px) 25vw, 50vw"
                loading="lazy"
            />
            <x-responsive-image
                path="pergoly/nalada-zip-steny.jpg"
                alt="Pergola s bočními zip stěnami — soukromí i ochrana"
                class-picture="block w-full"
                class-img="w-full aspect-[4/3] object-cover rounded-xl"
                sizes="(min-width: 1024px) 25vw, 50vw"
                loading="lazy"
            />
        </div>
    </div>
</section>

{{-- P-04 Konfigurátor variant --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Sestavte si svoji pergolu</span>
            <h2 class="section-title">Co si můžete zvolit</h2>
            <p class="section-sub">Každá pergola je kombinace vašich přání. Zde jsou hlavní volby.</p>
        </div>

        <div x-data="{ tab: 'strecha' }" class="mt-12">

            {{-- Tab navigace --}}
            <div class="flex flex-wrap gap-2 border-b border-gray-200 mb-10" role="tablist">
                @foreach ([
                    ['key' => 'strecha',   'label' => 'Střecha'],
                    ['key' => 'steny',     'label' => 'Stěny'],
                    ['key' => 'ovladani',  'label' => 'Ovládání'],
                    ['key' => 'osvetleni', 'label' => 'Osvětlení'],
                    ['key' => 'barvy',     'label' => 'Barvy'],
                ] as $t)
                    <button
                        role="tab"
                        :aria-selected="tab === '{{ $t['key'] }}'"
                        @click="tab = '{{ $t['key'] }}'"
                        class="px-5 pb-3 text-sm font-semibold transition-all border-b-2 -mb-px"
                        :class="tab === '{{ $t['key'] }}'
                            ? 'border-sage text-sage'
                            : 'border-transparent text-muted hover:text-body'"
                    >
                        {{ $t['label'] }}
                    </button>
                @endforeach
            </div>

            {{-- Tab panely --}}
            @foreach ([
                [
                    'key'   => 'strecha',
                    'title' => 'Lamelová střecha — srdce pergoly',
                    'desc'  => 'Otočné hliníkové lamely jsou to, co dělá bioklimatickou pergolu bioklimatickou. Natáčí se plynule od 0° (zavřeno, chrání před deštěm) do 135° (otevřeno pro maximální větrání a průchod světla). Nabízíme dva typy lamel: SLIM (trapézový profil) a Standard (plochý profil).',
                    'items' => ['Plynulé natáčení 0°–135°', 'Integrovaný odtok dešťové vody v profilech', 'Sněhová zátěž 72 kg/m² (profil 120 mm) nebo 200 kg/m² (profil 140 mm)', 'Bezúdržbový povrch — prach smyje déšť'],
                    'img'   => 'pergoly/tab-strecha.jpg',
                ],
                [
                    'key'   => 'steny',
                    'title' => 'Boční stěny — sklo, screen rolety nebo slunolamce',
                    'desc'  => 'Boky pergoly lze uzavřít nebo zastínit — každé řešení funguje jinak a hodí se na jinou situaci. Zasklení vytvoří uzavřený prostor chráněný před počasím, screen rolety pohltí sluneční žár a přitom propouštějí vzduch i výhled ven, slunolamce lámou přímé paprsky bez pocitu uzavřenosti. Na každé straně pergoly lze zvolit jiné řešení nebo varianty libovolně kombinovat.',
                    'items' => ['Posuvné zasklení — rámové (v hliníkovém rámu) nebo bezrámové (čisté sklo bez viditelných profilů), panely se odsunují stranou a uzavírají prostor před větrem, deštěm i chladem', 'Screen rolety — sítovaná tkanina se vytáhne nebo sroluje motoricky, blokuje přímé sluneční záření a teplo, ale světlo i výhled zůstanou zachovány — ovládání je propojeno s pergolou na jeden ovladač', 'Slunolamce — pevné hliníkové lamely zabudované po boku pergoly lámou přímé sluneční paprsky pod ostrým úhlem, vzduch ale volně prochází — žádné vedro ani pocit uzavřenosti', 'Každá strana jinak — varianty lze libovolně kombinovat, například zasklení ze severu a screen rolety z jihu'],
                    'img'   => 'pergoly/tab-steny.jpg',
                    'img2'  => 'pergoly/nalada-sklenene-steny.jpg',
                ],
                [
                    'key'   => 'ovladani',
                    'title' => 'Ovládání — pergola na dosah prstu',
                    'desc'  => 'Lamely, stěny i osvětlení ovládáte jedním dotykem. Zvolte způsob, který vám nejvíce vyhovuje — nebo kombinujte více metod.',
                    'items' => ['Dálkový ovladač (standard)', 'Nástěnný dotykový panel', 'Chytrá aplikace (iOS / Android)', 'Automatická čidla větru a deště'],
                    'img'   => 'pergoly/tab-ovladani.jpg',
                ],
                [
                    'key'   => 'osvetleni',
                    'title' => 'Osvětlení — večery bez kompromisů',
                    'desc'  => 'LED osvětlení je integrováno přímo do profilů lamel nebo bočních sloupků. Žádné kabely viditelně, žádné dodatečné svítilny. Nastavte barvu světla podle nálady.',
                    'items' => ['LED pásky integrované v profilech lamel', 'Bodová světla ve sloupcích', 'RGB barevné scény (aplikace)', 'Stmívání a časovač'],
                    'img'   => 'pergoly/tab-osvetleni.webp',
                ],
                [
                    'key'   => 'barvy',
                    'title' => 'Barvy — váš styl, vaše barva',
                    'desc'  => '12 standardních barev ze vzorníku RAL — nejoblíbenější jsou antracit RAL 7016, bílá RAL 9010 a šedá RAL 7037. Další odstíny jsou dostupné na vyžádání, vybereme přesně tu barvu, která ladí s vaším domem.',
                    'items' => ['12 standardních barev ze vzorníku RAL', 'Další odstíny RAL na vyžádání', 'Strukturované povrchy (mat, lesk, písek)', 'Vzorek barvy před objednávkou zdarma'],
                    'img'   => 'pergoly/tab-barvy.jpg',
                ],
            ] as $panel)
                <div
                    x-show="tab === '{{ $panel['key'] }}'"
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-2"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-cloak
                    role="tabpanel"
                >
                    <div class="grid lg:grid-cols-2 gap-10 items-center">
                        <div>
                            <h3 class="text-2xl font-bold text-heading mb-4">{{ $panel['title'] }}</h3>
                            <p class="text-body leading-relaxed mb-6">{{ $panel['desc'] }}</p>
                            <ul class="space-y-3">
                                @foreach ($panel['items'] as $item)
                                    <li class="flex items-start gap-3">
                                        <x-icon.check class="w-5 h-5 text-sage shrink-0 mt-0.5" />
                                        <span class="text-body text-sm">{{ $item }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="flex flex-col gap-4">
                            <x-responsive-image path="{{ $panel['img'] }}" alt="{{ $panel['title'] }}" class-picture="block w-full" class-img="w-full aspect-4/3 object-cover rounded-2xl shadow-md" sizes="(min-width: 1024px) 50vw, 100vw" loading="lazy" />
                            @if (!empty($panel['img2']))
                                <x-responsive-image path="{{ $panel['img2'] }}" alt="{{ $panel['title'] }} — prosklené posuvné stěny" class-picture="block w-full" class-img="w-full aspect-4/3 object-cover rounded-2xl shadow-md" sizes="(min-width: 1024px) 50vw, 100vw" loading="lazy" />
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </div>
</section>

{{-- P-05 Galerie --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Galerie</span>
            <h2 class="section-title">Naše realizace pergol</h2>
        </div>
        <x-sections.gallery-grid
            :images="[
                ['path' => 'realizace/pergola-1a.jpg', 'alt' => 'Bioklimatická pergola s bočními stěnami, Brno'],
                ['path' => 'realizace/pergola-1b.jpg', 'alt' => 'Pergola detail lamel, Brno'],
                ['path' => 'realizace/pergola-1c.jpg', 'alt' => 'Pergola noční osvětlení, Brno'],
                ['path' => 'realizace/pergola-2a.jpg', 'alt' => 'Volně stojící pergola, Znojmo'],
                ['path' => 'realizace/pergola-2b.jpg', 'alt' => 'Pergola bílá, Znojmo'],
                ['path' => 'realizace/pergola-3a.jpg', 'alt' => 'Pergola u bazénu, Břeclav'],
                ['path' => 'realizace/pergola-3b.jpg', 'alt' => 'Pergola zip stěny, Břeclav'],
                ['path' => 'realizace/kombinace-1a.jpg', 'alt' => 'Pergola + brána, Mikulov'],
                ['path' => 'realizace/pergola-4a.jpg',   'alt' => 'Bioklimatická pergola u bazénu'],
            ]"
            :cols="4"
            :cols-md="3"
            :cols-mobile="2"
            gallery="pergoly"
        />
    </div>
</section>

{{-- P-06 Technické parametry --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="grid lg:grid-cols-2 gap-12 items-start">
            <div data-reveal="fade-left">
                <span class="section-eyebrow">Technické parametry</span>
                <h2 class="section-title mb-6">Pro ty, kteří chtějí čísla</h2>
                <p class="text-body leading-relaxed">
                    Prémiový hliník, prášková barva, motorové pohony — vše dimenzováno pro dlouhý provoz bez starostí. Zde jsou základní technické parametry.
                </p>
            </div>
            <div data-reveal="fade-right">
                <div class="overflow-x-auto rounded-2xl border border-gray-200">
                    <table class="tech-table w-full">
                        <thead>
                            <tr>
                                <th>Parametr</th>
                                <th>Hodnota</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                ['Materiál', 'Prémiový hliník, práškové lakování'],
                                ['Rozměry segmentu', 'do 6×4 m (profil 120 mm) nebo 7×5 m (profil 140 mm)'],
                                ['Odolnost větru', 'do 110 km/h'],
                                ['Nosnost střechy (sníh)', '72 kg/m² (profil 120 mm) / 200 kg/m² (profil 140 mm)'],
                                ['Odtok dešťové vody', 'Integrovaný v profilech sloupků'],
                                ['Pohon lamel', 'Elektromotor 24V, tichý chod'],
                                ['Napájení', '230V / záložní baterie (volitelně)'],
                                ['Záruka', '5 let na konstrukci a pohony'],
                            ] as [$label, $value])
                                <tr>
                                    <td class="font-medium text-heading">{{ $label }}</td>
                                    <td>{{ $value }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- P-06b Srovnání profilů --}}
<section class="section-wrapper--sm bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Dva typy profilů</span>
            <h2 class="section-title">Profil 120 mm nebo 140 mm?</h2>
            <p class="section-sub">Nabízíme dvě řady s různými maximálními rozměry a nosností. Správný profil vybereme spolu s vámi při zaměření.</p>
        </div>
        <div class="overflow-x-auto rounded-2xl border border-gray-200 mt-8" data-reveal>
            <table class="tech-table compare-table w-full">
                <thead>
                    <tr>
                        <th class="text-left">Parametr</th>
                        <th class="text-center">Profil 120 mm</th>
                        <th class="text-center">Profil 140 mm</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ([
                        ['Max. rozměr segmentu', '6 × 4 m', '7 × 5 m'],
                        ['Max. výška', '3 m', '3 m'],
                        ['Sněhová zátěž', '72 kg/m²', '200 kg/m²'],
                        ['Odolnost větru', '110 km/h', '110 km/h'],
                        ['Odvod vody', '57 l/min', '57 l/min'],
                        ['Typy lamel', 'SLIM / Standard', 'SLIM / Standard'],
                    ] as [$param, $p120, $p140])
                        <tr>
                            <td class="font-medium text-heading">{{ $param }}</td>
                            <td class="text-center">{{ $p120 }}</td>
                            <td class="text-center">{{ $p140 }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <p class="text-center text-xs text-muted mt-4" data-reveal>Hodnoty pro maximální rozměry segmentu při standardních podmínkách instalace. Atypické rozměry řešíme individuálně.</p>
    </div>
</section>

{{-- P-07 Investice --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Orientační ceny</span>
            <h2 class="section-title">Kolik stojí bioklimatická pergola?</h2>
            <p class="section-sub">Cena vychází z profilu konstrukce, rozměrů a zvolené konfigurace. Orientační rozsahy níže vám pomohou odhadnout investici — přesnou kalkulaci zpracujeme po bezplatném zaměření.</p>
        </div>

        <div class="grid sm:grid-cols-3 gap-5 mt-12" data-reveal-group>

            {{-- Kompaktní --}}
            <div class="card p-7 flex flex-col">
                <p class="text-xs font-bold uppercase tracking-widest text-muted mb-1">Kompaktní</p>
                <p class="text-sm text-muted mb-6">Cca 3 × 4 m</p>
                <div class="flex items-baseline gap-1 mb-7">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-4xl font-extrabold text-heading tabular-nums leading-none">85 000</span>
                    <span class="text-base font-semibold text-muted leading-none">Kč</span>
                </div>
                <ul class="space-y-2.5 flex-1 mb-8">
                    @foreach ([
                        'Hliníková konstrukce na míru (Profil 120)',
                        'Motorové otočné lamely 0–135°',
                        'Výběr SLIM nebo Standard lamel',
                        'Efektivní odvodnění (57 l/min)',
                        'Dálkové ovládání (1 ovladač)',
                        '12 barev ze vzorníku RAL',
                        'Montáž s kyvnými kotvami',
                    ] as $feat)
                        <li class="flex items-start gap-2.5 text-body text-sm">
                            <x-icon.check class="w-4 h-4 text-sage shrink-0 mt-0.5" />
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-outline-sage w-full justify-center mt-auto">
                    Nezávazná nabídka
                    <x-icon.arrow-right class="w-4 h-4" />
                </a>
            </div>

            {{-- Standardní — nejoblíbenější --}}
            <div class="card p-7 flex flex-col relative" style="border-color: var(--color-gold); box-shadow: 0 0 0 1px rgba(212,168,83,0.2);">
                <span class="badge badge--gold absolute -top-3.5 left-1/2 -translate-x-1/2 whitespace-nowrap">Nejoblíbenější</span>
                <p class="text-xs font-bold uppercase tracking-widest text-muted mb-1 mt-2">Standardní</p>
                <p class="text-sm text-muted mb-6">Cca 4 × 6 m</p>
                <div class="flex items-baseline gap-1 mb-7">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-4xl font-extrabold text-heading tabular-nums leading-none">145 000</span>
                    <span class="text-base font-semibold text-muted leading-none">Kč</span>
                </div>
                <ul class="space-y-2.5 flex-1 mb-8">
                    @foreach ([
                        'Hliníková konstrukce na míru (Profil 120, max. 6×4 m)',
                        'Motorové lamely 0–135° + čidla větru a deště',
                        'LED osvětlení po obvodě nebo v lamelách',
                        'Dálkové ovládání nebo ovládání přes aplikaci',
                        'Efektivní odvodnění (57 l/min)',
                        '12 barev ze vzorníku RAL',
                        'Montáž s kyvnými kotvami',
                    ] as $feat)
                        <li class="flex items-start gap-2.5 text-body text-sm">
                            <x-icon.check class="w-4 h-4 text-sage shrink-0 mt-0.5" />
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-primary w-full justify-center mt-auto">
                    Nezávazná nabídka
                    <x-icon.arrow-right class="w-4 h-4" />
                </a>
            </div>

            {{-- Prémiová --}}
            <div class="card p-7 flex flex-col">
                <p class="text-xs font-bold uppercase tracking-widest text-muted mb-1">Prémiová</p>
                <p class="text-sm text-muted mb-6">5 × 7 m a více</p>
                <div class="flex items-baseline gap-1 mb-7">
                    <span class="text-xs text-muted leading-none">od</span>
                    <span class="text-4xl font-extrabold text-heading tabular-nums leading-none">235 000</span>
                    <span class="text-base font-semibold text-muted leading-none">Kč</span>
                </div>
                <ul class="space-y-2.5 flex-1 mb-8">
                    @foreach ([
                        'Robustní Profil 140 (max. 7×5 m, sněh 200 kg/m²)',
                        'Motorové lamely 0–135° + automatická čidla',
                        'LED osvětlení + RGB barevné scény',
                        'Boční stěny — zasklení nebo screen rolety',
                        'Integrované infrazářiče',
                        '12 barev RAL + atypické laky',
                        'Montáž s kyvnými kotvami',
                    ] as $feat)
                        <li class="flex items-start gap-2.5 text-body text-sm">
                            <x-icon.check class="w-4 h-4 text-sage shrink-0 mt-0.5" />
                            {{ $feat }}
                        </li>
                    @endforeach
                </ul>
                <a href="{{ route('kontakt') }}" class="btn btn-outline-sage w-full justify-center mt-auto">
                    Nezávazná nabídka
                    <x-icon.arrow-right class="w-4 h-4" />
                </a>
            </div>

        </div>

        <p class="text-center text-xs text-muted mt-8" data-reveal>
            Orientační ceny jsou uvedeny bez DPH a zahrnují návrh, výrobu, dopravu i montáž. Přesná cena závisí na zvolené řadě, konečných rozměrech a výběru doplňků — vždy ji zpracujeme v závazné nabídce po bezplatném zaměření.
        </p>
    </div>
</section>

{{-- P-08 CTA --}}
<x-sections.cta-band
    eyebrow="Bioklimatická pergola BARANA"
    title="Prostě vždycky."
    subtitle="I když bude pršet. I když bude vítr. Vaše pergola zůstane místem, kam se budete vracet — a ostatní vám ji budou tiše závidět"
    btn-label="Poptejte nezávazně"
    btn-route="kontakt"
/>

{{-- P-09 FAQ --}}
<section class="section-wrapper bg-warm-white">
    <div class="container-site max-w-3xl">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Nejčastější dotazy</span>
            <h2 class="section-title">Co zákazníci řeší nejčastěji</h2>
        </div>
        <x-faq-accordion :items="[
            [
                'question' => 'Potřebuji na bioklimatickou pergolu stavební povolení?',
                'answer'   => 'Ve většině případů ne. Pergola přichycená k domu nebo stojící samostatně na pozemku rodinného domu zpravidla nevyžaduje povolení ani ohlášení, pokud nepřesáhne zastavěnou plochu 40 m² a výšku 5 m. Výjimkou jsou lokality v CHKO nebo památkové zóně — tam je potřeba ověřit podmínky u místního úřadu. Při zaměření vám poradíme konkrétně pro váš pozemek a obec.',
            ],
            [
                'question' => 'Kam odteče dešťová voda? Nezaplaví mi terasu?',
                'answer'   => 'Ne. Dešťová voda se zachytí přímo v dutině hliníkových lamel a odtéká skrz dutou konstrukci sloupků do odtoku v podlaze nebo dál do kanalizace. Na terasu nespadne ani kapka — je to uzavřený systém, stejně jako střecha domu. Systém odvede až 57 l/min (norma vyžaduje přibližně 30 l/min). Trasu odtoku navrhujeme při zaměření přesně pro váš pozemek.',
            ],
            [
                'question' => 'Jaký základ pergola potřebuje? Musím betonovat?',
                'answer'   => 'Záleží na situaci. Pokud máte stávající betonovou terasu nebo dlažbu s dostatečnou tloušťkou (min. 15–20 cm betonu), lze kotvit přímo do ní pomocí chemických kotev. Pokud pevný podklad není, připravíme betonové patky pod každý sloup — to je spolehlivé a trvalé řešení. Vše vyhodnotíme při zaměření a navrhneme optimální postup.',
            ],
            [
                'question' => 'Musím si připravit elektřinu, nebo to zajistíte vy?',
                'answer'   => 'Přívod elektřiny k pergole si zákazník obvykle zajišťuje sám — ideálně ještě před betonováním, aby byl kabel schovaný v zemi. Stačí dedikovaný okruh 230V (pro topení 400V). Samotné zapojení pohonů, osvětlení a elektroniky v pergole provádíme my, ve spolupráci s certifikovaným elektrikářem.',
            ],
            [
                'question' => 'Musím pergolu v zimě rozebrat nebo zakrýt?',
                'answer'   => 'Vůbec ne. Hliníková konstrukce odolá mrazu, sněhu i dešti bez jakékoli ochrany nebo přípravy. Lamely jsou certifikovaně dimenzovány na sněhovou zátěž 72 kg/m² (profil 120 mm) nebo 200 kg/m² (profil 140 mm). V zimě pergolu prostě zavřete a zapomenete na ni do jara — žádné plachty, žádné skladování.',
            ],
            [
                'question' => 'Musí být pergola přichycená ke zdi domu?',
                'answer'   => 'Ne. Pergola může stát zcela samostatně na čtyřech sloupcích — třeba u bazénu, uprostřed zahrady nebo na volné terase. Volně stojící i přichycená varianta mají stejné parametry a odolnost. Při zaměření doporučíme, co dává smysl pro váš konkrétní pozemek.',
            ],
            [
                'question' => 'Jak se pergola čistí a jak náročná je údržba?',
                'answer'   => 'Minimální. Hliník s práškovým lakem nevyžaduje natírání ani žádné ošetřování. Jednou za sezónu stačí opláchnout tlakovou vodou nebo zahradní hadicí — prach a nečistoty sjede sám. Pohony doporučujeme jednou za 2–3 roky zkontrolovat servisním technikem, podobně jako servis auta.',
            ],
        ]" />
    </div>
</section>

{{-- P-07 Cross-sell --}}
<section class="section-wrapper--sm bg-warm-white">
    <div class="container-site">
        <div class="section-header" data-reveal>
            <span class="section-eyebrow">Doplňte pergolu</span>
            <h2 class="text-2xl font-bold text-heading">Sladěná brána a plot — pro celý pozemek</h2>
            <p class="mt-3 text-body">Stejný hliník, stejná barva, stejný výrobce. Žádné hledání dalšího dodavatele.</p>
        </div>
        <div class="grid sm:grid-cols-2 gap-5 mt-8" data-reveal-group>
            <a href="{{ route('brany-a-ploty') }}" class="card overflow-hidden group flex flex-col">
                <x-responsive-image path="brany/brana-1.jpg" alt="Hliníkové brány BARANA" class-picture="block w-full" class-img="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105" sizes="(min-width: 640px) 50vw, 100vw" loading="lazy" />
                <div class="p-5 flex-1 flex flex-col gap-3">
                    <h3 class="font-bold text-heading text-lg">Hliníkové brány</h3>
                    <p class="text-body text-sm">Posuvné i dvoukřídlé, s automatickým pohonem. Sladěné s pergolou do posledního detailu.</p>
                    <span class="mt-auto inline-flex items-center gap-2 text-sage font-semibold text-sm group-hover:gap-3 transition-all">Více o branách <x-icon.arrow-right class="w-4 h-4" /></span>
                </div>
            </a>
            <a href="{{ route('brany-a-ploty') }}" class="card overflow-hidden group flex flex-col">
                <x-responsive-image path="brany/plot-1.jpg" alt="Hliníkové ploty BARANA" class-picture="block w-full" class-img="w-full aspect-video object-cover transition-transform duration-500 group-hover:scale-105" sizes="(min-width: 640px) 50vw, 100vw" loading="lazy" />
                <div class="p-5 flex-1 flex flex-col gap-3">
                    <h3 class="font-bold text-heading text-lg">Hliníkové ploty</h3>
                    <p class="text-body text-sm">Lamelové i deskové, ve výšce a designu přesně podle vás. Bezúdržbové a odolné.</p>
                    <span class="mt-auto inline-flex items-center gap-2 text-sage font-semibold text-sm group-hover:gap-3 transition-all">Více o plotech <x-icon.arrow-right class="w-4 h-4" /></span>
                </div>
            </a>
        </div>
    </div>
</section>

@endsection
