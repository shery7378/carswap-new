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

class AdminVehicleController extends Controller
{
    public function index(Request $request)
    {
        $query = Vehicle::with(['brand', 'model', 'fuelType', 'transmission', 'user']);
        
        if ($request->filled('status')) {
            $query->where('ad_status', $request->status);
        }

        $vehicles = $query->orderBy('id', 'desc')->paginate(500);
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
                'brand_id' => 'nullable|exists:brands,id',
                'model_id' => 'nullable|exists:vehicle_models,id',
                'year' => 'nullable|integer',
                'price' => 'nullable|numeric',
                'regular_price_label' => 'nullable|string',
                'regular_price_description' => 'nullable|string',
                'sale_price' => 'nullable|numeric',
                'sale_price_label' => 'nullable|string',
                'instant_savings_label' => 'nullable|string',
                'currency' => 'nullable|string',
                'mileage' => 'nullable|integer',
                'fuel_type_id' => 'nullable|exists:fuel_types,id',
                'transmission_id' => 'nullable|exists:transmissions,id',
                'drive_type_id' => 'nullable|exists:drive_types,id',
                'body_type_id' => 'nullable|exists:body_types,id',
                'exterior_color_id' => 'nullable|exists:colors,id',
                'interior_color_id' => 'nullable|exists:colors,id',
                'sales_method_id' => 'nullable|exists:sales_methods,id',
                'document_type_id' => 'nullable|exists:document_types,id',
                'vehicle_status_id' => 'nullable|exists:vehicle_statuses,id',
                'cylinder_capacity' => 'nullable|string',
                'performance' => 'nullable|string',
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
                'ad_status' => 'nullable|in:published,rejected,pending,draft',
                'exchange_preferences' => 'nullable|array',
                'exchange_preferences.*.brand_id' => 'nullable|exists:brands,id',
                'exchange_preferences.*.model_id' => 'nullable|exists:vehicle_models,id',
                'exchange_preferences.*.body_type_id' => 'nullable|exists:body_types,id',
                'exchange_preferences.*.fuel_type_id' => 'nullable|exists:fuel_types,id',
                'exchange_preferences.*.transmission_id' => 'nullable|exists:transmissions,id',
                'exchange_preferences.*.drive_type_id' => 'nullable|exists:drive_types,id',
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

            if ($request->hasFile('main_image')) {
                $validated['main_image'] = $request->file('main_image')->store('vehicles', 'public');
            }

            if ($request->hasFile('gallery_images')) {
                $gallery = [];
                foreach ($request->file('gallery_images') as $image) {
                    $gallery[] = $image->store('vehicles/gallery', 'public');
                }
                $validated['gallery_images'] = json_encode($gallery);
            }

            $validated['is_featured'] = $request->has('is_featured');
            $validated['user_id'] = auth()->id() ?? 1;
            $validated['ad_status'] = $request->input('ad_status', 'published');

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
                'brand_id' => 'nullable|exists:brands,id',
                'model_id' => 'nullable|exists:vehicle_models,id',
                'year' => 'nullable|integer',
                'price' => 'nullable|numeric',
                'regular_price_label' => 'nullable|string',
                'regular_price_description' => 'nullable|string',
                'sale_price' => 'nullable|numeric',
                'sale_price_label' => 'nullable|string',
                'instant_savings_label' => 'nullable|string',
                'currency' => 'nullable|string',
                'mileage' => 'nullable|integer',
                'fuel_type_id' => 'nullable|exists:fuel_types,id',
                'transmission_id' => 'nullable|exists:transmissions,id',
                'drive_type_id' => 'nullable|exists:drive_types,id',
                'body_type_id' => 'nullable|exists:body_types,id',
                'exterior_color_id' => 'nullable|exists:colors,id',
                'interior_color_id' => 'nullable|exists:colors,id',
                'sales_method_id' => 'nullable|exists:sales_methods,id',
                'document_type_id' => 'nullable|exists:document_types,id',
                'vehicle_status_id' => 'nullable|exists:vehicle_statuses,id',
                'cylinder_capacity' => 'nullable|string',
                'performance' => 'nullable|string',
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
                'ad_status' => 'nullable|in:published,rejected,pending,draft',
                'exchange_preferences' => 'nullable|array',
                'exchange_preferences.*.brand_id' => 'nullable|exists:brands,id',
                'exchange_preferences.*.model_id' => 'nullable|exists:vehicle_models,id',
                'exchange_preferences.*.body_type_id' => 'nullable|exists:body_types,id',
                'exchange_preferences.*.fuel_type_id' => 'nullable|exists:fuel_types,id',
                'exchange_preferences.*.transmission_id' => 'nullable|exists:transmissions,id',
                'exchange_preferences.*.drive_type_id' => 'nullable|exists:drive_types,id',
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
                $validated['gallery_images'] = json_encode($gallery);
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

            if ($vehicle->gallery_images && is_array(json_decode($vehicle->gallery_images, true))) {
                foreach (json_decode($vehicle->gallery_images, true) as $img) {
                    Storage::disk('public')->delete($img);
                }
            }

            $vehicle->delete();
            return redirect()->route('admin.vehicles.index')->with('success', 'Vehicle deleted successfully.');
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
            'ad_status' => 'required|in:published,rejected,pending,draft'
        ]);

        $vehicle = Vehicle::findOrFail($id);
        $vehicle->update(['ad_status' => $request->ad_status]);

        return redirect()->back()->with('success', 'Vehicle status updated to ' . $request->ad_status);
    }
}
