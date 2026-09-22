#!/usr/bin/env python3
"""Bezpečnostní analýza worktree před úklidem. Dry-run: nic nemaže."""
import json, os, re, subprocess, sys, time

ROOT = "/home/paperclip/workspaces/itwebtech/repo"
WT = os.path.join(ROOT, ".paperclip/worktrees")
status = json.load(open("/tmp/ceo_issue_status.json"))
OPEN = {"todo", "in_progress", "in_review", "blocked", "backlog"}
NOW = time.time()


def git(args, cwd=ROOT):
    return subprocess.run(["git"] + args, cwd=cwd, capture_output=True, text=True).stdout.strip()


rows = []
for name in sorted(os.listdir(WT)):
    path = os.path.join(WT, name)
    if not os.path.isdir(path):
        continue
    m = re.match(r"(OND-\d+)", name)
    ident = m.group(1) if m else None
    st = status.get(ident, "?")
    dirty = git(["status", "--porcelain"], path)
    n_dirty = len([l for l in dirty.splitlines() if l.strip()])
    head = git(["rev-parse", "HEAD"], path)
    # je HEAD dosažitelný z nějaké remote větve?
    remote = git(["branch", "-r", "--contains", head], path)
    on_remote = bool(remote.strip())
    # nejnovější mtime v pracovním stromu (bez node_modules/vendor)
    newest = 0
    for dirpath, dirnames, filenames in os.walk(path):
        dirnames[:] = [d for d in dirnames if d not in ("node_modules", "vendor", ".git", "reference")]
        for f in filenames:
            try:
                t = os.lstat(os.path.join(dirpath, f)).st_mtime
                if t > newest:
                    newest = t
            except OSError:
                pass
    age_days = (NOW - newest) / 86400 if newest else 999
    size = subprocess.run(["du", "-sm", path], capture_output=True, text=True).stdout.split()[0]
    rows.append(dict(name=name, ident=ident, status=st, dirty=n_dirty, on_remote=on_remote,
                     age=round(age_days, 1), size_mb=int(size), head=head[:8]))

safe, keep = [], []
for r in rows:
    reasons = []
    if r["name"].startswith("OND-259"):
        reasons.append("aktuální worktree")
    if r["status"] in OPEN:
        reasons.append(f"issue {r['status']}")
    if r["status"] == "?":
        reasons.append("issue neznámá")
    if r["dirty"]:
        reasons.append(f"{r['dirty']} nescommitovaných změn")
    if not r["on_remote"]:
        reasons.append("HEAD není na originu")
    if r["age"] < 2:
        reasons.append(f"aktivita před {r['age']} dny")
    (keep if reasons else safe).append((r, reasons))

print(f"{'worktree':60} {'issue':9} {'MB':>6} {'stáří':>6} verdikt")
for r, reasons in sorted(rows and keep, key=lambda x: -x[0]["size_mb"]):
    print(f"{r['name'][:58]:60} {r['status']:9} {r['size_mb']:6} {r['age']:6} NECHAT: {', '.join(reasons)}")
print()
tot = 0
for r, _ in sorted(safe, key=lambda x: -x[0]["size_mb"]):
    tot += r["size_mb"]
    print(f"{r['name'][:58]:60} {r['status']:9} {r['size_mb']:6} {r['age']:6} SMAZAT")
print(f"\nSMAZAT: {len(safe)} worktree, {tot} MB | NECHAT: {len(keep)}")
json.dump([r["name"] for r, _ in safe], open("/tmp/ceo_worktrees_to_remove.json", "w"))
