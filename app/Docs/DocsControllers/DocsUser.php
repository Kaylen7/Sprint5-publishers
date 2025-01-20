<?php

namespace App\Docs\DocsControllers;

class DocsUser
{
    /**
     * @OA\Get(
     *     path="/users",
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
    public function index(){}

    /**
     * @OA\Get(
     *     path="/users/{uuid}",
     *     summary="Get user details",
     *     description="Returns the details of current user. Only admin can see others.",
     *     tags={"User"},
     *     @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="user uuid",
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
    public function show(){ }

    /**
     * @OA\PUT(
     *     path="/users/{uuid}",
     *     summary="Update user",
     *     tags={"User"},
     *     @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="user uuid",
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
    public function update(){}

    /**
     * @OA\DELETE(
     *     path="/users/{uuid}",
     *     summary="Delete user",
     *     description="Deletes user. Regular users can only remove themselves. Admin can remove everyone except admins. Regular users must provide their password.",
     *     tags={"User"},
     *     @OA\RequestBody(
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="password", type="string", example="password")
     *      ),
     * ),
     *     @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="user uuid",
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
    public function destroy(){}
}
