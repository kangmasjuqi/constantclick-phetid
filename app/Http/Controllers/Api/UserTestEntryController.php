<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserTestEntryRequest; // Import the Form Request
use App\Http\Resources\UserTestEntryResource;
use App\Models\UserTestEntry;
use App\Models\UserMarkerValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // For database transactions
use Illuminate\Http\JsonResponse; // For consistent return types

class UserTestEntryController extends Controller
{
    /**
     * Display a listing of the authenticated user's test entries.
     */
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // Clever: Paginate results for performance on potentially large datasets
        // Eager load testPanel and userMarkerValues with their markers for frontend display
        $userTestEntries = $request->user()->userTestEntries()
                                   ->with(['testPanel', 'userMarkerValues.marker'])
                                   ->orderByDesc('test_date') // Most recent first
                                   ->paginate(10); // Adjust pagination size as needed

        return UserTestEntryResource::collection($userTestEntries);
    }

    /**
     * Store a newly created user test entry in storage.
     */
    public function store(StoreUserTestEntryRequest $request): UserTestEntryResource|JsonResponse
    {
        try {
            DB::beginTransaction(); // Start a database transaction

            // Create the UserTestEntry
            $userTestEntry = $request->user()->userTestEntries()->create([
                'test_panel_id' => $request->validated('test_panel_id'),
                'test_date' => $request->validated('test_date'),
            ]);

            // Prepare marker values for insertion
            $markerValuesData = collect($request->validated('marker_values'))
                                ->map(function ($item) use ($userTestEntry) {
                                    return [
                                        'user_test_entry_id' => $userTestEntry->id,
                                        'marker_id' => $item['marker_id'],
                                        'value' => $item['value'],
                                        'created_at' => now(),
                                        'updated_at' => now(),
                                    ];
                                })->all();

            // Insert marker values in bulk for efficiency
            UserMarkerValue::insert($markerValuesData);

            DB::commit(); // Commit the transaction

            // Clever: Eager load relationships for the resource response
            $userTestEntry->load(['testPanel', 'userMarkerValues.marker']);

            return new UserTestEntryResource($userTestEntry);

        } catch (\Exception $e) {
            DB::rollBack(); // Rollback on error
            // Log the error for debugging
            \Log::error('Failed to store user test entry: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'message' => 'Failed to store test entry. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : 'Server Error', // Only show detailed error in debug mode
            ], 500);
        }
    }

    /**
     * Display the specified user test entry.
     */
    public function show(UserTestEntry $userTestEntry): UserTestEntryResource|JsonResponse
    {
        // Clever: Use policy authorization to ensure user can only view their own entries
        // This is a comprehensive security measure.
        if ($userTestEntry->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized access.'], 403);
        }

        // Eager load relationships for the resource response
        $userTestEntry->load(['testPanel', 'userMarkerValues.marker']);

        return new UserTestEntryResource($userTestEntry);
    }

    // You might add update and delete methods here as well if needed in the future
    // public function update(StoreUserTestEntryRequest $request, UserTestEntry $userTestEntry): UserTestEntryResource { ... }
    // public function destroy(UserTestEntry $userTestEntry): JsonResponse { ... }
}