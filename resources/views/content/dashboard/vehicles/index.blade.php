@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vehicles List</h5>
                    <div class="d-flex align-items-center">
                        <form action="{{ route('admin.vehicles.index') }}" method="GET" class="me-3">
                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                <option value="">All Statuses</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
                        </form>
                        @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('create-vehicles', 'admin-guard'))
                        <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">Add New Vehicle</a>
                        @endif
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Thumbnail</th>
                                    <th>Vehicle</th>
                                    <th>User</th>
                                    <th>Year</th>
                                    <th>Price</th>
                                    <th>Details</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody class="table-border-bottom-0">
                                @forelse($vehicles as $vehicle)
                                    <tr>
                                        <td>
                                            @if($vehicle->main_image)
                                                <img src="{{ asset('storage/' . $vehicle->main_image) }}"
                                                    alt="{{ $vehicle->title }}" width="50" class="rounded">
                                            @else
                                                <span class="badge bg-secondary">No Image</span>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $vehicle->title }}</strong><br>
                                            <small class="text-muted">{{ optional($vehicle->brand)->name }}
                                                {{ optional($vehicle->model)->name }}</small>
                                        </td>
                                        <td>
                                            @if($vehicle->user)
                                                {{ $vehicle->user->first_name }} {{ $vehicle->user->last_name }}
                                            @else
                                                <span class="text-muted">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $vehicle->year }}</td>
                                        <td>{{ number_format($vehicle->price, 0, ',', ' ') }} Ft</td>
                                        <td>
                                            <small>
                                                Fuel: {{ optional($vehicle->fuelType)->name }}<br>
                                                Trans: {{ optional($vehicle->transmission)->name }}<br>
                                                Mileage: {{ $vehicle->mileage }} km
                                            </small>
                                        </td>
                                        <td>
                                            @if ($vehicle->is_featured)
                                                <span class="badge bg-label-warning me-1">Featured</span>
                                            @endif

                                            @php
                                                $statusClass = match ($vehicle->ad_status) {
                                                    'published' => 'bg-label-success',
                                                    'pending' => 'bg-label-warning',
                                                    'rejected' => 'bg-label-danger',
                                                    'draft' => 'bg-label-secondary',
                                                    default => 'bg-label-primary',
                                                };
                                            @endphp
                                            @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                            <div class="btn-group">
                                                <button type="button"
                                                    class="btn btn-sm {{ str_replace('bg-label', 'btn-outline', $statusClass) }} dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    {{ ucfirst($vehicle->ad_status) }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="ad_status" value="published">
                                                            <button type="submit" class="dropdown-item">Approve /
                                                                Publish</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="ad_status" value="rejected">
                                                            <button type="submit" class="dropdown-item text-danger">Reject
                                                                / Hide</button>
                                                        </form>
                                                    </li>
                                                    <li>
                                                        <form action="{{ route('admin.vehicles.update-status', $vehicle->id) }}"
                                                            method="POST">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="hidden" name="ad_status" value="pending">
                                                            <button type="submit" class="dropdown-item">Set to
                                                                Pending</button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                            @else
                                                <span class="badge {{ $statusClass }}">{{ ucfirst($vehicle->ad_status) }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard'))
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.vehicles.edit', $vehicle->id) }}"><i
                                                            class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    @endif
                                                    
                                                    @if(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard'))
                                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure?')"><i
                                                                class="bx bx-trash me-1"></i> Delete</button>
                                                    </form>
                                                    @endif

                                                    @if(!(auth('admin-guard')->user()->hasRole('super-admin') || auth('admin-guard')->user()->hasPermissionTo('edit-vehicles', 'admin-guard') || auth('admin-guard')->user()->hasPermissionTo('delete-vehicles', 'admin-guard')))
                                                        <span class="dropdown-item disabled">No permissions</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No vehicles found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-3">
                        {{ $vehicles->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection