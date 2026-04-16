import './bootstrap';
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
            this.error = 'Maximum 5 files at once.';
            return false;
        }
        const totalSize = fileList.reduce((sum, f) => sum + f.size, 0);
        if (totalSize > 20 * 1024 * 1024) {
            this.error = 'Total size must not exceed 20 MB.';
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

        try {
            await window.axios.post('/contact', data);

            await Swal.fire({
                icon: 'success',
                title: 'Sent!',
                text: 'Your message has been received. We will get back to you within 24 business hours.',
                confirmButtonColor: '#1B2E5A',
                confirmButtonText: 'Close',
            });

            form.reset();
            // Notify all file drop zones to reset their state
            form.querySelectorAll('[x-data]').forEach(el => {
                el.dispatchEvent(new CustomEvent('file-drop:reset'));
            });

        } catch (err) {
            const errors = err.response?.data?.errors;
            let msg = 'The form could not be submitted. Please try again.';
            if (errors) {
                msg = Object.values(errors).flat().join('\n');
            } else if (err.response?.data?.message) {
                msg = err.response.data.message;
            }

            Swal.fire({
                icon: 'error',
                title: 'Submission error',
                text: msg,
                confirmButtonColor: '#1B2E5A',
                confirmButtonText: 'Close',
            });
        } finally {
            this.loading = false;
        }
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

        const updateProgress = () => {
            if (!video.duration) return;
            const pct = video.currentTime / video.duration;
            fill.style.width = (pct * 100) + '%';
            // thumb position = offset from left edge of progress bar
            const barW = progress.clientWidth - 24; // minus padding 2×12px
            thumb.style.left = (12 + pct * barW) + 'px';
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

// Floating contact FAB — zobrazí se po 300px scrollu
document.addEventListener('DOMContentLoaded', () => {
    const fab = document.getElementById('contact-fab');
    if (!fab) return;

    const toggle = () => fab.classList.toggle('is-visible', window.scrollY > 300);
    toggle();
    window.addEventListener('scroll', toggle, { passive: true });
});

// Smart navbar: hide on scroll down, show on scroll up + glass effect after scroll
document.addEventListener('DOMContentLoaded', () => {
    const navbar = document.querySelector('.navbar');
    if (!navbar) return;

    let lastScrollY = window.scrollY;

    const update = () => {
        const currentScrollY = window.scrollY;

        // Glass/border effect after 20px scroll
        navbar.classList.toggle('is-scrolled', currentScrollY > 20);

        // Hide on scroll down, show on scroll up (only after passing navbar height)
        if (currentScrollY > 80) {
            if (currentScrollY > lastScrollY) {
                navbar.style.transform = 'translateY(-100%)';
            } else {
                navbar.style.transform = '';
            }
        } else {
            navbar.style.transform = '';
        }

        lastScrollY = currentScrollY;
    };

    // Initial state
    update();

    window.addEventListener('scroll', update, { passive: true });
});
