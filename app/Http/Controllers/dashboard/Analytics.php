<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Analytics extends Controller
{
    public function index()
    {
        $stats = [
            'total_vehicles' => \App\Models\Vehicle::count(),
            'total_partners' => \App\Models\Partner::count(),
            'total_users' => \App\Models\User::count(),
        ];

        $recent_vehicles = \App\Models\Vehicle::with(['brand', 'model'])->latest()->take(5)->get();
        $recent_partners = \App\Models\Partner::latest()->take(5)->get();

        return view('content.dashboard.dashboards-analytics', compact('stats', 'recent_vehicles', 'recent_partners'));
    }
}
