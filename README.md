# my-starter — Laravel 12 Multilingual Starter

Osobní starter šablona pro rychlý start nových webových projektů.
Extrahováno z produkčního projektu MAKOplast (2025).

> **Varianta:** `my-starter-simple` — stejný stack, ale bez vícejazyčnosti (standardní Laravel routování)

---

## Stack

| Technologie | Verze | Poznámka |
|---|---|---|
| PHP | ^8.4 | |
| Laravel | 12 | |
| Tailwind CSS | v4 | bez `tailwind.config.js`, tokeny v `@theme` |
| Vite | v7 | |
| Alpine.js | v3 | |
| vite-imagetools | v9 | AVIF/WebP responzivní obrázky |
| Inter Variable | v5 | font |
| GLightbox | v3 | lazy-load lightbox |
| SweetAlert2 | v11 | formulářové dialogy |
| CMS | ❌ není | přidej Filament nebo Twill dle projektu |

---

## Vícejazyčnost

Starter je **připraven na cs / en / de** lokalizaci:

- URL struktura: `/{locale}/stranka` (např. `/cs/`, `/en/`, `/de/`)
- `SetLocale` middleware nastavuje locale z URL
- `lroute('home')` helper generuje URL pro aktuální locale
- `config/slugs.php` — mapa lokalizovaných slugů
- `lang/{cs,en,de}/` — překladové soubory (jeden soubor na stránku)
- hreflang tagy v layoutu

Pokud projekt vícejazyčnost nepotřebuje → použij `my-starter-simple`.

---

## Co je připraveno

- Fixní navbar s Alpine.js mobile drawerem (slide-in z pravé strany)
- Smart navbar — skryje se při scrollu dolů, ukáže při scrollu nahoru (vanilla JS)
- Jazykový přepínač v navbaru (cs/en/de)
- 61 SVG ikon jako Blade komponenty (`<x-icon.arrow-right />`)
- Responzivní obrázky (`<x-responsive-image>`) — automatický AVIF/WebP srcset
- File drop zone komponenta (`<x-form.file-drop />`) — drag & drop, validace
- Kontaktní formulář s async submitem (Alpine + axios + SweetAlert2)
- Scroll reveal animace (`data-reveal`, `data-reveal-group`)
- Counter animace (`data-counter`)
- CSS design systém — tokeny v `@theme` a `:root`, BEM komponenty

---

## Instalace nového projektu

### 1. Zkopíruj starter

```bash
cp -r c:/wamp64/www/my-starter c:/wamp64/www/nazev-projektu
cd c:/wamp64/www/nazev-projektu
```

### 2. PHP závislosti

```bash
del composer.lock
composer install
```

### 3. Prostředí

```bash
cp .env.example .env
php artisan key:generate
```

Uprav `.env`:
```
APP_NAME="Název projektu"
APP_URL=http://nazev-projektu.local
APP_LOCALE=cs
DB_DATABASE=nazev_projektu
```

### 4. Databáze

```bash
# SQLite (rychlý start):
php artisan migrate

# MySQL (produkce):
# nejdřív vytvoř DB, uprav .env, pak:
php artisan migrate
```

### 5. CMS (volitelné)

```bash
# Filament:
composer require filament/filament
php artisan filament:install --panels
php artisan make:filament-user

# Twill:
composer require area17/twill
php artisan twill:install
```

### 6. JS závislosti

```bash
npm install
```

### 7. WampServer — virtual host

Uprav `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1  nazev-projektu.local
```

Uprav `C:\wamp64\bin\apache\apache2.4.59\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerName nazev-projektu.local
    DocumentRoot "c:/wamp64/www/nazev-projektu/public"
    <Directory "c:/wamp64/www/nazev-projektu/public">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Restart Apache v WampServeru.

### 8. Dev server

```bash
npm run dev
```

Web běží na `http://nazev-projektu.local`

### 9. Git

```bash
git init
git add .
git commit -m "Initial commit from my-starter"
```

---

## Přidání nové stránky

1. Přidej slugy do `config/slugs.php` pro všechny 3 locales
2. Přidej route do `routes/web.php` (do `foreach` bloku)
3. Přidej metodu do `app/Http/Controllers/PageController.php`
4. Vytvoř `resources/views/pages/{stranka}.blade.php`
5. Vytvoř `lang/{cs,en,de}/{stranka}.php`

---

## Ikony

Použití:

```blade
<x-icon.arrow-right class="w-4 h-4" />
<x-icon.check class="w-5 h-5 text-green-600" />
```

Dostupné ikony: viz `resources/views/components/icon/`

**Přidání nové ikony:**
1. Vezmi `<path>` z [heroicons.com](https://heroicons.com/) nebo [lucide.dev](https://lucide.dev/) nebo [phosphoricons.com](https://phosphoricons.com/)
2. Vytvoř soubor `resources/views/components/icon/{nazev}.blade.php` — jen samotný `<path>` element (base SVG wrapper je v `svg-icon.blade.php`)

---

## Obrázky

### Malé / jednoduché obrázky
Patří přímo do `public/assets/`:

```blade
<img src="{{ asset('assets/img/brands/logo.svg') }}" alt="..." />
```

### Obrázky optimalizované přes imagetools
Patří do `resources/img/` — Vite je automaticky převede na AVIF/WebP srcset.

> Pokud bude obrázek v lightboxu, ulož ho ve **2× nebo 3× větší** rozlišení než je zobrazená velikost.

Použití VS Code snippetu `x-responsive`:

```blade
<x-responsive-image
    path="hero/photo.jpg"
    alt="Popis obrázku"
    sizes="(min-width: 1024px) 50vw, 100vw"
    width=""
    height=""
    loading="lazy"
    fetchpriority=""
    classPicture=""
    classImg=""
    decoding=""
    data-cue=""
    style=""
/>
```

Použití VS Code snippetu `x-responsive-lightbox` (přidá lightbox parametry):

```blade
<x-responsive-image
    path="photos/img-name@2x.jpg"
    alt="Popis obrázku"
    sizes="(min-width: 1024px) 50vw, 100vw"
    loading="lazy"
    lightboxTitle="Titulek v lightboxu"
    lightboxGallery="nazev-galerie"
/>
```

Lightbox se aktivuje přítomností parametru `lightboxTitle`. Parametr `sizes` nastavuj podle skutečné zobrazené šířky v různých breakpointech — má vliv na výběr správné velikosti obrázku prohlížečem.

> **Pozor:** Lightbox nefunguje v `npm run dev` (Vite virtual URLs). Pro otestování lightboxu lokálně spusť `npm run build`.

---

## Údržba starteru

Pokud v starteru něco změníš (nová ikona, CSS komponenta, JS utilita...), poznamenej to sem:

### Changelog

| Datum | Změna |
|---|---|
| 2025-03 | Vznik starteru (extrakce z MAKOplast) |
| | |
