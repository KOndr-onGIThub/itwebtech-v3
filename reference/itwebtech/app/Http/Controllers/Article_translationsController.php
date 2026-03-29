<?php

namespace App\Http\Controllers;
use App\Models\Article;
use App\Models\Article_slug;
use App\Models\Article_translation;
use Illuminate\Contracts\View\View;
/* use App; */

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Article_translationsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        $articles = Article::orderBy('updated_at', 'desc')->get();

        /* $setedLang = session()->get('locale') ? session()->get('locale') : 'cs';
        
        $article_translated_slugs = Article_slug::where('locale', $setedLang)->orderBy('created_at')->get();

        $translated_articles = Article_translation::where('locale', $setedLang)->orderBy('created_at')->get();
 */
       /*  $articles = [ $article_translated_slugs, $translated_articles]; */
            
        return view('articles', compact('articles'));
        
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Article_translation  $translated_article
     * @return \Illuminate\Http\Response
     */
    public function show(Article_translation $article, $slug): View
    {
        // Oříznutí slugu při nalezení nepovolených znaků
        // je to z důvodu, že v Collabimu jsem našel že měří divné URL kde parametry nezačínají ?, ale &. Nevím jak a kdo tyto parametry kde přidal.
        // Nakonec jsem přidal kontrolu do Middlevaru
        $slug = strtok($slug, '&');

        $article_slug = Article_slug::select('article_id')->where('slug', $slug)->first();

        // Ověření, zda existuje článek s daným slugem
        if (!$article_slug) {
            Log::error('Slug not found: ' . $slug);
            abort(404, 'Article not found.');
        }

        $article_id = $article_slug->article_id;
        

        $setedLang = session()->get('locale') ? session()->get('locale') : 'cs'; 
        $translated_article = Article_translation::where('article_id', $article_id)->where('locale', $setedLang)->first();
        
        // Ověření, zda existuje překlad článku
        if (!$translated_article) {
            Log::error('Translated article not found: ' . $translated_article);
            abort(404, 'Translated article not found.');
        }

        $article_info = Article::where('id', $article_id)->first();
        $article_slug = Article_slug::where('article_id', $article_id)->where('locale', $setedLang)->first();
        
        $random3_articles_aside_list = Article::select('*')->where('published' , '1')->where('id' , '<>' , $article_id)->inRandomOrder()->limit(3)->get();
      
        return view('article_post', compact('translated_article', 'article_info', 'article_slug', 'random3_articles_aside_list'));
    }
}
