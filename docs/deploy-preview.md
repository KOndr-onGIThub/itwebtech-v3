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
- **Subdoména: `preview-{ticket}.itwebtech.cz`** — fixed per review track
  (rozhodnuto v OND-141). Pro tenhle track: `preview-sitewide-redesign.itwebtech.cz`.
  Wildcard DNS varianta je nepoužitá (DNS u itwebtech.cz nemá Coolify plugin
  pro DNS-01 challenge); každá další preview app potřebuje vlastní A-záznam.
- Robots: `noindex` + `Disallow: /` (preview nesmí do indexu Googlu).

## Kdy spustit preview

Jen pokud:

1. Feature branch je veřejně review-ready (homepage / sitewide redesign tracks,
   pre-merge CRO review iterace), **a**
2. Path 1 (string review) už nestačí — recenzent potřebuje vidět layout,
   typografii, fotografie, mobile responsiveness atd.

Pro QA-only / interní smoke checky stačí `staging` po merge.

## Předpoklady (jednorázové)

- Coolify root account (drží Ondřej / CEO). Engineer **nedostává** Coolify
  credentials — CEO sám nakonfiguruje aplikaci, Engineer dodá tento doc +
  env hodnoty.
- Přístup do DNS panelu domény `itwebtech.cz` (drží Ondřej) — potřeba pro
  přidání A-záznamu pro každou novou preview subdoménu (viz § 1. DNS).
- MariaDB / MySQL service v Coolify s volnou kapacitou na další schema.

> **Proč ne wildcard DNS:** Zvažovali jsme `*.itwebtech.cz` s DNS-01 challenge
> pro auto-issuance wildcard certu. DNS provider domény Coolify plugin nemá,
> takže DNS-01 by vyžadoval ruční TXT-record dance při každém renewalu.
> Místo toho používáme **jednoduchý A-záznam per preview track** + standardní
> HTTP-01 Let's Encrypt cert. Trade-off: každý nový track = 1× ruční A-záznam
> v DNS panelu (~5 min). Pokud jednou bude víc paralelních preview tracků,
> přeladíme na wildcard (viz § Migrace na wildcard v budoucnu).

## Postup: vytvoření nové preview aplikace

Provádí Ondřej / CEO. Engineer dodá hodnoty + tento doc. Příklad
v krocích níže používá branch `feature/sitewide-redesign` a tedy track
`sitewide-redesign` (= subdoména `preview-sitewide-redesign.itwebtech.cz`).
Pro jiný track jen nahraď `sitewide-redesign` názvem nového tracku.

### 1. DNS A-záznam (vlastní DNS panel itwebtech.cz)

**Owner: Ondřej.** V DNS panelu domény `itwebtech.cz` (mimo Coolify) přidat:

| Type | Name | Value | TTL |
|---|---|---|---|
| `A` | `preview-sitewide-redesign` | `<IP Coolify serveru>` | 300 |

IP Coolify serveru = ta samá, na kterou ukazují `staging.itwebtech.cz` /
`itwebtech.cz` v Coolify dashboardu (`Servers → <node> → IP Address`).

Po propagaci (typicky < 5 min):

```bash
dig +short preview-sitewide-redesign.itwebtech.cz
# musí vrátit Coolify IP
```

### 2. Resources → New Resource → Application (Coolify)

- **Source**: GitHub App (existující, ten samý jako prod/staging).
- **Repository**: `KOndr-onGIThub/itwebtech-v3`.
- **Branch**: `feature/sitewide-redesign` (pro nový track odpovídající feature branch).
  > Coolify (v aktuální verzi) **nemá nativní "Preview Deployments per PR"** —
  > používáme **manuální app-per-branch** (zvolený fallback).
- **Build pack**: Dockerfile (root `Dockerfile`, nic nepřepisovat).
- **Port exposed**: `8080` (stejně jako prod — `serversideup/php` default).
- **Domain**: `https://preview-sitewide-redesign.itwebtech.cz`
  - Coolify si automaticky vystaví Let's Encrypt cert (HTTP-01 challenge přes
    port 80 — funguje jen pokud DNS A-záznam z kroku 1 už propaguje).
- **Application Name**: `itwebtech-preview-sitewide-redesign` (viditelné jen
  v Coolify, pro snadnou identifikaci v seznamu apps).

### 3. Auto-deploy

- Zapnout **Deploy on push** pro vybranou branch.
- Webhook se nastaví automaticky GitHub Appem; ověřit, že
  `Settings → Webhooks` v GitHubu obsahuje URL od Coolify a pushe se dostávají.

### 4. Databáze

