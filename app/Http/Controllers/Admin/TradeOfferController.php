<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TradeOffer;
use Illuminate\Http\Request;

class TradeOfferController extends Controller
{
    /**
     * Display a listing of the trade offers.
     */
    public function index()
    {
        $offers = TradeOffer::with('vehicle')->latest()->paginate(20);
        return view('content.dashboard.trade-offers.index', compact('offers'));
    }

    /**
     * Display the specified trade offer.
     */
    public function show($id)
    {
        $offer = TradeOffer::with('vehicle')->findOrFail($id);
        
        // Update status to 'viewed' if it's currently 'pending'
        if ($offer->status === 'pending') {
            $offer->update(['status' => 'viewed']);
        }
        
        return view('content.dashboard.trade-offers.show', compact('offer'));
    }

    /**
     * Update the status of a trade offer.
     */
    public function updateStatus(Request $request, $id)
    {
        $offer = TradeOffer::findOrFail($id);
        $offer->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Trade offer status updated.');
    }

    /**
     * Remove the specified trade offer.
     */
    public function destroy($id)
    {
        $offer = TradeOffer::findOrFail($id);
        $offer->delete();

        return redirect()->route('admin.trade-offers.index')->with('success', 'Trade offer deleted.');
    }
}
