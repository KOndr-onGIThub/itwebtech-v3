<?php

namespace App\Http\Controllers;
use App\Models\Article;
use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\ProjectsScreen;
use Illuminate\Contracts\View\View;


class MainController extends Controller
{

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        return view('home', [ 'projects' => Project::orderBy('sorting')->limit(6)->get(), 'latest_news' => Article::select('*')->where('published' , '1')->orderBy('created_at', 'desc')->limit(3)->get() ]);
    }

        /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(): View
    {
        return view('projects', [ 'projects' => Project::orderBy('sorting')->limit(99)->get() ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function showOne(Project $project, $url): View
    {
        $oneProject = Project::where('url', $url)->firstOrFail();

        $projectScreens = ProjectsScreen::select('*')->where('id_project', $oneProject->id)->orderBy('sorting', 'asc')->get() ;
       

        return view('project', compact('oneProject', 'projectScreens'));

    }


}
