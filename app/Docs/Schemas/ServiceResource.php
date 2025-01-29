<?php

namespace App\Docs\Schemas;

/**
 * @OA\Schema(
 *     schema="ServiceResource",
 *     type="object",
 *     @OA\Property(property="uuid", type="string", example="550e8400-e29b-41d4-a716-446655440000"),
 *     @OA\Property(property="name", type="string", example="Proofreading Service"),
 *     @OA\Property(property="type", type="string", example="proofreading"),
 *     @OA\Property(
 *         property="languages",
 *         type="array",
 *         @OA\Items(
 *             oneOf={
 *                 @OA\Schema(type="string", example="es-ES"),
 *                 @OA\Schema(
 *                     type="object",
 *                     @OA\Property(property="source", type="string", example="es-ES"),
 *                     @OA\Property(property="target", type="string", example="en-US"),
 *                     @OA\Property(property="bidirectional", type="boolean", example=true)
 *                 )
 *             }
 *         )
 *     ),
 *     @OA\Property(property="available", type="boolean", example=true),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2023-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2023-01-01T12:00:00Z")
 * )
 */
class ServiceResource
{
    // This class is only for documentation purposes
}