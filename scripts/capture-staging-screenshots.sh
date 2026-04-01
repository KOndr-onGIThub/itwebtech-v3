#!/usr/bin/env bash
set -euo pipefail

BASE_URL="${SCREENSHOT_BASE_URL:-https://itwebtech.ondrejkriska.cz}"
OUT_DIR="${SCREENSHOT_OUTPUT_DIR:-/home/paperclip/workspaces/itwebtech/screenshots}"
PREFIX="${SCREENSHOT_PREFIX:-OND-11}"

mkdir -p "$OUT_DIR"

capture() {
  local name="$1"
  local width="$2"
  local height="$3"
  local output="$OUT_DIR/${PREFIX}-${name}-${width}x${height}.png"

  npx playwright screenshot \
    --browser=chromium \
    --full-page \
    --wait-for-timeout=1800 \
    --viewport-size="${width},${height}" \
    "$BASE_URL" \
    "$output"

  printf '%s\n' "$output"
}

capture mobile 375 812
capture tablet 768 1024
capture desktop 1440 900
capture 4k 2560 1440
