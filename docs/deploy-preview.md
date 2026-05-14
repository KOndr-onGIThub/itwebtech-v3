# Coolify preview deployment per feature branch (OND-141)

Procedure-doc pro spuštění **preview Coolify aplikace** nad libovolnou aktivní
feature branch. Účel: umožnit **Path 2 (rendered viewport) review** — Jack /
stakeholder vidí změny v reálném prohlížeči ještě před merge do `staging`.

Production a staging cesta jsou popsané v `README.md` § Deploy a tahle doc je
**nemění**. Preview je samostatná Coolify aplikace, samostatný DB schema,
samostatná subdoména.

## Architektura

```
                ┌───────────────────────────────┐
GitHub  push →  │  Coolify app: itwebtech-prod  │ → itwebtech.cz       (main)
                ├───────────────────────────────┤
                │  Coolify app: itwebtech-stag  │ → staging.itwebtech…  (staging)
                ├───────────────────────────────┤
                │  Coolify app: itwebtech-prev  │ → preview-…itwebtech… (feature/*)
                └───────────────────────────────┘
                       │                │
                       │                ├── DB: nová MariaDB databáze (fresh)
                       │                └── Image: stejný Dockerfile, stejný entrypoint
                       └── deploy trigger: GitHub webhook na push do sledované branche
```

Klíčové vlastnosti:

- Stejný `Dockerfile` a `docker/entrypoint.d/50-laravel-deploy.sh` jako prod —
  preview tedy reálně reprodukuje produkční runtime (migrace + admin seed +
  články seed proběhnou samy).
- **Samostatná DB schema**, seedovaná z fixtures (`PortfolioSeeder` +
  `EnsureArticlesSeededSeeder`). Nikdy nesdílet prod / staging DB —
  preview app je read-write a může zápisy z Filamentu rozbít data v reviewu.
- Subdoména **per branch** nebo **rotující single-slot** — viz § Subdoména.
- Robots: `noindex` + `Disallow: /` (preview nesmí do indexu Googlu).

## Kdy spustit preview

Jen pokud:

1. Feature branch je veřejně review-ready (homepage / sitewide redesign tracks,
   pre-merge CRO review iterace), **a**
2. Path 1 (string review) už nestačí — recenzent potřebuje vidět layout,
   typografii, fotografie, mobile responsiveness atd.

Pro QA-only / interní smoke checky stačí `staging` po merge.

## Předpoklady (jednorázové)

- Coolify root account (drží CEO). Engineer **nedostává** Coolify credentials —
  CEO sám nakonfiguruje aplikaci, Engineer dodá tento doc + env hodnoty.
- Wildcard DNS `*.itwebtech.cz` → Coolify server IP, certifikát Let's Encrypt
  s wildcard SAN (DNS-01 challenge přes Coolify-supported DNS plugin).
  Bez wildcard DNS musí každá nová preview subdoména dostat A-record
  ručně před prvním deployem.
- MariaDB / MySQL service v Coolify s volnou kapacitou na další schema.

## Postup: vytvoření nové preview aplikace

Provádí CEO v Coolify dashboardu. Engineer dodá hodnoty.

### 1. Resources → New Resource → Application

- **Source**: GitHub App (existující, ten samý jako prod/staging).
- **Repository**: `KOndr-onGIThub/itwebtech-v3`.
- **Branch**: konkrétní feature branch, např. `feature/sitewide-redesign`.
  > Pokud chceme parametrizovatelnost, klonujeme tuhle Coolify aplikaci a v
  > klonu jen přepneme branch. Coolify (v aktuální verzi) **nemá nativní
  > "Preview Deployments per PR"** — fallback je tedy **manuální
  > app-per-branch**.
- **Build pack**: Dockerfile (root `Dockerfile`, nic nepřepisovat).
- **Port exposed**: `8080` (stejně jako prod — `serversideup/php` default).
- **Domain**: viz § Subdoména.

### 2. Auto-deploy

- Zapnout **Deploy on push** pro vybranou branch.
- Webhook se nastaví automaticky GitHub Appem; ověřit, že
  `Settings → Webhooks` v GitHubu obsahuje URL od Coolify a pushe se dostávají.

### 3. Databáze

- **Resources → New Resource → MariaDB** (samostatná instance) nebo
  **schema** ve sdíleném MariaDB containeru. Doporučeno: schema ve sdíleném,
  šetří RAM.
