<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home()
    {
        return view('pages.home');
    }

    public function about()
    {
        return view('pages.about');
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
        return view('pages.projects', [
            'projects' => config('projects.items', []),
        ]);
    }

    public function project(string $url)
    {
        $projects = config('projects.items', []);

        abort_unless(isset($projects[$url]), 404);

        return view('pages.project', [
            'slug'    => $url,
            'project' => $projects[$url],
        ]);
    }

    public function blog()
    {
        return view('pages.blog', [
            'articles' => config('blog.items', []),
        ]);
    }

    public function article(string $slug)
    {
        $article = config('blog.items.' . $slug);

        abort_unless($article !== null, 404);

        return view('pages.article', [
            'slug'    => $slug,
            'article' => $article,
        ]);
    }
}
