<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserMarkerValueResource;
use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;
use App\Models\UserMarkerValue;

class UserMarkerValueController extends Controller
{
    /**
     * Display a listing of historical marker values for a specific marker for the authenticated user.
     * This is crucial for charting trends.
     */
    public function getByMarker(Marker $marker, Request $request): AnonymousResourceCollection|JsonResponse
    {
        // IMPORTANT: Removed the complex orderByDesc for debugging eager loading
        $userMarkerValues = UserMarkerValue::where('marker_id', $marker->id)
                                        ->whereHas('userTestEntry', function ($query) use ($request) {
                                            $query->where('user_id', $request->user()->id);
                                        })
                                        ->with(['marker', 'userTestEntry']) 
                                        ->get();

        // Add a dd() here to inspect the data before resource transformation
        // dd($userMarkerValues->toArray()); // Temporarily for debugging

        // Handle case where no data is found for this marker for the user
        if ($userMarkerValues->isEmpty()) {
            return response()->json(['message' => 'No historical data found for this marker.'], 200);
        }

        return UserMarkerValueResource::collection($userMarkerValues);
    }
}