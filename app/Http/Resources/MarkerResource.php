<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MarkerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'unit' => $this->unit,
            'healthy_min' => $this->healthy_min, // Casts will handle decimal formatting
            'healthy_max' => $this->healthy_max, // Casts will handle decimal formatting
            'description' => $this->description,
        ];
    }
}