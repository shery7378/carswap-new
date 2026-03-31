@extends('layouts/layoutMaster')

@section('title', 'Newsletter Subscribers')

@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Newsletter /</span> Subscribers</h4>

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
            <div class="dropdown">
              <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown"><i class="ti ti-dots-vertical"></i></button>
              <div class="dropdown-menu">
                <form action="{{ route('admin.newsletter.destroy', $subscriber->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subscriber?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="dropdown-item text-danger"><i class="ti ti-trash me-1"></i> Delete</button>
                </form>
              </div>
            </div>
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
