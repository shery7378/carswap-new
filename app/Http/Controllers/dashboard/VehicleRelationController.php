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

        $query = DB::table($table);

        // Handle color filtering by type if specified in the route {type}
        if ($type === 'exterior-colors') {
            $query->where('type', 'exterior');
        } elseif ($type === 'interior-colors') {
            $query->where('type', 'interior');
        }

        $items = $query->orderBy('id', 'desc')->get();
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

        $data = ['name' => $request->name, 'created_at' => now(), 'updated_at' => now()];

        // Add brand_id for models
        if ($type === 'models' && $request->has('brand_id')) {
            $data['brand_id'] = $request->brand_id;
        }

        // Add type for colors slug-based management
        if ($type === 'exterior-colors') {
            $data['type'] = 'exterior';
        } elseif ($type === 'interior-colors') {
            $data['type'] = 'interior';
        }

        $id = DB::table($table)->insertGetId($data);
        $item = DB::table($table)->where('id', $id)->first();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => Str::headline($type) . ' added successfully.',
                'item' => $item,
                'brand_name' => ($type === 'models' && isset($item->brand_id)) ? (DB::table('brands')->where('id', $item->brand_id)->value('name') ?? 'N/A') : null
            ]);
        }

        return redirect()->back()->with('success', Str::headline($type) . ' added successfully.');
    }

    /**
     * Update an existing record.
     */
    public function update(Request $request, $type, $id)
    {
        $table = $this->getTableName($type);
        if (!$table) abort(404);

        $request->validate(['name' => 'required|string|max:255']);

        $data = ['name' => $request->name, 'updated_at' => now()];

        if ($type === 'models' && $request->has('brand_id')) {
            $data['brand_id'] = $request->brand_id;
        }

        DB::table($table)->where('id', $id)->update($data);
        $item = DB::table($table)->where('id', $id)->first();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => Str::headline($type) . ' updated successfully.',
                'item' => $item,
                'brand_name' => ($type === 'models' && isset($item->brand_id)) ? (DB::table('brands')->where('id', $item->brand_id)->value('name') ?? 'N/A') : null
            ]);
        }

        return redirect()->back()->with('success', Str::headline($type) . ' updated successfully.');
    }

    /**
     * Toggle the active status of a record.
     */
    public function toggleStatus($type, $id)
    {
        $table = $this->getTableName($type);
        if (!$table) abort(404);

        $item = DB::table($table)->where('id', $id)->first();
        if (!$item) abort(404);

        $new_status = !($item->is_active ?? true);
        DB::table($table)->where('id', $id)->update(['is_active' => $new_status, 'updated_at' => now()]);

        return response()->json(['success' => true, 'is_active' => $new_status]);
    }

    /**
     * Remove the specified record.
     */
    public function destroy(Request $request, $type, $id)
    {
        $table = $this->getTableName($type);
        if (!$table)
            abort(404);

        DB::table($table)->where('id', $id)->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => Str::headline($type) . ' deleted successfully.'
            ]);
        }

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
            'exterior-colors' => 'colors',
            'interior-colors' => 'colors',
            'sales-methods' => 'sales_methods',
            'document-types' => 'document_types',
            'statuses' => 'vehicle_statuses',
            'vehicle-statuses' => 'vehicle_statuses',
            'extra-features' => 'properties',
        ];

        return $map[$type] ?? null;
    }
}
