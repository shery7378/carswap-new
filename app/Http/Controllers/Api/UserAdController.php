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
            ->where('ad_status', 'Publikált');

        // --- Filtering ---
        $filters = [
            'brand_id',
            'model_id',
            'body_type_id',
            'fuel_type_id',
            'transmission_id',
            'drive_type_id',
            'sales_method_id',
            'document_type_id',
            'vehicle_status_id',
            'exterior_color_id',
            'interior_color_id',
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
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            'oldest' => $query->orderBy('id', 'asc'),
            default => $query->orderBy('id', 'desc'),
        };

        $ads = $query->paginate($request->input('limit', 12));

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    // =========================================================================
    // PUBLIC: GET /api/ads/{id}
    // Show a single ad (must be active OR belong to requester)
    // =========================================================================
    public function show(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::with($this->relations)->findOrFail($id);

        // Non-published ads are only shown to the owner
        if ($vehicle->ad_status !== 'Publikált') {
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
            'data' => $vehicle,
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
        $user = $request->user();
        $validated['user_id'] = $user->id;

        // Check subscription limits
        $limitCheck = $this->checkSubscriptionLimits(
            $user, 
            ($validated['ad_status'] ?? 'Függőben') === 'Piszkozat', 
            false, 
            count($validated['gallery_images'] ?? [])
        );
        if ($limitCheck !== true) {
            return $limitCheck;
        }

        // Default ad_status for users is 'pending' for approval, unless they explicitly saved it as 'draft'
        if (isset($validated['ad_status']) && $validated['ad_status'] === 'Piszkozat') {
            $validated['ad_status'] = 'Piszkozat';
        } else {
            $validated['ad_status'] = 'Függőben';
        }

        // Remove file fields from mass-assignment
        $properties = $validated['properties'] ?? null;
        unset($validated['properties'], $validated['gallery_images'], $validated['documents'], $validated['main_image']);

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

        // Handle document uploads — save paths to the documents JSON column
        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('ads/documents', 'public');
            }
            $vehicle->update(['documents' => $docPaths]);
        }

        $vehicle->load($this->relations);

        return response()->json([
            'success' => true,
            'message' => 'Ad uploaded successfully.',
            'data' => $vehicle,
        ], 201);
    }

    // =========================================================================
    // AUTHENTICATED: GET /api/garage
    // List user uploaded vehicles that are published, pending, and draft.
    // =========================================================================
    public function garage(Request $request): JsonResponse
    {
        $ads = Vehicle::with($this->relations)
            ->where('user_id', $request->user()->id)
            ->whereIn('ad_status', ['Publikált', 'Függőben', 'Piszkozat'])
            ->orderBy('id', 'desc')
            ->paginate($request->input('limit', 12));

        return response()->json([
            'success' => true,
            'data' => $ads,
        ]);
    }

    // =========================================================================
    // AUTHENTICATED: GET /api/ads/my
    // List all ads belonging to the authenticated user
    // =========================================================================
    public function myAds(Request $request): JsonResponse
    {
        $query = Vehicle::with($this->relations)
            ->where('user_id', $request->user()->id);

        if ($request->filled('ad_status')) {
            $query->where('ad_status', $request->input('ad_status'));
        } elseif ($request->filled('status')) {
            $query->where('ad_status', $request->input('status'));
        }

        $ads = $query->orderBy('id', 'desc')
            ->paginate($request->input('limit', 12));

        return response()->json([
            'success' => true,
            'data' => $ads,
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
        unset($validated['properties'], $validated['gallery_images'], $validated['documents'], $validated['main_image']);

        // Reset status to pending for approval if updated, unless saving as draft
        $newStatus = (isset($validated['ad_status']) && $validated['ad_status'] === 'Piszkozat') ? 'Piszkozat' : 'Függőben';

        // Check active limit if moving from inactive (draft/rejected) to active
        // Also check HD image quota if the image count exceeds 6
        $newGalleryCount = $request->hasFile('gallery_images') ? count($request->file('gallery_images')) : count($vehicle->gallery_images ?? []);
        
        $limitCheck = $this->checkSubscriptionLimits(
            $request->user(), 
            $newStatus === 'Piszkozat', 
            true, 
            $newGalleryCount,
            $vehicle->id
        );
        
        if ($limitCheck !== true) {
            return $limitCheck;
        }

        $validated['ad_status'] = $newStatus;
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

        // Replace documents if new ones are uploaded
        if ($request->hasFile('documents')) {
            // Delete old documents
            if ($vehicle->documents) {
                foreach ($vehicle->documents as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('ads/documents', 'public');
            }
            $vehicle->update(['documents' => $docPaths]);
        }

        $vehicle->load($this->relations);

        return response()->json([
            'success' => true,
            'message' => 'Ad updated successfully.',
            'data' => $vehicle,
        ]);
    }

    /**
     * AUTHENTICATED: GET /api/ads/{id}/edit
     * Returns the vehicle data combined with all necessary form options (brands, fuel types, etc.)
     */
    public function edit(int $id): JsonResponse
    {
        $vehicle = Vehicle::with($this->relations)->findOrFail($id);

        if ($vehicle->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to edit this ad.',
            ], 403);
        }

        // Get options using the VehicleOptionController logic
        $options = (new VehicleOptionController())->getOptions()->getData();

        return response()->json([
            'success' => true,
            'data' => [
                'vehicle' => $vehicle,
                'options' => $options
            ],
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
            'ad_status' => 'required|in:Publikált,Elutasítva,Függőben,Piszkozat',
        ]);

        $newStatus = $request->input('ad_status');

        // Check limits if changing to published
        if ($newStatus === 'Publikált') {
            // If a user tries to set it to published, it should actually go to pending for approval
            // Actually, if this is coming from the user, they shouldn't be able to set it to 'published' directly.
            // But let's keep the logic for now or force it to pending.
            $newStatus = 'Függőben';

            $limitCheck = $this->checkSubscriptionLimits($request->user(), false, true, count($vehicle->gallery_images ?? []), $vehicle->id);
            if ($limitCheck !== true) {
                return $limitCheck;
            }
        }

        $vehicle->update(['ad_status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => 'Ad status updated.',
            'ad_status' => $vehicle->ad_status,
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

    /**
     * Check if the user has reached their subscription limits (Active ads, Garage space, HD images)
     */
    private function checkSubscriptionLimits($user, bool $isDraft = false, bool $isUpdate = false, int $galleryImageCount = 0, $vehicleId = null)
    {
        $user->loadMissing('activeSubscription.plan');
        $subscription = $user->activeSubscription;

        if (!$subscription || !$subscription->plan) {
            return response()->json([
                'success' => false,
                'message' => 'You do not have an active subscription plan.',
            ], 403);
        }

        $plan = $subscription->plan;

        // 1. Check Garage (Total) Limit
        if (!$isUpdate) {
            $garageAdsLimit = (int) $plan->garage_ads_limit;
            if ($garageAdsLimit > 0) {
                $totalCount = Vehicle::where('user_id', $user->id)
                    ->whereIn('ad_status', ['Publikált', 'Függőben', 'Piszkozat', 'Elutasítva'])
                    ->count();

                if ($totalCount >= $garageAdsLimit) {
                    return response()->json([
                        'success' => false,
                        'message' => "Your garage limit of {$garageAdsLimit} ads has been reached. Please upgrade your plan.",
                    ], 403);
                }
            }
        }

        // 2. Check Active Ads Limit (only if not saving as draft)
        if (!$isDraft) {
            // If it's an update, only check if we are moving from inactive to active
            $shouldCheckActive = true;
            if ($isUpdate && $vehicleId) {
                $v = Vehicle::find($vehicleId);
                if ($v && in_array($v->ad_status, ['Publikált', 'Függőben'])) {
                    $shouldCheckActive = false;
                }
            }

            if ($shouldCheckActive) {
                $activeAdsLimit = (int) $plan->active_ads_limit;
                if ($activeAdsLimit > 0) {
                    $activeCount = Vehicle::where('user_id', $user->id)
                        ->whereIn('ad_status', ['Publikált', 'Függőben'])
                        ->count();

                    if ($activeCount >= $activeAdsLimit) {
                        return response()->json([
                            'success' => false,
                            'message' => "Your active ads limit of {$activeAdsLimit} has been reached. You can save this as a draft, or upgrade your plan to publish it.",
                        ], 403);
                    }
                }
            }
        }

        // 3. Check HD Images Quota (Base limit is 6 images)
        if ($galleryImageCount > 6) {
            $hdQuota = (int) $plan->hd_images;
            
            // If quota is 0 (like Free plan), they can't have more than 6 images
            if ($hdQuota <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => "Your plan only allows up to 6 gallery images per ad. Please upgrade for HD ads.",
                ], 403);
            }

            // Count how many HD ads they currently have
            // and exclude the current one if it's an update and was already HD
            $hdAdsQuery = Vehicle::where('user_id', $user->id)
                ->whereRaw("JSON_LENGTH(gallery_images) > 6");
            
            if ($vehicleId) {
                $hdAdsQuery->where('id', '!=', $vehicleId);
            }

            $currentHdCount = $hdAdsQuery->count();

            if ($currentHdCount >= $hdQuota) {
                return response()->json([
                    'success' => false,
                    'message' => "You have reached your limit of {$hdQuota} HD ads. Please remove images from other ads or upgrade your plan.",
                ], 403);
            }
        }

        return true;
    }
}
