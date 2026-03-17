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

        if ($request->filled('search')) {
            $search = "%{$request->search}%";
            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', $search)
                  ->orWhere('description', 'LIKE', $search)
                  ->orWhere('address', 'LIKE', $search);
            });
        }

        // 2. Services Filter (array of service names)
        if ($request->has('services') && is_array($request->services) && count($request->services) > 0) {
            $services = $request->services;
            $query->whereHas('services', function($q) use ($services) {
                $q->whereIn('name', $services)->where('is_active', true);
            });
        }

        // 3. Opening Hours Filter (array of days, e.g., ['Monday', 'Tuesday'])
        if ($request->has('days') && is_array($request->days) && count($request->days) > 0) {
            $days = $request->days;
            foreach ($days as $day) {
                $query->whereHas('openingHours', function($q) use ($day) {
                    $q->where('day', $day)->where('is_closed', false);
                });
            }
        }

        // 4. Location / Distance Filter
        $hasLocation = $request->has('lat') && $request->has('lng') && $request->has('radius');
        $haversine = "";

        if ($hasLocation) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $radius = (float) $request->radius;

            $haversine = "(6371 * acos(cos(radians($lat)) 
                         * cos(radians(latitude)) 
                         * cos(radians(longitude) - radians($lng)) 
                         + sin(radians($lat)) 
                         * sin(radians(latitude))))";

            $query->whereNotNull('latitude')
                  ->whereNotNull('longitude')
                  ->selectRaw("partners.*, {$haversine} AS distance")
                  ->whereRaw("{$haversine} <= ?", [$radius]);
        } else {
            $query->select('partners.*');
        }

        // Always include basic stats to prevent N+1 and for accurate filtering
        $query->withAvg(['reviews' => function($q) { $q->where('is_approved', true); }], 'rating')
              ->withCount(['reviews' => function($q) { $q->where('is_approved', true); }]);

        // 5. Sorting Logic
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'top_rated':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'most_rated':
                    $query->orderBy('reviews_count', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'az':
                    $query->orderBy('name', 'asc');
                    break;
                case 'za':
                    $query->orderBy('name', 'desc');
                    break;
                default:
                    if ($hasLocation) {
                        $query->orderBy('distance', 'asc');
                    } else {
                        $query->orderBy('id', 'desc');
                    }
                    break;
            }
        } else {
            if ($hasLocation) {
                $query->orderBy('distance', 'asc');
            } else {
                $query->orderBy('id', 'desc');
            }
        }

        // 6. Rating Filter (Robust Approach)
        // We'll fetch all matching partners first (search, services, location)
        // and filter by rating using the collection's append 'average_rating'.
        // This is safe because partners lists are usually small (< 1000 items).

        $partners = $query->with(['services' => function($q) {
            $q->where('is_active', true);
        }])->get();

        // Append dynamic attributes
        $partners->each->append('average_rating');

        // Apply Rating Filter
        if ($request->filled('min_rating')) {
            $rawRating = $request->min_rating;
            // Handle both single value and array (checkboxes)
            if (is_array($rawRating)) {
                // If it's a list like ["5", "4", "3"], we usually want "any of these"
                // but since they are "X and more", the lowest selected value is the true threshold
                // Example: If ["5", "3"] are checked, you want everything >= 3.
                // However, if the checkboxes are exclusive, you might want something else.
                // Based on standard designs, taking the minimum of the selected ratings works best for "and more" filters.
                $minThreshold = (float) min($rawRating);
            } else {
                $minThreshold = (float) $rawRating;
            }

            if ($minThreshold > 0) {
                $partners = $partners->filter(function($partner) use ($minThreshold) {
                    return $partner->average_rating >= $minThreshold;
                })->values();
            }
        }

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
            'data' => $partner,
            'meta' => [
                'reviews_count' => $partner->reviews_count,
                'average_rating' => $partner->average_rating
            ]
        ]);
    }

    /**
     * Store a new review for a partner.
     */
    public function storeReview(Request $request, $partnerId)
    {
        $validated = $request->validate([
            'rating' => 'nullable|integer|min:1|max:5',
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
            'reviewer_name' => 'required|string|max:255',
            'reviewer_email' => 'required|email|max:255',
            'is_genuine' => 'required|boolean',
        ]);

        $partner = Partner::findOrFail($partnerId);

        $review = PartnerReview::create([
            'partner_id' => $partner->id,
            'rating' => $validated['rating'] ?? 5, // Default to 5 if not provided
            'title' => $validated['title'],
            'body' => $validated['body'],
            'reviewer_name' => $validated['reviewer_name'],
            'reviewer_email' => $validated['reviewer_email'],
            'is_genuine' => $validated['is_genuine'],
            'is_approved' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Review submitted successfully.',
            'data' => $review
        ]);
    }
    public function getFiltersData(Request $request)
    {
        $query = Partner::query()->where('is_active', true);

        // Apply Search Filter if present
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('address', 'LIKE', "%{$search}%");
            });
        }

        // Apply Location / Distance Filter if present
        if ($request->has('lat') && $request->has('lng') && $request->has('radius')) {
            $lat = (float) $request->lat;
            $lng = (float) $request->lng;
            $radius = (float) $request->radius;

            $haversine = "(6371 * acos(cos(radians($lat)) 
                         * cos(radians(latitude)) 
                         * cos(radians(longitude) - radians($lng)) 
                         + sin(radians($lat)) 
                         * sin(radians(latitude))))";

            $query->whereNotNull('latitude')
                  ->whereNotNull('longitude')
                  ->whereRaw("{$haversine} <= ?", [$radius]);
        }

        // Get the IDs of the partners that match the current context (search + location)
        $partnerIds = $query->pluck('id');

        // 1. Services Counts (only for filtered partners)
        $services = \App\Models\PartnerService::whereIn('partner_id', $partnerIds)
            ->where('is_active', true)
            ->select('name', \DB::raw('count(DISTINCT partner_id) as count'))
            ->groupBy('name')
            ->get();

        // 2. Opening Hours Counts (Days) (only for filtered partners)
        $days = \App\Models\PartnerOpeningHour::whereIn('partner_id', $partnerIds)
            ->where('is_closed', false)
            ->select('day', \DB::raw('count(DISTINCT partner_id) as count'))
            ->groupBy('day')
            ->get();

        // 3. Ratings Counts (Efficiently using a subquery)
        $ratings = [
            '5' => 0,
            '4' => 0,
            '3' => 0,
            '2' => 0,
            '1' => 0,
        ];

        $partnersWithAvg = Partner::whereIn('id', $partnerIds)
            ->withAvg(['reviews' => function($q) { $q->where('is_approved', true); }], 'rating')
            ->get();
        
        foreach ($partnersWithAvg as $partner) {
            $avg = (float) ($partner->reviews_avg_rating ?? 0);
            if ($avg >= 4.5) $ratings['5']++;
            if ($avg >= 3.5) $ratings['4']++;
            if ($avg >= 2.5) $ratings['3']++;
            if ($avg >= 1.5) $ratings['2']++;
            if ($avg >= 0.5) $ratings['1']++;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'services' => $services,
                'days' => $days,
                'ratings' => $ratings
            ]
        ]);
    }
}
