<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Location;

class GeocodeController extends Controller
{
    public function getLocation(Request $request)
    {
        $address = $request->query('address');

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address is required'], 400);
        }

        // Check if the location is already in the database to reduce API calls
        $existingLocation = Location::where('address_input', $address)->first();
        if ($existingLocation) {
            return response()->json([
                'success' => true,
                'latitude' => $existingLocation->latitude,
                'longitude' => $existingLocation->longitude,
                'source' => 'Database Cache'
            ]);
        }

        // Nominatim API Endpoint
        $url = "https://nominatim.openstreetmap.org/search?q=" . urlencode($address) . "&format=json&limit=1";

        // Send GET request with User-Agent and SSL bypass (for debugging only)
        $response = Http::withHeaders([
            'User-Agent' => 'MyLaravelApp'
        ])->withOptions(['verify' => false])->get($url);

        // Decode response
        $data = $response->json();

        if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
            $latitude = $data[0]['lat'];
            $longitude = $data[0]['lon'];

            // Store in the database for future queries
            Location::create([
                'address_input' => $address,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            return response()->json([
                'success' => true,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'source' => 'API Response'
            ]);
        }

        return response()->json(['success' => false, 'message' => 'Location not found'], 404);
    }

    public function autocomplete(Request $request)
    {
        $query = $request->query('query');
        if (!$query) return response()->json([]);

        $results = Location::where('address_input', 'LIKE', "%{$query}%")->limit(5)->get();
        return response()->json($results);
    }
    public function getSearchHistory()
{
    $history = Location::orderBy('created_at', 'desc')->limit(5)->get();
    return response()->json($history);
}


}