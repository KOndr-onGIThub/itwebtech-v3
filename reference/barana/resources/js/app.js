import './bootstrap';
import Alpine from 'alpinejs';
import Collapse from '@alpinejs/collapse';

Alpine.plugin(Collapse);

window.Alpine = Alpine;

const rawImages = import.meta.glob(
    '../img/**/*.{jpg,jpeg,png,webp,avif}',
    {
        eager: true,
        query: {
            w: '320;480;640;768;960;1280;1536',
            format: 'avif;webp',
            url: true,
        },
        import: 'default',
    }
);

// Normalize keys: '../img/hero/photo.jpg' → 'hero/photo.jpg'
window.sharedImages = Object.fromEntries(
    Object.entries(rawImages).map(([key, value]) => [
        key.replace('../img/', ''),
        value,
    ])
);

// ---------------------------------------------------------------------------
// File drop zone — used in all contact/inquiry forms
// ---------------------------------------------------------------------------

// ---------------------------------------------------------------------------
// Cookie consent — GDPR, podmíněné načtení Google Analytics
// ---------------------------------------------------------------------------
Alpine.data('cookieConsent', (gaId, suppressModal = false) => ({
    visible: false,

    _getConsent() {
        const raw = localStorage.getItem('barana_cookie_consent');
        if (!raw) return { status: null, expired: true };
        if (!raw.includes(':')) {
            // Starý formát bez timestampu
            return raw === 'accepted'
                ? { status: 'accepted', expired: false }
                : { status: 'declined', expired: true }; // staré odmítnutí = prošlé
        }
        const [status, ts] = raw.split(':');
        const age = Date.now() - Number(ts);
        const limit = status === 'accepted' ? 365 * 86400 * 1000 : 14 * 86400 * 1000;
        return { status, expired: age > limit };
    },

    init() {
        const { status, expired } = this._getConsent();
        if (status === 'accepted' && !expired) {
            this.loadGA(gaId);
            return;
        }
        // Zobrazit popup jen pokud: není na stránce zásad, a uživatel buď nerozhodl nebo mu rozhodnutí expiralo
        if (!suppressModal && !(status === 'declined' && !expired)) {
            setTimeout(() => { this.visible = true; }, 800);
        }
        // Posloucháme inline souhlas ze stránky zásad cookies
        window.addEventListener('barana:cookieAccepted', () => this.loadGA(gaId), { once: true });
    },

    accept() {
        localStorage.setItem('barana_cookie_consent', 'accepted:' + Date.now());
        this.visible = false;
        this.loadGA(gaId);
    },

    decline() {
        localStorage.setItem('barana_cookie_consent', 'declined:' + Date.now());
        this.visible = false;
    },

    close() {
        this.visible = false;
        // Bez uložení — zobrazí se znovu při příští návštěvě
    },

    loadGA(id) {
        if (!id || window._barana_ga_loaded) return;
        window._barana_ga_loaded = true;

        // Inicializace gtag
        window.dataLayer = window.dataLayer || [];
        window.gtag = function () { dataLayer.push(arguments); };
        gtag('js', new Date());
        gtag('config', id, { anonymize_ip: true });

        // Dynamicky načíst GA skript — POUZE po souhlasu
        const script = document.createElement('script');
        script.async = true;
        script.src = `https://www.googletagmanager.com/gtag/js?id=${id}`;
        document.head.appendChild(script);
    },
}));

