@extends('layouts/contentNavbarLayout')

@section('title', 'Payment History')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header border-bottom">
                <h5 class="card-title mb-0">Payment History</h5>
                <div class="d-flex gap-2 mt-3">
                    <div class="input-group" style="max-width: 300px;">
                        <input type="text" class="form-control" placeholder="Search payments...">
                        <button class="btn btn-outline-secondary" type="button">
                            <i class="bx bx-search"></i>
                        </button>
                    </div>
                    <button type="button" class="btn btn-outline-secondary waves-effect">
                        <i class="bx bx-filter me-1"></i> Filter
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
                            <th>Transaction ID</th>
                            <th>Customer</th>
                            <th>Plan</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td><code>#{{ $payment->transaction_id }}</code></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm me-3">
                                        @if($payment->user->profile_photo_url)
                                        <img src="{{ $payment->user->profile_photo_url }}" alt="Avatar" class="rounded-circle">
                                        @else
                                        <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($payment->user->name ?? 'U', 0, 1)) }}</span>
                                        @endif
                                    </div>
                                    <div>
                                        <span class="fw-medium">{{ $payment->user->name ?? 'Unknown' }}</span>
                                        <br>
                                        <small class="text-muted">{{ $payment->user->email ?? '' }}</small>
                                    </div>
                                </div>
                            </td>
                            <td><span class="badge bg-label-{{ $payment->plan->color ?? 'primary' }}">{{ $payment->plan->name ?? '-' }}</span></td>
                            <td>${{ $payment->amount }}</td>
                            <td><span class="badge bg-label-{{ $payment->status === 'completed' ? 'success' : ($payment->status === 'failed' ? 'danger' : 'warning') }}">{{ ucfirst($payment->status) }}</span></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="bx {{ $payment->payment_method === 'paypal' ? 'bxl-paypal' : ($payment->payment_method === 'bank_transfer' ? 'bx-bank' : 'bx-credit-card') }} me-2"></i>
                                    <span>{{ ucwords(str_replace('_', ' ', $payment->payment_method ?? 'Not Specified')) }}</span>
                                </div>
                            </td>
                            <td>{{ $payment->created_at->format('M d, Y') }}</td>
                            <td>
                                <div class="dropdown">
                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu">
                                        @if($payment->status === 'completed')
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-receipt me-1"></i> View Receipt</a>
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-download me-1"></i> Download Invoice</a>
                                        @elseif($payment->status === 'failed')
                                        <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-refresh me-1"></i> Retry</a>
                                        @endif
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
