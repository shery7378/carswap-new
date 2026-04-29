<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CMSSection;
use App\Models\CMSItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class CMSController extends Controller
{
    /**
     * Display a listing of the CMS sections.
     */
    public function index()
    {
        $sections = CMSSection::withCount('items')->orderBy('id', 'desc')->get();
        return view('content.dashboard.cms.index', compact('sections'));
    }

    /**
     * Show the form for creating a new CMS section.
     */
    public function create()
    {
        return view('content.dashboard.cms.sections-create');
    }

    /**
     * Store a newly created CMS section.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_sections,slug',
            'title' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $data = $request->except('image');
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cms', 'public');
        }

        $section = CMSSection::create($data);

        // Clear CMS cache
        \Illuminate\Support\Facades\Cache::forget("cms_section_{$section->slug}");
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->route('admin.cms.index')->with('success', __('Section created successfully.'));
    }

    /**
     * Show the form for editing the specified CMS section.
     */
    public function edit($id)
    {
        $section = CMSSection::with('items')->findOrFail($id);
        $tinymce_api_key = \App\Models\Setting::where('key', 'tinymce_api_key')->first()->value ?? 'zhq60pp8shp0wjkatmio4l9686eu1aqofwzmu475rtgnd098';
        return view('content.dashboard.cms.sections-edit', compact('section', 'tinymce_api_key'));
    }

    /**
     * Update the specified CMS section.
     */
    public function update(Request $request, $id)
    {
        $section = CMSSection::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:cms_sections,slug,' . $id,
            'title' => 'nullable|string',
            'status' => 'required|boolean',
        ]);

        $data = $request->except('image');
        
        if ($request->hasFile('image')) {
            if ($section->image) {
                Storage::disk('public')->delete($section->image);
            }
            $data['image'] = $request->file('image')->store('cms', 'public');
        }

        $section->update($data);

        // Clear CMS cache
        \Illuminate\Support\Facades\Cache::forget("cms_section_{$section->slug}");
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->route('admin.cms.index')->with('success', __('Section updated successfully.'));
    }

    /**
     * Remove the specified CMS section.
     */
    public function destroy($id)
    {
        $section = CMSSection::findOrFail($id);
        if ($section->image) {
            Storage::disk('public')->delete($section->image);
        }
        $section->delete();

        // Clear CMS cache
        \Illuminate\Support\Facades\Cache::forget("cms_section_{$section->slug}");
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->route('admin.cms.index')->with('success', __('Section deleted successfully.'));
    }

    /**
     * Store a new CMS item for a section.
     */
    public function storeItem(Request $request, $sectionId)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'date' => 'nullable|date',
            'order' => 'integer',
        ]);

        $data = $request->all();
        $data['section_id'] = $sectionId;

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('cms/items', 'public');
        }

        $item = CMSItem::create($data);

        // Clear CMS cache for this section and blog posts
        $section = CMSSection::find($sectionId);
        if ($section) {
            \Illuminate\Support\Facades\Cache::forget("cms_section_{$section->slug}");
        }
        \Illuminate\Support\Facades\Cache::forget("cms_item_{$item->id}");
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->back()->with('success', __('Item added successfully.'));
    }

    /**
     * Update a CMS item directly (optimized for document mode).
     */
    public function updateItemDirect(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:cms_sections,id',
            'item_id' => 'nullable|exists:cms_items,id',
            'description' => 'required|string',
            'icon' => 'nullable|string',
            'image' => 'nullable|image',
        ]);

        $itemData = [
            'description' => $request->description,
            'icon' => $request->icon,
        ];

        if ($request->hasFile('image')) {
            $itemData['image'] = $request->file('image')->store('cms/items', 'public');
        }

        if ($request->filled('item_id')) {
            $item = CMSItem::findOrFail($request->item_id);
            if ($request->hasFile('image') && $item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $item->update($itemData);
        } else {
            $itemData['section_id'] = $request->section_id;
            $itemData['title'] = 'Main Content';
            $itemData['order'] = 0;
            CMSItem::create($itemData);
        }

        // Clear CMS cache
        $section = CMSSection::find($request->section_id);
        if ($section) {
             \Illuminate\Support\Facades\Cache::forget("cms_section_{$section->slug}");
        }
        if ($request->filled('item_id')) {
             \Illuminate\Support\Facades\Cache::forget("cms_item_{$request->item_id}");
        }
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->back()->with('success', __('Document content saved successfully.'));
    }

    public function updateItem(Request $request, $itemId)
    {
        $item = CMSItem::findOrFail($itemId);
        
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string',
            'date' => 'nullable|date',
            'order' => 'integer',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('cms/items', 'public');
        }

        $item->update($data);

        // Clear CMS cache
        if ($item->section) {
            \Illuminate\Support\Facades\Cache::forget("cms_section_{$item->section->slug}");
        }
        \Illuminate\Support\Facades\Cache::forget("cms_item_{$item->id}");
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->back()->with('success', __('Item updated successfully.'));
    }

    /**
     * Delete a CMS item.
     */
    public function destroyItem($itemId)
    {
        $item = CMSItem::findOrFail($itemId);
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();

        // Clear CMS cache
        if ($item->section) {
            \Illuminate\Support\Facades\Cache::forget("cms_section_{$item->section->slug}");
        }
        \Illuminate\Support\Facades\Cache::forget("cms_item_{$item->id}");
        \Illuminate\Support\Facades\Cache::forget("cms_blog_posts");

        return redirect()->back()->with('success', __('Item deleted successfully.'));
    }
}
