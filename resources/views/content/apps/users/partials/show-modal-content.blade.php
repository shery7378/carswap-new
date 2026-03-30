<div class="modal-header border-bottom">
    <div class="d-flex align-items-center">
        <div class="avatar avatar-md me-3">
             <span class="avatar-initial rounded-circle bg-label-info border shadow-xs">
                {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
            </span>
        </div>
        <div>
            <h5 class="modal-title fw-bold mb-0 text-dark">{{ $user->name }}</h5>
            <small class="text-muted small d-block">
                <i class="bx bx-envelope me-1"></i> {{ $user->email }} 
                <span class="mx-1 text-light">|</span> 
                <i class="bx bx-id-card me-1"></i> ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
            </small>
        </div>
    </div>
    <div class="ms-auto d-flex align-items-center gap-2">
        @if($user->status == 'active')
            <span class="badge bg-label-success d-none d-sm-inline-flex align-items-center"><i class="bx bx-check me-1"></i> Active</span>
        @elseif($user->status == 'inactive')
            <span class="badge bg-label-warning d-none d-sm-inline-flex align-items-center"><i class="bx bx-time me-1"></i> Inactive</span>
        @else
            <span class="badge bg-label-danger d-none d-sm-inline-flex align-items-center"><i class="bx bx-block me-1"></i> Banned</span>
        @endif
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
</div>

<div class="modal-body p-0">
    <div class="row g-0">
        <!-- Sidebar Summary -->
        <div class="col-md-5 border-end bg-light p-4">
            <div class="text-center mb-4">
                <div class="avatar-container rounded-circle border shadow-sm p-1 bg-white mb-3 d-inline-block overflow-hidden" style="width: 120px; height: 120px;">
                    <img src="{{ $user->profile_picture ? asset('storage/' . $user->profile_picture) : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=696cff&color=fff&size=200' }}" class="img-fluid rounded-circle" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                <h6 class="fw-bold text-dark mb-1">{{ $user->name }}</h6>
                <p class="text-muted small mb-3">{{ $user->role ?? 'Regular User' }}</p>
                
                <div class="d-grid gap-2">
                    <a href="mailto:{{ $user->email }}" class="btn btn-sm btn-outline-primary">
                        <i class="bx bx-envelope me-1"></i> Send Email
                    </a>
                </div>
            </div>

            <div class="user-stats-section">
                <h6 class="fw-bold mb-3 small text-uppercase text-muted border-bottom pb-1">Activity Snapshot</h6>
                <div class="row g-2">
                    <div class="col-6">
                        <div class="p-2 border rounded bg-white shadow-xs text-center">
                            <small class="text-muted d-block smaller">Joined Date</small>
                            <span class="fw-bold text-dark">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="p-2 border rounded bg-white shadow-xs text-center">
                            <small class="text-muted d-block smaller">Last Seen</small>
                            <span class="fw-bold text-dark">{{ $user->last_seen ? \Carbon\Carbon::parse($user->last_seen)->diffForHumans(null, true) : 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-7">
            <div class="nav-align-top h-100">
                <ul class="nav nav-tabs nav-fill rounded-0 border-bottom" role="tablist">
                    <li class="nav-item">
                        <button type="button" class="nav-link active py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#user-modal-info">
                            User Profile
                        </button>
                    </li>
                    <li class="nav-item">
                        <button type="button" class="nav-link py-3 fw-bold" role="tab" data-bs-toggle="tab" data-bs-target="#user-modal-activity">
                            Recent History
                        </button>
                    </li>
                </ul>
                <div class="tab-content border-0 shadow-none bg-transparent p-4 custom-scrollbar" style="max-height: 400px; overflow-y: auto;">
                    <!-- Profile Info -->
                    <div class="tab-pane fade show active" id="user-modal-info" role="tabpanel">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="small text-muted uppercase fw-bold mb-1 d-block">Full Name</label>
                                <p class="fw-semibold text-dark border-bottom pb-1">{{ $user->name }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="small text-muted uppercase fw-bold mb-1 d-block">Role / Type</label>
                                <p class="fw-semibold text-dark border-bottom pb-1">{{ $user->role ?? 'Standard User' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="small text-muted uppercase fw-bold mb-1 d-block">Phone Number</label>
                                <p class="fw-semibold text-dark border-bottom pb-1">{{ $user->phone ?? 'Not provided' }}</p>
                            </div>
                            <div class="col-sm-6">
                                <label class="small text-muted uppercase fw-bold mb-1 d-block">Account Status</label>
                                <p class="fw-semibold text-dark border-bottom pb-1">{{ ucfirst($user->status) }}</p>
                            </div>
                            <div class="col-12">
                                <label class="small text-muted uppercase fw-bold mb-1 d-block">Personal Bio / Notes</label>
                                <div class="bg-light p-3 rounded border text-muted small">
                                    {{ $user->bio ?: 'No personal biography or notes available for this user.' }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity (Placeholder for real data) -->
                    <div class="tab-pane fade" id="user-modal-activity" role="tabpanel">
                        <div class="timeline p-2">
                             <div class="timeline-item d-flex mb-3">
                                <div class="flex-shrink-0 avatar avatar-xs me-3">
                                    <span class="avatar-initial rounded-circle bg-label-primary"><i class="bx bx-log-in small"></i></span>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between flex-wrap mb-1">
                                        <h6 class="mb-0 fw-bold small text-dark">Last Successful Login</h6>
                                        <small class="text-muted">{{ $user->last_seen ? \Carbon\Carbon::parse($user->last_seen)->format('H:i, d M') : 'Unknown' }}</small>
                                    </div>
                                    <p class="mb-0 small text-muted">User authenticated successfully from dashboard.</p>
                                </div>
                            </div>
                            <div class="timeline-item d-flex">
                                <div class="flex-shrink-0 avatar avatar-xs me-3">
                                    <span class="avatar-initial rounded-circle bg-label-info"><i class="bx bx-user-check small"></i></span>
                                </div>
                                <div class="timeline-event">
                                    <div class="d-flex justify-content-between flex-wrap mb-1">
                                        <h6 class="mb-0 fw-bold small text-dark">Account Registration</h6>
                                        <small class="text-muted">{{ $user->created_at->format('H:i, d M Y') }}</small>
                                    </div>
                                    <p class="mb-0 small text-muted">Member officially joined the platform.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal-footer border-top bg-white p-3">
    <a href="{{ route('admin.users.view', $user->id) }}" class="btn btn-outline-primary btn-sm me-auto px-3">
        <i class="bx bx-user me-1"></i> Full Profile
    </a>
    <button type="button" class="btn btn-label-secondary btn-sm" data-bs-dismiss="modal">Close</button>
    <button type="button" class="btn btn-primary btn-sm px-4 shadow">
        <i class="bx bx-edit-alt me-1"></i> Quick Action
    </button>
</div>

<style>
.shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.smaller { font-size: 0.7rem; }
.uppercase { text-transform: uppercase; letter-spacing: 0.5px; }

.custom-scrollbar::-webkit-scrollbar { width: 4px; }
.custom-scrollbar::-webkit-scrollbar-thumb { background: #e0e0e0; border-radius: 10px; }

.nav-tabs .nav-link { color: #8592a3; border-bottom: 3px solid transparent !important; }
.nav-tabs .nav-link.active { color: #696cff !important; border-bottom: 3px solid #696cff !important; background: transparent !important; }

.timeline-event { border-left: 2px solid #ebedf0; padding-left: 20px; position: relative; }
.timeline-event:before { content: ''; position: absolute; left: -6px; top: 5px; width: 10px; height: 10px; border-radius: 50%; background: #696cff; border: 2px solid #fff; }
</style>