- Schema name konvence: `itwebtech_preview_{ticket}` (např.
  `itwebtech_preview_ond141`) — viditelné v Coolify pro snadný cleanup.
- User: vlastní user na tu schemu, jen `ALL PRIVILEGES` na ten jeden schema,
  ne global.

### 4. Environment variables

Naplnit v `Configuration → Environment Variables`. **Tučně** jsou ty, které se
liší od prod hodnot:

```env
APP_NAME="ITWebTech (preview)"
APP_ENV=staging              # POZOR: ne 'local' (vyžaduje seed admin uživatele)
APP_KEY=                     # vygenerovat nový: `php artisan key:generate --show`
APP_DEBUG=false              # i v preview držet false, jinak Whoops leakne paths
APP_URL=https://preview-{branch}.itwebtech.cz   # MUSÍ být https + bez koncového lomítka

APP_LOCALE=cs
APP_FALLBACK_LOCALE=en

# DB — hodnoty z kroku 3
DB_CONNECTION=mysql
DB_HOST=<coolify-mariadb-host>
DB_PORT=3306
DB_DATABASE=itwebtech_preview_{ticket}
DB_USERNAME=<preview-user>
DB_PASSWORD=<preview-password>

# Admin login — vygenerovat unikátní silné heslo, NEPOUŽÍVAT prod hodnoty
ADMIN_EMAIL=admin@itwebtech.cz
ADMIN_PASSWORD=<random-32-char>
FILAMENT_ADMIN_PATH=admin-cms

# Analytics — VYPNOUT, ať preview nešpiní prod GA4/Plausible/Clarity
ANALYTICS_ENABLED=false

# Mailer — preview nesmí posílat reálné e-maily; log driver stačí pro review
MAIL_MAILER=log

# Session
SESSION_DRIVER=database
SESSION_SECURE_COOKIE=true

# Robots — viz § Indexace
APP_PREVIEW_NOINDEX=true
```

### 5. Indexace (noindex)

Preview URL **nesmí** skončit v Googlu. Dva nezávislé guardy:

1. **HTTP hlavička** `X-Robots-Tag: noindex, nofollow` — nastavit v Coolify
   `Configuration → Headers` (nebo přes nginx snippet, pokud Coolify
   neumí custom headers).
2. **`robots.txt`** override — pro preview nasadit přes
   `public/robots.preview.txt` (`Disallow: /`) a v Coolify build commandu
   přepsat `public/robots.txt` symbolicky pokud `APP_ENV=staging`.

Pokud `APP_PREVIEW_NOINDEX=true` v env, middleware `app/Http/Middleware`
emituje meta tag `<meta name="robots" content="noindex">` (TODO: implementace
v rámci OND-141 follow-up tasku, pokud Jack potvrdí, že preview-per-branch
zůstává v procesu).

> **Quick win bez kódu**: stačí v Coolify nginx config přidat
> `add_header X-Robots-Tag "noindex, nofollow" always;`. To je dostatečné
> pro krátkodobé review windowy.

### 6. První deploy + seed portfolia

Po prvním úspěšném deployi DB obsahuje:

- migrace ✅ (entrypoint)
- AdminUserSeeder ✅ (entrypoint)
- EnsureArticlesSeededSeeder ✅ (entrypoint — jen pokud `articles` table je prázdná)
- **PortfolioSeeder ❌** — *nespouští se automaticky* (úmyslně, aby
  staging/prod redeploy nepřepisoval ručně upravená portfolio data).

**Jednorázově po prvním deployi** spustit z Coolify `Terminal` v běžícím
kontejneru:

```bash
cd /var/www/html
php artisan db:seed --class="Database\\Seeders\\PortfolioSeeder" --force
```

Tím se z `docs/portfolio-data.yaml` napumpuje 3+ portfolio projektů.

> Pokud následně chceš re-seedovat (yaml se změnil), použij
> `PORTFOLIO_SEEDER_FORCE_OVERWRITE=1` env a redeploy, nebo manuální
> `--force` s tím samým env setnutým inline.

### 7. Subdoména

Tři varianty, vyber podle aktuálního review tracku (rozhodnutí leží na CEO,
default je **per-branch**):

| Pattern | Použití | Pros | Cons |
|---|---|---|---|
| `preview-{branch-slug}.itwebtech.cz` | default, multi-branch review | paralelní preview pro víc branchí | wildcard DNS + wildcard cert |
| `preview.itwebtech.cz` | single-slot, jeden aktivní review v čase | jednoduchý DNS / cert | jen jedna branch současně |
| `preview-{ticket}.itwebtech.cz` | fixed pro konkrétní track (např. `preview-ond141`) | predikovatelná URL pro stakeholdery | každý nový track = nová subdoména |

