{{-- OND-485: XSL styl pro /sitemap.xml. Prohlížeč podle něj vykreslí tabulku,
     vyhledávače instrukci ignorují. Prefixy URL počítá SitemapController::stylesheet(). --}}
@php
    $startsWithAny = fn (array $list) => implode(' or ', array_map(
        fn ($prefix) => "starts-with(s:loc, '" . $prefix . "')",
        $list,
    ));
    $isProject = $startsWithAny($prefixes['project']);
    $isArticle = $startsWithAny($prefixes['article']);
@endphp
{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    xmlns:s="http://www.sitemaps.org/schemas/sitemap/0.9"
    exclude-result-prefixes="s">

    <xsl:output method="html" encoding="UTF-8" indent="yes" doctype-system="about:legacy-compat"/>

    <xsl:variable name="base" select="'{{ $base }}'"/>

    <xsl:template match="/">
        <html lang="cs">
        <head>
            <meta charset="UTF-8"/>
            <meta name="viewport" content="width=device-width, initial-scale=1"/>
            <meta name="robots" content="noindex"/>
            <title>Mapa webu – {{ parse_url($base, PHP_URL_HOST) }}</title>
            <style>
                :root { color-scheme: dark; }
                * { box-sizing: border-box; }
                body {
                    margin: 0; padding: 48px 24px 64px;
                    background: #0A0A0B; color: #F2F0EA;
                    font: 15px/1.5 'Inter Variable', Inter, ui-sans-serif, system-ui, -apple-system, 'Segoe UI', sans-serif;
                }
                main { max-width: 960px; margin: 0 auto; }
                h1 { font-size: 32px; line-height: 1.15; margin: 0 0 8px; letter-spacing: -0.01em; }
                h1::before { content: ''; display: inline-block; width: 12px; height: 12px; margin-right: 12px; background: #D8FF3A; border-radius: 2px; vertical-align: middle; }
                .lead { color: #9B9AA0; margin: 0 0 40px; max-width: 640px; }
                h2 { font-size: 18px; margin: 40px 0 12px; display: flex; gap: 8px; align-items: baseline; }
                h2 span { color: #9B9AA0; font-weight: 400; font-size: 14px; }
                table { width: 100%; border-collapse: collapse; background: #131316; border: 1px solid #26262B; border-radius: 8px; overflow: hidden; }
                th, td { text-align: left; padding: 10px 14px; border-bottom: 1px solid #26262B; vertical-align: top; }
                tr:last-child td { border-bottom: 0; }
                th { white-space: nowrap; font-size: 12px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.06em; color: #9B9AA0; background: #1B1B20; }
                td.url { word-break: break-all; }
                td.lang, td.date { white-space: nowrap; color: #9B9AA0; }
                td.lang { width: 1%; text-transform: uppercase; font-size: 13px; }
                td.date { width: 1%; font-variant-numeric: tabular-nums; }
                a { color: #F2F0EA; text-decoration: none; border-bottom: 1px solid #3A3A41; }
                a:hover { color: #D8FF3A; border-bottom-color: #D8FF3A; }
                footer { margin-top: 40px; color: #9B9AA0; font-size: 13px; }
            </style>
        </head>
        <body>
            <main>
                <h1>Mapa webu</h1>
                <p class="lead">
                    Seznam všech veřejných stránek webu pro vyhledávače
                    (<xsl:value-of select="count(s:urlset/s:url)"/> adres ve třech jazycích).
                    Nový projekt nebo zápisek se tu objeví sám hned po zveřejnění.
                </p>

                <xsl:call-template name="group">
                    <xsl:with-param name="title" select="'Stránky'"/>
                    <xsl:with-param name="urls" select="s:urlset/s:url[not({!! $isProject !!}) and not({!! $isArticle !!})]"/>
                </xsl:call-template>
                <xsl:call-template name="group">
                    <xsl:with-param name="title" select="'Projekty'"/>
                    <xsl:with-param name="urls" select="s:urlset/s:url[{!! $isProject !!}]"/>
                </xsl:call-template>
                <xsl:call-template name="group">
                    <xsl:with-param name="title" select="'Zápisky'"/>
                    <xsl:with-param name="urls" select="s:urlset/s:url[{!! $isArticle !!}]"/>
                </xsl:call-template>

                <footer>Strojová verze pro vyhledávače je tentýž soubor – tohle je jen jeho čitelné zobrazení.</footer>
            </main>
        </body>
        </html>
    </xsl:template>

    <xsl:template name="group">
        <xsl:param name="title"/>
        <xsl:param name="urls"/>
        <xsl:if test="count($urls) &gt; 0">
            <h2><xsl:value-of select="$title"/> <span><xsl:value-of select="count($urls)"/></span></h2>
            <table>
                <thead>
                    <tr><th>Adresa</th><th>Jazyk</th><th>Poslední změna</th></tr>
                </thead>
                <tbody>
                    <xsl:for-each select="$urls">
                        <tr>
                            <td class="url">
                                <a href="{s:loc}">
                                    <xsl:choose>
                                        <xsl:when test="starts-with(s:loc, $base)"><xsl:value-of select="substring-after(s:loc, $base)"/></xsl:when>
                                        <xsl:otherwise><xsl:value-of select="s:loc"/></xsl:otherwise>
                                    </xsl:choose>
                                </a>
                            </td>
                            <td class="lang">
                                <xsl:choose>
@foreach ($prefixes['lang'] as $locale => $prefix)
                                    <xsl:when test="starts-with(s:loc, '{{ $prefix }}')">{{ $locale }}</xsl:when>
@endforeach
                                    <xsl:otherwise>{{ array_key_first(config('slugs')) }}</xsl:otherwise>
                                </xsl:choose>
                            </td>
                            <td class="date">
                                <xsl:choose>
                                    <xsl:when test="string-length(s:lastmod) &gt;= 10">
                                        <xsl:value-of select="concat(number(substring(s:lastmod, 9, 2)), '. ', number(substring(s:lastmod, 6, 2)), '. ', substring(s:lastmod, 1, 4))"/>
                                    </xsl:when>
                                    <xsl:otherwise>–</xsl:otherwise>
                                </xsl:choose>
                            </td>
                        </tr>
                    </xsl:for-each>
                </tbody>
            </table>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>
