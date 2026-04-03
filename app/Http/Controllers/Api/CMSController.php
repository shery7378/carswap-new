<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CMSSection;
use Illuminate\Http\Request;

class CMSController extends Controller
{
    /**
     * Get a CMS section with all its active items by slug.
     */
    public function show($slug)
    {
        $section = CMSSection::where('slug', $slug)
            ->where('status', true)
            ->with(['items' => function($query) {
                $query->where('status', true)->orderBy('order');
            }])
            ->first();

        if (!$section) {
            return response()->json([
                'success' => false, 
                'message' => 'CMS section not found or inactive.'
            ], 404);
        }

        // Format image URLs if they exist
        if ($section->image) {
            $section->image_url = asset('storage/' . $section->image);
        }

        $section->items->each(function($item) {
            if ($item->image) {
                $item->image_url = asset('storage/' . $item->image);
            }
        });

        return response()->json([
            'success' => true,
            'data' => $section
        ]);
    }

    /**
     * Get a specific CMS item by ID for its detail page.
     */
    public function showItem($id)
    {
        $item = \App\Models\CMSItem::with('section')->where('id', $id)->where('status', true)->first();

        if (!$item) {
            return response()->json([
                'success' => false, 
                'message' => 'CMS item not found or inactive.'
            ], 404);
        }

        if ($item->image) {
            $item->image_url = asset('storage/' . $item->image);
        }

        return response()->json([
            'success' => true,
            'data' => $item
        ]);
    }
}
