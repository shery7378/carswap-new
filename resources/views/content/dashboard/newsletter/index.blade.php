@extends('layouts/contentNavbarLayout')

@section('title', 'Newsletter Subscribers')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Newsletter /</span> Subscribers</h4>

@if(session('success'))
    <div class="alert alert-success alert-dismissible" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<!-- Basic Bootstrap Table -->
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="m-0">Subscribers List</h5>
  </div>
  <div class="table-responsive text-nowrap">
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Subscribed At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody class="table-border-bottom-0">
        @forelse($subscribers as $subscriber)
        <tr>
          <td>{{ $subscriber->id }}</td>
          <td>{{ $subscriber->name ?? 'N/A' }}</td>
          <td><strong>{{ $subscriber->email }}</strong></td>
          <td>{{ $subscriber->created_at->format('Y-m-d H:i') }}</td>
          <td>
            <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger shadow-none">
                    <i class="bx bx-trash me-1"></i> Delete
                </button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
            <td colspan="5" class="text-center">No subscribers found.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="card-footer pb-0">
      {{ $subscribers->links() }}
  </div>
</div>
<!--/ Basic Bootstrap Table -->
@endsection
