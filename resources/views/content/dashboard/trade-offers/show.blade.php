@extends('layouts/contentNavbarLayout')

@section('title', 'Csereajánlat részletei')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Csereajánlatok /</span> Részletek: {{ $offer->brand }} {{ $offer->model }}
</h4>

<div class="row">
    <!-- Main Content -->
    <div class="col-md-8">
        <!-- Target Vehicle Summary -->
        <div class="card mb-4">
            <h5 class="card-header">Érintett hirdetés (Cél)</h5>
            <div class="card-body d-flex align-items-center">
                @if($offer->vehicle)
                    @if($offer->vehicle->main_image)
                        <img src="{{ Storage::disk('public')->url($offer->vehicle->main_image) }}" width="80" class="rounded me-3 shadow-sm">
                    @endif
                    <div>
                        <h6 class="mb-0"><a href="{{ route('admin.vehicles.edit', $offer->vehicle->id) }}" target="_blank">{{ $offer->vehicle->title }}</a></h6>
                        <p class="text-muted small mb-0">Hirdető: {{ $offer->vehicle->posted_by }}</p>
                    </div>
                @else
                    <div class="alert alert-danger w-100 mb-0">Hiba: A jármű már nem található az adatbázisban.</div>
                @endif
            </div>
        </div>

        <!-- Offered Car Content -->
        <div class="card mb-4">
            <h5 class="card-header d-flex justify-content-between align-items-center">
                <span>Az ajánlott autó részletei</span>
                <span class="badge bg-label-info">{{ $offer->brand }} {{ $offer->model }} ({{ $offer->year }})</span>
            </h5>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6 border-end">
                        <h6 class="text-muted text-uppercase small">Technikai Adatok</h6>
                        <table class="table table-sm table-borderless mt-2">
                            <tr><th>Brand:</th><td>{{ $offer->brand }}</td></tr>
                            <tr><th>Model:</th><td>{{ $offer->model }}</td></tr>
                            <tr><th>Year:</th><td>{{ $offer->year }}</td></tr>
                            <tr><th>Odometer:</th><td>{{ $offer->odometer }} km</td></tr>
                            <tr><th>Fuel/Gearbox:</th><td>{{ $offer->fuel_type }} / {{ $offer->gearbox_type }}</td></tr>
                            <tr><th>Chassis #:</th><td><code>{{ $offer->chassis_number }}</code></td></tr>
                        </table>
                    </div>
                    <div class="col-md-6 ps-md-4">
                        <h6 class="text-muted text-uppercase small">Állapot Információ</h6>
                        <table class="table table-sm table-borderless mt-2">
                            <tr><th>Külső állapot:</th><td>{{ $offer->exterior_condition }}</td></tr>
                            <tr><th>Belső állapot:</th><td>{{ $offer->interior_condition }}</td></tr>
                            <tr><th>Sérülés (Baleset):</th><td>{{ $offer->is_accident }}</td></tr>
                            <tr><th>Szín (Külső/Belső):</th><td>{{ $offer->exterior_color }} / {{ $offer->interior_color }}</td></tr>
                            <tr><th>Drive:</th><td>{{ $offer->drive_type }}</td></tr>
                        </table>
                    </div>
                </div>

                @if($offer->comment)
                    <div class="mt-4 pt-3 border-top">
                        <h6 class="text-muted text-uppercase small">Megjegyzés</h6>
                        <div class="bg-label-primary p-3 rounded" style="font-style: italic;">
                            {{ $offer->comment }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Photos -->
        @if(!empty($offer->photos) && count($offer->photos) > 0)
            <div class="card mb-4">
                <h5 class="card-header">Fényképek az ajánlott autóról</h5>
                <div class="card-body gallery-container">
                    <div class="row g-2">
                        @foreach($offer->photos as $path)
                            <div class="col-md-3 col-6">
                                <a href="{{ Storage::disk('public')->url($path) }}" target="_blank" class="gallery-item">
                                    <img src="{{ Storage::disk('public')->url($path) }}" class="img-fluid rounded border hover-shadow" style="height: 120px; object-fit: cover; width: 100%;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        
        @if($offer->video_url)
            <div class="card mb-4">
                <h5 class="card-header">Videó Link</h5>
                <div class="card-body">
                    <a href="{{ $offer->video_url }}" target="_blank" class="btn btn-outline-info w-100">
                        <i class="bx bxl-youtube me-2"></i> Megtekintés külső oldalon
                    </a>
                </div>
            </div>
        @endif
    </div>

    <!-- Contact & Status Sidebar -->
    <div class="col-md-4">
        <!-- Contact Info -->
        <div class="card mb-4">
            <h5 class="card-header">Kapcsolatfelvétel (Feladó)</h5>
            <div class="card-body">
                <div class="d-flex mb-3">
                    <div class="avatar avatar-md bg-label-primary me-3 pt-2 text-center rounded">
                        <i class="bx bx-user fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-0">{{ $offer->sender_first_name }} {{ $offer->sender_last_name }}</h6>
                        <small class="text-muted">{{ $offer->owner_name ?? 'Magánszemély' }}</small>
                    </div>
                </div>
                
                <hr class="my-3">
                
                <dl class="row mb-0">
                  <dt class="col-sm-4 text-muted small">E-mail</dt>
                  <dd class="col-sm-8 mb-2"><a href="mailto:{{ $offer->sender_email }}">{{ $offer->sender_email }}</a></dd>
                  
                  <dt class="col-sm-4 text-muted small">Telefon</dt>
                  <dd class="col-sm-8 mb-0"><a href="tel:{{ $offer->sender_phone }}">{{ $offer->sender_phone }}</a></dd>
                </dl>
            </div>
        </div>

        <!-- Manage Status -->
        <div class="card mb-4">
            <h5 class="card-header">Ajánlat Kezelése</h5>
            <div class="card-body">
                <form action="{{ route('admin.trade-offers.update-status', $offer->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-3">
                        <label class="form-label" for="status">Aktuális Állapot</label>
                        <select class="form-select" id="status" name="status">
                            <option value="pending" {{ $offer->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="viewed" {{ $offer->status === 'viewed' ? 'selected' : '' }}>Viewed</option>
                            <option value="accepted" {{ $offer->status === 'accepted' ? 'selected' : '' }}>Accepted</option>
                            <option value="rejected" {{ $offer->status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Statusz Frissítése</button>
                </form>

                <hr>

                <form action="{{ route('admin.trade-offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretné?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100 mt-2">
                        <i class="bx bx-trash me-1"></i> Törlés
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
