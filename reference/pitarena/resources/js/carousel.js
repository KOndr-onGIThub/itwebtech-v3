/**
 * Swiper auto-inicializace
 * Nahrazuje Owl Carousel jQuery plugin
 *
 * Atributy na .swiper elementu:
 *   data-autoplay="4000"          — ms, nebo "false"
 *   data-loop="true"
 *   data-nav="true"               — zobrazit šipky
 *   data-slides-per-view-sm="2"   — breakpoint 640
 *   data-slides-per-view-md="3"   — breakpoint 768
 *   data-slides-per-view-lg="4"   — breakpoint 1024
 *   data-slides-per-view-xl="5"   — breakpoint 1280
 *   data-slides-per-view-xl="8"   — breakpoint 1536
 *   data-space-between="24"       — mezery (default 24)
 *   data-centered="true"          — centeredSlides
 */
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, A11y } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';

/**
 * Parsuje data atribut na int, s fallbackem
 */
function parseIntAttr(el, attr, fallback) {
    const val = el.dataset[attr];
    if (val === undefined || val === null) return fallback;
    const parsed = parseFloat(val);
    return isNaN(parsed) ? fallback : parsed;
}

function parseBoolAttr(el, attr, fallback = false) {
    const val = el.dataset[attr];
    if (val === undefined || val === null) return fallback;
    return val === 'true' || val === '1';
}

/**
 * Inicializuje jeden Swiper element
 */
function initSwiper(el) {
    const autoplayMs  = parseIntAttr(el, 'autoplay', 0);
    const loop        = parseBoolAttr(el, 'loop', false);
    const showNav     = parseBoolAttr(el, 'nav', false);
    const spaceBetween = parseIntAttr(el, 'spaceBetween', 24);
    const centered    = parseBoolAttr(el, 'centered', false);

    // Výchozí počet slidů = 1
    const spvBase = parseIntAttr(el, 'slidesPerView', 1);

    const breakpoints = {};

    const sm = parseIntAttr(el, 'slidesPerViewSm', 0);
    const md = parseIntAttr(el, 'slidesPerViewMd', 0);
    const lg = parseIntAttr(el, 'slidesPerViewLg', 0);
    const xl = parseIntAttr(el, 'slidesPerViewXl', 0);
    const xxl = parseIntAttr(el, 'slidesPerViewXxl', 0);

    if (sm) breakpoints[640]  = { slidesPerView: sm };
    if (md) breakpoints[768]  = { slidesPerView: md };
    if (lg) breakpoints[1024] = { slidesPerView: lg };
    if (xl) breakpoints[1280] = { slidesPerView: xl };
    if (xxl) breakpoints[1536] = { slidesPerView: xxl };

    const config = {
        modules: [Navigation, Pagination, Autoplay, A11y],
        slidesPerView: spvBase,
        spaceBetween,
        loop,
        centeredSlides: centered,
        a11y: { enabled: true },
    };

    if (autoplayMs > 0) {
        config.autoplay = { delay: autoplayMs, disableOnInteraction: false };
    }

    const nextEl = el.querySelector('.swiper-button-next') || el.parentElement?.querySelector('.swiper-button-next');
    const prevEl = el.querySelector('.swiper-button-prev') || el.parentElement?.querySelector('.swiper-button-prev');
    if (showNav || nextEl) {
        config.navigation = { nextEl: nextEl || '.swiper-button-next', prevEl: prevEl || '.swiper-button-prev' };
    }

    if (el.querySelector('.swiper-pagination')) {
        config.pagination = {
            el: el.querySelector('.swiper-pagination') || '.swiper-pagination',
            clickable: true,
        };
    }

    if (Object.keys(breakpoints).length > 0) {
        config.breakpoints = breakpoints;
    }

    return new Swiper(el, config);
}

/**
 * Inicializuje všechny .swiper elementy na stránce
 */
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.swiper').forEach(el => {
        initSwiper(el);
    });
});
