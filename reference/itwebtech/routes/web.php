<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\LangController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('sitemap.xml', [SitemapController::class, 'index' ])->name('get.sitemap');

Route::get('lang/change', [LangController::class, 'change'])->name('changeLang');

Route::get('/price', function () {
    return view('price');
});

Route::get('/privacy-policy', function () {
    return view('privacy_policy');
});

Route::get('/', 'App\Http\Controllers\MainController@index')->name('home');

Route::get('/projects', 'App\Http\Controllers\MainController@show')->name('projects');

Route::get('/projects/{url}', 'App\Http\Controllers\MainController@showOne')->name('project');

Route::get('/contact/{option?}', 'App\Http\Controllers\ContactsController@show');
//zpracovat kontaktni formular
Route::post('/contact', 'App\Http\Controllers\ContactsController@send');


Route::get('/jak-na-to/{slug}', 'App\Http\Controllers\Article_translationsController@show')->name('article');
Route::get('/jak-na-to', 'App\Http\Controllers\Article_translationsController@index')->name('articles');

