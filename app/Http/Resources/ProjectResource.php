<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description,
            'num_chars' => $this->num_chars,
            'num_pages' => $this->num_pages,
            'status' => $this->status,
            'total_price' => $this->total_price,
            'start_date' => $this->start_date,
            'projected_end_date' => $this->projected_end_date
            
        ];

        if($request->user()->hasRole('admin')){
            $data += [
                'owner_id' => $this->owner_id,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at
            ];
        }

        return $data;
    }
}
