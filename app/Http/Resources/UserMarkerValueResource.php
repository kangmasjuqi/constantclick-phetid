<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserMarkerValueResource extends JsonResource
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
            'value' => $this->value, // Casts will handle decimal formatting
            'marker_id' => $this->marker_id,
            // Clever: Conditionally load marker details if eager loaded, for a single API call benefit
            'marker' => MarkerResource::make($this->whenLoaded('marker')),
            // 'created_at' => $this->created_at?->toDateTimeString(),
            // 'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}