<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserMarkerValueResource;
use App\Models\Marker;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\JsonResponse;

class UserMarkerValueController extends Controller
{
    /**
     * Display a listing of historical marker values for a specific marker for the authenticated user.
     * This is crucial for charting trends.
     */
    public function getByMarker(Marker $marker, Request $request): AnonymousResourceCollection|JsonResponse
    {
        // Clever: Fetch all user's test entries that contain this marker,
        // then filter to get only the relevant marker values.
        // It's more efficient to eager load all necessary data and then filter.
        $userMarkerValues = $request->user()->userTestEntries()
                                   ->with(['userMarkerValues' => function ($query) use ($marker) {
                                       $query->where('marker_id', $marker->id)->with('marker'); // Eager load the marker itself for the value
                                   }])
                                   ->whereHas('userMarkerValues', function ($query) use ($marker) {
                                       $query->where('marker_id', $marker->id);
                                   })
                                   ->orderBy('test_date') // Order by date for charting
                                   ->get()
                                   ->flatMap(fn ($entry) => $entry->userMarkerValues) // Flatten to get just the marker values
                                   ->filter(fn ($value) => $value->marker_id === $marker->id); // Double check just in case

        // If you prefer to directly query UserMarkerValue:
        // $userMarkerValues = UserMarkerValue::where('marker_id', $marker->id)
        //                                   ->whereHas('userTestEntry', function ($query) use ($request) {
        //                                       $query->where('user_id', $request->user()->id);
        //                                   })
        //                                   ->with('marker') // Eager load the marker details
        //                                   ->orderByDesc(
        //                                       UserTestEntry::select('test_date')
        //                                           ->whereColumn('user_test_entries.id', 'user_marker_values.user_test_entry_id')
        //                                   )
        //                                   ->get();


        // Handle case where no data is found for this marker for the user
        if ($userMarkerValues->isEmpty()) {
            return response()->json(['message' => 'No historical data found for this marker.'], 200); // 200 OK with empty data is standard
        }

        return UserMarkerValueResource::collection($userMarkerValues);
    }
}