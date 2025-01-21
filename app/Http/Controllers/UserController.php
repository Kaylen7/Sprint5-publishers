<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use App\Http\Resources\ProjectResource;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\ShowUserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        return UserResource::collection(User::all());
    }

    public function show(Request $request, string $uuid)
    {

        $target = User::with('hasProjects')->where('uuid', $uuid)->firstOrFail();
        
        $this->authorize('view', $target);

        return new ShowUserResource($target);
        
    }

    public function showProjects(Request $request, string $uuid){
        $target = User::where('uuid', $uuid)->first();
        $this->authorize('view', $target);

        $projects = $target->hasProjects()->get();
        return ProjectResource::collection($projects);
    }

    public function update(UpdateUserRequest $request, string $uuid)
    {
        $target = User::where('uuid', $uuid)->first();

        $this->authorize('update', $target);
        
        if ($request->toArray() === []){
            return response([
                'message' => 'No content'
            ], 204);
        }

        $data = [
            'name' => $request->name ?? $target->name,
            'email' => $request->email ?? $target->email,
            'password' => $request->password ? Hash::make($request->password) : $target->password,
        ];

        $target->update($data);

        return $target;
    }

    public function destroy(Request $request, string $uuid)
    {
        $target = User::where('uuid', $uuid)->first();

        $this->authorize('delete', $target);
        if(!$request->user()->hasRole('admin') && !Hash::check($request->password, $target->password)){
            return response(["error" => 'Unauthorized action. Password incorrect.'], 403);
        }

        if($target->hasRole('admin')){
            return response(["error" => "🧙 You shall not remove admin."], 403);
        }

        $target->delete();
        return response(["message" => "User removed successfully."], 200);
    }
}
