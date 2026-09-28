# Ostrý web na Webglobe: nastavení a provoz

Ostrý web (`ondraweb.cz` a `itwebtech.cz`) běží na hostingu Webglobe, na stejném
místě jako dnes starý `itwebtech.cz`. Testovací web `itwebtech.ondrejkriska.cz`
zůstává na Coolify a nasazuje se sám z větve `staging` jako dosud.

**Jak se nasazuje:** sloučíš `staging` do `main` na GitHubu. GitHub pak sám
sestaví web, nahraje ho na Webglobe, upraví databázi a přepne web na novou
verzi. Trvá to několik minut. Předchozí verze běží až do chvíle přepnutí.
Když něco selže, web se nepřepne a zůstane předchozí verze.

Kdo co dělá:

| Část | Kdo |
|---|---|
| A. Nastavení v administraci Webglobe | Ondřej |
| B. Hesla a nastavení na GitHubu (Secrets) | Ondřej |
| C. Přesun dat z testovacího webu | CEO (příkazy níže) |
| D. První nasazení | Ondřej (jedno tlačítko) |
| E. Testovací web mimo Google | CEO (Coolify) |

---

## A. Administrace Webglobe (jednou)

Přihlášení: <https://admin.webglobe.cz>, hosting domény `itwebtech.cz`.

1. **PHP 8.4.** V nastavení hostingu (Hosting → Web → PHP) zvol verzi **8.4**.
   Ve stejném místě zkontroluj limity pro nahrávání souborů. Formulář bere
   přílohy do 10 MB, dohromady do 20 MB, takže nastav aspoň
   `upload_max_filesize` = **20M** a `post_max_size` = **32M**, pokud je tam méně.
2. **Typ webserveru.** Hosting → Web → Nastavení webserveru → Konfigurace web
   serveru pro doménu → zvol **Apache** (nebo **LiteSpeed**). Oba čtou soubor
   `.htaccess`, přes který web posílá všechny adresy do aplikace. U volby Nginx
   by fungovala jen úvodní stránka. Změna se projeví zhruba do 20 minut.
3. **Doména `ondraweb.cz`.** Přidej `ondraweb.cz` a `www.ondraweb.cz` ke
   **stejnému hostingu** jako `itwebtech.cz` (jako další doménu nebo alias,
   se stejným webovým adresářem). Zapni pro všechny čtyři adresy (`ondraweb.cz`,
   `www.ondraweb.cz`, `itwebtech.cz`, `www.itwebtech.cz`) certifikát
   Let's Encrypt. Pro `ondraweb.cz` to půjde až ve chvíli, kdy na Webglobe
   povede DNS.
4. **Databáze.** Hosting → Databáze → založ novou databázi (MariaDB nebo MySQL,
   nejnovější nabízená verze). Opiš si čtyři údaje: **server** (např.
   `c-mariadb`), **název databáze**, **uživatele** a **heslo**. Budou potřeba
   v kroku B.
5. **SSH přístup.** Hosting → FTP a soubory → FTP účty. Opiš si **FTP login**
   a **FTP host**. U tlačítka „Upravit“ nastav heslo, pokud ho neznáš.
   GitHub se přihlašuje stejným účtem přes SSH. Pokud administrace nabízí
   zapnutí SSH, zapni ho **natrvalo**.
   Pozor: „WebSSH“ (konzole v prohlížeči) se zapíná jen na hodinu. To je jiná
   věc a automatickému nasazení nestačí. Když administrace umožní SSH zapnout
   jen dočasně, napiš to CEO, bez trvalého SSH automatické nasazení nepojede.
6. **IP ochrana a GeoIP.** U stejného FTP účtu (Upravit → „IP ochrana + GeoIP“)
   nesmí být přístup omezený jen na Českou republiku. GitHub se připojuje
   ze serverů v zahraničí.

## B. GitHub Secrets (jednou)

Na GitHubu v repozitáři: **Settings → Secrets and variables → Actions →
New repository secret**. Založ tyhle čtyři:

| Název | Co do něj vložit | Odkud |
|---|---|---|
| `WEBGLOBE_SSH_HOST` | adresa serveru, např. `ftp.itwebtech.cz` | krok A5, „FTP host“ |
| `WEBGLOBE_SSH_USER` | přihlašovací jméno | krok A5, „FTP login“ |
| `WEBGLOBE_SSH_PASSWORD` | heslo FTP účtu | krok A5 |
| `PRODUCTION_ENV` | celá produkční konfigurace, vzor je níže | vzor + hodnoty z Coolify a z kroku A4 |

