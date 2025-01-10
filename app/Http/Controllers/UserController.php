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
     * @OA\Get(
     *     path="/api/users",
     *     summary="Get all user details",
     *     description="Returns list of id-email for users",
     *     tags={"User"},
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function index(Request $request)
    {
        return UserResource::collection(User::all());
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Get user details",
     *     description="Returns the details of current user. Only admin can see others.",
     *     tags={"User"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="user id",
     *     required=true
     *     ),
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     * )
     */
    public function show(Request $request, string $id)
    {

        $target = User::findOrFail($id);
        
        $this->authorize('view', $target);

        return $target;
        
    }

    /**
     * @OA\PUT(
     *     path="/api/users/{id}",
     *     summary="Update user",
     *     tags={"User"},
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="user id",
     *     required=true
     *     ),
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="password", type="string"),
     *              @OA\Property(property="email", type="string"),
     *      ),
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=402,
     *         description="No content",
     *     ),
     * )
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
     * @OA\DELETE(
     *     path="/api/users/{id}",
     *     summary="Delete user",
     *     description="Deletes user. Regular users can only remove themselves. They must provide password.",
     *     tags={"User"},
     *     @OA\RequestBody(
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="password", type="string")
     *      ),
     * ),
     *     @OA\Parameter(
     *     name="id",
     *     in="path",
     *     description="user id",
     *     required=true
     *     ),
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="User removed successfully",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     * )
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
