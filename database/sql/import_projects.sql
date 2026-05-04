-- ===========================================================================
-- Transformační SQL: staré tabulky projects + projects_screens → nová struktura
--
-- Tento soubor:
--  1. Vytvoří dočasné tabulky se starou strukturou
--  2. Naplní je daty z SQL dumpu (projects.sql, projects_screens.sql)
--  3. Transformuje data do nových tabulek (projects, project_translations,
--     project_slugs, project_screens)
--  4. Odstraní dočasné tabulky
--
-- Spuštění: DB::unprepared(file_get_contents('database/sql/import_projects.sql'))
-- ===========================================================================

-- ------------------------------------------------
-- 1. Dočasná tabulka pro starou strukturu projects
-- ------------------------------------------------
CREATE TEMPORARY TABLE _old_projects (
  id          bigint unsigned NOT NULL,
  url         varchar(255) DEFAULT NULL,
  kind        varchar(255) DEFAULT NULL,
  price_czk   varchar(255) DEFAULT NULL,
  price_eur   varchar(255) DEFAULT NULL,
  comparison  tinyint(1) NOT NULL DEFAULT 0,
  img_before  varchar(255) DEFAULT NULL,
  img_after   varchar(255) DEFAULT NULL,
  customer    varchar(255) DEFAULT NULL,
  name        varchar(255) DEFAULT NULL,
  sorting     int DEFAULT NULL,
  img         varchar(255) DEFAULT NULL,
  og_img      varchar(255) DEFAULT NULL,
  description text DEFAULT NULL,
  content     longtext DEFAULT NULL,
  testimonial text DEFAULT NULL,
  client_photo           varchar(255) DEFAULT NULL,
  client_name            varchar(255) DEFAULT NULL,
  client_role            varchar(255) DEFAULT NULL,
  client_says            text DEFAULT NULL,
  testimonial_source_img varchar(255) DEFAULT NULL,
  testimonial_src        varchar(255) DEFAULT NULL,
  cta         varchar(255) DEFAULT NULL,
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ------------------------------------------------
-- 2. Dočasná tabulka pro starou strukturu projects_screens
-- ------------------------------------------------
CREATE TEMPORARY TABLE _old_project_screens (
  id          bigint unsigned NOT NULL,
  id_project  bigint unsigned NOT NULL,
  is_video    tinyint(1) NOT NULL DEFAULT 0,
  video_url   varchar(255) DEFAULT NULL,
  screen_shot varchar(255) DEFAULT NULL,
  sorting     int unsigned NOT NULL DEFAULT 0,
  title       varchar(255) DEFAULT NULL,
  description text DEFAULT NULL,
  created_at  timestamp NULL DEFAULT NULL,
  updated_at  timestamp NULL DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
