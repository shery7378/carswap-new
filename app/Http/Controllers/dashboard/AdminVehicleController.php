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
    public function index()
    {
        $vehicles = Vehicle::with(['brand', 'model', 'fuelType', 'transmission', 'user'])->orderBy('id', 'desc')->paginate(10);
        return view('content.dashboard.vehicles.index', compact('vehicles'));
    }

    public function create()
    {
        $brands = Brand::all();
        $fuelTypes = FuelType::all();
        $transmissions = Transmission::all();
        $driveTypes = DriveType::all();
        $bodyTypes = BodyType::all();
        $salesMethods = SalesMethod::all();
        $vehicleStatuses = VehicleStatus::all();
        $properties = Property::all();
        $colors = Color::all();
        $documentTypes = DocumentType::all();

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
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
        $brands = Brand::all();
        $models = VehicleModel::where('brand_id', $vehicle->brand_id)->get();
        $fuelTypes = FuelType::all();
        $transmissions = Transmission::all();
        $driveTypes = DriveType::all();
        $bodyTypes = BodyType::all();
        $salesMethods = SalesMethod::all();
        $vehicleStatuses = VehicleStatus::all();
        $properties = Property::all();
        $colors = Color::all();
        $documentTypes = DocumentType::all();

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
                'main_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
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
}
