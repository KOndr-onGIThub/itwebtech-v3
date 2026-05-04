<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Project;
use App\Models\Slugs\ArticleSlug;
use App\Models\Slugs\ProjectSlug;
use Illuminate\Support\Facades\App;

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
        $locale   = App::getLocale();
        $projects = Project::where('published', true)
            ->orderBy('position')
            ->with(['translations', 'slugs'])
            ->get();

        return view('pages.projects', compact('projects', 'locale'));
    }

    public function project(string $url)
    {
        $locale = App::getLocale();

        $slugRecord = ProjectSlug::where('slug', $url)
            ->where('active', true)
            ->first();

        if (! $slugRecord) {
            abort(404);
        }

        $project = Project::where('id', $slugRecord->project_id)
            ->where('published', true)
            ->with(['translations', 'slugs', 'screens'])
            ->first();

        if (! $project) {
            abort(404);
        }

        $translation = $project->translation($locale);

        return view('pages.project', compact('project', 'translation', 'locale'));
    }

    public function blog()
    {
        $locale   = App::getLocale();
        $articles = Article::where('published', true)
            ->orderBy('position')
            ->with(['translations', 'slugs'])
            ->get();

        return view('pages.blog', compact('articles', 'locale'));
    }

    public function article(string $slug)
    {
        $locale = App::getLocale();

        $slugRecord = ArticleSlug::where('slug', $slug)
            ->where('active', true)
            ->first();

        if (! $slugRecord) {
            abort(404);
        }

        $article = Article::where('id', $slugRecord->article_id)
            ->where('published', true)
            ->with(['translations', 'slugs'])
            ->first();

        if (! $article) {
            abort(404);
        }

        $translation = $article->translation($locale);

        return view('pages.article', compact('article', 'translation', 'locale'));
    }
}
