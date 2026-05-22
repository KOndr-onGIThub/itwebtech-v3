#!/usr/bin/env python3
"""OND-138 QA regression matrix — automated checks against staging."""
import json
import re
import sys
import urllib.request
import urllib.error
from urllib.parse import urljoin

BASE = "https://itwebtech.ondrejkriska.cz"

PAGES = [
    # (page_type, locale, url, expected_lang)
    ("home", "cs", f"{BASE}/", "cs"),
    ("home", "en", f"{BASE}/en/", "en"),
    ("home", "de", f"{BASE}/de/", "de"),
    ("contact", "cs", f"{BASE}/kontakt", "cs"),
    ("contact", "en", f"{BASE}/en/contact", "en"),
    ("contact", "de", f"{BASE}/de/kontakt", "de"),
    ("price", "cs", f"{BASE}/cenik", "cs"),
    ("price", "en", f"{BASE}/en/price", "en"),
    ("price", "de", f"{BASE}/de/preisliste", "de"),
    ("projects", "cs", f"{BASE}/projekty", "cs"),
    ("projects", "en", f"{BASE}/en/projects", "en"),
    ("projects", "de", f"{BASE}/de/projekte", "de"),
    ("project_detail", "cs", f"{BASE}/projekty/yolk", "cs"),
    ("project_detail", "en", f"{BASE}/en/projects/yolk", "en"),
    ("project_detail", "de", f"{BASE}/de/projekte/yolk", "de"),
    ("blog", "cs", f"{BASE}/jak-na-to", "cs"),
    ("blog", "en", f"{BASE}/en/blog", "en"),
    ("blog", "de", f"{BASE}/de/blog", "de"),
    ("article", "cs", f"{BASE}/jak-na-to/kolik-stoji-webove-stranky", "cs"),
    ("article", "en", f"{BASE}/en/blog/how-much-does-a-website-cost", "en"),
    ("article", "de", f"{BASE}/de/blog/kolik-stoji-webove-stranky", "de"),
    ("privacy", "cs", f"{BASE}/zasady-ochrany-osobnich-udaju", "cs"),
    ("privacy", "en", f"{BASE}/en/privacy-policy", "en"),
    ("privacy", "de", f"{BASE}/de/datenschutz", "de"),
    # OND-168: /cookies je nově lokalizovaný (cs/en/de) — předtím sdílený CS-only per OND-125.
    ("cookies", "cs", f"{BASE}/cookies", "cs"),
    ("cookies", "en", f"{BASE}/en/cookies", "en"),
    ("cookies", "de", f"{BASE}/de/cookies", "de"),
]

def fetch(url):
    req = urllib.request.Request(url, headers={"User-Agent": "OND-138-qa-bot/1.0"})
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            body = resp.read().decode("utf-8", errors="replace")
            return resp.status, dict(resp.getheaders()), body
    except urllib.error.HTTPError as e:
        return e.code, dict(e.headers or {}), e.read().decode("utf-8", errors="replace")
    except Exception as e:
        return 0, {}, f"ERR: {e}"

def find_lang(html):
    m = re.search(r'<html[^>]+lang="([a-z-]+)"', html)
    return m.group(1) if m else None

def find_title(html):
    m = re.search(r'<title>([^<]+)</title>', html)
    return m.group(1).strip() if m else None

def find_meta_desc(html):
    m = re.search(r'<meta\s+name="description"\s+content="([^"]+)"', html)
    return m.group(1).strip() if m else None

def find_canonical(html):
    m = re.search(r'<link\s+rel="canonical"\s+href="([^"]+)"', html)
    return m.group(1) if m else None

def find_hreflangs(html):
    return re.findall(r'<link[^>]+hreflang="([^"]+)"[^>]+href="([^"]+)"', html)

def find_jsonld_blocks(html):
    blocks = re.findall(r'<script[^>]+type="application/ld\+json"[^>]*>(.*?)</script>', html, re.DOTALL)
    parsed = []
    for b in blocks:
        try:
            j = json.loads(b)
            parsed.append({"ok": True, "type": j.get("@type") if isinstance(j, dict) else "graph", "raw_len": len(b)})
        except Exception as e:
            parsed.append({"ok": False, "err": str(e), "raw_len": len(b)})
    return parsed

def find_ga4(html):
    return bool(re.search(r'G-SK49PHW6PP|gtag\(', html))

def find_clarity(html):
    return bool(re.search(r'clarity\.ms|wqlhcxhtti', html))

def find_consent_modal(html):
    return bool(re.search(r'(?i)cookie-consent|cookie-modal|consent-banner|cookies\.js|cookieConsent', html))

def find_csrf(html):
    return bool(re.search(r'<meta\s+name="csrf-token"', html))

def find_primary_cta_count(html):
    # CTA markers: data-analytics="cta_primary_click" or class containing "btn-primary"/"cta-primary"
    a = len(re.findall(r'data-analytics="cta_primary_click"', html))
    b = len(re.findall(r'data-analytics-event="cta_primary_click"', html))
    return a + b

def find_amber_misuse(html):
    # Amber should only appear inside CTA elements; flag standalone amber on non-CTA
    # Quick check: count "amber" usages
    return len(re.findall(r'(?i)bg-amber|text-amber|border-amber|--amber', html))

def find_dark_patterns(html):
    findings = []
    if re.search(r'(?i)checked["\s]+\w*gdpr|gdpr.*checked', html):
        findings.append("pre-checked GDPR")
    if re.search(r'(?i)data-countdown|class="[^"]*countdown', html):
        findings.append("countdown timer")
    return findings

def check_url(page_type, locale, url, expected_lang):
    status, headers, body = fetch(url)
    res = {
        "page_type": page_type,
        "locale": locale,
        "url": url,
        "status": status,
        "html_lang": find_lang(body),
        "expected_lang": expected_lang,
        "title": find_title(body),
        "title_len": len(find_title(body) or ""),
        "meta_desc_len": len(find_meta_desc(body) or ""),
        "canonical": find_canonical(body),
        "hreflang_count": len(find_hreflangs(body)),
        "hreflangs": find_hreflangs(body),
        "jsonld_blocks": find_jsonld_blocks(body),
        "ga4": find_ga4(body),
        "clarity": find_clarity(body),
        "consent_modal_markup": find_consent_modal(body),
        "csrf_meta": find_csrf(body),
        "primary_cta_count": find_primary_cta_count(body),
        "amber_uses": find_amber_misuse(body),
        "dark_pattern_findings": find_dark_patterns(body),
        "content_len": len(body),
        "headers": {
            "content-type": headers.get("Content-Type"),
            "x-content-type-options": headers.get("X-Content-Type-Options"),
            "x-frame-options": headers.get("X-Frame-Options"),
            "strict-transport-security": headers.get("Strict-Transport-Security"),
            "referrer-policy": headers.get("Referrer-Policy"),
            "permissions-policy": headers.get("Permissions-Policy"),
            "content-encoding": headers.get("Content-Encoding"),
        },
    }
    return res

if __name__ == "__main__":
    out = []
    for pt, loc, url, lang in PAGES:
        print(f"checking {url}", file=sys.stderr)
        out.append(check_url(pt, loc, url, lang))
    print(json.dumps(out, ensure_ascii=False, indent=2))
