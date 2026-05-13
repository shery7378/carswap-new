<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\FuelType;
use App\Models\Transmission;
use App\Models\DriveType;
use App\Models\BodyType;
use App\Models\SalesMethod;
use App\Models\VehicleStatus;
use App\Models\Property;
use App\Models\Color;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\EmailService;
use App\Traits\CanSyncEntities;

class AdminVehicleController extends Controller
{
    use CanSyncEntities;
    public function index(Request $request)
    {
        $query = Vehicle::with(['brand', 'model', 'fuelType', 'transmission', 'user']);
        
        if ($request->filled('status')) {
            $query->where('ad_status', $request->status);
        }

        $vehicles = $query->orderBy('created_at', 'desc')->paginate(500);
        return view('content.dashboard.vehicles.index', compact('vehicles'));
    }

    public function show($id, Request $request)
    {
        $vehicle = Vehicle::with([
            'brand', 'model', 'fuelType', 'transmission', 'driveType', 
            'bodyType', 'exteriorColor', 'interiorColor', 'salesMethod', 
            'documentType', 'vehicleStatus', 'user', 'properties'
        ])->findOrFail($id);

        if ($request->ajax() || $request->has('modal')) {
            return view('content.dashboard.vehicles.partials.show-modal-content', compact('vehicle'));
        }

        return view('content.dashboard.vehicles.show', compact('vehicle'));
    }