`branch-slug` = lower-case, `/` a `_` nahrazeno `-`, max 40 znaků (limit DNS
labelu). Příklad: `feature/sitewide-redesign` → `feature-sitewide-redesign`.

### 8. Sdělit URL Jackovi

Jakmile preview odpoví HTTP 200 na `/` a obsahuje očekávaný obsah:

- Postnout URL + admin login link (`/{FILAMENT_ADMIN_PATH}`) jako komentář
  do issue, která preview požaduje (OND-140 nebo successor).
- Zmínit, že je to **preview build z feature branche** (ne staging, ne prod),
  a co konkrétně se mění oproti aktuální produkci.

## Aktualizace preview (každý push)

Auto-deploy se postará. Workflow pro Engineera:

```bash
git checkout feature/sitewide-redesign
# ... commit změn ...
git push origin feature/sitewide-redesign
# → GitHub webhook → Coolify rebuild → ~3–5 min → nová verze na preview URL
```

Pokud build padne, Coolify pošle notifikaci (Discord/email per Coolify global
config). Engineer kouká do build logu v Coolify `Deployments` tabu.

## Decommission (po merge / po review)

Po merge feature branch do `staging`, preview app už nemá smysl. Postup:

1. Coolify: `itwebtech-preview-{branch}` → **Stop** → **Delete**.
2. MariaDB schema: `DROP DATABASE itwebtech_preview_{ticket};` + odebrat
   schema-scoped DB usera.
3. DNS: pokud jsi přidal A-record ručně (ne wildcard), odebrat.
4. Komentář do OND-141 (nebo navazujícího tasku) s informací, že preview je
   downed a důvod (merged / superseded / abandoned).

## Pitfally a riziko

- **Nesdílet prod / staging DB**. Preview je live-editable Filament — sdílení
  by znamenalo, že review-test zápisy přepíší produkční obsah. Vždy fresh
  schema, vždy unique credentials.
- **APP_ENV=local v preview = security risk**. `DatabaseSeeder::run()` má
  `if (app()->environment('local', 'testing'))` větev, která zakládá
  `test@example.com / Test User` v `users` tabulce. V preview držet
  `APP_ENV=staging`.
- **`db:seed` bez `--class` v preview je stejně nebezpečné jako v prod** —
  spustí `PortfolioSeeder::run()` s default chováním, které smaže existující
  portfolio záznamy (pokud je v DB ne-prázdná). Vždy `--class`.
- **`APP_DEBUG=true` v preview leakne paths a env keys** v error page. Držet
  `false` i v preview, pokud nereviewujeme konkrétní 500 error.
- **Stale review URL**: pokud preview žije moc dlouho po merge feature branch,
  recenzent může reviewovat zastaralý kód. Po merge **smaž preview app
  okamžitě** (viz Decommission).
- **Wildcard cert renewal**: Let's Encrypt wildcard expiruje co 90 dní;
  Coolify renewal job musí běžet. Pokud preview vrátí cert warning, zkontroluj
  `Coolify → Server → Logs → certbot`.

## Známé gapy / TODO follow-up

- Native Coolify "Preview Deployments per PR" — nezkoumáno (Coolify v aktuální
  verzi to nemá). Pokud upgrade přinese tuhle feature, tahle doc je obsoletní
  pro single-branch tracky; manual app-per-branch pak zůstane jako fallback
  pro experimentální branche mimo PR flow.
- Automatický `PortfolioSeeder` run při fresh DB v preview environments
  (např. nový env flag `PORTFOLIO_SEEDER_AUTO_RUN_ON_EMPTY=1` v entrypointu).
  Aktuálně manuální krok, viz § 6.
- E2E smoke test po každém preview deployi (curl `/` → check 200 + očekávaný
  string) — samostatný task, nepatří sem.

## Reference

- Parent epic: [OND-127](/OND/issues/OND-127) — Homepage v2 + sitewide CRO review by Jack.
- Tahle procedure byla zavedena v rámci [OND-141](/OND/issues/OND-141).
- Production deploy popis: `README.md` § Deploy.
- Entrypoint logic: `docker/entrypoint.d/50-laravel-deploy.sh`.
