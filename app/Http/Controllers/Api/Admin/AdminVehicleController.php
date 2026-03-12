<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdminVehicleController extends Controller
{
    public function index()
    {
        return Vehicle::with([
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
            'vehicleStatus'
        ])->orderBy('id', 'desc')->paginate(20);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'brand_id' => 'nullable|integer',
            'model_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'year' => 'nullable|integer',
            'mileage' => 'nullable|integer',
            'fuel_type_id' => 'nullable|integer',
            'transmission_id' => 'nullable|integer',
            'drive_type_id' => 'nullable|integer',
            'body_type_id' => 'nullable|integer',
            'cylinder_capacity' => 'nullable|integer',
            'performance' => 'nullable|integer',
            'exterior_color_id' => 'nullable|integer',
            'interior_color_id' => 'nullable|integer',
            'sales_method_id' => 'nullable|integer',
            'document_type_id' => 'nullable|integer',
            'vehicle_status_id' => 'nullable|integer',
            'location' => 'nullable|string',
            'is_featured' => 'boolean',
            'properties' => 'nullable|array', // many-to-many
        ]);

        $vehicle = Vehicle::create($validated);

        if ($request->has('properties')) {
            $vehicle->properties()->sync($request->properties);
        }

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('vehicles', 'public');
            $vehicle->update(['main_image' => $path]);
        }

        return response()->json(['success' => true, 'data' => $vehicle]);
    }

    public function show($id)
    {
        $vehicle = Vehicle::with([
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
            'properties'
        ])->findOrFail($id);

        return response()->json(['success' => true, 'data' => $vehicle]);
    }

    public function update(Request $request, $id)
    {
        $vehicle = Vehicle::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:191',
            'description' => 'nullable|string',
            'brand_id' => 'nullable|integer',
            'model_id' => 'nullable|integer',
            'price' => 'nullable|numeric',
            'year' => 'nullable|integer',
            'mileage' => 'nullable|integer',
            'fuel_type_id' => 'nullable|integer',
            'transmission_id' => 'nullable|integer',
            'drive_type_id' => 'nullable|integer',
            'body_type_id' => 'nullable|integer',
            'cylinder_capacity' => 'nullable|integer',
            'performance' => 'nullable|integer',
            'exterior_color_id' => 'nullable|integer',
            'interior_color_id' => 'nullable|integer',
            'sales_method_id' => 'nullable|integer',
            'document_type_id' => 'nullable|integer',
            'vehicle_status_id' => 'nullable|integer',
            'location' => 'nullable|string',
            'is_featured' => 'boolean',
            'properties' => 'nullable|array',
        ]);

        $vehicle->update($validated);

        if ($request->has('properties')) {
            $vehicle->properties()->sync($request->properties);
        }

        if ($request->hasFile('main_image')) {
            // Delete old image if exists
            if ($vehicle->main_image) {
                Storage::disk('public')->delete($vehicle->main_image);
            }
            $path = $request->file('main_image')->store('vehicles', 'public');
            $vehicle->update(['main_image' => $path]);
        }

        return response()->json(['success' => true, 'data' => $vehicle]);
    }

    public function destroy($id)
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();
        return response()->json(['success' => true]);
    }

    public function getModelsByBrand($brandId)
    {
        return response()->json(VehicleModel::where('brand_id', $brandId)->get());
    }
}
