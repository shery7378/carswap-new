<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerService;
use App\Models\PartnerOpeningHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PartnerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $partners = Partner::orderBy('id', 'desc')->paginate(10);
        return view('content.dashboard.partners.index', compact('partners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('content.dashboard.partners.create', compact('days'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('partners', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('partners/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = Str::slug($request->name);

        $partner = Partner::create($validated);

        // Handle Opening Hours
        if ($request->has('opening_hours')) {
            foreach ($request->opening_hours as $day => $hours) {
                PartnerOpeningHour::create([
                    'partner_id' => $partner->id,
                    'day' => $day,
                    'open_time' => $hours['open'] ?? null,
                    'close_time' => $hours['close'] ?? null,
                    'is_closed' => isset($hours['is_closed']),
                ]);
            }
        }

        // Handle Services
        if ($request->has('services')) {
            foreach ($request->services as $serviceData) {
                if (empty($serviceData['name'])) continue;
                
                PartnerService::create([
                    'partner_id' => $partner->id,
                    'name' => $serviceData['name'],
                    'description' => $serviceData['description'] ?? null,
                    'is_active' => isset($serviceData['is_active']),
                ]);
            }
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $partner = Partner::with(['services', 'openingHours'])->findOrFail($id);
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        return view('content.dashboard.partners.edit', compact('partner', 'days'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'gallery.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($partner->image) {
                Storage::disk('public')->delete($partner->image);
            }
            $validated['image'] = $request->file('image')->store('partners', 'public');
        }

        if ($request->hasFile('gallery')) {
            $galleryPaths = $partner->gallery ?? [];
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('partners/gallery', 'public');
            }
            $validated['gallery'] = $galleryPaths;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['slug'] = Str::slug($request->name);

        $partner->update($validated);

        // Update Opening Hours
        if ($request->has('opening_hours')) {
            foreach ($request->opening_hours as $day => $hours) {
                PartnerOpeningHour::updateOrCreate(
                    ['partner_id' => $partner->id, 'day' => $day],
                    [
                        'open_time' => $hours['open'] ?? null,
                        'close_time' => $hours['close'] ?? null,
                        'is_closed' => isset($hours['is_closed']),
                    ]
                );
            }
        }

        // Handle Services
        if ($request->has('services')) {
            $serviceIds = [];
            foreach ($request->services as $serviceData) {
                if (empty($serviceData['name'])) continue;
                
                $service = PartnerService::updateOrCreate(
                    ['id' => $serviceData['id'] ?? null, 'partner_id' => $partner->id],
                    [
                        'name' => $serviceData['name'],
                        'description' => $serviceData['description'] ?? null,
                        'is_active' => isset($serviceData['is_active']),
                    ]
                );
                $serviceIds[] = $service->id;
            }
            // Optional: delete services not in the list
            // $partner->services()->whereNotIn('id', $serviceIds)->delete();
        }

        return redirect()->route('admin.partners.index')->with('success', 'Partner updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $partner = Partner::findOrFail($id);
        if ($partner->image) {
            Storage::disk('public')->delete($partner->image);
        }
        $partner->delete();

        return redirect()->route('admin.partners.index')->with('success', 'Partner deleted successfully.');
    }
}