// ---------------------------------------------------------------------------
Alpine.data('fileDropZone', () => ({
    isDragOver: false,
    files: [],
    error: '',

    init() {
        // contactForm dispatches this event after successful submit
        this.$el.addEventListener('file-drop:reset', () => {
            this.files = [];
            this.error = '';
            if (this.$refs.input) this.$refs.input.value = '';
        });
    },

    validate(fileList) {
        if (fileList.length > 5) {
            this.error = 'Maximálně 5 souborů najednou.';
            return false;
        }
        const totalSize = fileList.reduce((sum, f) => sum + f.size, 0);
        if (totalSize > 20 * 1024 * 1024) {
            this.error = 'Celková velikost nesmí překročit 20 MB.';
            return false;
        }
        this.error = '';
        return true;
    },

    addFiles(newFiles) {
        // Skip duplicates (same name + size)
        const existingKeys = new Set(this.files.map(f => f.name + '_' + f.size));
        const toAdd = newFiles.filter(f => !existingKeys.has(f.name + '_' + f.size));
        const merged = [...this.files, ...toAdd];
        if (!this.validate(merged)) return;
        this.files = merged;
        this.syncInput();
    },

    removeFile(index) {
        this.files.splice(index, 1);
        this.error = '';
        this.syncInput();
    },

    syncInput() {
        const dt = new DataTransfer();
        this.files.forEach(f => dt.items.add(f));
        this.$refs.input.files = dt.files;
    },

    handleDrop(e) {
        this.isDragOver = false;
        const dropped = Array.from(e.dataTransfer?.files ?? []);
        if (dropped.length) this.addFiles(dropped);
    },

    handleChange(e) {
        const selected = Array.from(e.target.files);
        e.target.value = ''; // reset so same file can be picked again
        if (selected.length) this.addFiles(selected);
    },

    formatSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(0) + ' kB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    },
}));

// ---------------------------------------------------------------------------
// Contact / inquiry form — async submit with SweetAlert2 feedback
// ---------------------------------------------------------------------------
Alpine.data('contactForm', () => ({
    loading: false,

    async submit() {
        const form = this.$el;
        const data = new FormData(form);
        this.loading = true;

        // Dynamic import — Swal is loaded only on first form submission
        const { default: Swal } = await import('sweetalert2');
        const MySwal = Swal.mixin({
            confirmButtonText: 'Zavřít',
            showClass:  { popup: 'animate__animated animate__fadeInDown animate__faster' },
            hideClass:  { popup: 'animate__animated animate__fadeOutUp animate__faster' },
        });

        try {
            await window.axios.post('/kontakt', data);

            await MySwal.fire({
                icon: 'success',
                iconColor: '#D4A853',
                title: 'Zpráva odeslána!',
                text: 'Vaši zprávu jsme přijali. Potvrzení jsme zaslali na zadaný váš email.',
            });

            form.reset();
            // Notify all file drop zones to reset their state
            form.querySelectorAll('[x-data]').forEach(el => {
                el.dispatchEvent(new CustomEvent('file-drop:reset'));
            });

        } catch (err) {
            const errors = err.response?.data?.errors;
            let msg = 'Formulář se nepodařilo odeslat. Zkuste to prosím znovu.';
            if (errors) {
                msg = Object.values(errors).flat().join('\n');
            } else if (err.response?.data?.message) {
                msg = err.response.data.message;
            }

            MySwal.fire({
                icon: 'error',
                iconColor: '#DC5050',
                title: 'Chyba při odeslání',
                text: msg,
            });
        } finally {
            this.loading = false;
        }
    },
}));

// ---------------------------------------------------------------------------
// contactFab — floating action button (desktop), appears after scrolling
// ---------------------------------------------------------------------------
Alpine.data('contactFab', () => ({
    visible: false,
    open: false,

    init() {
        let ticking = false;
        const update = () => {
            this.visible = window.scrollY > 400;
            if (!this.visible) this.open = false;
            ticking = false;
        };
        window.addEventListener('scroll', () => {
            if (!ticking) { requestAnimationFrame(update); ticking = true; }
        }, { passive: true });
        update();
    },
}));

Alpine.start();

// ---------------------------------------------------------------------------
// GLightbox — lazy init, only on pages that contain [data-glightbox] elements
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    let lightbox = null;

    Promise.all([
        import('glightbox'),
        import('glightbox/dist/css/glightbox.min.css'),
    ]).then(([{ default: GLightbox }]) => {
        lightbox = GLightbox({ selector: '[data-glightbox]' });
    });

    // Fired by Alpine.js x-effect on /realizace after modal images are rendered
    window.addEventListener('glightbox:refresh', () => lightbox?.reload());

    // Blur the trigger link before GLightbox sets aria-hidden on <main>,
    // otherwise the browser warns: "Blocked aria-hidden on element … its descendant retained focus"
    document.addEventListener('click', (e) => {
        const trigger = e.target.closest('[data-glightbox]');
        if (trigger) trigger.blur();
    });
});

