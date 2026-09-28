import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { imagetools } from 'vite-imagetools';
import path from 'node:path';
import { fileURLToPath } from 'node:url';

/**
 * OND-449 (B-06): mapa „zdrojový obrázek → varianty z buildu“ do
 * `public/build/image-variants.json`. PHP (`responsive_image_srcsets()`)
 * podle ní vykreslí `<picture>` serverem i u obrázků se stejným jménem
 * v různých složkách (`projects/<slug>/hero-1.png`) — glob podle basename
 * je tam nejednoznačný a obrázek pak dosazoval až Alpine.
 *
 * vite-imagetools vrací z `load()` pole `"__VITE_ASSET__<ref>__"` ve stejném
 * pořadí jako query v resources/js/app.js; konečné jméno souboru zná až
 * `generateBundle` (`this.getFileName(ref)`).
 */
function imageVariantsManifest() {
    const imgRoot = fileURLToPath(new URL('./resources/img', import.meta.url));
    const refs = new Map();

    return {
        name: 'ondraweb-image-variants',
        apply: 'build',
        enforce: 'post',
        transform(code, id) {
            const [file, query] = id.split('?');
            if (!query || !query.includes('format=') || !file.startsWith(imgRoot + path.sep)) return null;
            const found = [...code.matchAll(/__VITE_ASSET__([\w$]+)__/g)].map((m) => m[1]);
            if (found.length) refs.set(path.relative(imgRoot, file).split(path.sep).join('/'), found);
            return null;
        },
        generateBundle() {
            const map = {};
            for (const [src, list] of [...refs].sort(([a], [b]) => a.localeCompare(b))) {
                map[src] = list.map((ref) => this.getFileName(ref));
            }
            this.emitFile({ type: 'asset', fileName: 'image-variants.json', source: JSON.stringify(map) });
        },
    };
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
        imagetools(),
        imageVariantsManifest(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: { host: 'localhost' },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
