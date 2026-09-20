#!/bin/sh
# Production asset build (`npm run build`).
#
# Wraps `vite build` with memory guards. The deploy host has no swap, so when
# the build's peak RSS collides with everything else running there, the kernel
# SIGKILLs vite mid-transform and Coolify reports "Deployment failed" with no
# error message at all (OND-239). Two things happen here:
#   1. cap the memory peak of the image pipeline,
#   2. make a kill visible in the build log instead of a truncated one.
#
# Kept POSIX sh on purpose — this runs inside the nixpacks build image.
set -u

cd "$(dirname "$0")/.." || exit 1

# Rollup fires the vite-imagetools load hooks for every AVIF/WebP variant at
# once and they queue on libuv's thread pool, so the pool size decides how many
# decoded images sit in memory at the same time. Measured on the full asset set
# (18 sources -> 70 variants): default pool ~690 MB peak / 8 s, size 2 ~565 MB /
# 11 s, size 1 ~449 MB / 19 s. Deploys are not time-critical, headroom is, so
# take the slowest and smallest. (VIPS_CONCURRENCY was measured too and made no
# difference on this asset set — that is why it is not set here.)
UV_THREADPOOL_SIZE="${UV_THREADPOOL_SIZE:-1}"

# Bound the JS heap so a runaway bundle dies with a readable V8 error instead of
# growing until the OOM killer takes the process down silently.
NODE_OPTIONS="${NODE_OPTIONS:-} --max-old-space-size=1024"

export UV_THREADPOOL_SIZE NODE_OPTIONS

echo "[build-assets] UV_THREADPOOL_SIZE=$UV_THREADPOOL_SIZE NODE_OPTIONS=$NODE_OPTIONS"
if [ -r /proc/meminfo ]; then
    echo "[build-assets] $(grep -E '^(MemTotal|MemAvailable|SwapTotal):' /proc/meminfo | tr -s ' ' | tr '\n' ' ')"
fi

./node_modules/.bin/vite build "$@"
status=$?

if [ "$status" -ne 0 ]; then
    echo "[build-assets] vite build FAILED with exit code $status" >&2
    if [ "$status" -eq 137 ] || [ "$status" -eq 135 ]; then
        echo "[build-assets] exit $status = killed by a signal, almost certainly the OOM killer." >&2
        echo "[build-assets] The build ran out of memory — this is NOT an error in the code being deployed." >&2
        echo "[build-assets] Check 'grep -i oom /proc/vmstat' and 'swapon --show' on the host." >&2
    fi
else
    echo "[build-assets] vite build OK"
fi

exit "$status"