// ---------------------------------------------------------------------------
// Counter animation — called when a [data-counter] element enters viewport
// ---------------------------------------------------------------------------
function animateCounter(el) {
    const text = el.textContent.trim();
    // Match optional prefix (±), integer or decimal, rest as suffix (M+, +, %, ...)
    const match = text.match(/^([±~]?)([\d.]+)(.*)$/);
    if (!match) return;
    const [, prefix, numStr, suffix] = match;
    const target = parseFloat(numStr);
    if (isNaN(target) || target === 0) return;
    const isDecimal = numStr.includes('.');
    const duration = 1400;
    const start = performance.now();

    const tick = (now) => {
        const elapsed = Math.min((now - start) / duration, 1);
        const eased = 1 - Math.pow(1 - elapsed, 3); // easeOutCubic
        const current = eased * target;
        el.textContent = prefix + (isDecimal ? current.toFixed(1) : Math.round(current)) + suffix;
        if (elapsed < 1) requestAnimationFrame(tick);
    };
    requestAnimationFrame(tick);
}

// ---------------------------------------------------------------------------
// Auto-reveal — add data-reveal to key elements BEFORE the observer runs
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const targets = [
        { sel: '.section-eyebrow', dir: 'fade-up' },
        { sel: '.section-title',   dir: 'fade-up' },
        { sel: '.section-sub',     dir: 'fade-up' },
        { sel: '.benefit-card',    dir: 'fade-up' },
        { sel: '.review-card',     dir: 'fade-up' },
        { sel: '.step-item',       dir: 'fade-up' },
        { sel: '.project-card',    dir: 'fade-up' },
        { sel: '.faq-item',        dir: 'fade-up' },
        { sel: '.trust-bar__item-value', dir: 'fade-up' },
    ];
    targets.forEach(({ sel, dir }) => {
        document.querySelectorAll(sel).forEach((el, i) => {
            if (el.dataset.reveal || el.closest('[data-reveal-group]')) return;
            el.dataset.reveal = dir;
            el.style.transitionDelay = `${Math.min((i % 5) * 75, 300)}ms`;
        });
    });
});

// ---------------------------------------------------------------------------
// Scroll reveal — IntersectionObserver + auto-stagger for [data-reveal-group]
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    // Skip if user prefers reduced motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
            // Trigger any counter animations inside the revealed element
            entry.target.querySelectorAll('[data-counter]').forEach(animateCounter);
        });
    }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

    // Auto-stagger: direct children of [data-reveal-group] get data-reveal + staggered delay
    document.querySelectorAll('[data-reveal-group]').forEach(group => {
        Array.from(group.children).forEach((child, i) => {
            if (!child.dataset.reveal) child.dataset.reveal = 'fade-up';
            child.style.transitionDelay = `${Math.min(i * 80, 400)}ms`;
            observer.observe(child);
        });
    });

    // Individual [data-reveal] elements (not inside a group)
    document.querySelectorAll('[data-reveal]:not([data-reveal-group] > *)').forEach(el => {
        observer.observe(el);
    });
});

// ---------------------------------------------------------------------------
// Navbar: transparent → frosted glass + hide on scroll down
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    let lastScrollY = window.scrollY;
    let ticking = false;
    let isHidden = false;

    const update = () => {
        const y = window.scrollY;

        // Frosted glass after 80px
        navbar.classList.toggle('navbar--scrolled', y > 80);

        // Hide on scroll down (only after 200px), show on any scroll up
        if (y <= 200) {
            isHidden = false;
        } else if (y > lastScrollY) {
            isHidden = true;
        } else if (y < lastScrollY) {
            isHidden = false;
        }

        navbar.classList.toggle('navbar--hidden', isHidden);

        lastScrollY = y;
        ticking = false;
    };

    window.addEventListener('scroll', () => {
        if (!ticking) { requestAnimationFrame(update); ticking = true; }
    }, { passive: true });

    // Run once on load (e.g. if page is already scrolled)
    update();
});


