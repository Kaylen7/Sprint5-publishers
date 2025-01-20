<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\UpdateProjectRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ProjectController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->status && !in_array($request->status, ['pending', 'ongoing', 'done', 'all'])){
            return response(['error' => 'Status not found. Try with pending, ongoing, done or all; or remove parameter from query.'], 404);
        }
        if(!$request->status || $request->status === 'all'){
            return ProjectResource::collection(Project::all());
        }

        return ProjectResource::collection(Project::all()->filter(fn($e) => $e['status'] === $request->status));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectRequest $request)
    {
        $project = Project::create([
            'name' => $request->name,
            'description' => $request->description,
            'num_chars' => $request->num_chars,
            'owner_id' => $request->user()->id,
            'start_date' => $request->start_date
        ]);

        if(!$project){
            return response(['message' => 'Project creation failed'], 500);
        }

        return response([
            'uuid' => $project->uuid,
            'name' => $project->name,
            'description' => $project->description,
            'num_chars' => $project->num_chars,
            'num_pages' => $project->num_pages,
            'status' => $project->status,
            'owner_id' => $project->owner_id,
            'total_price' => $project->total_price,
            'start_date' => $project->start_date,
            'projected_end_date' => $project->projected_end_date
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $uuid)
    {
        $target = Project::where('uuid', $uuid)->first();

        if(!$target){
            return response(['message' => 'Project not found'], 404);
        }
        return $target;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProjectRequest $request, string $uuid)
    {
        $target = Project::where('uuid', $uuid)->first();

        $this->authorize('update', $target);

        $data = $request->validated();
        if(!$data){
            return response([
                "message" => "No content"
            ], 204);
        }

        $target->update($data);

        return $target;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $uuid)
    {
        $target = Project::where('uuid', $uuid)->first();

        $this->authorize('delete', $target);
        if(!$target){
            return response([
                'error' => 'Project does not exist.'
            ], 404);
        }

        $target->delete();
        return response([
            'message' => 'Project removed successfully.'
        ], 200);
    }
}
