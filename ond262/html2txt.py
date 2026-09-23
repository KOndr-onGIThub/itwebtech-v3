import sys, re, html, glob, os
def conv(p):
    s = open(p, encoding='utf-8').read()
    s = re.sub(r'(?is)<(script|style|svg|noscript)\b.*?</\1>', ' ', s)
    s = re.sub(r'(?is)<!--.*?-->', ' ', s)
    s = re.sub(r'(?i)<(br|/p|/div|/li|/h[1-6]|/tr|/td|/section|/article)\s*/?>', '\n', s)
    s = re.sub(r'(?s)<[^>]+>', ' ', s)
    s = html.unescape(s)
    s = re.sub(r'[ \t ]+', ' ', s)
    s = re.sub(r'\n\s*\n+', '\n', s)
    return '\n'.join(l.strip() for l in s.split('\n') if l.strip())
for p in sys.argv[1:]:
    open(os.path.splitext(p)[0] + '.txt', 'w', encoding='utf-8').write(conv(p))
