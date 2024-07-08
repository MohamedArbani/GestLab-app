<?php

namespace App\Http\Controllers;
use App\Models\Laboratory;
use Illuminate\Http\Request;

class LaboratoryController extends Controller
{
    public function index(Request $request)
    {
        $laboratories = Laboratory::select(['id', 'name', 'address', 'phone', 'latitude', 'longitude'])
            ->when($request->filled('labName'), function ($query) use ($request) {
                $query->where('name', 'like', "%{$request->labName}%");
            })
            ->take(10)
            ->get();

        if ($request->filled(['long', 'lat'])) {
            $longitude = (float) $request->long;
            $latitude = (float) $request->lat;

            $laboratories = $laboratories->map(function ($lab) use ($latitude, $longitude) {
                $lab->distance = $this->calculateDistance($latitude, $longitude, $lab->latitude, $lab->longitude);
                return $lab;
            })->sortBy('distance');
        }

        return response()->json([
            'laboratories' => $laboratories->values()->all(),
        ]);
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        return ($miles * 1.609344); // Convert to kilometers
    }
}
