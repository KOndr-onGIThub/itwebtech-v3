import './bootstrap';
import './cookies';
import './analytics';
// OND-246 — hloubka. Sám se vypne, když na stránce není `.pd--depth`.
import './hloubka';
import Alpine from 'alpinejs';

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
// OND-256/8: hlášky chodí z blade (lang/*/contact.php), dřív byly natvrdo anglicky.
// OND-264: limity chodí z config/contact.php (`uploads`), aby se prohlížeč a
// server nemohly rozejít, a přibyla kontrola typu i velikosti jednoho souboru.
// Serverová 422 hláška se zobrazí ve stejném místě přes `file-drop:error`.
Alpine.data('fileDropZone', ({
    tooManyFiles = '',
    tooLarge = '',
    perFileTooLarge = '',
    badType = '',
    maxFiles = 5,
    maxFileMb = 10,
    maxTotalMb = 20,
    extensions = [],
} = {}) => ({
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

        // contactForm dispatches this when the server rejects the attachments
        this.$el.addEventListener('file-drop:error', (e) => {
            this.error = e.detail || '';
        });
    },

    validate(fileList) {
        if (fileList.length > maxFiles) {
            this.error = tooManyFiles;
            return false;
        }
        if (extensions.length) {
            const rejected = fileList.find((f) => {
                const ext = f.name.split('.').pop()?.toLowerCase() ?? '';
                return !extensions.includes(ext);
            });
            if (rejected) {
                this.error = badType;
                return false;
            }
        }
        if (fileList.some(f => f.size > maxFileMb * 1024 * 1024)) {
            this.error = perFileTooLarge;
            return false;
        }
        const totalSize = fileList.reduce((sum, f) => sum + f.size, 0);
        if (totalSize > maxTotalMb * 1024 * 1024) {
            this.error = tooLarge;
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
// Contact / inquiry form — async submit s inline chybami
// ---------------------------------------------------------------------------
// OND-256/1: dřív se chyby ukazovaly v anglickém SweetAlert2 modálu
// („Submission error" / „Close"). Teď stejný vzor jako inline formulář na
// homepage — česká hláška pod polem, kterého se týká. `genericError` je
// přeložený text pro pád, který nemá 422 payload (500, výpadek sítě).
Alpine.data('contactForm', ({ genericError = '' } = {}) => ({
    loading: false,
    submitted: false,
    errors: {},
    formError: '',

    // Chyba u pole zmizí, jakmile ho uživatel začne opravovat.
    clearError(field) {
        if (!this.errors[field]) return;
        const next = { ...this.errors };
        delete next[field];
        this.errors = next;
    },

    async submit() {
        const form = this.$refs.form;
        const data = new FormData(form);
        this.loading = true;
        this.errors = {};
        this.formError = '';

        try {
            await window.axios.post('/contact', data);

            // OND-137 P4 §6: analytics form_submit event (Jack §6) — dispatch
            // CustomEvent který analytics.js přemapuje na canonical `form_submit`.
            window.dispatchEvent(new CustomEvent('contact-form-submit-success'));

            // OND-136: in-DOM thank-you state replaces the form on success.
            this.submitted = true;
            form.reset();
            // Notify all file drop zones to reset their state
            form.querySelectorAll('[x-data]').forEach(el => {
                el.dispatchEvent(new CustomEvent('file-drop:reset'));
            });
            // Scroll the thanks block into view for visibility.
            this.$nextTick(() => {
                this.$root.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });

        } catch (err) {
            const fieldErrors = err.response?.data?.errors;

            if (fieldErrors) {
                // Laravel 422 — první hláška ke každému poli, česky z lang/*/validation.php.
                this.errors = Object.fromEntries(
                    Object.entries(fieldErrors).map(([field, messages]) => [
                        field,
                        Array.isArray(messages) ? messages[0] : messages,
                    ]),
                );

                // OND-264: chyby k přílohám (`attachment`, `attachment.0`, …) nemají
                // vlastní pole ve formuláři — patří do drop zóny, jinak by 422
                // skončila neviditelně a uživatel by klikal do prázdna.
                const attachmentError = Object.entries(this.errors)
                    .find(([field]) => field === 'attachment' || field.startsWith('attachment.'));

                if (attachmentError) {
                    form.querySelectorAll('[x-data]').forEach(el => {
                        el.dispatchEvent(new CustomEvent('file-drop:error', { detail: attachmentError[1] }));
                    });
                }

                this.$nextTick(() => this.focusFirstError());
            } else {
                // 500 / výpadek sítě — jedna souhrnná hláška nad tlačítkem.
                this.formError = genericError;
                this.$nextTick(() => {
                    this.$refs.formError?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            }
        } finally {
            this.loading = false;
        }
    },

    focusFirstError() {
        // Pořadí podle DOM, ne podle pořadí klíčů v odpovědi.
        const first = this.$refs.form.querySelector('[aria-invalid="true"]');
        if (!first) return;
        first.focus({ preventScroll: true });
        first.scrollIntoView({ behavior: 'smooth', block: 'center' });
    },
}));

// ---------------------------------------------------------------------------
// Consultation modal — video + Calendly CTA
// ---------------------------------------------------------------------------
Alpine.data('consultationModal', () => ({
    open: false,
    videoReady: false,
    playing: false,

    openModal() {
        this.open = true;
        this.videoReady = true;
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
        // Focus the dialog on next tick
        this.$nextTick(() => {
            const dialog = this.$el.querySelector('[role="dialog"]');
            if (dialog) dialog.focus({ preventScroll: true });
        });
    },

    closeModal() {
        this.open = false;
        this.playing = false;
        document.body.style.overflow = '';
        // Pause & reset video if present
        const video = this.$el.querySelector('video');
        if (video) { video.pause(); video.currentTime = 0; }
    },
}));

// ---------------------------------------------------------------------------
// Portfolio filter — client-side filter pro listing /projekty
// ---------------------------------------------------------------------------
Alpine.data('portfolioFilter', ({ target = 'portfolio-grid', categories = [], counts = {} } = {}) => ({
    active: 'all',
    target,
    categories,
    counts,
    visibleCount: counts.all ?? 0,

    init() {
        this.applyFilter();
    },

    setActive(category) {
        this.active = category;
        this.visibleCount = this.counts[category] ?? 0;
        this.applyFilter();
    },

    applyFilter() {
        const grid = document.getElementById(this.target);
        if (!grid) return;

        const cards = grid.querySelectorAll('[data-category]');
        cards.forEach(card => {
            const cat = card.dataset.category;
            const visible = this.active === 'all' || cat === this.active;
            card.dataset.filterHidden = visible ? 'false' : 'true';
        });
    },
}));

Alpine.start();

// ---------------------------------------------------------------------------
// GLightbox — lazy init, only on pages that contain [data-glightbox] elements
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    if (!document.querySelector('[data-glightbox]')) return;

    Promise.all([
        import('glightbox'),
        import('glightbox/dist/css/glightbox.min.css'),
    ]).then(([{ default: GLightbox }]) => {
        GLightbox({ selector: '[data-glightbox]' });
    });

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
// Video player — autoplay on scroll + full controls
// ---------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[data-video-player]').forEach(wrapper => {
        const video    = wrapper.querySelector('[data-video]');
        const overlay  = wrapper.querySelector('[data-vp-overlay]');
        const progress = wrapper.querySelector('[data-vp-progress]');
        const fill     = wrapper.querySelector('[data-vp-fill]');
        const thumb    = wrapper.querySelector('.vp-progress__thumb');
        const playBtn  = wrapper.querySelector('[data-vp-play]');
        const muteBtn  = wrapper.querySelector('[data-vp-mute]');
        const volSlider = wrapper.querySelector('[data-vp-vol]');
        const timeEl   = wrapper.querySelector('[data-vp-time]');
        if (!video) return;

        let userPaused = false;
        let hideTimer;

        // ── Helpers ────────────────────────────────────────────
        const fmt = s => {
            if (!isFinite(s)) return '0:00';
            const m = Math.floor(s / 60);
            return m + ':' + String(Math.floor(s % 60)).padStart(2, '0');
        };

        const setVolUI = () => {
            const pct = video.muted ? 0 : Math.round(video.volume * 100);
            volSlider.value = pct;
            volSlider.style.setProperty('--vol', pct + '%');
            muteBtn.classList.toggle('is-muted', video.muted || video.volume === 0);
        };

        const showControls = () => {
            overlay.classList.add('is-visible');
            clearTimeout(hideTimer);
            if (!video.paused) {
                hideTimer = setTimeout(() => overlay.classList.remove('is-visible'), 3000);
            }
        };

        // OND-123: cache progress bar šířku — bez cache každý timeupdate (≥4×/s)
        // četl progress.clientWidth, což trigovalo forced reflow uvnitř playbacku.
        let cachedBarW = progress.clientWidth - 24;
        const recomputeBarW = () => { cachedBarW = progress.clientWidth - 24; };
        window.addEventListener('resize', recomputeBarW, { passive: true });

        const updateProgress = () => {
            if (!video.duration) return;
            const pct = video.currentTime / video.duration;
            fill.style.width = (pct * 100) + '%';
            // thumb position = offset from left edge of progress bar (cached)
            thumb.style.left = (12 + pct * cachedBarW) + 'px';
            timeEl.textContent = fmt(video.currentTime) + ' / ' + fmt(video.duration);
        };

        // ── Autoplay on scroll ──────────────────────────────────
        const io = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting && !userPaused) {
                    video.play().catch(() => {});
                } else if (!e.isIntersecting) {
                    video.pause();
                }
            });
        }, { threshold: 0.25 });
        io.observe(video);

        // ── Play / Pause ────────────────────────────────────────
        const togglePlay = () => {
            if (video.paused) { video.play(); userPaused = false; }
            else              { video.pause(); userPaused = true; }
        };
        wrapper.addEventListener('click', e => {
            if (!e.target.closest('.vp-bar') && !e.target.closest('.vp-progress')) togglePlay();
        });
        playBtn.addEventListener('click', e => { e.stopPropagation(); togglePlay(); });

        video.addEventListener('play',  () => { playBtn.classList.add('is-playing'); showControls(); });
        video.addEventListener('pause', () => { playBtn.classList.remove('is-playing'); showControls(); });

        // ── Progress / Seek ─────────────────────────────────────
        video.addEventListener('timeupdate', updateProgress);
        video.addEventListener('loadedmetadata', updateProgress);

        let seeking = false;
        const seekTo = clientX => {
            const rect = progress.getBoundingClientRect();
            const pct  = Math.max(0, Math.min(1, (clientX - rect.left - 12) / (rect.width - 24)));
            video.currentTime = pct * video.duration;
        };
        progress.addEventListener('mousedown', e => { seeking = true; seekTo(e.clientX); showControls(); });
        progress.addEventListener('touchstart', e => { seeking = true; seekTo(e.touches[0].clientX); showControls(); }, { passive: true });
        document.addEventListener('mousemove',  e => { if (seeking) seekTo(e.clientX); });
        document.addEventListener('touchmove',  e => { if (seeking) seekTo(e.touches[0].clientX); }, { passive: true });
        document.addEventListener('mouseup',  () => { seeking = false; });
        document.addEventListener('touchend', () => { seeking = false; });

        // ── Volume ──────────────────────────────────────────────
        muteBtn.addEventListener('click', () => {
            video.muted = !video.muted;
            if (!video.muted && video.volume === 0) video.volume = 0.5;
            setVolUI();
        });
        volSlider.addEventListener('input', () => {
            video.volume = volSlider.value / 100;
            video.muted  = video.volume === 0;
            setVolUI();
        });
        setVolUI();

        // ── Show controls on interaction ────────────────────────
        wrapper.addEventListener('mousemove',  showControls);
        wrapper.addEventListener('touchstart', showControls, { passive: true });
    });
});

