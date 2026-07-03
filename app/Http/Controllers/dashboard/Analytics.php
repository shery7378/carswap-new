<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Models\Partner;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Contact;
use App\Models\TradeOffer;
use App\Models\NewsletterSubscriber;
use App\Models\VehicleInquiry;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class Analytics extends Controller
{
    public function index()
    {
        $thirtyDaysAgo = Carbon::now()->subDays(30);
        $sevenDaysAgo  = Carbon::now()->subDays(7);

        // ----------------------------------------------------------------
        // KPI Stats
        // ----------------------------------------------------------------
        $stats = [
            'total_vehicles'          => Vehicle::count(),
            'pending_vehicles'        => Vehicle::where('ad_status', 'Függőben')->count(),
            'total_users'             => User::count(),
            'total_partners'          => Partner::count(),
            'active_subscriptions'    => Subscription::where('status', 'active')->count(),
            'failed_subscriptions'    => Subscription::whereIn('status', ['failed', 'cancelled', 'past_due', 'expired'])->count(),
            'open_contacts'           => Contact::whereIn('status', ['new', 'open', 'pending'])->count(),
            'open_trade_offers'       => TradeOffer::whereIn('status', ['new', 'pending', null])->count(),
            'new_users_7d'            => User::where('created_at', '>=', $sevenDaysAgo)->count(),
            'new_subscribers_30d'     => NewsletterSubscriber::where('created_at', '>=', $thirtyDaysAgo)->count(),
            'new_vehicles_30d'        => Vehicle::where('created_at', '>=', $thirtyDaysAgo)->count(),
        ];

        // ----------------------------------------------------------------
        // Listings Pending Approval
        // ----------------------------------------------------------------
        $pending_vehicles = Vehicle::with(['brand', 'model', 'user'])
            ->where('ad_status', 'Függőben')
            ->latest()
            ->take(10)
            ->get();

        // ----------------------------------------------------------------
        // Recent Vehicles (last 5 overall)
        // ----------------------------------------------------------------
        $recent_vehicles = Vehicle::with(['brand', 'model'])
            ->latest()
            ->take(5)
            ->get();

        // ----------------------------------------------------------------
        // Recent Partners
        // ----------------------------------------------------------------
        $recent_partners = Partner::latest()->take(5)->get();

        // ----------------------------------------------------------------
        // New User Registrations (last 7 days)
        // ----------------------------------------------------------------
        $recent_users = User::where('created_at', '>=', $sevenDaysAgo)
            ->latest()
            ->take(10)
            ->get();

        // ----------------------------------------------------------------
        // New Partner Registrations (last 7 days)
        // ----------------------------------------------------------------
        $recent_partner_registrations = Partner::where('created_at', '>=', $sevenDaysAgo)
            ->latest()
            ->take(8)
            ->get();

        // ----------------------------------------------------------------
        // Subscriptions (recent)
        // ----------------------------------------------------------------
        $recent_subscriptions = Subscription::with(['user', 'plan'])
            ->latest()
            ->take(8)
            ->get();

        // ----------------------------------------------------------------
        // Failed / Changed Subscriptions (last 30 days)
        // ----------------------------------------------------------------
        $failed_subscriptions = Subscription::with(['user', 'plan'])
            ->whereIn('status', ['failed', 'cancelled', 'past_due', 'expired'])
            ->where('updated_at', '>=', $thirtyDaysAgo)
            ->latest('updated_at')
            ->take(8)
            ->get();

        // ----------------------------------------------------------------
        // Recent Contacts / Support Requests
        // ----------------------------------------------------------------
        $recent_contacts = Contact::latest()->take(8)->get();

        // ----------------------------------------------------------------
        // Recent Trade Offers
        // ----------------------------------------------------------------
        $recent_trade_offers = TradeOffer::with('vehicle')
            ->latest()
            ->take(8)
            ->get();

        // ----------------------------------------------------------------
        // Newsletter Subscribers (recent)
        // ----------------------------------------------------------------
        $recent_newsletter = NewsletterSubscriber::latest()->take(6)->get();

        // ----------------------------------------------------------------
        // Unified Activity Feed  (last 25 events across all models)
        // ----------------------------------------------------------------
        $activityItems = new Collection();

        // Pending vehicles
        foreach (Vehicle::with(['brand', 'model'])->where('ad_status', 'Függőben')->latest()->take(5)->get() as $v) {
            $activityItems->push([
                'type'    => 'vehicle_pending',
                'icon'    => 'bx-time-five',
                'color'   => 'warning',
                'label'   => ($v->brand->name ?? '') . ' ' . ($v->model->name ?? '') . ' ' . $v->year,
                'sub'     => __('Waiting for approval'),
                'time'    => $v->created_at,
                'link'    => route('admin.vehicles.show', $v->id),
            ]);
        }

        // New users
        foreach (User::where('created_at', '>=', $thirtyDaysAgo)->latest()->take(5)->get() as $u) {
            $activityItems->push([
                'type'    => 'user_registered',
                'icon'    => 'bx-user-plus',
                'color'   => 'success',
                'label'   => trim($u->first_name . ' ' . $u->last_name) ?: $u->email,
                'sub'     => __('New user registered'),
                'time'    => $u->created_at,
                'link'    => route('admin.web-users.view', $u->id),
            ]);
        }

        // New subscriptions
        foreach (Subscription::with(['user', 'plan'])->where('created_at', '>=', $thirtyDaysAgo)->latest()->take(5)->get() as $s) {
            $activityItems->push([
                'type'    => 'subscription_new',
                'icon'    => 'bx-badge-check',
                'color'   => 'primary',
                'label'   => optional(optional($s->user)->first_name . ' ' . optional($s->user)->last_name) ?: 'Unknown',
                'sub'     => __('Subscribed to') . ' ' . optional($s->plan)->name,
                'time'    => $s->created_at,
                'link'    => route('app-subscription-list'),
            ]);
        }

        // Failed subscriptions
        foreach (Subscription::with(['user', 'plan'])->whereIn('status', ['failed', 'cancelled', 'past_due', 'expired'])->where('updated_at', '>=', $thirtyDaysAgo)->latest('updated_at')->take(5)->get() as $s) {
            $activityItems->push([
                'type'    => 'subscription_failed',
                'icon'    => 'bx-error-circle',
                'color'   => 'danger',
                'label'   => optional($s->user) ? trim(optional($s->user)->first_name . ' ' . optional($s->user)->last_name) : 'Unknown',
                'sub'     => __('Subscription') . ' ' . $s->status,
                'time'    => $s->updated_at,
                'link'    => route('app-subscription-list'),
            ]);
        }

        // New contacts
        foreach (Contact::where('created_at', '>=', $thirtyDaysAgo)->latest()->take(5)->get() as $c) {
            $activityItems->push([
                'type'    => 'contact_new',
                'icon'    => 'bx-envelope',
                'color'   => 'info',
                'label'   => $c->name ?: $c->email,
                'sub'     => $c->subject ?: __('New contact request'),
                'time'    => $c->created_at,
                'link'    => route('admin.contacts.show', $c->id),
            ]);
        }

        // Trade offers
        foreach (TradeOffer::where('created_at', '>=', $thirtyDaysAgo)->latest()->take(5)->get() as $t) {
            $activityItems->push([
                'type'    => 'trade_offer',
                'icon'    => 'bx-transfer-alt',
                'color'   => 'secondary',
                'label'   => trim($t->sender_first_name . ' ' . $t->sender_last_name) ?: 'Unknown',
                'sub'     => __('Trade offer:') . ' ' . $t->brand . ' ' . $t->model,
                'time'    => $t->created_at,
                'link'    => route('admin.trade-offers.show', $t->id),
            ]);
        }

        // Sort by time desc, take 25
        $activity_feed = $activityItems->sortByDesc('time')->take(25)->values();

        return view('content.dashboard.dashboards-analytics', compact(
            'stats',
            'recent_vehicles',
            'recent_partners',
            'pending_vehicles',
            'recent_users',
            'recent_partner_registrations',
            'recent_subscriptions',
            'failed_subscriptions',
            'recent_contacts',
            'recent_trade_offers',
            'recent_newsletter',
            'activity_feed'
        ));
    }
}
