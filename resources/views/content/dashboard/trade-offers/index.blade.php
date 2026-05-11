@extends('layouts/contentNavbarLayout')

@section('title', __('Trade Offers'))

@section('content')
<h4 class="py-3 mb-4">
    <span class="text-muted fw-light">{{ __('Customer Support') }} /</span> {{ __('Trade Offers') }}
</h4>

<style>
    .table-responsive {
        overflow-x: auto !important;
    }
    .table th, .table td {
        padding: 0.55rem 0.5rem !important;
        font-size: 0.85rem !important;
        vertical-align: middle;
    }
    .table th {
        font-size: 0.75rem !important;
        text-transform: uppercase;
        letter-spacing: .5px;
        position: relative;
        padding-right: 30px !important;
        white-space: normal;
        line-height: 1.2;
    }

    /* Premium Action Buttons */
    .btn-action {
        width: 32px !important;
        height: 32px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        border-radius: 8px !important;
        padding: 0 !important;
        border: none !important;
        transition: all 0.2s ease !important;
        text-decoration: none !important;
    }
    .btn-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .btn-action.edit { background-color: #e0f7fa !important; color: #00bcd4 !important; }
    .btn-action.delete { background-color: #ffebee !important; color: #f44336 !important; }
    .btn-action.view { background-color: #f3e5f5 !important; color: #9c27b0 !important; }
    .btn-action i { font-size: 1.15rem !important; }
    
    .action-container {
        display: flex;
        gap: 8px;
        justify-content: flex-start;
    }
</style>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card">
    <h5 class="card-header">{{ __('Beérkezett csereajánlatok') }}</h5>
    <!-- TABLE VIEW -->
    <div class="table-responsive text-nowrap">
        <table class="table">
            <thead>
                <tr>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Cél Jármű') }}</th>
                    <th>{{ __('Feladó') }}</th>
                    <th>{{ __('Ajánlott Autó') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody class="table-border-bottom-0">
                @forelse($offers as $offer)
                    <tr>
                        <td>{{ $offer->created_at->formatDateTime() }}</td>
                        <td>
                            @if($offer->vehicle)
                                <a href="{{ route('admin.vehicles.edit', $offer->vehicle->id) }}" target="_blank">
                                    {{ $offer->vehicle->title }}
                                </a>
                            @else
                                <span class="text-danger">{{ __('Deleted vehicle') }}</span>
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
                            <span class="badge {{ $badgeClass }}">{{ __(ucfirst($offer->status)) }}</span>
                        </td>
                        <td>
                            <div class="action-container">
                                <a href="{{ route('admin.trade-offers.show', $offer->id) }}" class="btn-action view shadow-none" data-bs-toggle="tooltip" title="{{ __('View') }}">
                                    <i class="bx bx-show"></i>
                                </a>
                                <form action="{{ route('admin.trade-offers.destroy', $offer->id) }}" method="POST" onsubmit="return confirm('{{ __('Are you sure you want to delete this?') }}')" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-action delete shadow-none" data-bs-toggle="tooltip" title="{{ __('Delete') }}">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">{{ __('Nincsenek beérkezett csereajánlatok.') }}</td>
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
