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

1. **PHP 8.4.** Hosting → Web → PHP nastavení → verze **8.4** (od 29. 9.
   nastaveno, web běží na 8.4.24).
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
   **`www.ondraweb.cz`** (stav 29. 9.): hosting ho už obsluhuje a aplikace ho
   přesměruje 301 na `https://ondraweb.cz` se stejnou cestou. Chybí jen
   DNS záznam (Domény → DNS → DNS záznamy → Nový DNS záznam: jméno `www`,
   typ A, hodnota `62.109.154.42`) a potom certifikát Let's Encrypt,
   který kromě `ondraweb.cz` pokrývá i `www.ondraweb.cz`.
4. **Databáze.** Hosting → Databáze → založ novou databázi (MariaDB nebo MySQL,
   nejnovější nabízená verze). Opiš si **název databáze**, **uživatele**
   a **heslo**. Patří do `.env` na serveru (vzor v části B). Jako server databáze použij
   **`db.dw142.webglobe.com`**, ne `c-mariadb` z administrace. `c-mariadb`
   zná jen web, SSH ho nenajde. `db.dw142.webglobe.com` ze SSH funguje
   (ověřeno 29. 9.).
5. **SSH přístup.** SSH má jen multihosting a má **vlastní účet, jiný než FTP**.
   Hosting → FTP a soubory → WebSSH. Tam je server `dw142.webglobe.com`,
   port `20001`, jméno `ssh-608671` a heslo (tlačítko „Copy password“).
   Zdarma se WebSSH zapíná jen na hodinu, pak se samo vypne. Automatické
   nasazení potřebuje **Permanentní SSH konzoli** (306 Kč/rok bez DPH).
   Když si ji objednáš, zkontroluj, jestli se heslo nezměnilo.
6. **IP ochrana a GeoIP.** Webglobe zahazuje spojení z části serverů na
   internetu, mezi nimi i z GitHubu. Nastavením v administraci se to obejít nedá,
   proto nasazení chodí přes náš server (secret `DEPLOY_JUMP_KEY` v kroku B).

## B. GitHub Secrets (jednou)

Na GitHubu v repozitáři: **Settings → Secrets and variables → Actions →
New repository secret**. Založ tyhle:

| Název | Co do něj vložit | Odkud |
|---|---|---|
| `WEBGLOBE_SSH_USER` | `ssh-608671` | krok A5, stránka WebSSH |
| `WEBGLOBE_SSH_PASSWORD` | heslo SSH účtu (ne FTP!) | krok A5, „Copy password“ |
| `WEBGLOBE_SSH_PORT` | `20001` | krok A5, stránka WebSSH |
| `DEPLOY_JUMP_KEY` | klíč pro spojení přes náš server | pošle CEO v kartě OND-459 |

Adresu serveru zadávat nemusíš, nasazení ji má v sobě.
Secrety `WEBGLOBE_SSH_HOST` a `PRODUCTION_ENV`, pokud je máš z dřívějška, se
už nepoužívají a můžeš je smazat.

Nic dalšího není potřeba. **Produkční konfigurace (`.env`) žije jen na
serveru** v `/home/html/ondraweb.cz/app/shared/.env`. Nasazení ji nenahrává
ani nepřepisuje. Jak ji změnit, je v části F.

### Vzor `.env` (reference)

Takhle vypadá `/home/html/ondraweb.cz/app/shared/.env`. Na serveru už je
(29. 9. ji založil CEO), vzor slouží jen pro kontrolu nebo nové založení.
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
# db.dw142.webglobe.com, ne c-mariadb: na c-mariadb se nedostane SSH, a tím
# ani migrace při nasazení.
DB_HOST=db.dw142.webglobe.com
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

**Hotovo 29. 9.** Postup zůstává pro případ, že by se přesun opakoval.

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

Stav serveru: první verzi nahrál CEO 29. 9. ručně
(`/home/html/ondraweb.cz/app/releases/20260929024516-bff0683`, `current`
a `public_html` už jsou odkazy). První nasazení z GitHubu tedy vytvoří druhou
verzi a přepne na ni. Na ruční verzi se jde vrátit akcí `rollback`.