// ---------------------------------------------------------------------------
// Card spotlight — mouse-tracking radial glow via CSS custom properties
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const SELECTORS = '.card, .benefit-card, .review-card, .project-card';

    function attachSpotlight(root) {
        root.querySelectorAll(SELECTORS).forEach(card => {
            if (card._spotlightAttached) return;
            card._spotlightAttached = true;
            card.addEventListener('mousemove', (e) => {
                const r = card.getBoundingClientRect();
                card.style.setProperty('--mouse-x', ((e.clientX - r.left) / r.width  * 100).toFixed(1) + '%');
                card.style.setProperty('--mouse-y', ((e.clientY - r.top)  / r.height * 100).toFixed(1) + '%');
            });
        });
    }

    attachSpotlight(document);

    // Re-attach when Alpine renders dynamic cards (e.g. modal, filter)
    document.addEventListener('alpine:initialized', () => attachSpotlight(document));
});

// ---------------------------------------------------------------------------
// Hero parallax — background image moves at 0.35× scroll speed
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    const heroBg = document.querySelector('.hero__bg');
    if (!heroBg || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    let ticking = false;
    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                heroBg.style.transform = `translateY(${window.scrollY * 0.32}px)`;
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });
});

// ===========================================================================
// CONVERSION FEATURES
// Všechny texty a časy upravuj zde — na jediném místě.
// ===========================================================================
const BARANA_CONFIG = {

    // ── 1. Social proof toast ───────────────────────────────────────────────
    // Zprávy se náhodně střídají. Stejná zpráva se nezopakuje 7 dní.
    // Přidej/odeber/uprav libovolnou zprávu v poli níže.
    toast: {
        delay:       1500,  // ms po odscrollování z hero sekce → toast se zobrazí
        duration:    4000,  // ms jak dlouho toast visí, než zmizí sám
        cooldownDays: 7,    // počet dní, než se může stejná zpráva zopakovat
        messages: [
            'Bezplatná konzultace — i pro projekty teprve ve fázi plánování.',
            'Odpovídáme na poptávky do 24 hodin v pracovní dny.',
            'Jihomoravský kraj — návrh, výroba i montáž na míru.',
            'Pergolu plánujete dopředu? Čím dřív nás kontaktujete, tím lépe.',
            'Záruka 5 let na hliníkovou konstrukci.',
            'Hliníkové konstrukce na míru — bez kompromisů v kvalitě.',
        ],
    },

    // ── 2. Scroll cue ────────────────────────────────────────────────────────
    // Šipka se zobrazí, pokud uživatel X ms neodscrolluje. Zmizí při scrollu.
    scrollCue: {
        delay: 6000,           // ms bez scrollu → šipka se zobrazí
        label: 'Procházet',    // text nad šipkou (nastav null pro skrytí)
    },

    // ── 3. Urgency ribbon ────────────────────────────────────────────────────
    // Text se mění automaticky podle aktuálního měsíce.
    // {year} = automaticky správný rok jarní sezóny.
    // Klíče jsou čísla měsíců (1 = leden … 12 = prosinec).
    urgency: {
        1:  'Plánujete na jaro? Jarní termíny se obsazují — rezervujte s předstihem.',
        2:  'Jaro {year} — volné termíny se rychle plní, poptejte nyní.',
        3:  'Jaro {year} — volné termíny se rychle plní, poptejte nyní.',
        4:  'Jaro {year} — zbývají poslední volné týdny montáže.',
        5:  'Jaro {year} — zbývají poslední volné týdny montáže.',
        6:  'Letní montáž do několika týdnů — poptejte ještě dnes.',
        7:  'Letní montáž do několika týdnů — poptejte ještě dnes.',
        8:  'Plánujete na příští sezónu? Zajistěte si termín jako první.',
        9:  'Plánujete na příští sezónu? Zajistěte si termín jako první.',
        10: 'Plánujete na příští sezónu? Zajistěte si termín jako první.',
        11: 'Plánujete na jaro? Jarní termíny se obsazují — rezervujte s předstihem.',
        12: 'Plánujete na jaro? Jarní termíny se obsazují — rezervujte s předstihem.',
    },
};

