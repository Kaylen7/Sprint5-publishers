<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return UserResource::collection(User::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {

        $target = User::findOrFail($id);
        
        $this->authorize('view', $target);

        return $target;
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, string $id)
    {
        $target = User::findOrFail($id);

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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $target = User::findOrFail($id);

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
