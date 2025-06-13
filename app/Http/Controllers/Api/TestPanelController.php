<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TestPanelResource;
use App\Models\TestPanel;
use Illuminate\Http\Request;

class TestPanelController extends Controller
{
    /**
     * Display a listing of the test panels.
     */
    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        // Clever: Order by name for consistent display
        $testPanels = TestPanel::orderBy('name')->get();

        return TestPanelResource::collection($testPanels);
    }
}