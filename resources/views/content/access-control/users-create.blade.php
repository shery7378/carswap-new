@extends('layouts/contentNavbarLayout')

@section('title', 'Add Administrator')

@section('content')
<style>
  .admin-form-card {
    border: none;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
  }
  .section-title {
    font-size: 14px;
    font-weight: 700;
    color: #696cff;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
  }
  .section-title i {
    margin-right: 8px;
    font-size: 18px;
  }
  .form-control, .form-select {
    border-radius: 8px;
    padding: 10px 15px;
    border: 1px solid #d9dee3;
  }
  .form-control:focus {
    border-color: #696cff;
    box-shadow: 0 0 0 0.2rem rgba(105, 108, 255, 0.1);
  }
  .checkbox-group {
    background: #fcfcfd;
    border: 1px solid #f0f2f4;
    border-radius: 10px;
    padding: 20px;
  }
  .permission-card {
    transition: all 0.2s;
    border-radius: 8px;
    padding: 8px;
  }
  .permission-card:hover {
    background: #f0f2f4;
  }
</style>

<div class="d-flex align-items-center mb-4">
  <a href="{{ route('admin.users.index') }}" class="btn btn-label-secondary btn-icon me-3 shadow-sm rounded-circle">
    <i class="bx bx-chevron-left"></i>
  </a>
  <h4 class="mb-0">
    <span class="text-muted fw-light">Access Control / Admins /</span> 
    <span class="fw-bold">Create Administrator</span>
  </h4>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card admin-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.users.store') }}" method="POST">
          @csrf
          
          <!-- Basic Info Section -->
          <div class="section-title">
            <i class="bx bx-user-circle"></i> Basic Information
          </div>
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="first_name">First Name</label>
              <input type="text" class="form-control" id="first_name" name="first_name" placeholder="John" value="{{ old('first_name') }}" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="last_name">Last Name</label>
              <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Doe" value="{{ old('last_name') }}" required />
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="email">Email Address</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                <input type="email" class="form-control" id="email" name="email" placeholder="john.doe@example.com" value="{{ old('email') }}" required />
              </div>
              @error('email')
                <div class="text-danger mt-1 small">{{ $message }}</div>
              @enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold" for="password">Security Password</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="············" required />
              </div>
            </div>
          </div>

          <!-- Roles Section -->
          <div class="section-title mt-4">
            <i class="bx bx-shield-quarter"></i> Accountability & Roles
          </div>
          <div class="checkbox-group mb-4">
            <p class="text-muted small mb-3">Assign administrative roles to define baseline access levels.</p>
            <div class="row">
              @foreach($roles as $role)
              <div class="col-md-3 mb-2">
                <div class="form-check custom-option custom-option-basic">
                  <label class="form-check-label custom-option-content" for="role-{{ $role->id }}">
                    <input class="form-check-input" type="checkbox" name="roles[]" value="{{ $role->name }}" id="role-{{ $role->id }}">
                    <span class="custom-option-header">
                      <span class="h6 mb-0">{{ $role->name }}</span>
                    </span>
                  </label>
                </div>
              </div>
              @endforeach
            </div>
          </div>

          <!-- Direct Permissions Section -->
          <div class="section-title mt-4">
            <i class="bx bx-key"></i> Granular Permissions
          </div>
          <div class="checkbox-group mb-4">
            <p class="text-muted small mb-3">Grant specific extra permissions that act as overrides or additions to the assigned roles.</p>
            <div class="row">
              @foreach($permissions as $permission)
              <div class="col-md-4 col-lg-3">
                <div class="permission-card">
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $permission->name }}" id="perm-{{ $permission->id }}">
                    <label class="form-check-label fw-medium h6 mb-0" for="perm-{{ $permission->id }}">
                      {{ $permission->name }}
                    </label>
                  </div>
                </div>
              </div>
              @endforeach
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-12 d-flex justify-content-end gap-3">
              <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary px-4">Discard</a>
              <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bx bx-check-circle me-1"></i> Finalize & Create Admin
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
