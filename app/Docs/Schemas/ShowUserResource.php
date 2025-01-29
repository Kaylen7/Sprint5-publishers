<?php

namespace App\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="ShowUserResource",
 *     type="object",
 *     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john.doe@example.com"),
 *     @OA\Property(property="has_projects", type="array", @OA\Items(ref="#/components/schemas/ProjectResource")),
 *     @OA\Property(property="has_services", type="array", @OA\Items(ref="#/components/schemas/ServiceResource"))
 * )
 */

 class ShowUserResource {
    // This class is for documentation purposes
 }