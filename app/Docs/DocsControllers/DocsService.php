<?php

namespace App\Docs\DocsControllers;

class DocsService
{
    /**
     * @OA\Get(
     *     path="/services",
     *     summary="Get all services",
     *     description="Returns a list of all services.",
     *     tags={"Service"},
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/ServiceResource")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     * )
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/services",
     *     summary="Create a new service",
     *     description="Creates a new service with the provided data.",
     *     tags={"Service"},
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "type", "languages"},
     *             @OA\Property(property="name", type="string", example="Proofreading Service"),
     *             @OA\Property(property="type", type="string", enum={"proofreading", "translating"}, example="proofreading"),
     *             @OA\Property(
     *                 property="languages",
     *                 type="array",
     *                 description="Languages array. If type is 'proofreading', it must be a one-level array of language pairs. If type is 'translating', it must be an array of objects with 'source', 'target', and 'bidirectional' properties.",
     *                 @OA\Items(
     *                     oneOf={
     *                         @OA\Schema(type="string", example="es-ES"),
     *                         @OA\Schema(
     *                             type="object",
     *                             @OA\Property(property="source", type="string", example="es-ES"),
     *                             @OA\Property(property="target", type="string", example="en-US"),
     *                             @OA\Property(property="bidirectional", type="boolean", example=true)
     *                         )
     *                     }
     *                 )
     *             ),
     *             @OA\Property(property="available", type="boolean", example=true),
     *             @OA\Property(property="user_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Service created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Service creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service creation failed")
     *         )
     *     )
     * )
     */
    public function store()
    {
        //
    }

    /**
     * @OA\Get(
     *     path="/services/{uuid}",
     *     summary="Get service details",
     *     description="Returns the details of the specified service.",
     *     tags={"Service"},
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Service UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     )
     * )
     */
    public function show()
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/services/{uuid}",
     *     summary="Update service",
     *     description="Updates the specified service with the provided data.",
     *     tags={"Service"},
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Service UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Service Name"),
     *             @OA\Property(property="type", type="string", enum={"proofreading", "translating"}, example="proofreading"),
     *             @OA\Property(
     *                 property="languages",
     *                 type="array",
     *                 description="Languages array. If type is 'proofreading', it must be a one-level array of language pairs. If type is 'translating', it must be an array of objects with 'source', 'target', and 'bidirectional' properties.",
     *                 @OA\Items(
     *                     oneOf={
     *                         @OA\Schema(type="string", example="es-ES"),
     *                         @OA\Schema(
     *                             type="object",
     *                             @OA\Property(property="source", type="string", example="es-ES"),
     *                             @OA\Property(property="target", type="string", example="en-US"),
     *                             @OA\Property(property="bidirectional", type="boolean", example=true)
     *                         )
     *                     }
     *                 )
     *             ),
     *             @OA\Property(property="available", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ServiceResource")
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
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     )
     * )
     */
    public function update()
    {
        //
    }

    /**
     * @OA\Delete(
     *     path="/services/{uuid}",
     *     summary="Delete service",
     *     description="Deletes the specified service.",
     *     tags={"Service"},
     *     security={{"passport": {}}},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Service UUID",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Service deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service removed successfully.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Service not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Service not found")
     *         )
     *     )
     * )
     */
    public function destroy()
    {
        //
    }
}