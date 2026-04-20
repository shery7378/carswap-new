@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Web User')

@section('content')
<style>
  .user-form-card { border:none; border-radius:14px; box-shadow:0 4px 24px rgba(0,0,0,0.06); }
  .section-title {
    font-size:13px; font-weight:700; color:#696cff; text-transform:uppercase;
    letter-spacing:.6px; margin-bottom:18px; display:flex; align-items:center; gap:8px;
  }
  .form-control, .form-select { border-radius:8px; padding:10px 15px; border:1px solid #d9dee3; }
  .form-control:focus { border-color:#696cff; box-shadow:0 0 0 .2rem rgba(105,108,255,.1); }
</style>

<div class="d-flex align-items-center mb-4">
  <a href="{{ route('admin.web-users.index') }}" class="btn btn-label-secondary btn-icon me-3 shadow-sm rounded-circle">
    <i class="bx bx-chevron-left"></i>
  </a>
  <h4 class="mb-0">
    <span class="text-muted fw-light">Web Users /</span>
    <span class="fw-bold text-primary">Edit User: {{ $user->first_name }} {{ $user->last_name }}</span>
  </h4>
</div>

<div class="row">
  <div class="col-xl-12">
    <div class="card user-form-card mb-4">
      <div class="card-body p-4">
        <form action="{{ route('admin.web-users.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="section-title"><i class="bx bx-user-circle fs-5"></i> Personal Information</div>
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">First Name</label>
              <input type="text" class="form-control" name="first_name" value="{{ old('first_name', $user->first_name) }}" required />
              @error('first_name')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Last Name</label>
              <input type="text" class="form-control" name="last_name" value="{{ old('last_name', $user->last_name) }}" required />
              @error('last_name')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Email Address</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                <input type="email" class="form-control" name="email" value="{{ old('email', $user->email) }}" required />
              </div>
              @error('email')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Phone Number</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-phone"></i></span>
                <input type="text" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}" />
              </div>
              @error('phone')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
            </div>
          </div>

          <div class="section-title"><i class="bx bx-cog fs-5"></i> Security & Status</div>
          <div class="row mb-4">
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">Change Password</label>
              <div class="input-group input-group-merge">
                <span class="input-group-text"><i class="bx bx-lock-alt"></i></span>
                <input type="password" class="form-control" name="password" placeholder="············" />
              </div>
              <small class="text-muted">Leave blank to keep current password.</small>
              @error('password')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6 mb-3">
              <label class="form-label fw-bold">User Status</label>
              <select name="status" class="form-select" required>
                <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="banned" {{ old('status', $user->status) == 'banned' ? 'selected' : '' }}>Banned</option>
              </select>
            </div>
          </div>

          <div class="row mt-5">
            <div class="col-12 d-flex justify-content-end gap-3">
              <a href="{{ route('admin.web-users.index') }}" class="btn btn-outline-secondary px-4">Cancel</a>
              <button type="submit" class="btn btn-primary px-5 shadow-sm">
                <i class="bx bx-save me-1"></i> Update User
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
