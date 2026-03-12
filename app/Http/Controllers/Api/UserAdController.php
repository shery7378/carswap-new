<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAdRequest;
use App\Http\Requests\UpdateAdRequest;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserAdController extends Controller
{
    // -------------------------------------------------------------------------
    // Eager-load relationships used on every response
    // -------------------------------------------------------------------------
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

    // =========================================================================
    // PUBLIC: GET /api/ads
    // List all ACTIVE ads (publicly visible, paginated, filterable)
    // =========================================================================
    public function index(Request $request): JsonResponse
    {
        $query = Vehicle::with($this->relations)
            ->where('ad_status', 'active');

        // --- Filtering ---
        $filters = [
            'brand_id', 'model_id', 'body_type_id', 'fuel_type_id',
            'transmission_id', 'drive_type_id', 'sales_method_id',
            'document_type_id', 'vehicle_status_id',
            'exterior_color_id', 'interior_color_id',
        ];
        foreach ($filters as $filter) {
            if ($request->filled($filter)) {
                $query->where($filter, $request->input($filter));
            }
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }
        if ($request->filled('min_year')) {
            $query->where('year', '>=', $request->input('min_year'));
        }
        if ($request->filled('max_year')) {
            $query->where('year', '<=', $request->input('max_year'));
        }
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%");
            });
        }
        if ($request->filled('property_ids')) {
            $ids = is_array($request->property_ids)
                ? $request->property_ids
                : explode(',', $request->property_ids);
            foreach ($ids as $id) {
                $query->whereHas('properties', function ($q) use ($id) {
                    $q->where('properties.id', $id);
                });
            }
        }

        // --- Sorting ---
        match ($request->input('sort', 'newest')) {
            'price_low'  => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'oldest'     => $query->orderBy('created_at', 'asc'),
            default      => $query->orderBy('created_at', 'desc'),
        };

        $ads = $query->paginate($request->input('limit', 12));

        return response()->json([
            'success' => true,
            'data'    => $ads,
        ]);
    }

    // =========================================================================
    // PUBLIC: GET /api/ads/{id}
    // Show a single ad (must be active OR belong to requester)
    // =========================================================================
    public function show(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::with($this->relations)->findOrFail($id);

        // Non-active ads are only shown to the owner
        if ($vehicle->ad_status !== 'active') {
            $user = $request->user();
            if (!$user || $user->id !== $vehicle->user_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ad not found.',
                ], 404);
            }
        }

        return response()->json([
            'success' => true,
            'data'    => $vehicle,
        ]);
    }

    // =========================================================================
    // AUTHENTICATED: POST /api/ads
    // Create (upload) a new ad
    // =========================================================================
    public function store(StoreAdRequest $request): JsonResponse
    {
        $validated = $request->validated();

        // Auto-generate a title from brand/model/year if not provided
        if (empty($validated['title'])) {
            $validated['title'] = $this->generateTitle($validated);
        }

        // Assign the ad to the authenticated user
        $validated['user_id'] = $request->user()->id;

        // Default ad_status
        $validated['ad_status'] = $validated['ad_status'] ?? 'active';

        // Remove file fields from mass-assignment
        $properties = $validated['properties'] ?? null;
        unset($validated['properties'], $validated['gallery_images'], $validated['documents']);

        // Create the vehicle record
        $vehicle = Vehicle::create($validated);

        // Sync extra features / properties
        if (!empty($properties)) {
            $vehicle->properties()->sync($properties);
        }

        // Handle main image upload
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('ads/images', 'public');
            $vehicle->update(['main_image' => $path]);
        }

        // Handle gallery images (up to 8)
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('ads/gallery', 'public');
            }
            $vehicle->update(['gallery_images' => $galleryPaths]);
        }

        // Handle document uploads
        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('ads/documents', 'public');
            }
            // Store document paths in a separate JSON column if you have one,
            // or leave as a response-only field for now.
            // For now we attach them to the response only.
        }

        $vehicle->load($this->relations);

        return response()->json([
            'success' => true,
            'message' => 'Ad uploaded successfully.',
            'data'    => $vehicle,
        ], 201);
    }

    // =========================================================================
    // AUTHENTICATED: GET /api/ads/my
    // List all ads belonging to the authenticated user
    // =========================================================================
    public function myAds(Request $request): JsonResponse
    {
        $ads = Vehicle::with($this->relations)
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate($request->input('limit', 12));

        return response()->json([
            'success' => true,
            'data'    => $ads,
        ]);
    }

    // =========================================================================
    // AUTHENTICATED: PUT /api/ads/{id}
    // Update an existing ad (owner only)
    // =========================================================================
    public function update(UpdateAdRequest $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        // Ownership check
        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to update this ad.',
            ], 403);
        }

        $validated = $request->validated();

        $properties = $validated['properties'] ?? null;
        unset($validated['properties'], $validated['gallery_images'], $validated['documents']);

        $vehicle->update($validated);

        // Sync properties if provided
        if ($properties !== null) {
            $vehicle->properties()->sync($properties);
        }

        // Replace main image if a new one is uploaded
        if ($request->hasFile('main_image')) {
            if ($vehicle->main_image) {
                Storage::disk('public')->delete($vehicle->main_image);
            }
            $path = $request->file('main_image')->store('ads/images', 'public');
            $vehicle->update(['main_image' => $path]);
        }

        // Append / replace gallery images
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images
            if ($vehicle->gallery_images) {
                foreach ($vehicle->gallery_images as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryPaths[] = $image->store('ads/gallery', 'public');
            }
            $vehicle->update(['gallery_images' => $galleryPaths]);
        }

        $vehicle->load($this->relations);

        return response()->json([
            'success' => true,
            'message' => 'Ad updated successfully.',
            'data'    => $vehicle,
        ]);
    }

    // =========================================================================
    // AUTHENTICATED: DELETE /api/ads/{id}
    // Delete an ad (owner only)
    // =========================================================================
    public function destroy(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        // Ownership check
        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to delete this ad.',
            ], 403);
        }

        // Clean up stored files
        if ($vehicle->main_image) {
            Storage::disk('public')->delete($vehicle->main_image);
        }
        if ($vehicle->gallery_images) {
            foreach ($vehicle->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        $vehicle->properties()->detach();
        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Ad deleted successfully.',
        ]);
    }

    // =========================================================================
    // AUTHENTICATED: PATCH /api/ads/{id}/status
    // Change ad status: active, garage, draft, inactive
    // =========================================================================
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        if ($vehicle->user_id !== $request->user()->id) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to modify this ad.',
            ], 403);
        }

        $request->validate([
            'ad_status' => 'required|in:active,garage,draft,inactive',
        ]);

        $vehicle->update(['ad_status' => $request->input('ad_status')]);

        return response()->json([
            'success'    => true,
            'message'    => 'Ad status updated.',
            'ad_status'  => $vehicle->ad_status,
        ]);
    }

    // =========================================================================
    // Private helper: auto-generate ad title
    // =========================================================================
    private function generateTitle(array $data): string
    {
        $parts = [];

        if (!empty($data['year'])) {
            $parts[] = $data['year'];
        }

        // Use brand_id / model_id — actual names are resolved via relations
        // Frontend should always send a title if it wants a custom one;
        // this fallback just avoids a DB error since title is NOT NULL.
        return implode(' ', $parts) ?: 'Vehicle Ad';
    }
}
