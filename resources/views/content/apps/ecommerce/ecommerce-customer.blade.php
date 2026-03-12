@extends('layouts/contentNavbarLayout')

@section('title', 'eCommerce Customers')

@section('content')
  <!-- Content -->
  <div class="container-xxl flex-grow-1 container-p-y">
    <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">eCommerce /</span> Customers</h4>

    <!-- Basic Bootstrap Table -->
    <div class="card">
      <div class="card-header">
        <h5 class="mb-0">Customer List</h5>
      </div>
      <div class="card-body">
        <div class="table-responsive text-nowrap">
          <table class="table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Country</th>
                <th>Registered</th>
                <th>Status</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody class="table-border-bottom-0">
              @foreach($customers as $customer)
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    @if($customer->profile_picture)
                      <img src="{{ asset($customer->profile_picture) }}" alt="Avatar" class="rounded-circle me-3" width="40">
                    @else
                      <div class="avatar avatar-sm me-3">
                        <span class="avatar-initial rounded-circle bg-label-primary">{{ strtoupper(substr($customer->first_name, 0, 1)) }}</span>
                      </div>
                    @endif
                    <div>
                      <h6 class="mb-0">{{ $customer->first_name }} {{ $customer->last_name }}</h6>
                      <small class="text-muted">Subscriber</small>
                    </div>
                  </div>
                </td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? 'N/A' }}</td>
                <td>{{ $customer->country ?? 'N/A' }}</td>
                <td>{{ $customer->created_at->format('M d, Y') }}</td>
                <td><span class="badge bg-label-success">Active</span></td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a class="dropdown-item" href="{{ route('admin.users.edit', $customer->id) }}"><i class="bx bx-edit-alt me-2"></i> Edit</a>
                      <form action="{{ route('admin.users.destroy', $customer->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item text-danger"><i class="bx bx-trash me-2"></i> Delete</button>
                      </form>
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
  <!-- / Content -->
@endsection