Na čistém serveru (kdyby se hosting zakládal znovu) se dosavadní obsah
webového adresáře `/home/html/ondraweb.cz/public_html` při prvním nasazení
**přejmenuje** na `public_html.pred-ondraweb-<datum>`. Nic se nemaže. Místo
něj vznikne odkaz na nový web.

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
- **Žluté upozornění po nasazení** („…/up vrací HTTP … místo 200“): nová verze
  je přepnutá, ale web zvenku neodpovídá. Otevři web, a když je rozbitý,
  spusť `rollback`.
- **Změna konfigurace** (heslo k poště apod.): `.env` se mění jen na serveru
  přes SSH. Úprava jde přes kopii, ať se živý soubor nikdy nepřepisuje
  napůl:

  ```sh
  bash
  cd /home/html/ondraweb.cz/app/shared
  cp -p .env .env.new
  nano .env.new          # nebo vi; uprav hodnoty
  mv -f .env.new .env
  ```

  (První řádek `bash`: přihlašovací shell na Webglobe je fish.) Konfigurace
  je v cache, proto potom spusť nasazení: Actions → „Nasazení produkce
  (Webglobe)“ → Run workflow → akce `deploy`. Nová verze si `.env` načte
  znovu.
- **Změna hesla k databázi:** nejdřív v administraci Webglobe (Hosting →
  Databáze, uživatel `ondraweb_adminik`), hned potom v SSH konzoli:

  ```sh
  bash /home/html/ondraweb.cz/app/current/scripts/deploy/change-db-password.sh
  ```

  Skript si heslo vyžádá (při psaní se nezobrazuje), nejdřív ho vyzkouší
  proti databázi a teprve pak ho zapíše do `.env`, obnoví cache konfigurace
  (i ve starších verzích kvůli `rollback`) a vyzkouší stránky. Když databáze
  heslo nepřijme, `.env` nezmění. Nasazení spouštět není potřeba. Web je bez
  databáze jen mezi uložením hesla v administraci a doběhnutím skriptu.
  Heslo nikam nevkládej ani neposílej, stačí ho mít ve schránce.

---

## Technická příloha

Soubory: `.github/workflows/deploy-production.yml` (build na GitHubu, SSH
přes náš server, nahrání), `scripts/deploy/remote-deploy.sh` (kroky na
serveru), `scripts/deploy/smoke.php` (zkouška stránek nové verze),
`scripts/deploy/change-db-password.sh` (výměna hesla k databázi, spouští se
ručně, viz F).

**Přihlašovací shell na Webglobe je `fish`, ne bash.** Workflow proto na
server neposílá příkazy přímo přes `ssh host "…"` (fish nezná třeba `$(...)`).
Všechno jde přes pomocníka `rbash` = `ssh webglobe bash -s` se skriptem na
standardním vstupu. Fish dostane jen `bash -s`, skript pak běží v bashi.
Výjimka je `rsync`: vzdálený `rsync --server …` jsou jen argumenty, fish je
spustí bez potíží.

**Databáze:** ze SSH je dosažitelná přes `db.dw142.webglobe.com` (ověřil CEO
29. 9.: `migrate`, seedery i `smoke.php` přes SSH nad produkční databází
prošly). Proto `DB_HOST=db.dw142.webglobe.com` v `shared/.env`.
`c-mariadb` z administrace SSH v DNS nenajde.

**Rozložení na serveru** (absolutní cesty, ne v domovském adresáři SSH účtu
`/home/html/multi_608671`):

```
/home/html/ondraweb.cz/app/releases/<UTC čas>-<commit>/   jednotlivé verze, drží se posledních 5
/home/html/ondraweb.cz/app/shared/.env                    produkční konfigurace, jen na serveru, nasazení ji nemění
/home/html/ondraweb.cz/app/shared/storage/                nahrané soubory, logy (storage/logs), cache
/home/html/ondraweb.cz/app/current -> releases/<id>       aktivní verze
/home/html/ondraweb.cz/public_html -> app/current/public  webový adresář (doc root) domény
```

