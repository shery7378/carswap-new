@extends('layouts/contentNavbarLayout')

@section('title', __('Dashboard') . ' - ' . __('Analytics'))

@section('vendor-style')
@endsection

@section('page-style')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
/* ============================================================
   BASE & TYPOGRAPHY
   ============================================================ */
body, .card, .table { font-family: 'Inter', sans-serif; }

/* ============================================================
   HERO CARD — gradient welcome banner
   ============================================================ */
.hero-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
    border: none;
    border-radius: 16px;
    overflow: hidden;
    position: relative;
}
.hero-card::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -10%;
    width: 300px;
    height: 300px;
    background: rgba(255,255,255,0.08);
    border-radius: 50%;
}
.hero-card::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 200px;
    height: 200px;
    background: rgba(255,255,255,0.06);
    border-radius: 50%;
}
.hero-card .card-body { position: relative; z-index: 2; }
.hero-card h4, .hero-card p, .hero-card small { color: #fff !important; }
.hero-admin-name { font-size: 1.5rem; font-weight: 700; color: #fff !important; }
.hero-role-badge {
    display: inline-block;
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.3);
    color: #fff;
    border-radius: 20px;
    padding: 3px 14px;
    font-size: 0.78rem;
    font-weight: 500;
    letter-spacing: 0.5px;
}
.hero-time { color: rgba(255,255,255,0.75) !important; font-size: 0.82rem; }
.hero-avatar { border: 3px solid rgba(255,255,255,0.4) !important; }
.hero-btn {
    background: rgba(255,255,255,0.2);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.35);
    color: #fff;
    border-radius: 8px;
    padding: 6px 16px;
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.hero-btn:hover { background: rgba(255,255,255,0.35); color: #fff; transform: translateY(-1px); }

/* ============================================================
   KPI STAT CARDS
   ============================================================ */
.kpi-card {
    border: none;
    border-radius: 14px;
    transition: all 0.3s ease;
    cursor: default;
    position: relative;
    overflow: hidden;
}
.kpi-card:hover { transform: translateY(-4px); box-shadow: 0 12px 30px rgba(0,0,0,0.12); }
.kpi-card .kpi-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.4rem;
    flex-shrink: 0;
}
.kpi-card .kpi-number {
    font-size: 1.9rem;
    font-weight: 800;
    line-height: 1;
    color: #1e293b;
}
.kpi-card .kpi-label { font-size: 0.78rem; font-weight: 500; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
.kpi-card .kpi-trend { font-size: 0.75rem; font-weight: 600; }
/* Urgent/warning KPI */
.kpi-card.kpi-urgent { border-left: 4px solid #f59e0b; }
.kpi-card.kpi-danger  { border-left: 4px solid #ef4444; }

/* KPI icon variants */
.kpi-icon-blue    { background: #eff6ff; color: #3b82f6; }
.kpi-icon-amber   { background: #fffbeb; color: #f59e0b; }
.kpi-icon-green   { background: #f0fdf4; color: #22c55e; }
.kpi-icon-purple  { background: #faf5ff; color: #a855f7; }
.kpi-icon-red     { background: #fef2f2; color: #ef4444; }
.kpi-icon-cyan    { background: #ecfeff; color: #06b6d4; }
.kpi-icon-indigo  { background: #eef2ff; color: #6366f1; }
.kpi-icon-orange  { background: #fff7ed; color: #f97316; }

/* ============================================================
   SECTION HEADERS
   ============================================================ */
.section-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    padding-bottom: 0.75rem;
    border-bottom: 2px solid #f1f5f9;
}
.section-title {
    font-size: 0.95rem;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 8px;
}
.section-title i { font-size: 1.1rem; }
.section-badge {
    background: #f1f5f9;
    color: #475569;
    border-radius: 20px;
    padding: 2px 10px;
    font-size: 0.72rem;
    font-weight: 600;
}

/* ============================================================
   ATTENTION PANEL — Pending Approvals
   ============================================================ */
.attention-card {
    border: none;
    border-radius: 14px;
    border-top: 3px solid #f59e0b;
}
.attention-card.danger-card { border-top-color: #ef4444; }

.pending-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 0;
    border-bottom: 1px solid #f8fafc;
    transition: background 0.15s;
    border-radius: 8px;
}
.pending-row:last-child { border-bottom: none; }
.pending-row:hover { background: #f8fafc; padding-left: 6px; }
.pending-car-thumb {
    width: 46px;
    height: 36px;
    border-radius: 8px;
    object-fit: cover;
    background: #f1f5f9;
    flex-shrink: 0;
}
.pending-car-placeholder {
    width: 46px;
    height: 36px;
    border-radius: 8px;
    background: #f1f5f9;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #94a3b8;
    font-size: 1rem;
    flex-shrink: 0;
}
.pending-car-name { font-weight: 600; font-size: 0.85rem; color: #1e293b; line-height: 1.2; }
.pending-car-meta { font-size: 0.72rem; color: #64748b; }
.pending-approve-btn {
    margin-left: auto;
    flex-shrink: 0;
    font-size: 0.72rem;
    padding: 3px 10px;
    border-radius: 6px;
    font-weight: 600;
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
    text-decoration: none;
    transition: all 0.2s;
}
.pending-approve-btn:hover { background: #22c55e; color: #fff; }

/* ============================================================
   ACTIVITY FEED TIMELINE
   ============================================================ */
.activity-feed { list-style: none; padding: 0; margin: 0; }
.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 10px 0;
    position: relative;
    transition: all 0.15s;
}
.activity-item:not(:last-child)::after {
    content: '';
    position: absolute;
    left: 19px;
    top: 46px;
    width: 2px;
    height: calc(100% - 20px);
    background: #e2e8f0;
}
.activity-dot {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
    position: relative;
    z-index: 1;
}
.activity-dot-warning { background: #fffbeb; color: #f59e0b; border: 2px solid #fde68a; }
.activity-dot-success { background: #f0fdf4; color: #22c55e; border: 2px solid #bbf7d0; }
.activity-dot-primary { background: #eff6ff; color: #3b82f6; border: 2px solid #bfdbfe; }
.activity-dot-danger  { background: #fef2f2; color: #ef4444; border: 2px solid #fecaca; }
.activity-dot-info    { background: #ecfeff; color: #06b6d4; border: 2px solid #a5f3fc; }
.activity-dot-secondary { background: #f8fafc; color: #64748b; border: 2px solid #e2e8f0; }
.activity-body { flex: 1; }
.activity-label { font-weight: 600; font-size: 0.83rem; color: #1e293b; }
.activity-sub { font-size: 0.75rem; color: #64748b; }
.activity-time { font-size: 0.7rem; color: #94a3b8; white-space: nowrap; }
.activity-link { text-decoration: none; color: inherit; }
.activity-link:hover .activity-label { color: #6366f1; }

/* ============================================================
   USER / PARTNER LIST ITEMS
   ============================================================ */
.user-list-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 0;
    border-bottom: 1px solid #f1f5f9;
    transition: all 0.15s;
}
.user-list-item:last-child { border-bottom: none; }
.user-list-item:hover { padding-left: 4px; }
.user-avatar-sm {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}
.user-name { font-weight: 600; font-size: 0.83rem; color: #1e293b; }
.user-email { font-size: 0.72rem; color: #94a3b8; }
.user-time-badge {
    margin-left: auto;
    font-size: 0.68rem;
    color: #94a3b8;
    white-space: nowrap;
    flex-shrink: 0;
}

/* ============================================================
   CONTACT / TRADE OFFER LIST ITEMS
   ============================================================ */
.inbox-item {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    padding: 10px 12px;
    border-radius: 10px;
    transition: background 0.15s;
    text-decoration: none;
    margin-bottom: 4px;
}
.inbox-item:hover { background: #f8fafc; }
.inbox-icon {
    width: 38px;
    height: 38px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    flex-shrink: 0;
}
.inbox-subject { font-weight: 600; font-size: 0.82rem; color: #1e293b; line-height: 1.3; }
.inbox-from    { font-size: 0.72rem; color: #64748b; }
.inbox-time    { font-size: 0.68rem; color: #94a3b8; white-space: nowrap; }

/* ============================================================
   STATUS BADGES
   ============================================================ */
.status-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    border-radius: 20px;
    padding: 2px 9px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.3px;
}
.status-pill::before { content: ''; width: 6px; height: 6px; border-radius: 50%; display: inline-block; }
.status-published  { background: #dcfce7; color: #16a34a; }
.status-published::before  { background: #22c55e; }
.status-pending    { background: #fffbeb; color: #d97706; }
.status-pending::before    { background: #f59e0b; }
.status-draft      { background: #f1f5f9; color: #475569; }
.status-draft::before      { background: #94a3b8; }
.status-rejected   { background: #fef2f2; color: #dc2626; }
.status-rejected::before   { background: #ef4444; }
.status-active     { background: #dcfce7; color: #16a34a; }
.status-active::before     { background: #22c55e; }
.status-failed     { background: #fef2f2; color: #dc2626; }
.status-failed::before     { background: #ef4444; }
.status-cancelled  { background: #fff7ed; color: #ea580c; }
.status-cancelled::before  { background: #f97316; }
.status-expired    { background: #fef2f2; color: #dc2626; }
.status-expired::before    { background: #ef4444; }
.status-past_due   { background: #fff7ed; color: #ea580c; }
.status-past_due::before   { background: #f97316; }
.status-new        { background: #eff6ff; color: #2563eb; }
.status-new::before        { background: #3b82f6; }
.status-open       { background: #ecfeff; color: #0891b2; }
.status-open::before       { background: #06b6d4; }
.status-closed     { background: #f1f5f9; color: #475569; }
.status-closed::before     { background: #94a3b8; }

/* ============================================================
   SUBSCRIBER ROW
   ============================================================ */
.subscriber-row {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 7px 0;
    border-bottom: 1px solid #f1f5f9;
}
.subscriber-row:last-child { border-bottom: none; }
.subscriber-email { font-size: 0.82rem; font-weight: 500; color: #334155; flex: 1; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
.subscriber-time  { font-size: 0.7rem; color: #94a3b8; white-space: nowrap; }

/* ============================================================
   CARD POLISH
   ============================================================ */
.dashboard-card {
    border: none;
    border-radius: 14px;
    box-shadow: 0 1px 4px rgba(0,0,0,0.06), 0 4px 16px rgba(0,0,0,0.04);
    transition: box-shadow 0.3s ease;
}
.dashboard-card:hover { box-shadow: 0 4px 20px rgba(0,0,0,0.09); }
.dashboard-card .card-header {
    border-bottom: 1px solid #f1f5f9;
    background: transparent;
    border-radius: 14px 14px 0 0;
    padding: 16px 20px 12px;
}
.dashboard-card .card-body { padding: 16px 20px; }

/* ============================================================
   EMPTY STATE
   ============================================================ */
.empty-state {
    text-align: center;
    padding: 28px 16px;
    color: #94a3b8;
}
.empty-state i { font-size: 2.2rem; display: block; margin-bottom: 8px; opacity: 0.5; }
.empty-state p { font-size: 0.82rem; margin: 0; }

/* ============================================================
   HOVER ON CARD ROWS
   ============================================================ */
.card-hover-click {
    transition: all 0.3s ease;
    cursor: pointer;
}
.card-hover-click:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

/* ============================================================
   ANIMATED KPI COUNTER
   ============================================================ */
@keyframes countUp { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }
.kpi-number { animation: countUp 0.5s ease forwards; }

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 576px) {
    .hero-admin-name { font-size: 1.2rem; }
    .kpi-number { font-size: 1.5rem; }
}
</style>
@endsection

@section('vendor-script')
@endsection

@section('content')

{{-- ================================================================
     ROW 1 — HERO CARD + KPI STATS
     ================================================================ --}}
<div class="row g-4 mb-4">

    {{-- HERO WELCOME --}}
    <div class="col-12 col-xl-5">
        <div class="card hero-card h-100">
            <div class="card-body d-flex flex-column justify-content-between p-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <img src="{{ Auth::user()->getAvatarUrl() }}"
                         alt="avatar"
                         class="rounded-circle hero-avatar"
                         width="54" height="54"
                         style="object-fit:cover;">
                    <div>
                        <p class="hero-admin-name mb-1">
                            {{ __('Welcome back, :name! 👋', ['name' => Auth::user()->first_name ?? __('Admin')]) }}
                        </p>
                        <span class="hero-role-badge">
                            {{ __(Auth::user()->roles->pluck('name')->first() ?? 'Staff') }}
                        </span>
                    </div>
                </div>

                <div class="mb-4">
                    <p class="text-white mb-1" style="font-size:0.9rem;opacity:0.85;">
                        {{ __("Here's what's happening in your platform today.") }}
                    </p>
                    <p class="hero-time mb-0">
                        <i class="bx bx-time me-1"></i>
                        {{ now()->format('l, F j, Y — H:i') }}
                    </p>
                </div>

                <div class="d-flex flex-wrap gap-2">
                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-vehicles', 'admin-guard'))
                    <a href="{{ route('admin.vehicles.index') }}" class="hero-btn">
                        <i class="bx bx-car"></i> {{ __('Vehicles') }}
                    </a>
                    @endif
                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-users', 'admin-guard'))
                    <a href="{{ route('admin.web-users.index') }}" class="hero-btn">
                        <i class="bx bx-group"></i> {{ __('Users') }}
                    </a>
                    @endif
                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-subscriptions', 'admin-guard'))
                    <a href="{{ route('app-subscription-list') }}" class="hero-btn">
                        <i class="bx bx-badge-check"></i> {{ __('Subscriptions') }}
                    </a>
                    @endif
                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-contacts', 'admin-guard'))
                    <a href="{{ route('admin.contacts.index') }}" class="hero-btn">
                        <i class="bx bx-envelope"></i> {{ __('Contacts') }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- KPI STAT CARDS --}}
    <div class="col-12 col-xl-7">
        <div class="row g-3 h-100">

            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-vehicles', 'admin-guard'))
            {{-- Total Vehicles --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('admin.vehicles.index') }}" class="text-decoration-none">
                    <div class="card dashboard-card kpi-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="kpi-icon kpi-icon-blue">
                                    <i class="bx bx-car"></i>
                                </div>
                                <div class="kpi-number">{{ $stats['total_vehicles'] }}</div>
                            </div>
                            <div class="kpi-label">{{ __('Total Vehicles') }}</div>
                            <div class="kpi-trend text-primary mt-1">
                                <i class="bx bx-up-arrow-alt"></i> +{{ $stats['new_vehicles_30d'] }} {{ __('this month') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            {{-- Pending Approval --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('admin.vehicles.index') }}?status=Függőben" class="text-decoration-none">
                    <div class="card dashboard-card kpi-card kpi-urgent h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="kpi-icon kpi-icon-amber">
                                    <i class="bx bx-time-five"></i>
                                </div>
                                <div class="kpi-number text-warning">{{ $stats['pending_vehicles'] }}</div>
                            </div>
                            <div class="kpi-label">{{ __('Jóváhagyásra vár') }}</div>
                            @if($stats['pending_vehicles'] > 0)
                            <div class="kpi-trend text-warning mt-1">
                                <i class="bx bx-error-circle"></i> {{ __('Felülvizsgálat szükséges') }}
                            </div>
                            @else
                            <div class="kpi-trend text-success mt-1">
                                <i class="bx bx-check-circle"></i> {{ __('All clear') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-users', 'admin-guard'))
            {{-- Total Users --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('admin.web-users.index') }}" class="text-decoration-none">
                    <div class="card dashboard-card kpi-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="kpi-icon kpi-icon-green">
                                    <i class="bx bx-group"></i>
                                </div>
                                <div class="kpi-number">{{ $stats['total_users'] }}</div>
                            </div>
                            <div class="kpi-label">{{ __('Regisztrált felhasználók') }}</div>
                            <div class="kpi-trend text-success mt-1">
                                <i class="bx bx-user-plus"></i> +{{ $stats['new_users_7d'] }} {{ __('this week') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-partners', 'admin-guard'))
            {{-- Total Partners --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('admin.partners.index') }}" class="text-decoration-none">
                    <div class="card dashboard-card kpi-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="kpi-icon kpi-icon-purple">
                                    <i class="bx bx-buildings"></i>
                                </div>
                                <div class="kpi-number">{{ $stats['total_partners'] }}</div>
                            </div>
                            <div class="kpi-label">{{ __('Partners') }}</div>
                            <div class="kpi-trend text-muted mt-1">
                                <i class="bx bx-building"></i> {{ __('Márkakereskedők és szolgáltatások') }}
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-subscriptions', 'admin-guard'))
            {{-- Active Subscriptions --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('app-subscription-list') }}" class="text-decoration-none">
                    <div class="card dashboard-card kpi-card h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="kpi-icon kpi-icon-indigo">
                                    <i class="bx bx-badge-check"></i>
                                </div>
                                <div class="kpi-number">{{ $stats['active_subscriptions'] }}</div>
                            </div>
                            <div class="kpi-label">{{ __('Aktív előfizetések') }}</div>
                            @if($stats['failed_subscriptions'] > 0)
                            <div class="kpi-trend text-danger mt-1">
                                <i class="bx bx-error"></i> {{ $stats['failed_subscriptions'] }} {{ __('sikertelen') }}
                            </div>
                            @else
                            <div class="kpi-trend text-success mt-1">
                                <i class="bx bx-check-circle"></i> {{ __('All healthy') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endif

            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-contacts', 'admin-guard'))
            {{-- Open Contacts --}}
            <div class="col-6 col-md-4">
                <a href="{{ route('admin.contacts.index') }}" class="text-decoration-none">
                    <div class="card dashboard-card kpi-card {{ $stats['open_contacts'] > 0 ? 'kpi-danger' : '' }} h-100">
                        <div class="card-body p-3">
                            <div class="d-flex align-items-center gap-3 mb-2">
                                <div class="kpi-icon {{ $stats['open_contacts'] > 0 ? 'kpi-icon-red' : 'kpi-icon-cyan' }}">
                                    <i class="bx bx-envelope"></i>
                                </div>
                                <div class="kpi-number {{ $stats['open_contacts'] > 0 ? 'text-danger' : '' }}">{{ $stats['open_contacts'] }}</div>
                            </div>
                            <div class="kpi-label">{{ __('Nyitott kapcsolatok') }}</div>
                            @if($stats['open_contacts'] > 0)
                            <div class="kpi-trend text-danger mt-1">
                                <i class="bx bx-bell"></i> {{ __('Válaszra vár') }}
                            </div>
                            @else
                            <div class="kpi-trend text-success mt-1">
                                <i class="bx bx-check-circle"></i> {{ __('All handled') }}
                            </div>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
            @endif

        </div>
    </div>
</div>

{{-- ================================================================
     ROW 2 — ATTENTION REQUIRED
     ================================================================ --}}
<div class="row g-4 mb-4">

    {{-- PENDING VEHICLE APPROVALS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-vehicles', 'admin-guard'))
    <div class="col-12 col-xl-7">
        <div class="card dashboard-card attention-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-time-five text-warning"></i>
                        {{ __('Jóváhagyásra váró hirdetések') }}
                        @if($stats['pending_vehicles'] > 0)
                            <span class="badge bg-warning text-dark ms-1">{{ $stats['pending_vehicles'] }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.76rem;">
                        {{ __('Összes megtekintése') }} <i class="bx bx-right-arrow-alt"></i>
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($pending_vehicles as $vehicle)
                <div class="pending-row">
                    @if($vehicle->main_image)
                        <img src="{{ asset('storage/' . $vehicle->main_image) }}"
                             alt="car"
                             class="pending-car-thumb">
                    @else
                        <div class="pending-car-placeholder"><i class="bx bx-car"></i></div>
                    @endif
                    <div class="flex-grow-1 min-width-0">
                        <div class="pending-car-name">
                            {{ $vehicle->brand->name ?? '' }} {{ $vehicle->model->name ?? '' }} {{ $vehicle->year }}
                        </div>
                        <div class="pending-car-meta">
                            @if($vehicle->user)
                                <i class="bx bx-user" style="font-size:0.7rem;"></i>
                                {{ trim($vehicle->user->first_name . ' ' . $vehicle->user->last_name) ?: $vehicle->user->email }}
                                &bull;
                            @endif
                            {{ $vehicle->created_at->diffForHumans() }}
                            @if($vehicle->price)
                                &bull; @formatCurrency($vehicle->price)
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('admin.vehicles.show', $vehicle->id) }}" class="pending-approve-btn">
                        <i class="bx bx-show" style="font-size:0.8rem;"></i> {{ __('Ellenőrzés') }}
                    </a>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bx bx-check-circle text-success"></i>
                    <p>{{ __('No listings waiting for approval. All clear!') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- FAILED / CHANGED SUBSCRIPTIONS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-subscriptions', 'admin-guard'))
    <div class="col-12 col-xl-5">
        <div class="card dashboard-card attention-card danger-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-error-circle text-danger"></i>
                        {{ __('Sikertelen / Törölt előfizetések') }}
                        @if($stats['failed_subscriptions'] > 0)
                            <span class="badge bg-danger ms-1">{{ $stats['failed_subscriptions'] }}</span>
                        @endif
                    </div>
                    <a href="{{ route('app-subscription-list') }}" class="btn btn-sm btn-outline-danger" style="font-size:0.76rem;">
                        {{ __('Összes megtekintése') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($failed_subscriptions as $sub)
                @php
                    $subUser = $sub->user;
                    $subAvatarName = trim(optional($subUser)->first_name . ' ' . optional($subUser)->last_name) ?: 'User';
                    $subAvatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($subAvatarName) . '&background=EBF4FF&color=7F9CF5';
                @endphp
                <div class="user-list-item">
                    <img src="{{ optional($subUser)->getAvatarUrl() ?? $subAvatarFallback }}"
                         class="user-avatar-sm"
                         alt="user"
                         onerror="this.onerror=null; this.src='{{ $subAvatarFallback }}';">
                    <div class="flex-grow-1 min-width-0">
                        <div class="user-name">
                            {{ optional($sub->user) ? trim(optional($sub->user)->first_name . ' ' . optional($sub->user)->last_name) : __('Unknown User') }}
                        </div>
                        <div class="user-email">{{ optional(optional($sub)->plan)->name ?? __('No Plan') }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        @php $st = strtolower($sub->status ?? 'unknown'); @endphp
                        <span class="status-pill status-{{ $st }}">
                            @php
                                $statusLabel = match ($st) {
                                    'cancelled' => __('Törölve'),
                                    'expired' => __('Lejárt'),
                                    'failed' => __('Sikertelen'),
                                    default => ucfirst($sub->status ?? 'unknown'),
                                };
                            @endphp
                            {{ $statusLabel }}
                        </span>
                        <span class="user-time-badge">{{ $sub->updated_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bx bx-shield-check text-success"></i>
                    <p>{{ __('Nincsenek sikertelen vagy törölt előfizetések. Minden rendben!') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ================================================================
     ROW 3 — ACTIVITY FEED + NEW USER REGISTRATIONS
     ================================================================ --}}
<div class="row g-4 mb-4">

    {{-- UNIFIED ACTIVITY FEED --}}
    <div class="col-12 col-xl-8">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-pulse text-primary"></i>
                        {{ __('Legutóbbi tevékenység') }}
                        <span class="section-badge">{{ __('Utolsó 30 nap') }}</span>
                    </div>
                </div>
            </div>
            <div class="card-body" style="max-height: 480px; overflow-y: auto;">
                @if($activity_feed->count())
                <ul class="activity-feed">
                    @foreach($activity_feed as $item)
                    <li class="activity-item">
                        <div class="activity-dot activity-dot-{{ $item['color'] }}">
                            <i class="bx {{ $item['icon'] }}"></i>
                        </div>
                        <a href="{{ $item['link'] }}" class="activity-link d-flex flex-grow-1 align-items-center gap-2">
                            <div class="activity-body flex-grow-1">
                                <div class="activity-label">{{ $item['label'] }}</div>
                                <div class="activity-sub">{{ $item['sub'] }}</div>
                            </div>
                            <div class="activity-time">{{ $item['time']->diffForHumans() }}</div>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <div class="empty-state">
                    <i class="bx bx-history"></i>
                    <p>{{ __('No recent activity to display.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- NEW USER REGISTRATIONS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-users', 'admin-guard'))
    <div class="col-12 col-xl-4">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-user-plus text-success"></i>
                        {{ __('Új regisztrációk') }}
                        <span class="section-badge">{{ __('7 nap') }}</span>
                    </div>
                    <a href="{{ route('admin.web-users.index') }}" class="btn btn-sm btn-outline-success" style="font-size:0.76rem;">
                        {{ __('Összes felhasználó') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recent_users as $user)
                <a href="{{ route('admin.web-users.view', $user->id) }}" class="text-decoration-none">
                    <div class="user-list-item">
                        <img src="{{ $user->getAvatarUrl() }}" class="user-avatar-sm" alt="user">
                        <div class="flex-grow-1 min-width-0">
                            <div class="user-name">
                                {{ trim($user->first_name . ' ' . $user->last_name) ?: __('User') }}
                            </div>
                            <div class="user-email">{{ $user->email }}</div>
                        </div>
                        <div class="user-time-badge">{{ $user->created_at->diffForHumans() }}</div>
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <i class="bx bx-user-x"></i>
                    <p>{{ __('Nincsenek új regisztrációk az elmúlt 7 napban.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ================================================================
     ROW 4 — RECENT CONTACTS + RECENT TRADE OFFERS
     ================================================================ --}}
<div class="row g-4 mb-4">

    {{-- CONTACT / SUPPORT REQUESTS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-contacts', 'admin-guard'))
    <div class="col-12 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-support text-info"></i>
                        {{ __('Támogatási kérések') }}
                    </div>
                    <a href="{{ route('admin.contacts.index') }}" class="btn btn-sm btn-outline-info" style="font-size:0.76rem;">
                        {{ __('Összes megtekintése') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recent_contacts as $contact)
                <a href="{{ route('admin.contacts.show', $contact->id) }}" class="inbox-item">
                    <div class="inbox-icon kpi-icon-cyan">
                        <i class="bx bx-envelope"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="inbox-subject">{{ Str::limit($contact->subject ?: __('No subject'), 40) }}</div>
                        <div class="inbox-from">
                            <i class="bx bx-user" style="font-size:0.68rem;"></i>
                            {{ $contact->name ?: $contact->email }}
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1 ms-2">
                        @php $cst = strtolower($contact->status ?? 'new'); @endphp
                        <span class="status-pill status-{{ $cst }}">
                            @php
                                $contactStatusLabel = match ($cst) {
                                    'replied' => __('Válaszolt'),
                                    'read' => __('Olvasva'),
                                    'new' => __('Új'),
                                    default => ucfirst($contact->status ?? 'new'),
                                };
                            @endphp
                            {{ $contactStatusLabel }}
                        </span>
                        <span class="inbox-time">{{ $contact->created_at->diffForHumans() }}</span>
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <i class="bx bx-envelope-open"></i>
                    <p>{{ __('Nem található támogatási kérés.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- TRADE OFFERS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-trade_offers', 'admin-guard'))
    <div class="col-12 col-md-6">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-transfer-alt text-secondary"></i>
                        {{ __('Csereláda bejövő üzenetek') }}
                        @if($stats['open_trade_offers'] > 0)
                            <span class="badge bg-secondary ms-1">{{ $stats['open_trade_offers'] }}</span>
                        @endif
                    </div>
                    <a href="{{ route('admin.trade-offers.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.76rem;">
                        {{ __('Összes megtekintése') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recent_trade_offers as $offer)
                <a href="{{ route('admin.trade-offers.show', $offer->id) }}" class="inbox-item">
                    <div class="inbox-icon kpi-icon-indigo">
                        <i class="bx bx-transfer-alt"></i>
                    </div>
                    <div class="flex-grow-1 min-width-0">
                        <div class="inbox-subject">
                            {{ $offer->brand }} {{ $offer->model }} ({{ $offer->year }})
                        </div>
                        <div class="inbox-from">
                            <i class="bx bx-user" style="font-size:0.68rem;"></i>
                            {{ trim($offer->sender_first_name . ' ' . $offer->sender_last_name) ?: $offer->sender_email }}
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1 ms-2">
                        @php $tst = strtolower($offer->status ?? 'new'); @endphp
                        <span class="status-pill status-{{ in_array($tst, ['new','pending','open','closed','rejected']) ? $tst : 'new' }}">
                            @php
                                $offerStatusLabel = match ($tst) {
                                    'viewed' => __('Megtekintve'),
                                    'pending' => __('Függőben'),
                                    'closed' => __('Lezárva'),
                                    'rejected' => __('Elutasítva'),
                                    'new' => __('Új'),
                                    default => ucfirst($offer->status ?? 'New'),
                                };
                            @endphp
                            {{ $offerStatusLabel }}
                        </span>
                        <span class="inbox-time">{{ $offer->created_at->diffForHumans() }}</span>
                    </div>
                </a>
                @empty
                <div class="empty-state">
                    <i class="bx bx-transfer-alt"></i>
                    <p>{{ __('No trade offers received yet.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ================================================================
     ROW 5 — RECENT SUBSCRIPTIONS + NEW PARTNERS + NEWSLETTER
     ================================================================ --}}
<div class="row g-4 mb-4">

    {{-- RECENT SUBSCRIPTIONS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-subscriptions', 'admin-guard'))
    <div class="col-12 col-md-6 col-xl-5">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-badge-check text-primary"></i>
                        {{ __('Legutóbbi előfizetések') }}
                    </div>
                    <a href="{{ route('app-subscription-list') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.76rem;">
                        {{ __('Összes megtekintése') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recent_subscriptions as $sub)
                @php
                    $subUser = $sub->user;
                    $subAvatarName = trim(optional($subUser)->first_name . ' ' . optional($subUser)->last_name) ?: 'User';
                    $subAvatarFallback = 'https://ui-avatars.com/api/?name=' . urlencode($subAvatarName) . '&background=EBF4FF&color=7F9CF5';
                @endphp
                <div class="user-list-item">
                    <img src="{{ optional($subUser)->getAvatarUrl() ?? $subAvatarFallback }}"
                         class="user-avatar-sm"
                         alt="user"
                         onerror="this.onerror=null; this.src='{{ $subAvatarFallback }}';">
                    <div class="flex-grow-1 min-width-0">
                        <div class="user-name">
                            {{ optional($sub->user) ? trim(optional($sub->user)->first_name . ' ' . optional($sub->user)->last_name) : __('Unknown') }}
                        </div>
                        <div class="user-email">
                            {{ optional(optional($sub)->plan)->name ?? __('No Plan') }}
                            @if($sub->amount)
                                &bull; {{ number_format($sub->amount, 0) }}
                            @endif
                        </div>
                    </div>
                    <div class="d-flex flex-column align-items-end gap-1">
                        @php $sst = strtolower($sub->status ?? 'unknown'); @endphp
                        <span class="status-pill status-{{ in_array($sst, ['active','failed','cancelled','expired','past_due']) ? $sst : 'new' }}">
                            {{ ucfirst($sub->status ?? 'Unknown') }}
                        </span>
                        <span class="user-time-badge">{{ $sub->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="empty-state">
                    <i class="bx bx-badge"></i>
                    <p>{{ __('No subscriptions found.') }}</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    {{-- NEW PARTNERS + NEWSLETTER (stacked right column) --}}
    <div class="col-12 col-md-6 col-xl-7">
        <div class="row g-4 h-100">

            {{-- NEW PARTNER REGISTRATIONS --}}
            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-partners', 'admin-guard'))
            <div class="col-12 col-xl-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header">
                        <div class="section-header mb-0">
                            <div class="section-title" style="font-size:0.85rem;">
                                <i class="bx bx-buildings text-purple" style="color:#a855f7;"></i>
                                {{ __('Új partnerek') }}
                                <span class="section-badge">7 nap</span>
                            </div>
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.7rem; padding:2px 8px;">
                                {{ __('Mind') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($recent_partner_registrations as $partner)
                        <a href="{{ route('admin.partners.show', $partner->id) }}" class="text-decoration-none">
                            <div class="user-list-item">
                                @if($partner->image)
                                    <img src="{{ asset('storage/' . $partner->image) }}"
                                         class="user-avatar-sm rounded"
                                         alt="partner"
                                         style="border-radius:8px!important;">
                                @else
                                    <div class="user-avatar-sm rounded d-flex align-items-center justify-content-center bg-light" style="border-radius:8px!important;">
                                        <i class="bx bx-buildings text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1 min-width-0">
                                    <div class="user-name" style="font-size:0.8rem;">{{ Str::limit($partner->name, 22) }}</div>
                                    <div class="user-email">{{ Str::limit($partner->address ?? '', 28) }}</div>
                                </div>
                                <span class="user-time-badge">{{ $partner->created_at->diffForHumans() }}</span>
                            </div>
                        </a>
                        @empty
                        <div class="empty-state" style="padding:20px 0;">
                            <i class="bx bx-buildings"></i>
                            <p>{{ __('Nincsenek új partnerek ezen a héten.') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

            {{-- NEWSLETTER SUBSCRIBERS --}}
            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-newsletter', 'admin-guard'))
            <div class="col-12 col-xl-6">
                <div class="card dashboard-card h-100">
                    <div class="card-header">
                        <div class="section-header mb-0">
                            <div class="section-title" style="font-size:0.85rem;">
                                <i class="bx bx-news text-orange" style="color:#f97316;"></i>
                                {{ __('Newsletter') }}
                                <span class="section-badge">{{ $stats['new_subscribers_30d'] }} {{ __('new') }}</span>
                            </div>
                            <a href="{{ route('admin.newsletter.index') }}" class="btn btn-sm btn-outline-secondary" style="font-size:0.7rem; padding:2px 8px;">
                                {{ __('All') }}
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @forelse($recent_newsletter as $subscriber)
                        <div class="subscriber-row">
                            <div class="kpi-icon kpi-icon-orange" style="width:28px;height:28px;border-radius:6px;font-size:0.8rem;flex-shrink:0;">
                                <i class="bx bx-mail-send"></i>
                            </div>
                            <div class="subscriber-email">{{ $subscriber->email }}</div>
                            <div class="subscriber-time">{{ $subscriber->created_at->diffForHumans() }}</div>
                        </div>
                        @empty
                        <div class="empty-state" style="padding:20px 0;">
                            <i class="bx bx-news"></i>
                            <p>{{ __('No new subscribers recently.') }}</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

</div>

{{-- ================================================================
     ROW 6 — RECENT VEHICLES TABLE + RECENT PARTNERS LIST
     ================================================================ --}}
<div class="row g-4 mb-4">

    {{-- RECENT VEHICLE LISTINGS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-vehicles', 'admin-guard'))
    <div class="col-12 col-xl-8">
        <div class="card dashboard-card">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-list-ul text-primary"></i>
                        {{ __('Recent Vehicle Listings') }}
                    </div>
                    <a href="{{ route('admin.vehicles.index') }}" class="btn btn-sm btn-primary" style="font-size:0.76rem;">
                        {{ __('View All') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" style="font-size:0.83rem;">
                        <thead style="background:#f8fafc;">
                            <tr>
                                <th class="px-4 py-3 text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;color:#64748b;font-weight:600;">{{ __('Vehicle') }}</th>
                                <th class="py-3 text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;color:#64748b;font-weight:600;">{{ __('Owner') }}</th>
                                <th class="py-3 text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;color:#64748b;font-weight:600;">{{ __('Price') }}</th>
                                <th class="py-3 text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;color:#64748b;font-weight:600;">{{ __('Date') }}</th>
                                <th class="py-3 text-uppercase" style="font-size:0.7rem;letter-spacing:0.5px;color:#64748b;font-weight:600;">{{ __('Status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent_vehicles as $vehicle)
                            <tr class="clickable-vehicle-row" data-id="{{ $vehicle->id }}" style="cursor:pointer;">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center gap-2">
                                        @if($vehicle->main_image)
                                            <img src="{{ asset('storage/' . $vehicle->main_image) }}"
                                                 alt="car"
                                                 style="width:40px;height:32px;border-radius:6px;object-fit:cover;">
                                        @else
                                            <div style="width:40px;height:32px;border-radius:6px;background:#f1f5f9;display:flex;align-items:center;justify-content:center;">
                                                <i class="bx bx-car text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div style="font-weight:600;color:#1e293b;">
                                                {{ $vehicle->brand->name ?? '' }} {{ $vehicle->model->name ?? '' }}
                                            </div>
                                            <div style="font-size:0.72rem;color:#94a3b8;">{{ $vehicle->year }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3" style="color:#475569;">
                                    {{ optional($vehicle->user) ? Str::limit(trim(optional($vehicle->user)->first_name . ' ' . optional($vehicle->user)->last_name), 18) : '—' }}
                                </td>
                                <td class="py-3" style="font-weight:600;color:#1e293b;">@formatCurrency($vehicle->price)</td>
                                <td class="py-3" style="color:#64748b;">{{ $vehicle->created_at->format('M j, Y') }}</td>
                                <td class="py-3">
                                    @php
                                        $adStatus = $vehicle->ad_status ?? 'Piszkozat';
                                        $statusMap = [
                                            'Publikált'  => 'published',
                                            'Függőben'   => 'pending',
                                            'Piszkozat'  => 'draft',
                                            'Elutasítva' => 'rejected',
                                        ];
                                        $cssClass = $statusMap[$adStatus] ?? 'draft';
                                    @endphp
                                    <span class="status-pill status-{{ $cssClass }}">{{ __($adStatus) }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-5" style="color:#94a3b8;">
                                    <i class="bx bx-car" style="font-size:1.8rem;display:block;margin-bottom:6px;"></i>
                                    {{ __('No recent vehicles') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- GLOBAL PARTNERS --}}
    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('view-partners', 'admin-guard'))
    <div class="col-12 col-xl-4">
        <div class="card dashboard-card h-100">
            <div class="card-header">
                <div class="section-header mb-0">
                    <div class="section-title">
                        <i class="bx bx-buildings" style="color:#a855f7;"></i>
                        {{ __('Global Partners') }}
                    </div>
                    <a href="{{ route('admin.partners.index') }}" class="btn btn-sm btn-outline-primary" style="font-size:0.76rem;">
                        {{ __('View All') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <ul class="p-0 m-0 list-unstyled">
                    @forelse($recent_partners as $partner)
                    <li class="clickable-partner-item user-list-item" data-id="{{ $partner->id }}" style="cursor:pointer;">
                        @if($partner->image)
                            <img src="{{ asset('storage/' . $partner->image) }}"
                                 alt="partner"
                                 class="user-avatar-sm"
                                 style="border-radius:8px!important;object-fit:cover;">
                        @else
                            <div class="user-avatar-sm d-flex align-items-center justify-content-center bg-light" style="border-radius:8px!important;">
                                <i class="bx bx-buildings text-muted"></i>
                            </div>
                        @endif
                        <div class="flex-grow-1 min-width-0">
                            <div class="user-name">{{ Str::limit($partner->name, 22) }}</div>
                            <div class="user-email">{{ Str::limit($partner->address ?? '', 28) }}</div>
                        </div>
                        <div>
                            @if($partner->is_active)
                                <span class="badge" style="background:#dcfce7;color:#16a34a;font-size:0.68rem;">{{ __('Active') }}</span>
                            @else
                                <span class="badge" style="background:#f1f5f9;color:#64748b;font-size:0.68rem;">{{ __('Inactive') }}</span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="empty-state">
                        <i class="bx bx-buildings"></i>
                        <p>{{ __('No partners found') }}</p>
                    </li>
                    @endforelse
                </ul>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ================================================================
     MODALS (Vehicle + Partner detail)
     ================================================================ --}}
<div class="modal fade" id="vehicleDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" id="vehicle-modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-grow text-primary" role="status"></div>
                <p class="mt-3 text-muted fw-semibold">{{ __('Loading vehicle details...') }}</p>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="partnerDetailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content shadow-lg border-0" id="partner-modal-content">
            <div class="modal-body text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="mt-2 text-muted">{{ __('Loading partner information...') }}</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('page-script')
<script>
$(document).ready(function () {

    // ── Vehicle Modal ─────────────────────────────────────────────
    $(document).on('click', '.clickable-vehicle-row', function () {
        const id        = $(this).data('id');
        const modal     = new bootstrap.Modal(document.getElementById('vehicleDetailsModal'));
        const container = document.getElementById('vehicle-modal-content');

        container.innerHTML = `<div class="modal-body text-center py-5">
            <div class="spinner-grow text-primary" role="status"></div>
            <p class="mt-3 text-muted fw-semibold">{{ __('Fetching vehicle data...') }}</p>
        </div>`;
        modal.show();

        fetch(`{{ url('/app/vehicles') }}/${id}?modal=1`)
            .then(r => r.text())
            .then(html => { container.innerHTML = html; })
            .catch(() => { container.innerHTML = '<div class="modal-body text-center py-5 text-danger">{{ __('Error loading vehicle data.') }}</div>'; });
    });

    // ── Partner Modal ─────────────────────────────────────────────
    $(document).on('click', '.clickable-partner-item', function () {
        const id        = $(this).data('id');
        const modal     = new bootstrap.Modal(document.getElementById('partnerDetailsModal'));
        const container = document.getElementById('partner-modal-content');

        container.innerHTML = `<div class="modal-body text-center py-5">
            <div class="spinner-border text-primary" role="status"></div>
            <p class="mt-2 text-muted">{{ __('Loading partner information...') }}</p>
        </div>`;
        modal.show();

        fetch(`{{ url('/app/partners') }}/${id}?modal=1`)
            .then(r => r.text())
            .then(html => { container.innerHTML = html; })
            .catch(() => { container.innerHTML = '<div class="modal-body text-center py-5 text-danger">{{ __('Error loading partner data.') }}</div>'; });
    });

    // ── Animated KPI counters ─────────────────────────────────────
    function animateCounter(el) {
        const target = parseInt(el.textContent.replace(/[^0-9]/g, ''), 10);
        if (isNaN(target) || target === 0) return;
        let start = 0;
        const duration = 900;
        const step = Math.ceil(target / (duration / 16));
        el.textContent = '0';
        const timer = setInterval(function () {
            start += step;
            if (start >= target) {
                el.textContent = target.toLocaleString();
                clearInterval(timer);
            } else {
                el.textContent = start.toLocaleString();
            }
        }, 16);
    }

    document.querySelectorAll('.kpi-number').forEach(function (el) {
        // Only animate plain numbers
        if (/^\d+$/.test(el.textContent.trim())) {
            animateCounter(el);
        }
    });

});
</script>
@endsection
