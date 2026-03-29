# Plán: itwebtech.cz → nový Laravel 12 web

## Kontext

Nahrazujeme stávající web **itwebtech.cz** moderním Laravel 12 projektem s multijazyčností (cs/en/de), perfektním SEO a čistou strukturou bez CMS závislostí.

**Stack:** Laravel 12 + Tailwind v4 + Alpine.js v3 + Vite 7
**Lokalizace:** cs (default, bez prefixu) / en (/en/) / de (/de/)
**Reference materiály:** `/workspace/reference/itwebtech` + `/workspace/reference/agents` — **NESMAZAT** (až na úplném konci projektu, agents nikdy)

---

## ✅ FÁZE 1 — Migrace obsahu a struktury (DOKONČENO)

### Strukturální soubory
- [x] `config/slugs.php` — URL slugy pro cs/en/de (6 stránek)
- [x] `routes/web.php` — 34 routes + legacy redirecty (/price→/cenik atd.) + POST /contact
- [x] `app/Http/Controllers/PageController.php` — home, contact, price, privacy, projects, project, blog, article
- [x] `app/Http/Controllers/ContactController.php` — validace + stub (TODO: odeslání emailu)

### Lang soubory (cs/en/de)
- [x] `layout.php` — navigace, footer, prefooter, GDPR
- [x] `home.php` — hero, services, commitment, about, advantages, steps, projects, testimonials, price, CTA
- [x] `contact.php` — formulář, otevírací doby, texty
- [x] `price.php` — ceník one-off, dlouhodobý, balíčky
- [x] `privacy.php` — GDPR text
- [x] `projects.php` — projekty, filtry, why-me sekce
- [x] `blog.php` — blog, sidebar ad
- [x] `testimonials.php` — 16 recenzí s kompletními texty, fotkami, platformami

### Blade views
- [x] `pages/home.blade.php` — všechny sekce + `<x-responsive-image>`
- [x] `pages/contact.blade.php` — kontaktní formulář (Alpine + axios)
- [x] `pages/price.blade.php` — tabulky ceníků
- [x] `pages/privacy.blade.php` — GDPR text
- [x] `pages/projects.blade.php` — filtry + why-me (DB placeholder)
- [x] `pages/blog.blade.php` — seznam článků (DB placeholder) + sidebar

### Komponenty a layout
- [x] `navbar.blade.php` — 5 položek nav, cs/en/de switcher v draweru
- [x] `layouts/app.blade.php` — footer linky, GDPR odkaz, JSON-LD Organization schema

### Obrázky
- [x] `public/img/logo/` — správné logo (itwebtech_400x100_transparent.svg + varianty)
- [x] `public/img/brands/` — 13 brand log klientů
- [x] `public/img/testimonials/` — platform ikony (Google, Facebook, Firmy.cz, MAKOplast)
- [x] `public/img/og/og-default.jpg` — OG placeholder (**TODO: nahradit 1200×630 OG obrázkem**)
- [x] `resources/img/hero/itwebtech_3.webp` — hero sekce
- [x] `resources/img/about/ondrej_kriska.jpg` — profilová fotka
- [x] `resources/img/testimonials/` — 16 fotek klientů

### Agenti (~/.claude/agents/)
- [x] Přeneseno 9 stávajících agentů (content-writer, ux-designer, graphic-design, ...)
- [x] Vytvořeno 3 nových: `laravel-developer`, `content-migrator`, `seo-auditor`

---

## ✅ FÁZE 2 — Design (Tailwind CSS styling) — DOKONČENO