// Floating contact FAB — zobrazí se po 300px scrollu.
// OND-123: scroll handler wrapped in rAF — odděluje read scrollY od zápisu
// třídy, aby browser nebatchoval reflow uvnitř scroll eventu.
document.addEventListener('DOMContentLoaded', () => {
    const fab = document.getElementById('contact-fab');
    if (!fab) return;

    let fabTicking = false;
    const apply = () => {
        fab.classList.toggle('is-visible', window.scrollY > 300);
        fabTicking = false;
    };
    apply();
    window.addEventListener('scroll', () => {
        if (fabTicking) return;
        fabTicking = true;
        requestAnimationFrame(apply);
    }, { passive: true });
});

// Smart navbar: hide on scroll down, show on scroll up + glass effect after scroll.
// OND-123: scroll handler je rAF-throttled — read scrollY zůstává v handleru,
// ale class/style writes proběhnou v rAF tick. Eliminuje forced reflow
// při rychlém scrollování (PSI mobile audit, plán OND-99 §9.1).
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    let lastScrollY = window.scrollY;
    let navTicking = false;

    const apply = () => {
        const currentScrollY = window.scrollY;

        // OND-123 follow-up: výhradně classList.toggle (žádné inline style writes
        // ani reads). PSI forced reflow 1,2 s předtím — atribuce neukázala viník,
        // tohle uzavírá nejtypičtější write path během scroll/rAF cyklu.
        navbar.classList.toggle('is-scrolled', currentScrollY > 20);
        navbar.classList.toggle(
            'is-hidden',
            currentScrollY > 80 && currentScrollY > lastScrollY,
        );

        lastScrollY = currentScrollY;
        navTicking = false;
    };

    // Initial state
    apply();

    window.addEventListener('scroll', () => {
        if (navTicking) return;
        navTicking = true;
        requestAnimationFrame(apply);
    }, { passive: true });
});

