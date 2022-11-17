<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Psy\Exception\ErrorException;

class ProjectController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     */
    public function store(Request $request): void
    {
        $request->validate([
            'name' => ['required'],
            'author_id' => ['required', 'integer']
        ]);

        Project::create($request->post());
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function show(ProjectFilter $filters): \Illuminate\Database\Eloquent\Collection
    {
        return Project::filter($filters)->get();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     */
    public function sync(Request $request, int $id): void
    {
        $project = Project::find($id);
        if (!$project) {
            throw new ErrorException('Project is NOT exits');
        }
        $project->users()->sync($request->post());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     */
    public function destroy(int $id): void
    {
        $project = Project::find($id);
        $project->users()->detach();
        $project->labels()->detach();
        $project->delete();
    }
}

