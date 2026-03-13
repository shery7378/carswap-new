<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use App\Models\PartnerReview;
use Illuminate\Http\Request;

class PartnerController extends Controller
{
    /**
     * Display a listing of the partners.
     */
    public function index(Request $request)
    {
        $query = Partner::query()->where('is_active', true);

        // Optional: filter by search term
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        $partners = $query->with(['services' => function($q) {
            $q->where('is_active', true);
        }])->orderBy('id', 'desc')->get();

        $partners->each(function($partner) {
            $partner->append('average_rating');
        });

        return response()->json([
            'success' => true,
            'data' => $partners
        ]);
    }

    /**
     * Display the specified partner with services and opening hours.
     */
    public function show($idOrSlug)
    {
        $partner = Partner::where('is_active', true)
            ->where(function($query) use ($idOrSlug) {
                $query->where('id', $idOrSlug)
                      ->orWhere('slug', $idOrSlug);
            })
            ->with(['services' => function($q) {
                $q->where('is_active', true);
            }, 'openingHours', 'reviews' => function($q) {
                $q->where('is_approved', true)->orderBy('created_at', 'desc');
            }])
            ->firstOrFail();

        $partner->append('average_rating');

        return response()->json([
            'success' => true,
            'data' => $partner
        ]);
    }

    /**
     * Store a new review for a partner.
     */
    public function storeReview(Request $request, $partnerId)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
            'reviewer_name' => 'required|string|max:255',
            'reviewer_email' => 'required|email|max:255',
        ]);

        $partner = Partner::findOrFail($partnerId);

        $review = PartnerReview::create([
            'partner_id' => $partner->id,
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'],
            'is_approved' => true, // Auto-approve for simplicity
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'data' => $review
        ]);
    }
}
