@php 
echo('<?xml version="1.0" encoding="UTF-8"?>');
@endphp
<urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach ($sitemaps as $sitemap)
        <url>
            <loc>{{ route('home') }}/{{ $sitemap->slug }}</loc>
            <lastmod>{{ $sitemap->updated_at->tz('UTC')->toAtomString() }}</lastmod>
            <changefreq>{{ $sitemap->changefreq }}</changefreq>
            <priority>{{ $sitemap->priority }}</priority>
        </url>
    @endforeach
</urlset>