<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowUserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'email' => $this->email,
            'projects' => ProjectResource::collection($this->hasProjects)
        ];
    }
}
