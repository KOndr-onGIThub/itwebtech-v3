# [Project Name] — Claude Code Instructions

## Language
- Respond to the user in **[LANGUAGE]**
- All code, comments, variable names, and file names in **English**

## Workflow
- NEVER run `npm run build` — `npm run dev` is always running in a separate terminal
- NEVER auto-commit without explicit request

## Project Stack
- Laravel 12 + PHP 8.4, Tailwind CSS v4 + Vite v7, Alpine.js v3
- WampServer local, domain: `[your-domain].local`
- Filament v5 at `/admin-cms`

## Lang / Translations
- One file per page: `lang/{cs,en,de}/home.php`, etc.
- Shared layout strings: `lang/{cs,en,de}/layout.php` (nav, footer, cta)
- Always create/update all three locales (cs + en + de) simultaneously

## CSS (app.css)
- Tailwind v4 — no `tailwind.config.js`, tokens in `@theme` and `:root`
- Component styles go in `@layer components` with BEM-style class names
- Do NOT use inline Tailwind utility classes for layout/spacing of page sections — use dedicated CSS classes
- Reusable patterns: `.section-wave--top/bottom`, `.section-wrapper`, `.container-site`, `.card`, `.btn`

## Blade Components
- Responsive images: always use `<x-responsive-image>` with correct `sizes` attribute
- Icons: `<x-icon.NAME class="w-4 h-4">` — base has aria-hidden + focusable=false built in
- Never use inline SVG in blade templates when an `<x-icon.*>` component exists

## Alpine.js
- Use Alpine for UI state (dropdowns, tabs, toggles)
- For CSS-only solutions (accordion height, chevron rotation), prefer `:class` + CSS over `x-show` + `x-transition`
- Smart navbar scroll behavior is handled by vanilla JS in `app.js` — do not duplicate in Alpine

## Adding a New Page
1. Add route to `routes/web.php` for all 3 locales
2. Add slugs to `config/slugs.php` for all 3 locales
3. Add method to `app/Http/Controllers/PageController.php`
4. Create `resources/views/pages/{page}.blade.php`
5. Create `lang/{cs,en,de}/{page}.php`
