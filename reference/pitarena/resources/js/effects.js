/**
 * PitArena Premium Effects
 * - Card mouse spotlight
 * - Counter animations
 * - Social proof toast
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── Card Mouse Spotlight ────────────────────────────────────
    // Nastaví CSS proměnné --mouse-x a --mouse-y na každé .card-dark kartě
    const cards = document.querySelectorAll('.card-dark');
    cards.forEach(card => {
        card.addEventListener('mousemove', (e) => {
            const rect = card.getBoundingClientRect();
            const x = ((e.clientX - rect.left) / rect.width)  * 100;
            const y = ((e.clientY - rect.top)  / rect.height) * 100;
            card.style.setProperty('--mouse-x', `${x}%`);
            card.style.setProperty('--mouse-y', `${y}%`);
        });
    });

    // ── Counter Animations ──────────────────────────────────────
    // Použití: <span data-counter>1 234</span> nebo <span data-counter>98,5 %</span>
    function animateCounter(el) {
        const raw = el.textContent.trim();
        const match = raw.match(/^([^\d]*)(\d[\d\s.]*)(.*)$/);
        if (!match) return;
        const [, prefix, numRaw, suffix] = match;
        const num = parseFloat(numRaw.replace(/\s/g, '').replace(',', '.'));
        if (isNaN(num) || num === 0) return;
        const isDecimal = numRaw.includes(',') || (numRaw.includes('.') && !numRaw.endsWith('.'));
        const duration = 1400;
        const start = performance.now();

        const tick = (now) => {
            const elapsed = Math.min((now - start) / duration, 1);
            const eased   = 1 - Math.pow(1 - elapsed, 3);
            const current = eased * num;
            const formatted = isDecimal
                ? current.toFixed(1).replace('.', ',')
                : Math.round(current).toLocaleString('cs-CZ');
            el.textContent = prefix + formatted + suffix;
            if (elapsed < 1) requestAnimationFrame(tick);
        };
        requestAnimationFrame(tick);
    }

    const counterObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            animateCounter(entry.target);
            counterObserver.unobserve(entry.target);
        });
    }, { threshold: 0.5 });

    document.querySelectorAll('[data-counter]').forEach(el => counterObserver.observe(el));

    // ── Social Proof Toast ──────────────────────────────────────
    // Zobrazí toast po 2.5s, max 1× za 4 hodiny (localStorage)
    const toastEl = document.getElementById('proof-toast');
    if (toastEl) {
        const TOAST_KEY = 'proof_toast_last';
        const FOUR_HOURS = 4 * 60 * 60 * 1000;
        const lastShown = parseInt(localStorage.getItem(TOAST_KEY) || '0', 10);
        const shouldShow = Date.now() - lastShown > FOUR_HOURS;

        if (shouldShow) {
            setTimeout(() => {
                toastEl.classList.add('is-visible');
                localStorage.setItem(TOAST_KEY, String(Date.now()));

                // Auto-hide po 7 sekundách
                setTimeout(() => toastEl.classList.remove('is-visible'), 7000);
            }, 2500);
        }

        // Manuální zavření vždy funguje
        const closeBtn = toastEl.querySelector('[data-toast-close]');
        if (closeBtn) closeBtn.addEventListener('click', () => toastEl.classList.remove('is-visible'));
    }

});
