<?php

namespace App\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="ProjectResource",
 *     type="object",
 *     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="name", type="string", example="My Project"),
 *     @OA\Property(property="description", type="string", example="This is a project description"),
 *     @OA\Property(property="num_chars", type="integer", example=100),
 *     @OA\Property(property="start_date", type="string", format="date", example="2023-01-01"),
 *     @OA\Property(property="status", type="string", example="pending"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
 * )
 */
class ProjectResource
{
    // This class is only for documentation purposes
}