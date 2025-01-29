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
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/UserResource")
     *         )
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
     *         name="uuid",
     *         in="path",
     *         description="User UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/ShowUserResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized action.")
     *         )
     *     ),
     * )
     */
    public function show(){ }

    /**
     * @OA\Get(
     *     path="/users/{uuid}/projects",
     *     summary="Get all projects from user",
     *     description="Returns all project details from current user. Only admin can see other's projects.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="User UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ProjectResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized action.")
     *         )
     *     ),
     * )
     */
    public function showProjects(){ }

    /**
     * @OA\Put(
     *     path="/users/{uuid}",
     *     summary="Update user",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="User UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="password", type="string", example="newpassword123"),
     *             @OA\Property(property="email", type="string", format="email", example="john.doe@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ShowUserResource")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="No content",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No content")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Unauthorized action.")
     *         )
     *     ),
     * )
     */
    public function update(){}

    /**
     * @OA\Delete(
     *     path="/users/{uuid}",
     *     summary="Delete user",
     *     description="Deletes user. Regular users can only remove themselves. Admin can remove everyone except admins. Regular users must provide their password.",
     *     tags={"User"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="User UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="password", type="string", example="password")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="User removed successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *         @OA\JsonContent(
     *             oneOf={
     *                 @OA\Schema(
     *                     @OA\Property(property="error", type="string", example="Unauthorized action. Password incorrect.")
     *                 ),
     *                 @OA\Schema(
     *                     @OA\Property(property="error", type="string", example="🧙 You shall not remove admin.")
     *                 )
     *             }
     *         )
     *     ),
     * )
     */
    public function destroy(){}
}