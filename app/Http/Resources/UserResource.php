<?php

namespace App\Http\Resources;

use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = [
            'uuid' => $this->uuid,
            'email' => $this->email,
            'project_count' => $this->getProjectCount(),
            'services' => $this->hasServices()
            ->get()
            ->filter(fn($e) => $e['available'])
            ->pluck('languages', 'type')
        ];

        if($request->user()->hasRole('admin')){
            $data += [
                'name' => $this->name,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
        }

        return $data;
    }
}
