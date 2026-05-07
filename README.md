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

Pokud projekt vícejazyčnost nepotřebuje → použij `my-starter-simple-lang`.

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

### 1. Klonuj starter

> **Důležité:** Projekt klonuj do **WSL2 Linux filesystému**, ne na Windows disk (`C:/`).
> Projekty na Windows disku jsou v Dockeru 5–20× pomalejší kvůli cross-OS filesystem overhead.

Otevři **WSL2 terminál** (Ubuntu nebo jiná distribuce):

```bash
mkdir -p ~/projects && cd ~/projects
git clone https://github.com/KOndr-onGIThub/my-starter.git nazev-projektu
cd nazev-projektu
```

> **Hned poté odstraň remote** — jinak bys mohl omylem pushovat změny projektu zpět do starteru:

```bash
git remote remove origin
```

Otevři projekt ve VS Code přímo z WSL terminálu:

```bash
code .
```

### 1.1. Git — napojení na GitHub (volitelné)

Pokud chceš projekt zálohovat nebo deployovat přes GitHub, nejjednodušší je GitHub CLI:

```bash
gh repo create nazev-projektu --private --source=. --push
```

Jedním příkazem vytvoří repo, nastaví remote i pushne. Bez toho funguje git normálně lokálně.

---

## Způsob A — Dev Container (VS Code + Docker Desktop)

> Izolované prostředí v Dockeru. Neinstaluje nic na hostitelský systém.
> Vyžaduje: Docker Desktop + VS Code extension `ms-vscode-remote.remote-containers`.

### A1. Připrav `.env`

```bash
cp .env.example .env
```

Uprav `.env`:
```
APP_NAME="Název projektu"
APP_URL=http://localhost:8000
APP_LOCALE=cs
DB_DATABASE=nazev_projektu
```

### A2. Otevři v kontejneru

Ve VS Code: pravý dolní roh → **"Reopen in Container"**
(nebo `F1` → `Dev Containers: Reopen in Container`)

Container automaticky provede: `composer install`, `npm install`, `php artisan key:generate`, vytvoří databázi a spustí migrace.

### A3. Spusť dev servery

```bash
composer run dev
```

Spustí zároveň: PHP server, Vite, queue worker a log tail.
Web běží na `http://localhost:8000`.

### A3b. Storage symlink (pro Filament admin uploady)

Portfolio admin nahrává screenshoty do `storage/app/public/portfolio/{slug}/` a
přístup k nim potřebuje veřejný symlink `public/storage`. Stačí jednou:

```bash
php artisan storage:link
```

Idempotentní — lze přidat do deploy skriptu. Bez toho vrátí `/storage/portfolio/...` 404.

### A4. CMS (volitelné)

```bash
# Filament:
composer require filament/filament
php artisan filament:install --panels
php artisan make:filament-user

# Twill:
composer require area17/twill
php artisan twill:install
```

---

## Způsob B — WampServer (lokální Apache + PHP)

> Klasický lokální vývoj přes WampServer. Projekt běží přímo na hostitelském systému.

### B1. PHP závislosti

```powershell
composer install
```

### B2. Prostředí

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

### B3. Databáze

```bash
# Nejdřív vytvoř DB v MySQL, uprav .env, pak:
php artisan migrate
```

### B4. CMS (volitelné)

```bash
# Filament:
composer require filament/filament
php artisan filament:install --panels
php artisan make:filament-user

# Twill:
composer require area17/twill
php artisan twill:install
```

### B5. JS závislosti

```bash
npm install
```

### B6. Virtual host

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

### B7. Dev server

```bash
npm run dev
```

Web běží na `http://nazev-projektu.local`.

## Testy (`php` není v PATH)

Pokud v host shellu nemáš dostupné `php` (chyba `php: command not found`), použij wrapper:

```bash
./scripts/artisan-test.sh
```

Skript:
- použije lokální `php`, pokud je v `PATH`
- jinak automaticky použije `.devcontainer/docker-compose.yml` a spustí testy v kontejneru

Pro běh konkrétního testu můžeš předat argumenty dál:

```bash
./scripts/artisan-test.sh --filter=ExampleTest
```


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

## Deploy

Aplikace běží na Coolify (Docker image z `Dockerfile` postavený na `serversideup/php:8.4-fpm-nginx`).

### Automatický post-deploy

Při startu kontejneru se z `docker/entrypoint.d/50-laravel-deploy.sh`
automaticky spustí přesně dva příkazy:

```bash
php artisan migrate --force --no-interaction
php artisan db:seed --class=Database\\Seeders\\AdminUserSeeder --force --no-interaction
```

Obojí je idempotentní:
- `migrate --force` aplikuje jen pending migrace, neexistující data nemaže.
- `AdminUserSeeder` dělá `updateOrCreate` na jednom `users` řádku podle
  `ADMIN_EMAIL` / `ADMIN_PASSWORD` — opakovaný deploy aktualizuje heslo
  podle aktuální env hodnoty.

Vlastník po deployi nemusí dělat nic ručně — admin se nalogguje na `/admin`
podle `ADMIN_EMAIL` / `ADMIN_PASSWORD` z Coolify env.

### ⚠️ NIKDY nespouštěj `db:seed` bez `--class`

Bare `php artisan db:seed` spustí celý `DatabaseSeeder`, který volá
`PortfolioSeeder`. Ten v transakci pro každý projekt **smaže a znovu vytvoří**
všechny překlady, screenshoty, outcomes a tagové vazby z
`docs/portfolio-data.yaml` → tím přepíše ruční úpravy z Filament adminu.

Stejně tak nikdy nespouštěj `migrate:fresh` ani `migrate:refresh`.

Ochrana je dvojitá:
1. Entrypoint skript volá pouze whitelistované příkazy (viz výše).
2. `PortfolioSeeder::run()` má guard: pokud v DB existují portfolio
   projekty, seed se přeskočí. Re-seed dat z YAMLu jde vynutit jen
   přes env proměnnou `PORTFOLIO_SEEDER_FORCE_OVERWRITE=1`.

### Lokální test entrypointu

```bash
docker build -t my-starter:deploy-test .
docker run --rm \
    -e APP_ENV=production \
    -e DB_CONNECTION=mysql \
    -e DB_HOST=... -e DB_DATABASE=... -e DB_USERNAME=... -e DB_PASSWORD=... \
    -e ADMIN_EMAIL=admin@example.com -e ADMIN_PASSWORD=secret \
    my-starter:deploy-test
```

V logu hledej řádky `[laravel-deploy] Running database migrations...`
a `[laravel-deploy] Seeding admin user...`.

---

## Údržba starteru

Pokud v starteru něco změníš (nová ikona, CSS komponenta, JS utilita...), poznamenej to sem:

### Changelog

| Datum | Změna |
|---|---|
| 2025-03 | Vznik starteru (extrakce z MAKOplast) |
| | |
