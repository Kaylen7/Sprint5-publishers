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
     *         description="Successful response"
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *     ),
     * )
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * @OA\PUT(
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
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * @OA\DELETE(
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