Nic dalšího není potřeba. Změnu konfigurace později uděláš úpravou
`PRODUCTION_ENV` a novým nasazením (část F).

### Vzor `PRODUCTION_ENV`

Zkopíruj celý blok do hodnoty secretu a doplň místa označená `‹…›`.
Hodnoty „z Coolify“ najdeš v Coolify u aplikace v záložce **Environment
Variables** a zkopíruješ je beze změny.

```dotenv
APP_NAME="ONDRAWEB"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ondraweb.cz
ASSET_URL=
# Musí být STEJNÝ jako na Coolify, jinak přestane fungovat dvoufázové
# přihlášení do administrace.
APP_KEY=‹APP_KEY z Coolify›

CANONICAL_HOST=ondraweb.cz
LEGACY_HOSTS=itwebtech.cz,www.itwebtech.cz,www.ondraweb.cz

APP_LOCALE=cs
APP_FALLBACK_LOCALE=en
LOG_CHANNEL=stack
LOG_STACK=daily
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=‹server z kroku A4, např. c-mariadb›
DB_PORT=3306
DB_DATABASE=‹název databáze z kroku A4›
DB_USERNAME=‹uživatel z kroku A4›
DB_PASSWORD=‹heslo z kroku A4›

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=sync
FILESYSTEM_DISK=local

ADMIN_EMAIL=‹z Coolify›
ADMIN_PASSWORD=‹z Coolify›
FILAMENT_ADMIN_PATH=‹z Coolify›

MAIL_MAILER=smtp
MAIL_HOST=smtp.seznam.cz
MAIL_PORT=587
MAIL_USERNAME=‹z Coolify›
MAIL_PASSWORD=‹z Coolify›
MAIL_FROM_ADDRESS=‹z Coolify›
MAIL_FROM_NAME="ONDRAWEB"
CONTACT_TO=ok@ondraweb.cz

CALENDLY_URL=‹z Coolify›
CONSULTATION_VIDEO_URL=‹z Coolify›

ANALYTICS_ENABLED=true
GA4_MEASUREMENT_ID=‹z Coolify›
CLARITY_PROJECT_ID=‹z Coolify›

SHOW_PORTFOLIO_SECTION=true
SHOW_TOYOTA_TESTIMONIAL=true
```

Adresu testovacího webu (`itwebtech.ondrejkriska.cz`) do konfigurace
nedávej. Ten běží na jiném serveru a na Webglobe se žádný požadavek na něj
nedostane. `SEO_NOINDEX` na produkci taky nepatří.

<details>
<summary>Když Webglobe nepovolí přihlášení heslem přes SSH</summary>

Nasazení v tom případě skončí chybou „Nepodařilo se připojit přes SSH“.
Místo hesla pak použij klíč. Vyrobíš ho v konzoli WebSSH v administraci
Webglobe (Hosting → FTP a soubory → WebSSH → Aktivovat konzoli), bez vlastního
terminálu. Do konzole vlož postupně tyto tři řádky:

```sh
mkdir -p ~/.ssh && chmod 700 ~/.ssh && ssh-keygen -t ed25519 -N "" -C github-deploy -f ~/.ssh/github-deploy
cat ~/.ssh/github-deploy.pub >> ~/.ssh/authorized_keys && chmod 600 ~/.ssh/authorized_keys
cat ~/.ssh/github-deploy
```

Poslední řádek vypíše soukromý klíč (od `-----BEGIN` do `-----END … KEY-----`
včetně). Celý ho zkopíruj do nového secretu **`WEBGLOBE_SSH_KEY`**. Secret
`WEBGLOBE_SSH_PASSWORD` pak smaž. Pak ještě `rm ~/.ssh/github-deploy`, ať
soukromý klíč na serveru nezůstane.
</details>

## C. Přesun dat z testovacího webu (CEO, jednou)

Obsah (články, případovky, administrátor, poptávky) je dnes v databázi na
Coolify. Přenáší se **celá** databáze včetně tabulky `migrations`, jinak by
první nasazení zkoušelo tabulky zakládat znovu. Tabulky `sessions`, `cache`
a `cache_locks` jdou jen jako prázdná struktura. Soubory z `storage/app/public`
se nepřenášejí, je tam jen `.gitignore` (ověřeno 28. 9. 2026).