// ---------------------------------------------------------------------------
// Reservanto widget — patch missing href on vendor-injected <a> (OND-123)
// ---------------------------------------------------------------------------
// Reservanto vendor skript injektuje <a class="reservanto-button …"> bez href,
// PSI „Odkazy nelze procházet" → SEO score 92 → fail (cíl ≥ 95).
//
// Vendor skript přidá vlastní click handler s preventDefault → uživatelský
// klik otevře modal, ne href. Cíl: dodat crawler-readable href + no-JS fallback.
// Direct URL přichází z data-direct-url na wrapperu (config/site.booking).
document.addEventListener('DOMContentLoaded', () => {
    const widgets = document.querySelectorAll('.reservanto-widget');
    if (!widgets.length) return;

    const patchAnchors = () => {
        widgets.forEach(widget => {
            const href = widget.dataset.directUrl;
            if (!href) return;
            widget.querySelectorAll('a:not([href])').forEach(a => {
                a.setAttribute('href', href);
                a.setAttribute('rel', 'noopener');
                a.setAttribute('target', '_blank');
            });
        });
    };

    // Vendor skript je `defer`, takže může injektovat <a> až po DOMContentLoaded.
    // MutationObserver na každém widget kontejneru zachytí přidání <a> i pozdější
    // re-rendery (např. když Reservanto resize-uje).
    const observer = new MutationObserver(patchAnchors);
    widgets.forEach(widget => {
        observer.observe(widget, { childList: true, subtree: true });
    });
    // První pass — kdyby už byly injektnuté (race po defer scriptu)
    patchAnchors();
});
