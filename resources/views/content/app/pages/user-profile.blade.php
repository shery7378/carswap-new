@extends('layouts/contentNavbarLayout')

@section('title', 'User Profile')

@section('content')
<div class="row">
    <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="avatar avatar-xl">
                        <img src="{{ asset('assets/img/avatars/1.png') }}" alt="Profile" class="rounded-circle">
                    </div>
                    <div class="dropdown">
                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                            <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu">
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                            <a class="dropdown-item" href="javascript:void(0);"><i class="bx bx-trash me-1"></i> Delete</a>
                        </div>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <h4 class="mb-1">John Doe</h4>
                    <p class="text-muted">Web Developer</p>
                    <div class="d-flex justify-content-center flex-wrap gap-2">
                        <span class="badge bg-label-primary">Premium</span>
                        <span class="badge bg-label-success">Verified</span>
                    </div>
                </div>
                <div class="d-flex justify-content-center my-4">
                    <div class="text-center me-4">
                        <h5 class="mb-0">156</h5>
                        <small class="text-muted">Projects</small>
                    </div>
                    <div class="text-center me-4">
                        <h5 class="mb-0">2.5k</h5>
                        <small class="text-muted">Followers</small>
                    </div>
                    <div class="text-center">
                        <h5 class="mb-0">89</h5>
                        <small class="text-muted">Following</small>
                    </div>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase fw-medium mb-3">About</h6>
                    <p class="text-muted">Experienced web developer with expertise in modern JavaScript frameworks and responsive design. Passionate about creating user-friendly interfaces and optimizing web applications for performance.</p>
                </div>
                <div class="mb-3">
                    <h6 class="text-uppercase fw-medium mb-3">Contact Information</h6>
                    <div class="mb-2">
                        <i class="bx bx-envelope me-2"></i>
                        <span class="text-muted">john.doe@example.com</span>
                    </div>
                    <div class="mb-2">
                        <i class="bx bx-phone me-2"></i>
                        <span class="text-muted">+1 (555) 123-4567</span>
                    </div>
                    <div class="mb-2">
                        <i class="bx bx-map me-2"></i>
                        <span class="text-muted">New York, USA</span>
                    </div>
                </div>
                <div>
                    <h6 class="text-uppercase fw-medium mb-3">Social Links</h6>
                    <div class="d-flex gap-2">
                        <a href="javascript:void(0);" class="btn btn-outline-primary btn-sm">
                            <i class="bx bxl-facebook"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-info btn-sm">
                            <i class="bx bxl-twitter"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-dark btn-sm">
                            <i class="bx bxl-github"></i>
                        </a>
                        <a href="javascript:void(0);" class="btn btn-outline-danger btn-sm">
                            <i class="bx bxl-instagram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
        <ul class="nav nav-tabs nav-lined-tab" role="tablist">
            <li class="nav-item">
                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab" data-bs-target="#profile-overview">
                    <i class="bx bx-user me-1"></i> Overview
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#profile-projects">
                    <i class="bx bx-briefcase me-1"></i> Projects
                </button>
            </li>
            <li class="nav-item">
                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab" data-bs-target="#profile-activity">
                    <i class="bx bx-trending-up me-1"></i> Activity
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <div class="tab-pane fade show active" id="profile-overview">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Profile Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Full Name</label>
                                <input type="text" class="form-control" value="John Doe" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" value="johndoe" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="john.doe@example.com" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <input type="text" class="form-control" value="+1 (555) 123-4567" readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Company</label>
                                <input type="text" class="form-control" value="Tech Solutions Inc." readonly>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Department</label>
                                <input type="text" class="form-control" value="Development" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Address</label>
                                <input type="text" class="form-control" value="123 Business Street, New York, NY 10001" readonly>
                            </div>
                            <div class="col-12">
                                <label class="form-label">Bio</label>
                                <textarea class="form-control" rows="3" readonly>Experienced web developer with expertise in modern JavaScript frameworks and responsive design.</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="profile-projects">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Projects</h5>
                    </div>
                    <div class="card-body">
                        <div class="timeline">
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-primary"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">E-commerce Platform</h6>
                                        <small class="text-muted">2 days ago</small>
                                    </div>
                                    <p class="text-muted mb-2">Built a full-featured e-commerce platform with React and Node.js</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-label-primary">React</span>
                                        <span class="badge bg-label-info">Node.js</span>
                                        <span class="badge bg-label-success">MongoDB</span>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-info"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Mobile App Design</h6>
                                        <small class="text-muted">1 week ago</small>
                                    </div>
                                    <p class="text-muted mb-2">Designed and developed a mobile application for iOS and Android</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-label-primary">React Native</span>
                                        <span class="badge bg-label-warning">Firebase</span>
                                    </div>
                                </div>
                            </div>
                            <div class="timeline-item">
                                <div class="timeline-point timeline-point-warning"></div>
                                <div class="timeline-content">
                                    <div class="d-flex justify-content-between">
                                        <h6 class="mb-1">Dashboard Analytics</h6>
                                        <small class="text-muted">2 weeks ago</small>
                                    </div>
                                    <p class="text-muted mb-2">Created an analytics dashboard with real-time data visualization</p>
                                    <div class="d-flex gap-2">
                                        <span class="badge bg-label-primary">Vue.js</span>
                                        <span class="badge bg-label-info">Chart.js</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="profile-activity">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Recent Activity</h5>
                    </div>
                    <div class="card-body">
                        <div class="activity-timeline">
                            <div class="activity">
                                <div class="activity-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Updated profile picture</h6>
                                            <p class="text-muted mb-0">Changed profile avatar</p>
                                        </div>
                                        <small class="text-muted">2 hours ago</small>
                                    </div>
                                </div>
                            </div>
                            <div class="activity">
                                <div class="activity-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Completed project</h6>
                                            <p class="text-muted mb-0">Finished E-commerce Platform project</p>
                                        </div>
                                        <small class="text-muted">2 days ago</small>
                                    </div>
                                </div>
                            </div>
                            <div class="activity">
                                <div class="activity-content">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">Added new skill</h6>
                                            <p class="text-muted mb-0">Added TypeScript to skills list</p>
                                        </div>
                                        <small class="text-muted">3 days ago</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
