@extends('layouts/contentNavbarLayout')

@section('title', 'Csereajánlatok')

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">Ügyfélszolgálat /</span> Csereajánlatok
</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">Beérkezett csereajánlatok</h5>
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>Dátum</th>
                    <th>Cél Jármű</th>
                    <th>Feladó</th>
                    <th>Ajánlott Autó</th>
                    <th>Státusz</th>
                    <th>Műveletek</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($offers as $offer)
                    <tr>
                        <td>{{ $offer->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            @if($offer->vehicle)
                                <a href="{{ route('admin.vehicles.edit', $offer->vehicle->id) }}" target="_blank">
                                    {{ $offer->vehicle->title }}
                                </a>
                            @else
                                <span class="text-danger">Törölt jármű</span>
                            @endif
                        </td>
                        <td>
                            {{ $offer->sender_first_name }} {{ $offer->sender_last_name }}<br>
                            <small class="text-muted">{{ $offer->sender_email }}</small>
                        </td>
                        <td>{{ $offer->brand }} {{ $offer->model }} ({{ $offer->year }})</td>
                        <td>
                            @php
                                $badgeClass = [
                                    'pending' => 'bg-label-warning',
                                    'viewed' => 'bg-label-info',
                                    'accepted' => 'bg-label-success',
                                    'rejected' => 'bg-label-danger',
                                ][$offer->status] ?? 'bg-label-secondary';
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ ucfirst($offer->status) }}</span>
                        </td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('admin.trade-offers.show', $offer->id) }}" class="btn btn-sm btn-icon btn-outline-primary me-2">
                                    <i class="bx bx-show"></i>
                                </a>
                                <form action="{{ route('admin.trade-offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('Biztosan törölni szeretné?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-icon btn-outline-danger shadow-none">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Nincsenek beérkezett csereajánlatok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer p-2">
        {{ $offers->links() }}
    </div>
</div>
@endsection
