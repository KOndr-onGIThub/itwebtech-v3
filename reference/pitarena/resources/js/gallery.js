/**
 * LightGallery v2 — vanilla JS inicializace
 * Nahrazuje jQuery LightGallery plugin
 *
 * Použití v šabloně:
 *   <div class="lg-container" id="lightgallery">
 *     <a href="/path/big.jpg" class="lg-item">
 *       <img src="/path/thumb.jpg" />
 *     </a>
 *   </div>
 *
 * Pro Swiper galerie s lightboxem: prvky s class .lg-item v .swiper-slide
 */
import lightGallery from 'lightgallery';
import lgZoom from 'lightgallery/plugins/zoom';
import lgThumbnail from 'lightgallery/plugins/thumbnail';
import 'lightgallery/css/lightgallery.css';
import 'lightgallery/css/lg-zoom.css';
import 'lightgallery/css/lg-thumbnail.css';

document.addEventListener('DOMContentLoaded', () => {

    // Hlavní galerie — .lg-container nebo [data-lg-gallery]
    document.querySelectorAll('.lg-container, [data-lg-gallery]').forEach(el => {
        lightGallery(el, {
            plugins:        [lgZoom, lgThumbnail],
            selector:       '.lg-item',
            speed:          500,
            thumbnail:      true,
            animateThumb:   true,
            showZoomInOutIcons: true,
            actualSize:     false,
            loop:           true,
        });
    });

    // Swiper + lightgallery (Kudy k nám, moto galerie)
    document.querySelectorAll('.distance-gallery-item, .moto-gallery-item').forEach(link => {
        link.addEventListener('click', e => e.preventDefault());
    });

    const distanceSwiper = document.querySelector('.distance-swiper');
    if (distanceSwiper) {
        lightGallery(distanceSwiper, {
            plugins:   [lgZoom],
            selector:  '.distance-gallery-item',
            speed:     500,
        });
    }

    // Moto product image gallery
    document.querySelectorAll('.moto-gallery-swiper').forEach(el => {
        lightGallery(el, {
            plugins:   [lgZoom, lgThumbnail],
            selector:  '.moto-gallery-item',
            speed:     500,
            thumbnail: true,
        });
    });

});
