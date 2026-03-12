<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class VehicleRelationController extends Controller
{
    /**
     * Display a listing of any relationship table.
     */
    public function index($type)
    {
        $table = $this->getTableName($type);
        if (!$table)
            abort(404);

        $items = DB::table($table)->orderBy('id', 'desc')->paginate(10);
        $title = Str::headline($type);

        return view('content.dashboard.relationships.index', compact('items', 'type', 'title'));
    }

    /**
     * Store a new record in relationship table.
     */
    public function store(Request $request, $type)
    {
        $table = $this->getTableName($type);
        if (!$table)
            abort(404);

        $request->validate(['name' => 'required|string|max:255']);

        $data = ['name' => $request->name];

        // Add brand_id for models
        if ($type === 'models' && $request->has('brand_id')) {
            $data['brand_id'] = $request->brand_id;
        }

        DB::table($table)->insert($data);

        return redirect()->back()->with('success', Str::headline($type) . ' added successfully.');
    }

    /**
     * Remove the specified record.
     */
    public function destroy($type, $id)
    {
        $table = $this->getTableName($type);
        if (!$table)
            abort(404);

        DB::table($table)->where('id', $id)->delete();

        return redirect()->back()->with('success', Str::headline($type) . ' deleted successfully.');
    }

    /**
     * Helper to map type to table name.
     */
    private function getTableName($type)
    {
        $map = [
            'brands' => 'brands',
            'models' => 'vehicle_models',
            'fuel-types' => 'fuel_types',
            'transmissions' => 'transmissions',
            'drive-types' => 'drive_types',
            'body-types' => 'body_types',
            'colors' => 'colors',
            'sales-methods' => 'sales_methods',
            'document-types' => 'document_types',
            'statuses' => 'vehicle_statuses',
            'extra-features' => 'properties',
        ];

        return $map[$type] ?? null;
    }
}
