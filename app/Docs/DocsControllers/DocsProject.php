<?php

namespace App\Docs\DocsControllers;

class DocsProject
{
    /**
     * @OA\Get(
     *     path="/projects",
     *     summary="Get all projects",
     *     description="Returns list of projects with status done",
     *     tags={"Project"},
     *     @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="project status. Accepts: all, pending, ongoing, done.",
     *     required=false,
     *     @OA\Schema(
     *      type="string",
     *      enum={"all", "pending", "ongoing", "done"},
     *      default="all"
     *     )
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
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Status not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="Status not found. Try with pending, ongoing, done or all; or remove parameter from query.")
     *         )
     *     ),
     * )
     */
    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/projects",
     *     summary="Create a new project",
     *     description="Creates a new project with the provided data",
     *     tags={"Project"},
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description", "num_chars", "start_date"},
     *             @OA\Property(property="name", type="string", example="New Project"),
     *             @OA\Property(property="description", type="string", example="Project description"),
     *             @OA\Property(property="num_chars", type="integer", example=100),
     *             @OA\Property(property="start_date", type="string", format="date", example="2023-01-01")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Project created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthorized",
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Project creation failed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project creation failed")
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
     *     path="/projects/{uuid}",
     *     summary="Get project details",
     *     description="Returns the details of specified project.",
     *     tags={"Project"},
     *     @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="project uuid",
     *     required=true
     *     ),
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectResource")
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Project not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project not found")
     *         )
     *     ),
     * )
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * @OA\Put(
     *     path="/projects/{uuid}",
     *     summary="Update project",
     *     tags={"Project"},
     *     @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="project uuid",
     *     required=true
     *     ),
     *     security={{"passport": {}}},
     *     @OA\RequestBody(
     *     @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="name", type="string"),
     *              @OA\Property(property="description", type="string"),
     *              @OA\Property(property="num_chars", type="integer"),
     *              @OA\Property(property="start_date", type="date"),
     *      ),
     * ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\JsonContent(ref="#/components/schemas/ProjectResource")
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
     * )
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * @OA\Delete(
     *     path="/projects/{uuid}",
     *     summary="Delete project",
     *     description="Deletes project. Regular users can only remove their projects. Admin can remove any project.",
     *     tags={"Project"},
     *     @OA\Parameter(
     *     name="uuid",
     *     in="path",
     *     description="project uuid",
     *     required=true
     *     ),
     *     security={{"passport": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Project removed successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Project removed successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     * )
     */
    public function destroy(Project $project)
    {
        //
    }
}