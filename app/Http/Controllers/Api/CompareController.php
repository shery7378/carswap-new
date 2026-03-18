<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class CompareController extends Controller
{
    private array $relations = [
        'brand',
        'model',
        'bodyType',
        'fuelType',
        'transmission',
        'driveType',
        'exteriorColor',
        'interiorColor',
        'salesMethod',
        'documentType',
        'vehicleStatus',
        'properties',
    ];

    /**
     * Compare multiple vehicles.
     * Expects 'ids' as an array of vehicle IDs.
     */
    public function compare(Request $request)
    {
        $ids = $request->input('ids');

        if (empty($ids) || !is_array($ids)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide an array of vehicle IDs to compare.',
            ], 400);
        }

        // Limit to matching active ads for public comparison
        $vehicles = Vehicle::with($this->relations)
            ->whereIn('id', $ids)
            ->where('ad_status', 'published')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $vehicles
        ]);
    }
}
