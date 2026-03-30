<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectsScreen;
use App\Http\Requests\Project\StoreRequest;
use App\Http\Requests\Project\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\View\View;
use Exception;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(): View
    {
        

        return view('projects.projects_content', [ 'projects' => Project::orderBy('sorting')->get(), 'projectScreens' => ProjectsScreen::orderBy('sorting')->get() ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request): RedirectResponse
    {

        Project::create($request->all());
    
        return redirect()->route('projects.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function show(Project $project, $url): View
    {
        $oneProject = Project::where('url', $url)->firstOrFail();

        $projectScreens = ProjectsScreen::select('*')->where('id_project', $oneProject->id)->orderBy('sorting', 'asc')->get() ;
       

        return view('projects.projects_content', compact('oneProject', 'projectScreens'));

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function edit(Project $project): View
    {
        return view('projects.edit', ['project' => $project]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, Project $project)
    {
        $project->update($request->all());

        return redirect()->route('projects.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Project  $project
     * @return \Illuminate\Http\Response
     */
    public function destroy(Project $project): RedirectResponse
    {
        try {
            $project->delete();
        } catch (\Exception $exception) {
            return redirect()->back()->withErrors(['Při procesu odstranění projektu došlo k chybě.']);
        }
    
        return redirect()->route('projects.index');
    }
}