Nejlepší je přesun udělat **před prvním nasazením**. Migrace pak nic nedělají
a seedery nic nepřepíšou. Obrácené pořadí taky funguje, protože import tabulky
nejdřív smaže a založí znovu.

1. Na hostu s Coolify vyrob dump (DB kontejner aplikace je
   `y14muufdmum2j959zpxynlia`, databáze `default`, MySQL 8.0):

   ```sh
   umask 077
   DB=y14muufdmum2j959zpxynlia
   OUT=~/ondraweb-data-$(date +%Y%m%d-%H%M).sql
   docker exec "$DB" sh -c 'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" \
       --single-transaction --no-tablespaces --set-gtid-purged=OFF \
       --default-character-set=utf8mb4 --skip-comments \
       --ignore-table=default.sessions --ignore-table=default.cache \
       --ignore-table=default.cache_locks default' > "$OUT"
   docker exec "$DB" sh -c 'mysqldump -u root -p"$MYSQL_ROOT_PASSWORD" \
       --no-data --no-tablespaces --set-gtid-purged=OFF --skip-comments \
       default sessions cache cache_locks' >> "$OUT"
   ls -l "$OUT"
   ```

2. Import do databáze na Webglobe (údaje z kroku A4). Jsou dvě cesty:

   - **a) Přímo z hostu**, pokud Webglobe povolí vzdálený přístup k databázi
     a host se na ni dostane (28. 9. se na `62.109.154.42` nedostal na žádném
     portu):

     ```sh
     docker exec -i "$DB" mysql -h ‹DB server pro vzdálený přístup› -P 3306 \
         -u ‹uživatel› -p‹heslo› ‹název databáze› < "$OUT"
     ```

   - **b) Přes administraci Webglobe.** Soubor `$OUT` předej Ondřejovi (mimo
     Paperclip, obsahuje osobní údaje z poptávek) a ten ho nahraje v
     administraci Webglobe → Databáze → phpMyAdmin/Adminer → Import.

3. Kontrola počtů. Na Coolify:

   ```sh
   docker exec "$DB" sh -c 'mysql -u root -p"$MYSQL_ROOT_PASSWORD" default -N -e "
     select \"articles\", count(*) from articles union all
     select \"portfolio_projects\", count(*) from portfolio_projects union all
     select \"projects\", count(*) from projects union all
     select \"users\", count(*) from users union all
     select \"contact_submissions\", count(*) from contact_submissions union all
     select \"landing_leads\", count(*) from landing_leads union all
     select \"migrations\", count(*) from migrations"'
   ```

   Stejný dotaz (bez `docker exec`) pusť v phpMyAdmin na Webglobe. Čísla se
   musí shodovat. Po úspěšném importu dump smaž (`rm "$OUT"`).

## D. První nasazení (Ondřej)

Předpoklad: části A a B jsou hotové a workflow už je ve větvi `main`
(dostane se tam prvním sloučením `staging` → `main` po tomhle PR).

1. Pokud se nasazení po sloučení do `main` spustilo samo a skončilo červeně
   jen kvůli chybějícímu nastavení, nevadí. Na webu se nic nezměnilo.
2. GitHub → záložka **Actions** → vlevo **„Nasazení produkce (Webglobe)“** →
   vpravo **Run workflow** → větev `main`, akce `deploy` → **Run workflow**.
3. Počkej na zelenou fajfku (několik minut). V logu kroku „Migrace, optimize,
   zkouška a přepnutí webu“ je vidět, že stránky nové verze vrátily `OK 200`.

Co se při prvním nasazení stane se starým webem: dosavadní obsah webového
adresáře `www` se **přejmenuje** na `www.pred-ondraweb-<datum>`. Nic se
nemaže. Místo něj vznikne odkaz na nový web.

Pořadí kolem DNS: jakmile první nasazení doběhne, `itwebtech.cz` začne
přesměrovávat na `ondraweb.cz`. DNS `ondraweb.cz` proto přepni na Webglobe
hned potom.

## E. Testovací web mimo Google (CEO)

