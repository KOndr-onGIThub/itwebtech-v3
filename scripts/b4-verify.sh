#!/usr/bin/env bash
# B4 verification runner (OND-132)
# ────────────────────────────────────
# Pustí kompletní B4 suite proti zvolenému baseUrl:
#   1. Static check (JSON-LD / hreflang / canonical / Consent v2)
#   2. Lighthouse mobile per URL → JSON do storage/b4/lighthouse/
#   3. axe-core CLI per URL      → JSON do storage/b4/axe/
#
# Requirements (lokální / CI host):
#   - Node 20+
#   - Chrome / Chromium dostupný v PATH (CHROME_PATH env aby Lighthouse našel
#     headless binary; defaultně Playwright cache, viz níže).
#   - npm i -g lighthouse @axe-core/cli   nebo `npx` ekvivalenty.
#
# Usage:
#   ./scripts/b4-verify.sh https://preview-sitewide-redesign.itwebtech.cz \
#       [--project hellsearch] [--article jak-vybrat-cms]
#
# Output:
#   storage/b4-static-report.json
#   storage/b4/lighthouse/<locale>-<label>.json
#   storage/b4/axe/<locale>-<label>.json
#   storage/b4/summary.md   ← Markdown report agregující všechny tři vrstvy.
set -euo pipefail

BASE="${1:-}"
shift || true
PROJECT_SLUG="hellsearch"
ARTICLE_SLUG="jak-vybrat-cms"

while [[ $# -gt 0 ]]; do
    case "$1" in
        --project) PROJECT_SLUG="$2"; shift 2;;
        --article) ARTICLE_SLUG="$2"; shift 2;;
        *) echo "unknown arg: $1"; exit 2;;
    esac
done

if [[ -z "$BASE" ]]; then
    echo "usage: $0 <base-url> [--project <slug>] [--article <slug>]" >&2
    exit 2
fi

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.."; pwd)"
OUT="$ROOT/storage/b4"
mkdir -p "$OUT/lighthouse" "$OUT/axe"

# Defaultní Chrome binary z Playwright cache (Paperclip workspace má jen tu).
if [[ -z "${CHROME_PATH:-}" ]]; then
    for c in \
        "$HOME/.cache/ms-playwright/chromium-1217/chrome-linux64/chrome" \
        "$HOME/.cache/ms-playwright/chromium-1181/chrome-linux/chrome" \
        "$(command -v chromium 2>/dev/null || true)" \
        "$(command -v chromium-browser 2>/dev/null || true)" \
        "$(command -v google-chrome 2>/dev/null || true)"; do
        if [[ -n "$c" && -x "$c" ]]; then
            export CHROME_PATH="$c"
            break
        fi
    done
fi
echo "[b4] CHROME_PATH=${CHROME_PATH:-<none>}"
echo "[b4] base=$BASE"

# Page set: cs_path|en_path|de_path|label (project/article placeholdery)
PAGES=$(grep -v '^#' "$ROOT/scripts/b4-pages.txt" | grep -v '^$')

run_lh() {
    local url="$1" outfile="$2"
    npx --yes lighthouse "$url" \
        --quiet \
        --chrome-flags="--headless=new --no-sandbox --disable-gpu" \
        --form-factor=mobile \
        --throttling-method=simulate \
        --only-categories=performance,accessibility,best-practices,seo \
        --output=json --output-path="$outfile" \
        --max-wait-for-load=45000 || echo "[b4] lighthouse FAIL $url"
}

run_axe() {
    local url="$1" outfile="$2"
    npx --yes @axe-core/cli "$url" \
        --chromium-path "${CHROME_PATH:-}" \
        --exit \
        --save "$outfile" || echo "[b4] axe FAIL $url"
}

# 1) Static check
echo "[b4] ── static check ───────────────"
node "$ROOT/scripts/b4-static-check.mjs" \
    --base "$BASE" --project "$PROJECT_SLUG" --article "$ARTICLE_SLUG" \
    --out "$ROOT/storage/b4-static-report.json" || true

# 2 + 3) Lighthouse + axe per URL
echo "[b4] ── lighthouse + axe ─────────────"
while IFS='|' read -r cs en de label; do
    cs="$(echo "$cs" | xargs)"; en="$(echo "$en" | xargs)"
    de="$(echo "$de" | xargs)"; label="$(echo "$label" | xargs)"
    for entry in "cs|$cs" "en|$en" "de|$de"; do
        locale="${entry%%|*}"; rel="${entry#*|}"
        rel="${rel//\{project\}/$PROJECT_SLUG}"
        rel="${rel//\{article\}/$ARTICLE_SLUG}"
        url="${BASE%/}$rel"
        echo "  · $locale  $label  $rel"
        run_lh  "$url" "$OUT/lighthouse/${locale}-${label}.json"
        run_axe "$url" "$OUT/axe/${locale}-${label}.json"
    done
done <<< "$PAGES"

# 4) Aggregate summary
echo "[b4] ── summary ───────────────────"
node "$ROOT/scripts/b4-summarize.mjs" --out-dir "$OUT" || true

echo "[b4] done → $OUT/summary.md"
