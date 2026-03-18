@extends('layouts/contentNavbarLayout')

@section('title', 'Add Permission')

@section('content')
<style>
  .form-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(105, 108, 255, 0.08);
  }
  .form-header {
    background: linear-gradient(to right, #696cff, #8e91ff);
    color: white;
    border-radius: 16px 16px 0 0;
    padding: 24px;
  }
  .form-label {
    font-weight: 600;
    color: #566a7f;
    margin-bottom: 8px;
  }
  .form-control, .form-select {
    border-radius: 10px;
    padding: 12px 16px;
    border: 1px solid #d9dee3;
    transition: all 0.2s;
  }
  .form-control:focus, .form-select:focus {
    box-shadow: 0 0 0 3px rgba(105, 108, 255, 0.1);
    border-color: #696cff;
  }
  .input-icon {
    position: relative;
  }
  .input-icon i {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #a1acb8;
  }
  .input-icon .form-control {
    padding-left: 40px;
  }
  .btn-premium {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    letter-spacing: 0.5px;
    transition: all 0.3s;
  }
  .btn-premium:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(105, 108, 255, 0.3);
  }
</style>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">
        <span class="text-muted fw-light">Access Control / </span> 
        <span class="fw-bold text-primary">Add Permission</span>
      </h4>
      <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
        <i class="bx bx-arrow-back me-1"></i> Back to List
      </a>
    </div>

    <div class="card form-card">
      <div class="form-header text-center">
        <h5 class="mb-1 text-white">Create New Permission</h5>
        <p class="mb-0 text-white-50">Specify the permission name and its security guard environment.</p>
      </div>
      <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.permissions.store') }}" method="POST">
          @csrf
          
          <div class="mb-4">
            <label class="form-label" for="permission-name text-uppercase">Permission Name</label>
            <div class="input-icon">
              <i class="bx bx-lock-alt"></i>
              <input type="text" class="form-control" id="permission-name" name="name" 
                placeholder="e.g. manage-users, view-reports" value="{{ old('name') }}" required />
            </div>
            @error('name')
              <div class="text-danger mt-1 small"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
            @enderror
            <small class="text-muted mt-2 d-block">Use kebab-case or snake_case for consistency (e.g. `edit-settings`).</small>
          </div>

          <div class="mb-4">
            <label class="form-label" for="guard-name">Security Guard Context</label>
            <div class="input-icon">
              <i class="bx bx-shield-quarter"></i>
              <select class="form-select ps-5" id="guard-name" name="guard_name">
                <option value="admin-guard" {{ old('guard_name') == 'admin-guard' ? 'selected' : '' }}>Administrative Panel (admin-guard)</option>
                <option value="web" {{ old('guard_name') == 'web' ? 'selected' : '' }}>Customer Frontend (web)</option>
              </select>
            </div>
            @error('guard_name')
              <div class="text-danger mt-1 small"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
            @enderror
          </div>

          <hr class="my-4 opacity-25">

          <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-label-secondary btn-premium px-4">Cancel</a>
            <button type="submit" class="btn btn-primary btn-premium px-4">
              <i class="bx bx-check-circle me-1"></i> Create Permission
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
