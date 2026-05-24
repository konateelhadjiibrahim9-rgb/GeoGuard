<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        $location = Location::create([
            'user_id' => $request->user()->id,
            'device_id' => $validated['device_id'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Localisation enregistrée avec succès',
            'data' => $location,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $locations = Location::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        return response()->json([
            'success' => true,
            'data' => $locations,
        ]);
    }
}