    public function create()
    {
        $brands = Brand::where('is_active', true)->get();
        $fuelTypes = FuelType::where('is_active', true)->get();
        $transmissions = Transmission::where('is_active', true)->get();
        $driveTypes = DriveType::where('is_active', true)->get();
        $bodyTypes = BodyType::where('is_active', true)->get();
        $salesMethods = SalesMethod::where('is_active', true)->get();
        $vehicleStatuses = VehicleStatus::where('is_active', true)->get();
        $properties = Property::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $exteriorColors = Color::where('is_active', true)->where('type', 'exterior')->get();
        $interiorColors = Color::where('is_active', true)->where('type', 'interior')->get();
        $documentTypes = DocumentType::where('is_active', true)->get();

        return view('content.dashboard.vehicles.create', compact(
            'brands',
            'fuelTypes',
            'transmissions',
            'driveTypes',
            'bodyTypes',
            'salesMethods',
            'vehicleStatuses',
            'properties',
            'colors',
            'exteriorColors',
            'interiorColors',
            'documentTypes'
        ));
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'brand_id' => 'nullable',
                'model_id' => 'nullable',
                'year' => 'nullable|integer',
                'price' => 'nullable|numeric',
                'regular_price_label' => 'nullable|string',
                'regular_price_description' => 'nullable|string',
                'sale_price' => 'nullable|numeric',
                'sale_price_label' => 'nullable|string',
                'instant_savings_label' => 'nullable|string',
                'currency' => 'nullable|string',
                'mileage' => 'nullable|integer',
                'fuel_type_id' => 'nullable',
                'transmission_id' => 'nullable',
                'drive_type_id' => 'nullable',
                'body_type_id' => 'nullable',
                'exterior_color_id' => 'nullable',
                'interior_color_id' => 'nullable',
                'sales_method_id' => 'nullable',
                'document_type_id' => 'nullable',
                'vehicle_status_id' => 'nullable',
                'cylinder_capacity' => 'nullable|string',
                'performance' => 'nullable|string',
                'battery_capacity' => 'nullable|numeric|min:0',
                'range' => 'nullable|integer|min:0',
                'vin_number' => 'nullable|string',
                'engine_number' => 'nullable|string',
                'location' => 'nullable|string',
                'address' => 'nullable|string',
                'latitude' => 'nullable|string',
                'longitude' => 'nullable|string',
                'description' => 'nullable|string',
                'video_url' => 'nullable|string',
                'is_featured' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'documents.*' => 'nullable|file|mimes:pdf|max:10240',
                'technical_expiration' => 'nullable|date',
                'history_report' => 'nullable|string|max:500',
                'ad_status' => 'nullable|in:Publikált,Elutasítva,Függőben,Piszkozat',
                'exchange_preferences' => 'nullable|array',
                'exchange_preferences.*.brand_id' => 'nullable',
                'exchange_preferences.*.model_id' => 'nullable',
                'exchange_preferences.*.body_type_id' => 'nullable',
                'exchange_preferences.*.fuel_type_id' => 'nullable',
                'exchange_preferences.*.transmission_id' => 'nullable',
                'exchange_preferences.*.drive_type_id' => 'nullable',
                'exchange_preferences.*.year_from' => 'nullable|integer',
                'exchange_preferences.*.cylinder_capacity' => 'nullable|integer',
                'exchange_preferences.*.battery_capacity' => 'nullable|numeric|min:0',
                'exchange_preferences.*.range' => 'nullable|integer|min:0',
            ], [
                'main_image.mimes' => 'Supported image types: jpg, jpeg, png, webp.',
                'main_image.max' => 'Main image must be less than 10 MB.',
                'gallery_images.*.mimes' => 'Supported image types: jpg, jpeg, png, webp.',
                'gallery_images.*.max' => 'Each gallery image must be less than 10 MB.',
                'documents.*.mimes' => 'Supported document types: pdf.',
                'documents.*.max' => 'Each document must be less than 10 MB.',
            ]);

            // --- Sync/Resolve Entities (Check if exists, if not add it) ---
            $validated['brand_id']          = $this->resolveEntityId(\App\Models\Brand::class, $validated['brand_id'] ?? null);
            $validated['model_id']          = $this->resolveEntityId(\App\Models\VehicleModel::class, $validated['model_id'] ?? null, ['brand_id' => $validated['brand_id']]);
            $validated['body_type_id']      = $this->resolveEntityId(\App\Models\BodyType::class, $validated['body_type_id'] ?? null);
            $validated['fuel_type_id']      = $this->resolveEntityId(\App\Models\FuelType::class, $validated['fuel_type_id'] ?? null);
            $validated['transmission_id']   = $this->resolveEntityId(\App\Models\Transmission::class, $validated['transmission_id'] ?? null);
            $validated['drive_type_id']     = $this->resolveEntityId(\App\Models\DriveType::class, $validated['drive_type_id'] ?? null);
            $validated['exterior_color_id'] = $this->resolveEntityId(\App\Models\Color::class, $validated['exterior_color_id'] ?? null);
            $validated['interior_color_id'] = $this->resolveEntityId(\App\Models\Color::class, $validated['interior_color_id'] ?? null);
            $validated['document_type_id']  = $this->resolveEntityId(\App\Models\DocumentType::class, $validated['document_type_id'] ?? null);
            $validated['sales_method_id']   = $this->resolveEntityId(\App\Models\SalesMethod::class, $validated['sales_method_id'] ?? null);
            $validated['vehicle_status_id'] = $this->resolveEntityId(\App\Models\VehicleStatus::class, $validated['vehicle_status_id'] ?? null);

            if ($request->hasFile('main_image')) {
                $validated['main_image'] = $request->file('main_image')->store('vehicles', 'public');
            }

            if ($request->hasFile('gallery_images')) {
                $gallery = [];
                foreach ($request->file('gallery_images') as $image) {
                    $gallery[] = $image->store('vehicles/gallery', 'public');
                }
                $validated['gallery_images'] = $gallery;
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['user_id'] = auth()->id() ?? 1;
            $validated['ad_status'] = $request->input('ad_status', 'Publikált');

            $vehicle = Vehicle::create($validated);

            if ($request->has('properties')) {
                $vehicle->properties()->sync($request->properties);
            }

            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle created successfully.');
        } catch (\Exception $e) {
            \Log::error('Vehicle creation error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error creating vehicle: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $vehicle = Vehicle::with('properties')->findOrFail($id);
        $brands = Brand::where('is_active', true)->get();
        $models = VehicleModel::where('brand_id', $vehicle->brand_id)->where('is_active', true)->get();
        $fuelTypes = FuelType::where('is_active', true)->get();
        $transmissions = Transmission::where('is_active', true)->get();
        $driveTypes = DriveType::where('is_active', true)->get();
        $bodyTypes = BodyType::where('is_active', true)->get();
        $salesMethods = SalesMethod::where('is_active', true)->get();
        $vehicleStatuses = VehicleStatus::where('is_active', true)->get();
        $properties = Property::where('is_active', true)->get();
        $colors = Color::where('is_active', true)->get();
        $exteriorColors = Color::where('is_active', true)->where('type', 'exterior')->get();
        $interiorColors = Color::where('is_active', true)->where('type', 'interior')->get();
        $documentTypes = DocumentType::where('is_active', true)->get();

        return view('content.dashboard.vehicles.edit', compact(
            'vehicle',
            'brands',
            'models',
            'fuelTypes',
            'transmissions',
            'driveTypes',
            'bodyTypes',
            'salesMethods',
            'vehicleStatuses',
            'properties',
            'colors',
            'exteriorColors',
            'interiorColors',
            'documentTypes'
        ));
    }

    public function update(Request $request, $id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'brand_id' => 'nullable',
                'model_id' => 'nullable',
                'year' => 'nullable|integer',
                'price' => 'nullable|numeric',
                'regular_price_label' => 'nullable|string',
                'regular_price_description' => 'nullable|string',
                'sale_price' => 'nullable|numeric',
                'sale_price_label' => 'nullable|string',
                'instant_savings_label' => 'nullable|string',
                'currency' => 'nullable|string',
                'mileage' => 'nullable|integer',
                'fuel_type_id' => 'nullable',
                'transmission_id' => 'nullable',
                'drive_type_id' => 'nullable',
                'body_type_id' => 'nullable',
                'exterior_color_id' => 'nullable',
                'interior_color_id' => 'nullable',
                'sales_method_id' => 'nullable',
                'document_type_id' => 'nullable',
                'vehicle_status_id' => 'nullable',
                'cylinder_capacity' => 'nullable|string',
                'performance' => 'nullable|string',
                'battery_capacity' => 'nullable|numeric|min:0',
                'range' => 'nullable|integer|min:0',
                'vin_number' => 'nullable|string',
                'engine_number' => 'nullable|string',
                'location' => 'nullable|string',
                'address' => 'nullable|string',
                'latitude' => 'nullable|string',
                'longitude' => 'nullable|string',
                'description' => 'nullable|string',
                'video_url' => 'nullable|string',
                'is_featured' => 'boolean',
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240',
                'documents.*' => 'nullable|file|mimes:pdf|max:10240',
                'technical_expiration' => 'nullable|date',
                'history_report' => 'nullable|string|max:500',
                'ad_status' => 'nullable|in:Publikált,Elutasítva,Függőben,Piszkozat',
                'exchange_preferences' => 'nullable|array',
                'exchange_preferences.*.brand_id' => 'nullable',
                'exchange_preferences.*.model_id' => 'nullable',
                'exchange_preferences.*.body_type_id' => 'nullable',
                'exchange_preferences.*.fuel_type_id' => 'nullable',
                'exchange_preferences.*.transmission_id' => 'nullable',
                'exchange_preferences.*.drive_type_id' => 'nullable',
                'exchange_preferences.*.year_from' => 'nullable|integer',
                'exchange_preferences.*.cylinder_capacity' => 'nullable|integer',
            ], [
                'main_image.mimes' => 'Supported image types: jpg, jpeg, png, webp.',
                'main_image.max' => 'Main image must be less than 10 MB.',
                'gallery_images.*.mimes' => 'Supported image types: jpg, jpeg, png, webp.',
                'gallery_images.*.max' => 'Each gallery image must be less than 10 MB.',
                'documents.*.mimes' => 'Supported document types: pdf.',
                'documents.*.max' => 'Each document must be less than 10 MB.',
            ]);

            // --- Sync/Resolve Entities (Check if exists, if not add it) ---
            if (isset($validated['brand_id'])) {
                $validated['brand_id'] = $this->resolveEntityId(\App\Models\Brand::class, $validated['brand_id']);
            }
            if (isset($validated['model_id'])) {
                $validated['model_id'] = $this->resolveEntityId(\App\Models\VehicleModel::class, $validated['model_id'], ['brand_id' => $validated['brand_id'] ?? $vehicle->brand_id]);
            }
            if (isset($validated['body_type_id'])) {
                $validated['body_type_id'] = $this->resolveEntityId(\App\Models\BodyType::class, $validated['body_type_id']);
            }
            if (isset($validated['fuel_type_id'])) {
                $validated['fuel_type_id'] = $this->resolveEntityId(\App\Models\FuelType::class, $validated['fuel_type_id']);
            }
            if (isset($validated['transmission_id'])) {
                $validated['transmission_id'] = $this->resolveEntityId(\App\Models\Transmission::class, $validated['transmission_id']);
            }
            if (isset($validated['drive_type_id'])) {
                $validated['drive_type_id'] = $this->resolveEntityId(\App\Models\DriveType::class, $validated['drive_type_id']);
            }
            if (isset($validated['exterior_color_id'])) {
                $validated['exterior_color_id'] = $this->resolveEntityId(\App\Models\Color::class, $validated['exterior_color_id']);
            }
            if (isset($validated['interior_color_id'])) {
                $validated['interior_color_id'] = $this->resolveEntityId(\App\Models\Color::class, $validated['interior_color_id']);
            }
            if (isset($validated['document_type_id'])) {
                $validated['document_type_id'] = $this->resolveEntityId(\App\Models\DocumentType::class, $validated['document_type_id']);
            }
            if (isset($validated['sales_method_id'])) {
                $validated['sales_method_id'] = $this->resolveEntityId(\App\Models\SalesMethod::class, $validated['sales_method_id']);
            }
            if (isset($validated['vehicle_status_id'])) {
                $validated['vehicle_status_id'] = $this->resolveEntityId(\App\Models\VehicleStatus::class, $validated['vehicle_status_id']);
            }

            if ($request->hasFile('main_image')) {
                if ($vehicle->main_image)
                    Storage::disk('public')->delete($vehicle->main_image);
                $validated['main_image'] = $request->file('main_image')->store('vehicles', 'public');
            }

            if ($request->hasFile('gallery_images')) {
                $currentGallery = $vehicle->gallery_images;
                if (is_string($currentGallery)) {
                    $currentGallery = json_decode($currentGallery, true);
                }
                $gallery = is_array($currentGallery) ? $currentGallery : [];
                foreach ($request->file('gallery_images') as $image) {
                    $gallery[] = $image->store('vehicles/gallery', 'public');
                }
                $validated['gallery_images'] = $gallery;
            }

            $validated['is_featured'] = $request->has('is_featured');
            $vehicle->update($validated);

            if ($request->has('properties')) {
                $vehicle->properties()->sync($request->properties);
            }

            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Vehicle update error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error updating vehicle: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);

            if ($vehicle->main_image) {
                Storage::disk('public')->delete($vehicle->main_image);
            }

            $gallery = $vehicle->gallery_images;
            if (is_string($gallery)) {
                $gallery = json_decode($gallery, true);
            }
            
            if (is_array($gallery)) {
                foreach ($gallery as $img) {
                    Storage::disk('public')->delete($img);
                }
            }

            $vehicle->delete();
            return redirect()->route('admin.vehicles.index')->with('success', 'A jármű sikeresen törölve.');
        } catch (\Exception $e) {
            \Log::error('Vehicle deletion error: ' . $e->getMessage());
            return redirect()->route('admin.vehicles.index')->with('error', 'Error deleting vehicle: ' . $e->getMessage());
        }
    }

    /**
     * API for fetching models based on brand.
     */
    public function getModelsByBrand($brandId)
    {
        return response()->json(VehicleModel::where('brand_id', $brandId)->get());
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'ad_status' => 'required|in:Publikált,Elutasítva,Függőben,Piszkozat'
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $oldStatus = $vehicle->ad_status;
        $vehicle->update(['ad_status' => $request->ad_status]);

        // Send Email if newly published
        if ($request->ad_status === 'Publikált' && $oldStatus !== 'Publikált' && $vehicle->user) {
            EmailService::send($vehicle->user->email, 'vehicle-approved', [
                'first_name' => $vehicle->user->first_name,
                'vehicle_name' => $vehicle->title,
                'vehicle_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/vehicles/' . $vehicle->id
            ]);
        }

        return redirect()->back()->with('success', 'Vehicle status updated to ' . $request->ad_status);
    }

    public function bulkUpdateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:vehicles,id',
            'ad_status' => 'required|in:Publikált,Elutasítva,Függőben,Piszkozat'
        ]);

        $vehicles = Vehicle::whereIn('id', $request->ids)->get();

        foreach ($vehicles as $vehicle) {
            $oldStatus = $vehicle->ad_status;
            $vehicle->update(['ad_status' => $request->ad_status]);

            // Send Email if newly published
            if ($request->ad_status === 'Publikált' && $oldStatus !== 'Publikált' && $vehicle->user) {
                EmailService::send($vehicle->user->email, 'vehicle-approved', [
                    'first_name' => $vehicle->user->first_name,
                    'vehicle_name' => $vehicle->title,
                    'vehicle_url' => env('FRONTEND_URL', 'http://localhost:3000') . '/vehicles/' . $vehicle->id
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Status updated for ' . count($request->ids) . ' vehicles.'
        ]);
    }

    public function toggleFeatured($id)
    {
        try {
            $vehicle = Vehicle::findOrFail($id);
            $new_status = !$vehicle->is_featured;
            $vehicle->update(['is_featured' => $new_status]);

            return response()->json([
                'success' => true,
                'is_featured' => $new_status,
                'message' => 'Vehicle ' . ($new_status ? 'Marked as Featured' : 'Removed from Featured')
            ]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
