<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function index()
    {
        return view('pages.home');
    }

    // TODO: add methods for each page, e.g.:
    // public function about()   { return view('pages.about'); }
    // public function contact() { return view('pages.contact'); }
}
