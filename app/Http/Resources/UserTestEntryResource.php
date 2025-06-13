<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTestEntryResource extends JsonResource
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
            'user_id' => $this->user_id,
            'test_panel_id' => $this->test_panel_id,
            'test_date' => $this->test_date->format('Y-m-d'), // Format the date for consistent output
            'test_panel' => TestPanelResource::make($this->whenLoaded('testPanel')), // Include test panel details if eager loaded
            // Clever: Conditionally load marker values only if requested/eager loaded
            // This prevents over-fetching for lists of entries if you only need summary data.
            'marker_values' => UserMarkerValueResource::collection($this->whenLoaded('userMarkerValues')),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}