// ── 1. Social proof toast ────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const cfg = BARANA_CONFIG.toast;
    const STORAGE_KEY = 'barana_toast_seen';
    const isDev = document.querySelector('meta[name="app-env"]')?.content !== 'production';

    // Ve vývojovém prostředí ignorujeme cooldown — vždy vybereme náhodnou zprávu
    let pool = cfg.messages;
    if (!isDev) {
        let seen = [];
        try { seen = JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch {}
        const now = Date.now();
        seen = seen.filter(s => now - s.ts < cfg.cooldownDays * 86_400_000);
        const seenTexts = new Set(seen.map(s => s.msg));
        pool = cfg.messages.filter(m => !seenTexts.has(m));
        if (!pool.length) pool = cfg.messages; // reset — všechny viděny
    }

    const message = pool[Math.floor(Math.random() * pool.length)];

    const toast = document.createElement('div');
    toast.className = 'proof-toast';
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-live', 'polite');
    toast.innerHTML = `
        <div class="proof-toast__icon" aria-hidden="true">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" width="14" height="14">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd"/>
            </svg>
        </div>
        <p class="proof-toast__text">${message}</p>
        <button class="proof-toast__close" aria-label="Zavřít">&times;</button>
    `;
    document.body.appendChild(toast);

    let hideTimer;
    const hide = () => { toast.classList.remove('is-visible'); clearTimeout(hideTimer); };
    toast.querySelector('.proof-toast__close').addEventListener('click', hide);

    const showToast = () => {
        toast.classList.add('is-visible');
        hideTimer = setTimeout(hide, cfg.duration);
        if (!isDev) {
            let seen = [];
            try { seen = JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; } catch {}
            seen.push({ msg: message, ts: Date.now() });
            try { localStorage.setItem(STORAGE_KEY, JSON.stringify(seen)); } catch {}
        }
    };

    // Zobrazit až po odscrollování z hero sekce + delay
    // Na stránkách bez hero se spustí ihned po delay
    const hero = document.querySelector('.hero');
    if (hero) {
        let triggered = false;
        const observer = new IntersectionObserver(([entry]) => {
            if (!entry.isIntersecting && !triggered) {
                triggered = true;
                observer.disconnect();
                setTimeout(showToast, cfg.delay);
            }
        }, { threshold: 0 });
        observer.observe(hero);
    } else {
        setTimeout(showToast, cfg.delay);
    }
});

// ── 2. Scroll cue ────────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.querySelector('.hero:not(.hero--sm)');
    if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const cfg = BARANA_CONFIG.scrollCue;

    const cue = document.createElement('button');
    cue.className = 'scroll-cue';
    cue.setAttribute('aria-label', 'Scrollovat dolů');
    cue.innerHTML = `
        ${cfg.label ? `<span class="scroll-cue__label">${cfg.label}</span>` : ''}
        <svg class="scroll-cue__arrow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <polyline points="6 9 12 15 18 9"/>
        </svg>
    `;
    hero.appendChild(cue);

    cue.addEventListener('click', () => {
        const next = hero.nextElementSibling;
        if (next) next.scrollIntoView({ behavior: 'smooth' });
    });

    const timer = setTimeout(() => {
        if (window.scrollY < 80) cue.classList.add('is-visible');
    }, cfg.delay);

    window.addEventListener('scroll', () => {
        if (window.scrollY > 80) {
            cue.classList.remove('is-visible');
            clearTimeout(timer);
        }
    }, { passive: true });
});

// ── 3. Urgency ribbon ─────────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', () => {
    const hero = document.querySelector('.hero:not(.hero--sm)');
    if (!hero) return;

    const month = new Date().getMonth() + 1;           // 1–12
    const year  = new Date().getFullYear();
    const springYear = month >= 11 ? year + 1 : year;  // v listopadu/prosinci = příští rok

    const template = BARANA_CONFIG.urgency[month];
    if (!template) return;

    const ribbon = document.createElement('p');
    ribbon.className = 'hero__urgency';
    ribbon.textContent = template.replace('{year}', springYear);

    const ctas = hero.querySelector('.hero__ctas');
    if (ctas) ctas.after(ribbon);
});