Jakmile běží ostrý web, nastav v Coolify u aplikace proměnnou
`SEO_NOINDEX=true` a spusť redeploy. Testovací web pak v `robots.txt` zakáže
vyhledávačům vše a ke každé stránce pošle `X-Robots-Tag: noindex, nofollow`.
Ověření:

```sh
curl -s https://itwebtech.ondrejkriska.cz/robots.txt        # User-agent: * / Disallow: /
curl -sI https://itwebtech.ondrejkriska.cz/ | grep -i x-robots-tag
```

## F. Běžný provoz

- **Nasazení:** sloučit `staging` → `main`. Nic dalšího.
- **Výsledek:** GitHub → Actions. Zelená = nová verze běží. Červená = web
  běží dál v předchozí verzi a červená hláška v logu říká proč.
- **Návrat na předchozí verzi:** Actions → „Nasazení produkce (Webglobe)“ →
  Run workflow → akce `rollback`. Každé spuštění vrátí web o jednu verzi zpět.
  Změny v databázi (migrace) se nevracejí.
- **Změna konfigurace** (heslo k poště apod.): upravit secret `PRODUCTION_ENV`
  a spustit Run workflow s akcí `deploy`.

---

## Technická příloha

Soubory: `.github/workflows/deploy-production.yml` (build na GitHubu a
nahrání), `scripts/deploy/remote-deploy.sh` (kroky na serveru),
`scripts/deploy/smoke.php` (zkouška stránek před přepnutím).

**Rozložení na serveru** (v domovském adresáři SSH účtu):

```
ondraweb/releases/<UTC čas>-<commit>/   jednotlivé verze, drží se posledních 5
ondraweb/shared/.env                    z PRODUCTION_ENV, přepisuje se při každém nasazení
ondraweb/shared/storage/                nahrané soubory, logy (storage/logs), cache
ondraweb/current -> releases/<id>       aktivní verze
www -> ~/ondraweb/current/public        webový adresář hostingu
```

**Průběh nasazení na serveru:** kontrola PHP 8.4 a rozšíření → propojení
`storage` a `.env` → `migrate --force` → seedery `AdminUserSeeder`,
`EnsureArticlesSeededSeeder`, `EnsurePortfolioSeededSeeder` → `storage:link`
→ `optimize` → zkouška `/up`, `/`, `/en/`, `/de/`, `/projekty`, `/zapisky`,
`/robots.txt`, `/sitemap.xml` přímo přes Laravel → přepnutí `current`
a `www` → úklid starých verzí. Selže-li cokoli před přepnutím, web zůstane
na předchozí verzi. Verze, která nikdy neběžela, se při `rollback` přeskočí.

**Volitelné proměnné** (Settings → Secrets and variables → Actions →
Variables), jen když se Webglobe liší od předpokladu:

| Proměnná | Výchozí | Kdy změnit |
|---|---|---|
| `WEBGLOBE_WEB_ROOT` | `www` | webový adresář domény je jinde |
| `WEBGLOBE_APP_DIR` | `ondraweb` | aplikace má ležet jinde |
| `WEBGLOBE_PHP_BIN` | `php8.4` | PHP 8.4 je na serveru pod jiným příkazem |

**Volitelné secrets:** `WEBGLOBE_SSH_PORT` (když SSH neběží na 22),
`WEBGLOBE_SSH_KEY` (místo hesla), `WEBGLOBE_SSH_KNOWN_HOSTS` (otisk serveru,
výstup `ssh-keyscan <host>`. Bez něj se otisk přijme při každém běhu).

**Když hosting nedovolí nahradit `www` odkazem** (nasazení skončí hláškou
„Nejde přejmenovat“ / „Nejde vytvořit symlink“): v administraci nastav
kořenový adresář domén na `ondraweb/current/public`, pokud to jde, a
proměnnou `WEBGLOBE_WEB_ROOT` na nepoužívanou cestu, např.
`ondraweb/web-root`.

**Co není ověřené** (k hostingu jsme 28. 9. neměli přístup a server nebyl
z našeho hostu dosažitelný): přesný název webového adresáře, jestli SSH
přijme heslo, jestli je na serveru `rsync` (když ne, workflow pošle soubory
přes `tar`), jestli webserver sleduje odkaz `www` a jestli CLI PHP 8.4 má
všechna rozšíření. Na poslední dvě věci nasazení odpoví jasnou chybou.
