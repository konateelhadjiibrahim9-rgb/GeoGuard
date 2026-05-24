<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $locations = Location::with('user')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();

        return view('dashboard', compact('locations'));
    }

    public function mapData()
    {
        $locations = Location::with('user')
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get()
            ->map(function ($location) {
                return [
                    'id' => $location->id,
                    'latitude' => $location->latitude,
                    'longitude' => $location->longitude,
                    'device_id' => $location->device_id,
                    'user_name' => $location->user->name ?? 'Inconnu',
                    'created_at' => $location->created_at->format('d/m/Y H:i'),
                ];
            });

        return response()->json($locations);
    }
}
