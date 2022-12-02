<?php

namespace App\Http\Controllers\Api;

use App\Filters\ProjectFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ProjectController extends Controller
{

    public function __construct()
    {
        $this->authorizeResource(Project::class, 'project');
    }

    /**
     * @OA\Post (
     * path="/api/projects",
     * summary="Add projects",
     * description="Add project",
     * operationId="add_project",
     * tags={"projects"},
     * security={ {"bearer": {} }},
     *
     * @OA\RequestBody(
     *    required=true,
     *    description="param project",
     *    @OA\JsonContent(
     *       @OA\Property(property="name", type="string", example="Project_1"),
     *       @OA\Property(property="author_id", type="int", example="1"),
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Project created",
     *     )
     * )
     *
     *
     * Store a newly created resource in storage.
     * @param ProjectRequest $request
     */
    public function store(ProjectRequest $request): void
    {
        Project::create($request->post());
    }

    /**
     * @OA\Post (
     * path="/api/projects/{projectId}",
     * summary="Sync project to users",
     * description="Sync project to users",
     * operationId="sync_project",
     * tags={"projects"},
     * security={ {"bearer": {} }},
     *
     *     @OA\Parameter (
     *        in="path",
     *        name="projectId",
     *        required=true,
     *        example="1",
     *           @OA\Schema(
     *               type="integer",
     *               format="int"
     *               )
     *      ),
     *
     *    @OA\RequestBody(
     *    required=true,
     *    description="Users id to this project",
     *    @OA\JsonContent(
     *     type="int",
     *     example={1,2,3},
     *    ),
     * ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully synced",
     *   )
     *  )
     *
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Project $project
     */
    public function sync(Request $request, Project $project): void
    {
        $project->users()->sync($request->post());
    }

    /**
     * @OA\Get (
     * path="/api/projects",
     * summary="Get projects",
     * description="Get projects",
     * operationId="get_projects",
     * tags={"projects"},
     * security={ {"bearer": {} }},
     *
     * @OA\Response(
     *    response=200,
     *    description="Response JSON array",
     *     @OA\JsonContent(
     *       @OA\Property(property="id", type="int", example="1"),
     *       @OA\Property(property="name", type="string", example="Project_name"),
     *       @OA\Property(property="author", type="string", example="Egor"),
     *       @OA\Property(property="labels", type="JSON", example="['Label_name_1','Label_name_1']"),
     *    )
     *   )
     * )
     *
     *
     * Display the specified resource.
     *
     * @param ProjectFilter $filters
     * @return AnonymousResourceCollection
     */


    public function show(ProjectFilter $filters): AnonymousResourceCollection
    {
        return ProjectResource::collection(Project::filter($filters)->get());
    }

    /**
     * @OA\Delete (
     * path="/api/projects/{projectId}",
     * summary="Delete project to users",
     * description="Delete project to users",
     * operationId="delete_project",
     * tags={"projects"},
     * security={ {"bearer": {} }},
     *
     *     @OA\Parameter (
     *        in="path",
     *        name="projectId",
     *        required=true,
     *        example="1",
     *           @OA\Schema(
     *               type="integer",
     *               format="int"
     *               )
     *      ),
     * @OA\Response(
     *    response=200,
     *    description="Successfully removed",
     *   )
     *  )
     *
     * Remove the specified resource from storage.
     *
     * @param Project $project
     * @throw AuthorizationException
     */
    public function destroy(Project $project): void
    {
        $project->users()->detach();
        $project->labels()->detach();
        $project->delete();
    }
}

