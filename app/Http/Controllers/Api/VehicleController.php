<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Display a listing of the vehicles with filtering.
     */
    public function index(Request $request)
    {
        $query = Vehicle::with([
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
            'user'
        ])->where('ad_status', 'published');

        // Filtering by Featured Status
        if ($request->has('featured')) {
            $query->where('is_featured', $request->featured == '1' || $request->featured == 'true');
        }

        // Filtering by Brand
        if ($request->has('brand_id')) {
            $query->where('brand_id', $request->brand_id);
        }

        // Filtering by Model
        if ($request->has('model_id')) {
            $query->where('model_id', $request->model_id);
        }

        // Filtering by Body Type (Design)
        if ($request->has('body_type_id')) {
            $query->where('body_type_id', $request->body_type_id);
        }

        // Filtering by Fuel Type
        if ($request->has('fuel_type_id')) {
            $query->where('fuel_type_id', $request->fuel_type_id);
        }

        // Filtering by Gearbox (Transmission)
        if ($request->has('transmission_id')) {
            $query->where('transmission_id', $request->transmission_id);
        }

        // Filtering by Drive
        if ($request->has('drive_type_id')) {
            $query->where('drive_type_id', $request->drive_type_id);
        }

        // Filtering by Sales Method
        if ($request->has('sales_method_id')) {
            $query->where('sales_method_id', $request->sales_method_id);
        }

        // Filtering by Document Type
        if ($request->has('document_type_id')) {
            $query->where('document_type_id', $request->document_type_id);
        }

        // Filtering by Vehicle Status
        if ($request->has('vehicle_status_id')) {
            $query->where('vehicle_status_id', $request->vehicle_status_id);
        }

        // Filtering by Colors
        if ($request->has('exterior_color_id')) {
            $query->where('exterior_color_id', $request->exterior_color_id);
        }
        if ($request->has('interior_color_id')) {
            $query->where('interior_color_id', $request->interior_color_id);
        }

        // Many-to-Many Filtering for Properties
        if ($request->has('property_ids')) {
            $propertyIds = is_array($request->property_ids) ? $request->property_ids : explode(',', $request->property_ids);
            foreach ($propertyIds as $id) {
                $query->whereHas('properties', function ($q) use ($id) {
                    $q->where('properties.id', $id);
                });
            }
        }

        // Price Range (min_price and max_price)
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Smooth Price Range (pricerange=1000-5000)
        if ($request->has('pricerange')) {
            $range = explode('-', $request->pricerange);
            if (count($range) == 2) {
                $min = (int) $range[0];
                $max = (int) $range[1];
                if ($min > 0)
                    $query->where('price', '>=', $min);
                if ($max > 0)
                    $query->where('price', '<=', $max);
            }
        }

        // Year Range
        if ($request->has('min_year')) {
            $query->where('year', '>=', $request->min_year);
        }
        if ($request->has('max_year')) {
            $query->where('year', '<=', $request->max_year);
        }

        // Search by keyword
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'newest');
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

        $vehicles = $query->paginate($request->get('limit', 12));

        return response()->json($vehicles);
    }

    /**
     * Display the specified vehicle.
     */
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
            'user',
            'salesMethod',
            'documentType',
            'vehicleStatus',
            'properties'
        ])->where('ad_status', 'published')->findOrFail($id);

        return response()->json($vehicle);
    }
}
