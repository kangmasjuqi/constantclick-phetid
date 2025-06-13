<?php
// app/Http/Resources/UserMarkerValueResource.php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserTestEntryResource; 
use App\Http\Resources\MarkerResource;

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
            'value' => $this->value,
            'marker_id' => $this->marker_id,
            'marker' => MarkerResource::make($this->whenLoaded('marker')),
            'user_test_entry_id' => $this->user_test_entry_id, // Add this for clarity if not already
            'user_test_entry' => UserTestEntryResource::make($this->whenLoaded('userTestEntry')),
            // 'created_at' => $this->created_at?->toDateTimeString(), // Optional
            // 'updated_at' => $this->updated_at?->toDateTimeString(), // Optional
        ];
    }
}