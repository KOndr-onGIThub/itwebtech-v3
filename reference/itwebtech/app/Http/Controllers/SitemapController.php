<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Sitemap;

class SitemapController extends Controller
{
    public function index()
    {
        $sitemaps = Sitemap::latest()->get();
 
        return response()->view('sitemap', [
            'sitemaps' => $sitemaps
        ])->header('Content-Type', 'text/xml');
    }
}
