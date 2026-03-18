<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class FavoriteController extends Controller
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
        'user',
    ];

    /**
     * List all favorite vehicles of the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        $favorites = $request->user()
            ->favorites()
            ->with($this->relations)
            ->where('ad_status', 'published')
            ->orderBy('favorites.created_at', 'desc')
            ->paginate($request->input('limit', 12));

        return response()->json([
            'success' => true,
            'data'    => $favorites,
        ]);
    }

    /**
     * Toggle a vehicle in the user's favorites.
     */
    public function toggle(Request $request, int $vehicleId): JsonResponse
    {
        $user = $request->user();
        $vehicle = Vehicle::findOrFail($vehicleId);

        $status = $user->favorites()->toggle($vehicle->id);

        $isFavorited = count($status['attached']) > 0;

        return response()->json([
            'success' => true,
            'message' => $isFavorited ? 'Vehicle added to favorites.' : 'Vehicle removed from favorites.',
            'is_favorited' => $isFavorited,
        ]);
    }
}
