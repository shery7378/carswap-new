<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVehicleController extends Controller
{
    // -------------------------------------------------------------------------
    // All relations loaded in every response
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
    // GET /api/admin/vehicles
    // List ALL ads/vehicles (all statuses), with filters & pagination
    // =========================================================================
    public function index(Request $request): JsonResponse
    {
        $query = Vehicle::with($this->relations);

        // --- Filter by ad_status / status ---
        if ($request->filled('ad_status')) {
            $query->where('ad_status', $request->input('ad_status'));
        } elseif ($request->filled('status')) {
            $query->where('ad_status', $request->input('status'));
        }

        // --- Filter by owner/user ---
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->input('user_id'));
        }

        // --- Standard attribute filters ---
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

        // --- Featured filter ---
        if ($request->filled('is_featured')) {
            $query->where('is_featured', filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN));
        }

        // --- Price range ---
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // --- Year range ---
        if ($request->filled('min_year')) {
            $query->where('year', '>=', $request->input('min_year'));
        }
        if ($request->filled('max_year')) {
            $query->where('year', '<=', $request->input('max_year'));
        }

        // --- Keyword search ---
        if ($request->filled('search')) {
            $term = $request->input('search');
            $query->where(function ($q) use ($term) {
                $q->where('title', 'LIKE', "%{$term}%")
                  ->orWhere('description', 'LIKE', "%{$term}%")
                  ->orWhere('vin_number', 'LIKE', "%{$term}%");
            });
        }

        // --- Sorting ---
        $sort = $request->input('sort', 'newest');
        switch ($sort) {
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $vehicles = $query->paginate($request->input('limit', 20));

        return response()->json([
            'success' => true,
            'data'    => $vehicles,
        ]);
    }

    // =========================================================================
    // GET /api/admin/vehicles/{id}
    // Show a single vehicle/ad (any status)
    // =========================================================================
    public function show(int $id): JsonResponse
    {
        $vehicle = Vehicle::with($this->relations)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data'    => $vehicle,
        ]);
    }

    // =========================================================================
    // POST /api/admin/vehicles
    // Admin creates a new ad/vehicle
    // =========================================================================
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // Core required fields
            'title'                => 'required|string|max:191',
            'description'          => 'nullable|string',

            // IDs / foreign keys
            'brand_id'             => 'nullable|integer|exists:brands,id',
            'model_id'             => 'nullable|integer|exists:vehicle_models,id',
            'body_type_id'         => 'nullable|integer|exists:body_types,id',
            'fuel_type_id'         => 'nullable|integer|exists:fuel_types,id',
            'transmission_id'      => 'nullable|integer|exists:transmissions,id',
            'drive_type_id'        => 'nullable|integer|exists:drive_types,id',
            'exterior_color_id'    => 'nullable|integer|exists:colors,id',
            'interior_color_id'    => 'nullable|integer|exists:colors,id',
            'sales_method_id'      => 'nullable|integer|exists:sales_methods,id',
            'document_type_id'     => 'nullable|integer|exists:document_types,id',
            'vehicle_status_id'    => 'nullable|integer|exists:vehicle_statuses,id',

            // Numeric
            'price'                => 'nullable|numeric|min:0',
            'sale_price'           => 'nullable|numeric|min:0',
            'year'                 => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'mileage'              => 'nullable|integer|min:0',
            'cylinder_capacity'    => 'nullable|integer|min:1',
            'performance'          => 'nullable|integer|min:1',

            // Strings
            'currency'             => 'nullable|string|max:10',
            'location'             => 'nullable|string|max:191',
            'address'              => 'nullable|string|max:255',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'vin_number'           => 'nullable|string|max:191',
            'history_report'       => 'nullable|string|max:500',
            'technical_expiration' => 'nullable|date',
            'video_url'            => 'nullable|url|max:500',

            // Booleans
            'is_featured'          => 'nullable|boolean',
            'request_price_option' => 'nullable|boolean',

            // Ad control
            'ad_status'            => 'nullable|in:published,rejected,pending,draft',
            'owner_type'           => 'nullable|in:private,dealer',

            // Owner assignment (admin can assign to any user)
            'user_id'              => 'nullable|integer|exists:users,id',

            // Many-to-many
            'properties'           => 'nullable|array',
            'properties.*'         => 'integer|exists:properties,id',

            // Images
            'main_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_images'       => 'nullable|array|max:8',
            'gallery_images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:10240',

            // Documents
            'documents'            => 'nullable|array|max:5',
            'documents.*'          => 'file|mimes:pdf|max:10240',
        ], [
            'gallery_images.max'         => 'You can upload a maximum of 8 pictures.',
            'gallery_images.*.max'       => 'Each image must be less than 10 MB.',
            'gallery_images.*.mimes'     => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.mimes'           => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.max'             => 'Main image must be less than 10 MB.',
            'documents.*.mimes'          => 'Supported document types: pdf.',
            'documents.*.max'            => 'Each document must be less than 10 MB.',
        ]);

        // Separate relational and file fields before mass-assignment
        $properties = $validated['properties'] ?? null;
        unset($validated['properties'], $validated['gallery_images'], $validated['main_image'], $validated['documents']);

        $validated['ad_status'] = $validated['ad_status'] ?? 'published';

        $vehicle = Vehicle::create($validated);

        // Sync extra features
        if (!empty($properties)) {
            $vehicle->properties()->sync($properties);
        }

        // Main image
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('vehicles/images', 'public');
            $vehicle->update(['main_image' => $path]);
        }

        // Gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $img) {
                $galleryPaths[] = $img->store('vehicles/gallery', 'public');
            }
            $vehicle->update(['gallery_images' => $galleryPaths]);
        }

        // Documents — save paths to the documents JSON column
        if ($request->hasFile('documents')) {
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('vehicles/documents', 'public');
            }
            $vehicle->update(['documents' => $docPaths]);
        }

        $vehicle->load($this->relations);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle created successfully.',
            'data'    => $vehicle,
        ], 201);
    }

    // =========================================================================
    // PUT /api/admin/vehicles/{id}
    // Admin updates any vehicle/ad
    // =========================================================================
    public function update(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'title'                => 'sometimes|required|string|max:191',
            'description'          => 'nullable|string',
            'brand_id'             => 'nullable|integer|exists:brands,id',
            'model_id'             => 'nullable|integer|exists:vehicle_models,id',
            'body_type_id'         => 'nullable|integer|exists:body_types,id',
            'fuel_type_id'         => 'nullable|integer|exists:fuel_types,id',
            'transmission_id'      => 'nullable|integer|exists:transmissions,id',
            'drive_type_id'        => 'nullable|integer|exists:drive_types,id',
            'exterior_color_id'    => 'nullable|integer|exists:colors,id',
            'interior_color_id'    => 'nullable|integer|exists:colors,id',
            'sales_method_id'      => 'nullable|integer|exists:sales_methods,id',
            'document_type_id'     => 'nullable|integer|exists:document_types,id',
            'vehicle_status_id'    => 'nullable|integer|exists:vehicle_statuses,id',
            'price'                => 'nullable|numeric|min:0',
            'sale_price'           => 'nullable|numeric|min:0',
            'year'                 => 'nullable|integer|min:1900|max:' . (date('Y') + 1),
            'mileage'              => 'nullable|integer|min:0',
            'cylinder_capacity'    => 'nullable|integer|min:1',
            'performance'          => 'nullable|integer|min:1',
            'currency'             => 'nullable|string|max:10',
            'location'             => 'nullable|string|max:191',
            'address'              => 'nullable|string|max:255',
            'latitude'             => 'nullable|numeric',
            'longitude'            => 'nullable|numeric',
            'vin_number'           => 'nullable|string|max:191',
            'history_report'       => 'nullable|string|max:500',
            'technical_expiration' => 'nullable|date',
            'video_url'            => 'nullable|url|max:500',
            'is_featured'          => 'nullable|boolean',
            'request_price_option' => 'nullable|boolean',
            'ad_status'            => 'nullable|in:published,rejected,pending,draft',
            'owner_type'           => 'nullable|in:private,dealer',
            'user_id'              => 'nullable|integer|exists:users,id',
            'properties'           => 'nullable|array',
            'properties.*'         => 'integer|exists:properties,id',
            'main_image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'gallery_images'       => 'nullable|array|max:8',
            'gallery_images.*'     => 'image|mimes:jpg,jpeg,png,webp|max:10240',
            'documents'            => 'nullable|array|max:5',
            'documents.*'          => 'file|mimes:pdf|max:10240',
        ], [
            'gallery_images.max'         => 'You can upload a maximum of 8 pictures.',
            'gallery_images.*.max'       => 'Each image must be less than 10 MB.',
            'gallery_images.*.mimes'     => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.mimes'           => 'Supported image types: jpg, jpeg, png, webp.',
            'main_image.max'             => 'Main image must be less than 10 MB.',
            'documents.*.mimes'          => 'Supported document types: pdf.',
            'documents.*.max'            => 'Each document must be less than 10 MB.',
        ]);

        $properties = $validated['properties'] ?? null;
        unset($validated['properties'], $validated['gallery_images'], $validated['main_image'], $validated['documents']);

        $vehicle->update($validated);

        // Sync properties if sent
        if ($properties !== null) {
            $vehicle->properties()->sync($properties);
        }

        // Replace main image
        if ($request->hasFile('main_image')) {
            if ($vehicle->main_image) {
                Storage::disk('public')->delete($vehicle->main_image);
            }
            $path = $request->file('main_image')->store('vehicles/images', 'public');
            $vehicle->update(['main_image' => $path]);
        }

        // Replace gallery images
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images first
            if ($vehicle->gallery_images) {
                foreach ($vehicle->gallery_images as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $galleryPaths = [];
            foreach ($request->file('gallery_images') as $img) {
                $galleryPaths[] = $img->store('vehicles/gallery', 'public');
            }
            $vehicle->update(['gallery_images' => $galleryPaths]);
        }

        // Replace documents if new ones are uploaded
        if ($request->hasFile('documents')) {
            if ($vehicle->documents) {
                foreach ($vehicle->documents as $old) {
                    Storage::disk('public')->delete($old);
                }
            }
            $docPaths = [];
            foreach ($request->file('documents') as $doc) {
                $docPaths[] = $doc->store('vehicles/documents', 'public');
            }
            $vehicle->update(['documents' => $docPaths]);
        }

        $vehicle->load($this->relations);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle updated successfully.',
            'data'    => $vehicle,
        ]);
    }

    // =========================================================================
    // DELETE /api/admin/vehicles/{id}
    // Admin deletes any vehicle/ad, with image cleanup
    // =========================================================================
    public function destroy(int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        // Clean up main image
        if ($vehicle->main_image) {
            Storage::disk('public')->delete($vehicle->main_image);
        }

        // Clean up gallery images
        if ($vehicle->gallery_images) {
            foreach ($vehicle->gallery_images as $img) {
                Storage::disk('public')->delete($img);
            }
        }

        // Detach properties pivot
        $vehicle->properties()->detach();

        $vehicle->delete();

        return response()->json([
            'success' => true,
            'message' => 'Vehicle deleted successfully.',
        ]);
    }

    // =========================================================================
    // PATCH /api/admin/vehicles/{id}/status
    // Admin changes the ad_status of any ad
    // =========================================================================
    public function changeStatus(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'ad_status' => 'required|in:published,rejected,pending,draft',
        ]);

        $vehicle->update(['ad_status' => $request->input('ad_status')]);

        return response()->json([
            'success'   => true,
            'message'   => 'Ad status updated.',
            'ad_status' => $vehicle->ad_status,
        ]);
    }

    // =========================================================================
    // PATCH /api/admin/vehicles/{id}/featured
    // Admin toggles is_featured flag
    // =========================================================================
    public function toggleFeatured(Request $request, int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);

        $request->validate([
            'is_featured' => 'required|boolean',
        ]);

        $vehicle->update(['is_featured' => $request->boolean('is_featured')]);

        return response()->json([
            'success'     => true,
            'message'     => 'Featured status updated.',
            'is_featured' => $vehicle->is_featured,
        ]);
    }

    // =========================================================================
    // GET /api/admin/brands/{brandId}/models
    // =========================================================================
    public function getModelsByBrand(int $brandId): JsonResponse
    {
        $models = VehicleModel::where('brand_id', $brandId)
            ->orderBy('name')
            ->get();

        return response()->json([
            'success' => true,
            'data'    => $models,
        ]);
    }
}
