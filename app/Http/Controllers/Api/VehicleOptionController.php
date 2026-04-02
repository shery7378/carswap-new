<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\BodyType;
use App\Models\Color;
use App\Models\DocumentType;
use App\Models\DriveType;
use App\Models\FuelType;
use App\Models\Property;
use App\Models\SalesMethod;
use App\Models\Transmission;
use App\Models\VehicleStatus;
use App\Models\VehicleModel;
use Illuminate\Http\Request;

class VehicleOptionController extends Controller
{
    /**
     * Get all vehicle-related options for filters and forms.
     */
    public function getOptions()
    {
        return response()->json([
            'brands' => Brand::orderBy('name')->get(),
            'models' => VehicleModel::orderBy('name')->get(),
            'body_types' => BodyType::orderBy('name')->get(),
            'fuel_types' => FuelType::orderBy('name')->get(),
            'transmissions' => Transmission::orderBy('name')->get(),
            'drive_types' => DriveType::orderBy('name')->get(),
            'colors' => Color::orderBy('name')->get(),
            'exterior_colors' => Color::where('type', 'exterior')->orderBy('name')->get(),
            'interior_colors' => Color::where('type', 'interior')->orderBy('name')->get(),
            'sales_methods' => SalesMethod::orderBy('name')->get(),
            'document_types' => DocumentType::orderBy('name')->get(),
            'vehicle_statuses' => VehicleStatus::orderBy('name')->get(),
            'properties' => Property::orderBy('name')->get(),
        ]);
    }

    /**
     * Get only brands list.
     */
    public function getBrands()
    {
        return response()->json(Brand::orderBy('name')->get());
    }

    /**
     * Get models for a specific brand.
     */
    public function getModels($brandId)
    {
        $models = VehicleModel::where('brand_id', $brandId)
            ->orderBy('name')
            ->get();

        return response()->json($models);
    }

    /**
     * Get brands and body types list.
     */
    public function getBrandsBodyTypes()
    {
        return response()->json([
            'brands' => Brand::where('is_active', true)->orderBy('name')->get(),
            'body_types' => BodyType::where('is_active', true)->orderBy('name')->get(),
        ]);
    }
}