**Průběh nasazení na serveru:** kontrola PHP 8.4 a rozšíření → propojení
`storage` a `.env` → `migrate --force` → seedery `AdminUserSeeder`,
`EnsureArticlesSeededSeeder`, `EnsurePortfolioSeededSeeder` → `storage:link`,
`optimize` → zkouška `/up`, `/`, `/en/`, `/de/`, `/projekty`, `/zapisky`,
`/robots.txt`, `/sitemap.xml` přímo přes HTTP kernel nového release → přepnutí
`current` a `public_html` → kontrola `WEBGLOBE_WEB_URL/up` přes skutečný web
(jen upozornění) → úklid starých verzí. Selže-li cokoli před přepnutím, web
zůstane na předchozí verzi. Verze, která nikdy neběžela, se při `rollback`
přeskočí. Verze, ze které se přepíná, dostane značku `.deployed` taky (týká se
ruční první verze od CEO).

**Volitelné proměnné** (Settings → Secrets and variables → Actions →
Variables), jen když se Webglobe liší od předpokladu:

| Proměnná | Výchozí | Kdy změnit |
|---|---|---|
| `WEBGLOBE_WEB_ROOT` | `/home/html/ondraweb.cz/public_html` | doc root domény je jinde |
| `WEBGLOBE_APP_DIR` | `/home/html/ondraweb.cz/app` | aplikace má ležet jinde |
| `WEBGLOBE_WEB_URL` | `https://ondraweb.cz` | kontrola po přepnutí má jít na jinou adresu |
| `WEBGLOBE_PHP_BIN` | `php8.4` | PHP 8.4 je na serveru pod jiným příkazem |
| `WEBGLOBE_SSH_USER` | `ssh-608671` | SSH účet se změnil |
| `WEBGLOBE_SSH_PORT` | `20001` | port WebSSH se změnil |

**Volitelné secrets:** `WEBGLOBE_SSH_KEY` (místo hesla),
`WEBGLOBE_SSH_KNOWN_HOSTS` (otisk serveru, výstup `ssh-keyscan <host>`. Bez
něj se otisk přijme při každém běhu).

**Spojení přes náš server je povinné, ne volitelné.** Webglobe zahazuje
spojení z části adres na internetu, mezi nimi ze všech runnerů GitHubu (29. 9.
změřeno: `Connection timed out` na portech 22, 20001 i 443). Workflow proto
vždy jde přes `ProxyJump` na `paperclip@46.224.218.19` (secret
`DEPLOY_JUMP_KEY` je tedy povinný, kontroluje se v prvním kroku workflow),
který se na `dw142.webglobe.com:20001` dostane přes IPv6. Klíč je na našem
serveru v `~paperclip/.ssh/authorized_keys` omezený na
`restrict,port-forwarding,permitopen="dw142.webglobe.com:20001",permitlisten="127.0.0.1:1",command="/bin/false"`,
takže neotevře shell ani spojení jinam. Soukromá část je na našem serveru
v `~paperclip/.ssh/ond459-jump-key`. Pozor na zkoušky se špatným heslem:
opakované neúspěšné přihlášení může Webglobe vyhodnotit jako útok a adresu
zablokovat.

**WebSSH musí být zapnuté trvale.** Zdarma se WebSSH na Webglobe zapíná jen
na hodinu a pak se samo vypne — to nasazení z GitHubu spouštěné kdykoli
nevystačí. Je potřeba **Permanentní SSH konzole** (krok A5), jinak nasazení
skončí na „Connection timed out“ nebo „open failed“ s hláškou, že WebSSH
vypršelo.

**Když hosting nedovolí nahradit `public_html` odkazem** (nasazení skončí
hláškou „Nejde přejmenovat“ / „Nejde vytvořit symlink“): v administraci
nastav kořenový adresář domén na `app/current/public`, pokud to jde, a
proměnnou `WEBGLOBE_WEB_ROOT` na nepoužívanou cestu.

**Co je ověřené přímo na produkci (WebSSH, 29. 9., OND-461, CEO):** SSH
uživatel/host/port, přihlašovací shell fish, absolutní cesty doc rootu
a aplikace, `public_html` jako odkaz web obslouží, databáze ze SSH přes
`db.dw142.webglobe.com`, na SSH je `php8.4`, `rsync`, `composer`, `git`,
`curl`. Web běží na PHP 8.4.24.

**Co ověřuje jen lokální simulace (mimo repozitář, popis v OND-461):** celé
workflow proti kontejneru s fish jako přihlašovacím shellem, přes náš server,
z výchozího stavu s ruční první verzí: nasazení, druhé nasazení, `rollback`
až na ruční verzi. Živé nasazení na Webglobe dělá CEO.
