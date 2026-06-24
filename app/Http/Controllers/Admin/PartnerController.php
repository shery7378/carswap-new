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
        $partners = Partner::orderBy('id', 'desc')->paginate(500);
        return view('content.dashboard.partners.index', compact('partners'));
    }

    public function show(Request $request, $id)
    {
        $partner = Partner::with(['services', 'openingHours'])->findOrFail($id);
        
        if ($request->has('modal')) {
            return view('content.dashboard.partners.partials.modal-content', compact('partner'));
        }
        
        return view('content.dashboard.partners.show', compact('partner'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $days = ['Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap'];
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
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'is_active' => 'nullable',
            'show_opening_hours' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);


        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('partners', 'public');
        }
        if ($request->hasFile('gallery')) {
            $paths = [];
            foreach ($request->file('gallery') as $file) {
                $paths[] = $file->store('partners/gallery', 'public');
            }
            $validated['gallery'] = $paths;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['show_opening_hours'] = $request->has('show_opening_hours');
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
                if (empty($serviceData['name']))
                    continue;

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
        $days = ['Hétfő', 'Kedd', 'Szerda', 'Csütörtök', 'Péntek', 'Szombat', 'Vasárnap'];
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
            'is_active' => 'nullable',
            'show_opening_hours' => 'nullable',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->input('delete_image') == '1' && !$request->hasFile('image')) {
            // Delete logo with no replacement
            if ($partner->image) {
                Storage::disk('public')->delete($partner->image);
            }
            $validated['image'] = null;
        } elseif ($request->hasFile('image')) {
            if ($partner->image) {
                Storage::disk('public')->delete($partner->image);
            }
            $validated['image'] = $request->file('image')->store('partners', 'public');
        }

        // Handle gallery: keep only the paths still in keep_gallery[], then add replacements & new uploads
        $existingGallery = $partner->gallery ?? [];
        $keepGallery = $request->input('keep_gallery', []);

        // Delete images that were removed (not in keep_gallery)
        foreach ($existingGallery as $oldPath) {
            if (!in_array($oldPath, $keepGallery)) {
                Storage::disk('public')->delete($oldPath);
            }
        }

        // Start with the kept images
        $galleryPaths = array_values($keepGallery);

        // Handle per-image replacements (replace_gallery[index] = new file)
        if ($request->hasFile('replace_gallery')) {
            foreach ($request->file('replace_gallery') as $index => $file) {
                // The original path at this index (before removal) — find it in the original array
                $originalPath = $existingGallery[$index] ?? null;
                if ($originalPath && in_array($originalPath, $galleryPaths)) {
                    // Remove the original from kept paths and delete the file
                    $galleryPaths = array_values(array_filter($galleryPaths, fn($p) => $p !== $originalPath));
                    Storage::disk('public')->delete($originalPath);
                }
                $galleryPaths[] = $file->store('partners/gallery', 'public');
            }
        }

        // Append brand new gallery photos
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                $galleryPaths[] = $file->store('partners/gallery', 'public');
            }
        }

        $validated['gallery'] = $galleryPaths;


        $validated['is_active'] = $request->has('is_active');
        $validated['show_opening_hours'] = $request->has('show_opening_hours');
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
                if (empty($serviceData['name']))
                    continue;

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
    public function toggleStatus($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->is_active = !$partner->is_active;
        $partner->save();

        $status = $partner->is_active ? 'Active' : 'Inactive';
        return redirect()->back()->with('success', "Partner status updated to {$status}.");
    }
}