- **Resources → New Resource → MariaDB** (samostatná instance) nebo
  **schema** ve sdíleném MariaDB containeru. Doporučeno: schema ve sdíleném,
  šetří RAM.
- Schema name konvence: `itwebtech_preview_{track}` — pro tenhle track
  `itwebtech_preview_sitewide_redesign`. Viditelné v Coolify pro snadný cleanup.
- User: vlastní user na tu schemu, jen `ALL PRIVILEGES` na ten jeden schema,
  ne global.

### 5. Environment variables

Naplnit v `Configuration → Environment Variables`. **Tučně** jsou ty, které se
liší od prod hodnot:

```env
APP_NAME="ITWebTech (preview)"
APP_ENV=staging              # POZOR: ne 'local' (vyžaduje seed admin uživatele)
APP_KEY=                     # vygenerovat nový: `php artisan key:generate --show`
APP_DEBUG=false              # i v preview držet false, jinak Whoops leakne paths
APP_URL=https://preview-sitewide-redesign.itwebtech.cz   # MUSÍ být https + bez koncového lomítka

APP_LOCALE=cs
APP_FALLBACK_LOCALE=en

# DB — hodnoty z kroku 3
DB_CONNECTION=mysql
DB_HOST=<coolify-mariadb-host>
DB_PORT=3306
DB_DATABASE=itwebtech_preview_sitewide_redesign
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

### 6. Indexace (noindex)

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

### 7. První deploy + seed portfolia

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

## Další preview track (nová feature branch)

Když potřebuješ rozjet preview pro **jinou** feature branch (např.
`feature/homepage-hero-v3`), použij **Clone & retarget** workflow — kopíruje
existující preview app, ušetří 90 % konfigurace.

1. **DNS** (Ondřej): přidej A-záznam `preview-{nový-track}` →
   `<IP Coolify serveru>` (ten samý A-record postup jako v § 1).
2. **Coolify**: u stávající `itwebtech-preview-sitewide-redesign` app klikni
   `... → Clone`. V klonu změň:
   - **Application Name**: `itwebtech-preview-{nový-track}`
   - **Branch**: `feature/{nový-track}`
   - **Domain**: `https://preview-{nový-track}.itwebtech.cz`
3. **DB schema**: vytvoř novou schemu `itwebtech_preview_{nový_track}` ve sdíleném MariaDB
   containeru + nového usera s `ALL PRIVILEGES` na tu schemu.
4. **Env**: v klonu přepiš `APP_URL`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`,
   `ADMIN_PASSWORD` (nový unikátní). Vygeneruj nový `APP_KEY`.
5. **Deploy** → po prvním úspěšném runu spustit `PortfolioSeeder` (viz § 7).
6. **Postnout URL** do issue, která preview požaduje.

> Když počet paralelních preview tracků překročí ~3, přejdi na wildcard
> DNS (viz § Migrace na wildcard) — manual A-record na track přestává být
> efektivní.

## Decommission (po merge / po review)

Po merge feature branch do `staging`, preview app už nemá smysl. Postup:

1. Coolify: `itwebtech-preview-{track}` → **Stop** → **Delete**.
2. MariaDB schema: `DROP DATABASE itwebtech_preview_{track};` + odebrat
   schema-scoped DB usera.
3. DNS: odebrat A-záznam `preview-{track}` z DNS panelu itwebtech.cz.
4. Komentář do OND-141 (nebo navazujícího tasku) s informací, že preview je
   downed a důvod (merged / superseded / abandoned).

## Migrace na wildcard (jen pokud bude paralelních tracků 3+)

Aktuálně nepoužito (rozhodnuto v OND-141). Když jednou bude víc paralelních
preview tracků a manuální A-záznamy začnou být otravné:

1. Přejít s DNS itwebtech.cz na providera s Coolify DNS plugin (Cloudflare,
   DigitalOcean, atd.) — předpokládá migraci DNS records, mimo scope této doc.
2. V DNS panelu nového providera přidat wildcard A-záznam `*.itwebtech.cz`
   → IP Coolify serveru.
3. V Coolify nakonfigurovat DNS plugin pro nového providera (`Settings → DNS`).
4. Existující `preview-{track}` A-záznamy zůstávají funkční jako specifická
   override; nová preview apps už nepotřebují manuální DNS step — stačí
   v Coolify nastavit domain `preview-{track}.itwebtech.cz` a wildcard cert
   se vystaví automaticky.

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
- **Let's Encrypt cert renewal**: standardní HTTP-01 cert expiruje co 90 dní;
  Coolify renewal job musí běžet. Pokud preview vrátí cert warning, zkontroluj
  `Coolify → Server → Logs → certbot`. (Wildcard nepoužíváme — viz § Architektura.)

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
