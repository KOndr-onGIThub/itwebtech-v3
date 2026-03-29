<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function contact()
    {
        return view('pages.contact');
    }

    public function price()
    {
        return view('pages.price');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function projects()
    {
        // TODO: load projects from DB
        return view('pages.projects');
    }

    public function project(string $url)
    {
        // TODO: load project by $url from DB
        abort(404);
    }

    public function blog()
    {
        // TODO: load articles from DB
        return view('pages.blog');
    }

    public function article(string $slug)
    {
        // TODO: load article by $slug from DB
        abort(404);
    }
}
