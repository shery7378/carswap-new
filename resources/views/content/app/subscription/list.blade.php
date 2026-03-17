@extends('layouts/contentNavbarLayout')

@section('title', 'Subscription List')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Subscription List</h5>
                <div class="d-flex gap-2 mt-3">
                    <button type="button" class="btn btn-primary waves-effect waves-light">
                        <i class="bx bx-plus me-1"></i> Create Subscription
                    </button>
                    <button type="button" class="btn btn-outline-secondary waves-effect">
                        <i class="bx bx-export me-1"></i> Export
                    </button>
                </div>
            </div>
            <div class="card-datatable table-responsive">
                <table class="dt-row table">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Next Billing</th>
                            <th>Duration</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscriptions as $subscription)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        @if($subscription->user->profile_photo_url)
                                        <img src="{{ $subscription->user->profile_photo_url }}" alt="Avatar" class="rounded-circle">
                                        @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($subscription->user->name ?? 'U', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $subscription->user->name ?? 'Unknown' }}</span>
                                        <br>
                                        <small class="text-muted">{{ $subscription->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-{{ $subscription->plan->color ?? 'primary' }}">{{ $subscription->plan->name ?? '-' }}</span></td>
                            <td>${{ $subscription->amount }}/{{ $subscription->plan->billing_period ?? 'month' }}</td>
                            <td><span class="badge bg-label-{{ $subscription->status === 'active' ? 'success' : 'warning' }}">{{ ucfirst($subscription->status) }}</span></td>
                            <td>{{ $subscription->next_billing_at ? $subscription->next_billing_at->format('M d, Y') : '-' }}</td>
                            <td>{{ $subscription->duration ?? '-' }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                                        @if($subscription->status === 'active')
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-pause me-1"></i> Pause</a>
                                        @else
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-play me-1"></i> Resume</a>
                                        @endif
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-x-circle me-1"></i> Cancel</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
