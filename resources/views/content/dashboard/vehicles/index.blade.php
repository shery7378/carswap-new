@extends('layouts/contentNavbarLayout')

@section('title', 'Vehicles')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Vehicles List</h5>
                    <a href="{{ route('admin.vehicles.create') }}" class="btn btn-primary btn-sm">Add New Vehicle</a>
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
                                            @if($vehicle->is_featured)
                                                <span class="badge bg-label-warning">Featured</span>
                                            @endif
                                            <span class="badge bg-label-primary">Active</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                    data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item"
                                                        href="{{ route('admin.vehicles.edit', $vehicle->id) }}"><i
                                                            class="bx bx-edit-alt me-1"></i> Edit</a>
                                                    <form action="{{ route('admin.vehicles.destroy', $vehicle->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item"
                                                            onclick="return confirm('Are you sure?')"><i
                                                                class="bx bx-trash me-1"></i> Delete</button>
                                                    </form>
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