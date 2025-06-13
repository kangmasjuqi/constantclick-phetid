<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MarkerResource;
use App\Models\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{
    /**
     * Display a listing of the markers.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // Clever: Order by name for consistent display
        $markers = Marker::orderBy('name')->get();

        return MarkerResource::collection($markers);
    }

    /**
     * Display the specified marker.
     */
    public function show(Marker $marker): MarkerResource
    {
        return new MarkerResource($marker);
    }
}