Paleta: tmavá (#0F172A) + zlatá (#F59E0B), styl: moderní minimalistický (Stripe/Linear/Vercel inspirace).
Reference: BARANA (glassmorphism, shimmer, cubic-bezier easing) + pitarena (noise texture, tight letter-spacing, sweep effects).

### Implementované komponenty
- [x] `resources/css/app.css` — kompletní design systém (~700 řádků): @theme tokeny, BEM komponenty
- [x] Typografie — Inter Variable, clamp() fluid sizes, letter-spacing -0.03em až -0.04em
- [x] Tlačítka — btn-primary (shimmer animace, pulse ring) + btn-secondary
- [x] Navbar — glass effect (is-scrolled), smart hide/show na scroll
- [x] Drawer — mobilní menu s overlay
- [x] Hero sekce — dark background, gradient overlay, gradient text `<em>`
- [x] Services grid — karty s SVG ikonami, cubic-bezier hover
- [x] Commitment steps, About layout, Advantages grid, Steps list
- [x] Brand logos grid, Testimonials grid (hvězdičky, rating)
- [x] Price teaser, CTA sekce (tmavá, noise texture)
- [x] Footer prefooter + footer bar
- [x] Contact — 2-sloupcový layout, styled form (gold focus ring)
- [x] Price cards — popular badge, features, CTA
- [x] Projects — filter tabs, why-grid
- [x] Blog — 2-sloupcový layout, sidebar-ad (tmavý)
- [x] Privacy — prose-content typografie
- [x] Page hero — tmavý heading blok pro vnitřní stránky
- [x] Floating contact FAB — zlatý, pulse ring animace, zobrazí se po 300px scrollu
- [x] Scroll reveal — translateY(36px) scale(0.985), cubic-bezier(0.22, 1, 0.36, 1)
- [x] Noise texture overlay — section-cta, page-hero
- [x] Lang klíče doplněny — reviews_label, cta.heading, price.subheading, contact.hours_label/sending

### Zbývá po designu
- [ ] `sizes` parametry ve všech `<x-responsive-image>` (aktuálně: odhadnuté hodnoty)
- [ ] OG obrázek 1200×630 (`public/img/og/og-default.jpg`)
- [ ] Favicons (nahradit placeholder v `public/`)

---

## 🔲 FÁZE 3 — Databáze: Projekty a Blog

Cíl: DB modely pro dynamický obsah. Použít agenta `laravel-developer`.

### Projekty (Portfolio)
- [ ] Eloquent model `Project` + migrace
- [ ] Sloupce: title, slug, description, category (web-site/web-app/other), client, date, price, images
- [ ] Multijazyčnost (translatable fields nebo JSON)
- [ ] Seed dat z původního webu (ručně nebo import)
- [ ] `PageController@projects` — načíst z DB
- [ ] `PageController@project` — detail projektu (nebo CMS)
- [ ] Možnost: Filament admin panel pro správu projektů

### Blog / Články
- [ ] Eloquent model `Article` + migrace
- [ ] Sloupce: title, slug, content, excerpt, published_at, meta
- [ ] Multijazyčnost
- [ ] Seed dat ze starého webu (Twill export)
- [ ] `PageController@blog` — seznam
- [ ] `PageController@article` — detail článku
- [ ] Možnost: Filament admin pro správu článků

---

## 🔲 FÁZE 4 — SEO finalizace

Cíl: Perfektní technické SEO. Použít agenta `seo-auditor`.

- [ ] Sitemap — nakonfigurovat `spatie/laravel-sitemap` (všechny stránky × 3 jazyky)
- [ ] Robots.txt — zkontrolovat a doplnit
- [ ] JSON-LD schema — rozšířit o `WebSite`, `BreadcrumbList`, `Person`, `FAQPage`
- [ ] hreflang audit — ověřit pro každou stránku a dynamické URL
- [ ] Core Web Vitals — LCP (hero img fetchpriority), CLS (reserved spaces), INP
- [ ] Meta tagy audit — title 50-60 znaků, description 120-155 znaků, unikátní
- [ ] Interní prolinkování — propojit stránky v textech
- [ ] Canonical URL — ověřit že je správně na každé stránce
- [ ] OG obrázek — finální verze 1200×630

---

## 🔲 FÁZE 5 — Kontaktní formulář (email)

- [ ] Nakonfigurovat `config/mail.php` + `.env` (SMTP/Mailgun/Resend)
- [ ] Vytvořit `App\Mail\ContactMessage` Mailable
- [ ] `ContactController@send` — odeslat email + potvrdit uživateli
- [ ] Přidat `contact_address` do `config/services.php`
- [ ] Otestovat odesílání

---

## 🔲 FÁZE 6 — Produkční spuštění

- [ ] Hosting + doména nastavena
- [ ] `.env` production hodnoty
- [ ] `npm run build` — Vite build pro produkci
- [ ] `php artisan optimize` — cache routes, views, config
- [ ] SSL certifikát
- [ ] Google Search Console — přidat nové URL, odeslat sitemap
- [ ] Nastavit 301 redirecty ze starých URL (přes .htaccess nebo hosting)
- [ ] Sledovat GSC po nasazení (změny indexace)
- [ ] Reservanto widget — vložit booking script
- [ ] Google Analytics / GTM

---

## 🔲 Smazání reference složek

**Provést POUZE na úplném konci projektu (po nasazení do produkce):**
- [ ] Smazat `/workspace/reference/itwebtech/`
- [ ] Smazat `/workspace/reference/agents/` — **agenti byli přesunuty do globální WSL složky** `~/.claude/agents/`, složka je prázdná

---

## Přehled souborů projektu

| Soubor / složka | Stav |
|---|---|
| `config/slugs.php` | ✅ hotovo |
| `routes/web.php` | ✅ hotovo |
| `app/Http/Controllers/PageController.php` | ✅ hotovo |
| `app/Http/Controllers/ContactController.php` | ✅ stub (TODO: mail) |
| `lang/{cs,en,de}/*.php` | ✅ hotovo (home, layout, contact, price, privacy, projects, blog, testimonials) |
| `resources/views/pages/*.blade.php` | ✅ hotovo (skeleton bez designu) |
| `resources/views/components/layout/navbar.blade.php` | ✅ hotovo |
| `resources/views/layouts/app.blade.php` | ✅ hotovo + JSON-LD |
| `public/img/logo/` | ✅ hotovo |
| `public/img/brands/` | ✅ hotovo |
| `public/img/testimonials/` | ✅ hotovo |
| `resources/img/hero/` | ✅ hotovo |
| `resources/img/about/` | ✅ hotovo |
| `resources/img/testimonials/` | ✅ hotovo |
| `public/img/og/og-default.jpg` | ⚠️ placeholder — nahradit |
| Tailwind design | 🔲 fáze 2 |
| DB modely (Project, Article) | 🔲 fáze 3 |
| Email (ContactController) | 🔲 fáze 5 |
| Sitemap + SEO audit | 🔲 fáze 4 |
