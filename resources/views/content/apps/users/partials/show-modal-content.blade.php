<div class="user-details-wrapper">
    <div class="card mb-4 border-0 shadow-sm overflow-hidden">
        <!-- HEADER: Matching Vehicles Design -->
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
            <div class="d-flex align-items-center">
                <div class="avatar avatar-md me-3">
                    @if($user->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="avatar" class="rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover;">
                    @else
                        <span class="avatar-initial rounded-circle bg-label-primary fw-bold border border-white shadow-sm">
                            {{ strtoupper(substr($user->first_name ?? 'U', 0, 1)) }}
                        </span>
                    @endif
                </div>
                <div>
                    <h5 class="mb-1 fw-bold outfit-font">{{ $user->first_name }} {{ $user->last_name }}</h5>
                    <div class="d-flex align-items-center flex-wrap gap-2">
                        <small class="text-muted"><i class="bx bx-envelope me-1"></i> {{ $user->email }}</small>
                        <span class="badge badge-center rounded-pill bg-label-secondary w-px-4 h-px-4 mx-1"></span>
                        <small class="text-muted"><i class="bx bx-id-card me-1"></i> ID: #{{ str_pad($user->id, 5, '0', STR_PAD_LEFT) }}</small>
                        <span class="badge badge-center rounded-pill bg-label-secondary w-px-4 h-px-4 mx-1"></span>
                        <small class="fw-bold text-{{ $user->status == 'active' ? 'success' : 'danger' }}"><i class="bx bx-check-circle me-1"></i> {{ ucfirst($user->status ?: 'active') }}</small>
                    </div>
                </div>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-secondary btn-sm shadow-none" data-bs-dismiss="modal">
                    <i class="bx bx-arrow-back me-1"></i> Back to List
                </button>
                <a href="{{ route('admin.web-users.edit', $user->id) }}" class="btn btn-primary btn-sm shadow-sm">
                    <i class="bx bx-edit-alt me-1"></i> Edit User
                </a>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="row g-0">
                <!-- Sidebar: User Snapshot -->
                <div class="col-md-5 border-end bg-light-soft p-4">
                    <div class="profile-image-card mb-4 text-center">
                        <div class="mb-3 position-relative d-inline-block">
                            @if($user->profile_picture)
                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="User Image" class="img-fluid rounded-4 shadow-sm" style="width: 250px; height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded-4 border" style="width: 250px; height: 250px;">
                                    <i class="bx bx-user text-muted display-1"></i>
                                </div>
                            @endif
                            <span class="position-absolute bottom-0 end-0 m-3 badge bg-white text-primary shadow-sm px-3 py-2 rounded-pill fw-bold border">
                                <i class="bx bx-shield-alt me-1"></i> {{ $user->role ?? 'Regular' }}
                            </span>
                        </div>
                    </div>

                    <!-- Quick Details List -->
                    <div class="user-stats-card p-4 bg-white rounded border shadow-xs">
                        <h6 class="fw-bold mb-3 small text-uppercase border-bottom pb-2 font-secondary">Engagement Overview</h6>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted small"><i class="bx bx-car me-2"></i>Total Postings</span>
                                <span class="fw-bold fs-5">{{ $totalPostings }}</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted small"><i class="bx bx-heart me-2"></i>Favorites Count</span>
                                <span class="fw-bold fs-5">{{ $totalFavorites }}</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted small"><i class="bx bx-trophy me-2"></i>Active Plan</span>
                                <span class="badge bg-label-info fw-bold">{{ $activePlan }}</span>
                            </div>
                            <div class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent py-3">
                                <span class="text-muted small"><i class="bx bx-calendar me-2"></i>Member Since</span>
                                <span class="fw-bold">{{ $user->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Social Presence -->
                    <div class="mt-4">
                        <h6 class="fw-bold mb-3 small text-uppercase px-2">Social presence</h6>
                        <div class="d-flex gap-2 px-1">
                            <a @if($user->facebook) href="{{ $user->facebook }}" target="_blank" @endif class="social-btn @if($user->facebook) active fb @endif" title="Facebook"><i class="bx bxl-facebook"></i></a>
                            <a @if($user->instagram) href="{{ $user->instagram }}" target="_blank" @endif class="social-btn @if($user->instagram) active ig @endif" title="Instagram"><i class="bx bxl-instagram"></i></a>
                            <a @if($user->youtube) href="{{ $user->youtube }}" target="_blank" @endif class="social-btn @if($user->youtube) active yt @endif" title="Youtube"><i class="bx bxl-youtube"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Main Content: Tabs -->
                <div class="col-md-7 p-4 bg-white">
                    <div class="nav-align-top">
                        <ul class="nav nav-tabs nav-fill mb-4" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#tab-user-info">
                                    <i class="bx bx-id-card fs-5 me-1"></i> Contact Info
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-user-postings">
                                    <i class="bx bx-list-ul fs-5 me-1"></i> User Postings
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#tab-user-security">
                                    <i class="bx bx-lock-alt fs-5 me-1"></i> Security
                                </button>
                            </li>
                        </ul>
                        <div class="tab-content border-0 p-0 shadow-none bg-transparent">
                            <!-- Contact Info Tab -->
                            <div class="tab-pane fade show active" id="tab-user-info" role="tabpanel">
                                <div class="p-4 border rounded bg-light-soft mb-4">
                                    <h6 class="fw-bold mb-3 border-bottom pb-2">Direct Contact Details</h6>
                                    <div class="row g-4">
                                        <div class="col-sm-6">
                                            <div class="p-3 bg-white border rounded shadow-xs">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Primary Phone</small>
                                                <span class="fs-5 fw-bold text-dark">{{ $user->phone ?? 'N/A' }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="p-3 bg-white border rounded shadow-xs">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">WhatsApp Access</small>
                                                <span class="badge bg-label-{{ $user->has_whatsapp ? 'success' : 'secondary' }} fs-6 mt-1">
                                                    {{ $user->has_whatsapp ? 'Connected' : 'Not Linked' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="p-3 bg-white border rounded shadow-xs">
                                                <small class="text-muted text-uppercase fw-bold d-block mb-1" style="font-size: 0.7rem;">Official Designation</small>
                                                <div class="d-flex align-items-center gap-2 mt-1">
                                                    <span class="badge bg-primary px-3 text-capitalize">{{ $user->role ?? 'Regular' }}</span>
                                                    @if($user->is_trader)
                                                        <span class="badge bg-info"><i class="bx bxs-badge-check me-1"></i>Verified Trader</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="px-2">
                                    <h6 class="fw-bold mb-3 border-bottom pb-2">Admin Notes</h6>
                                    <div class="bg-label-secondary p-3 rounded border border-light">
                                        User registered via Web UI. Current account status is <strong>{{ $user->status ?: 'active' }}</strong>. 
                                        No recent violations reported in the system.
                                    </div>
                                </div>
                            </div>

                            <!-- Postings Tab -->
                            <div class="tab-pane fade" id="tab-user-postings" role="tabpanel">
                                <div class="p-0 bg-white border rounded" style="min-height: 480px;">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-striped align-middle mb-0">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th class="ps-4">Vehicle Listing</th>
                                                    <th>Price</th>
                                                    <th class="text-end pe-4">Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($recentVehicles as $vehicle)
                                                    <tr>
                                                        <td class="ps-4">
                                                            <div class="d-flex align-items-center">
                                                                <div class="vehicle-thumb me-3 rounded border" style="background-image: url('{{ $vehicle->main_image_url ?? asset('assets/img/default-car.png') }}'); width: 45px; height: 45px; background-size: cover; background-position: center;"></div>
                                                                <div>
                                                                    <div class="fw-bold text-dark text-truncate" style="max-width: 150px;">{{ $vehicle->title }}</div>
                                                                    <small class="text-muted">{{ $vehicle->year }} &bull; {{ $vehicle->mileage }} km</small>
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td><span class="fw-bold text-primary">{{ number_format($vehicle->price) }} Ft</span></td>
                                                        <td class="text-end pe-4">
                                                            @php $vStatus = $vehicle->ad_status ?? 'pending'; @endphp
                                                            <span class="badge bg-label-{{ $vStatus == 'published' ? 'success' : ($vStatus == 'pending' ? 'warning' : 'danger') }} text-capitalize text-xs">
                                                                {{ $vStatus }}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="3" class="text-center py-5">
                                                            <i class="bx bx-car text-muted display-4 mb-3 d-block"></i>
                                                            <p class="text-muted">No vehicles listed by this user.</p>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <!-- Security Tab -->
                            <div class="tab-pane fade" id="tab-user-security" role="tabpanel">
                                <div class="p-4 bg-white border rounded" style="min-height: 480px;">
                                    <h6 class="fw-bold mb-4 border-bottom pb-2">Account Verification & Logs</h6>
                                    <div class="list-group list-group-flush">
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center py-4 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-{{ $user->email_verified_at ? 'success' : 'danger' }} me-3 p-2 rounded">
                                                    <i class="bx {{ $user->email_verified_at ? 'bx-badge-check' : 'bx-error-circle' }} fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Email Identity</h6>
                                                    <small class="text-muted">{{ $user->email_verified_at ? 'Verified on ' . $user->email_verified_at->format('M d, Y') : 'Identity not verified yet' }}</small>
                                                </div>
                                            </div>
                                            @if(!$user->email_verified_at)
                                                <button class="btn btn-sm btn-label-primary">Verify Manually</button>
                                            @else
                                                <span class="badge bg-success">Verified</span>
                                            @endif
                                        </div>
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center py-4 border-bottom">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-info me-3 p-2 rounded">
                                                    <i class="bx bx-time-five fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Recent Activity</h6>
                                                    <small class="text-muted">Last system access by user</small>
                                                </div>
                                            </div>
                                            <span class="fw-bold">{{ $user->updated_at->diffForHumans() }}</span>
                                        </div>
                                        <div class="list-group-item px-0 d-flex justify-content-between align-items-center py-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar bg-label-secondary me-3 p-2 rounded">
                                                    <i class="bx bx-key fs-3"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">Security Credentials</h6>
                                                    <small class="text-muted">Manually update user access password</small>
                                                </div>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-label-warning change-password-btn" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}">
                                                Change Password
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card-footer bg-light-soft border-top text-end p-4">
            <button type="button" class="btn btn-outline-secondary me-2 rounded-pill px-4" data-bs-dismiss="modal">
                <i class="bx bx-chevron-left me-1"></i> Close View
            </button>
            <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                <button type="button" class="btn btn-warning border-0 px-4 fw-bold change-password-btn" data-id="{{ $user->id }}" data-name="{{ $user->first_name }} {{ $user->last_name }}">Change Password</button>
                <a href="{{ route('admin.web-users.edit', $user->id) }}" class="btn btn-primary border-0 px-4 fw-bold">Edit User Profile</a>
            </div>
        </div>
    </div>
</div>

<style>
.bg-light-soft { background-color: #f9fbfc; }
.shadow-xs { box-shadow: 0 .125rem .25rem rgba(105, 108, 255, .05); }
.shadow-2xl { box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); }

.profile-image-card img { border: 8px solid white; box-shadow: 0 10px 30px rgba(0,0,0,0.1); }

.nav-tabs .nav-link {
    font-size: 0.95rem;
    padding: 1rem 1.25rem;
    font-weight: 500;
    color: #6c757d;
    border: none;
    border-bottom: 4px solid transparent;
}
.nav-tabs .nav-link.active {
    color: #696cff !important;
    background-color: transparent !important;
    border-bottom: 4px solid #696cff !important;
}

.social-btn {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f0f2f4;
    color: #a1aab2;
    transition: all 0.2s;
    text-decoration: none !important;
}
.social-btn.active { color: white; transform: translateY(-2px); box-shadow: 0 4px 8px rgba(0,0,0,0.1); }
.social-btn.fb.active { background: #1877F2; }
.social-btn.ig.active { background: #E4405F; }
.social-btn.yt.active { background: #FF0000; }

.font-secondary { font-family: 'Outfit', sans-serif; letter-spacing: 0.02em; }
.text-xs { font-size: 0.75rem; }
</style>
