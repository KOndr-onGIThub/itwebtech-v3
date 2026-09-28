/**
 * OND-438 — analytika a přednačtení (Speculation Rules prerender)
 * ─────────────────────────────────────────────────────────────────
 * Přednačtená stránka běží celá včetně skriptů dřív, než ji člověk otevře —
 * a často ji neotevře nikdy. Návštěva ani události se z ní proto nesmí
 * odeslat, dokud se opravdu nezobrazí.
 *
 * `whenActivated(fn)` pustí `fn` hned, když stránka přednačtená není
 * (běžné načtení, Firefox, Safari), jinak až na `prerenderingchange`.
 * Nepřednačtená stránka se tím nijak nezdrží.
 */
export function whenActivated(fn) {
    if (document.prerendering) {
        document.addEventListener('prerenderingchange', fn, { once: true });
    } else {
        fn();
    }
}
