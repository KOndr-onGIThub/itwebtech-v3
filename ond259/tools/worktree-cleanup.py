#!/usr/bin/env python3
"""Vlna 2: worktree uzavřených issue, kde jediná změna jsou netrackované pracovní
složky agentů. Ty se zazálohují do .paperclip/archive/, teprve pak se worktree maže."""
import json, os, re, subprocess, sys, tarfile, time

ROOT = "/home/paperclip/workspaces/itwebtech/repo"
WT = os.path.join(ROOT, ".paperclip/worktrees")
ARCH = os.path.join(ROOT, ".paperclip/archive")
os.makedirs(ARCH, exist_ok=True)
status = json.load(open("/tmp/ceo_issue_status.json"))
CLOSED = {"done", "cancelled"}
NOW = time.time()
APPLY = "--apply" in sys.argv

total = 0
for name in sorted(os.listdir(WT)):
    path = os.path.join(WT, name)
    if not os.path.isdir(path) or name.startswith("OND-259"):
        continue
    m = re.match(r"(OND-\d+)", name)
    st = status.get(m.group(1) if m else "", "?")
    if st not in CLOSED:
        continue

    def git(*a):
        return subprocess.run(["git"] + list(a), cwd=path, capture_output=True, text=True).stdout

    porcelain = [l for l in git("status", "--porcelain").splitlines() if l.strip()]
    if any(not l.startswith("??") for l in porcelain):
        print(f"PŘESKOČIT {name}: má trackované změny")
        continue
    head = git("rev-parse", "HEAD").strip()
    if not git("branch", "-r", "--contains", head).strip():
        print(f"PŘESKOČIT {name}: HEAD není na originu")
        continue
    newest = 0
    for dp, dn, fn in os.walk(path):
        dn[:] = [d for d in dn if d not in ("node_modules", "vendor", ".git", "reference")]
        for f in fn:
            try:
                newest = max(newest, os.lstat(os.path.join(dp, f)).st_mtime)
            except OSError:
                pass
    age = (NOW - newest) / 86400
    if age < 7:
        print(f"PŘESKOČIT {name}: aktivita před {age:.1f} dny")
        continue

    untracked = [l[3:].strip().strip('"') for l in porcelain]
    size = int(subprocess.run(["du", "-sm", path], capture_output=True, text=True).stdout.split()[0])
    print(f"{'MAŽU' if APPLY else 'DRY'} {name} ({size} MB, stáří {age:.0f} d) — záloha: {', '.join(untracked)}")
    if not APPLY:
        total += size
        continue
    tarpath = os.path.join(ARCH, f"{name}.tar.gz")
    try:
        with tarfile.open(tarpath, "w:gz") as t:
            for u in untracked:
                src = os.path.join(path, u)
                if os.path.exists(src):
                    t.add(src, arcname=os.path.join(name, u))
    except Exception as e:  # nečitelný soubor (cizí vlastník) — worktree pak nechávám být
        print(f"   ZÁLOHA SELHALA ({e.__class__.__name__}), worktree nechávám: {name}")
        continue
    r = subprocess.run(["git", "worktree", "remove", "--force", os.path.join(".paperclip/worktrees", name)],
                       cwd=ROOT, capture_output=True, text=True)
    if r.returncode:
        print("   FAIL:", r.stderr.strip()[:140])
    else:
        total += size
print(f"\nCelkem {'uvolněno' if APPLY else 'k uvolnění'}: {total} MB")
