@extends('layouts/contentNavbarLayout')

@section('title', 'Edit Permission')

@section('content')
<style>
  .form-card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(105, 108, 255, 0.08);
  }
  .form-header {
    background: linear-gradient(to right, #03c3ec, #009ef7);
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
    box-shadow: 0 0 0 3px rgba(3, 195, 236, 0.1);
    border-color: #03c3ec;
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
    box-shadow: 0 5px 15px rgba(3, 195, 236, 0.3);
  }
</style>

<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="mb-0">
        <span class="text-muted fw-light">Access Control / </span> 
        <span class="fw-bold text-info">Edit Permission</span>
      </h4>
      <a href="{{ route('admin.permissions.index') }}" class="btn btn-outline-secondary d-flex align-items-center">
        <i class="bx bx-arrow-back me-1"></i> Back to List
      </a>
    </div>

    <div class="card form-card">
      <div class="form-header text-center">
        <h5 class="mb-1 text-white">Modify Permission</h5>
        <p class="mb-0 text-white-50">Update the permission name for the <strong>{{ strtoupper($permission->guard_name) }}</strong> guard.</p>
      </div>
      <div class="card-body p-4 p-md-5">
        <form action="{{ route('admin.permissions.update', $permission->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="mb-4">
            <label class="form-label" for="permission-name">Permission Name</label>
            <div class="input-icon">
              <i class="bx bx-lock-alt"></i>
              <input type="text" class="form-control" id="permission-name" name="name" 
                value="{{ $permission->name }}" placeholder="Enter permission name" required />
            </div>
            @error('name')
              <div class="text-danger mt-1 small"><i class="bx bx-error-circle me-1"></i>{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label class="form-label d-block mb-2">Guard Environment (Locked)</label>
            <div class="d-flex align-items-center bg-light p-3 rounded-3">
              <div class="permission-icon me-3 bg-white shadow-sm" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; border-radius: 8px; color: #03c3ec;">
                <i class="bx bx-shield-quarter fs-4"></i>
              </div>
              <div>
                <span class="badge {{ $permission->guard_name === 'admin-guard' ? 'bg-label-danger' : 'bg-label-info' }} fw-bold">
                  {{ strtoupper($permission->guard_name) }}
                </span>
                <small class="text-muted d-block mt-1">Guard cannot be changed after creation to maintain integrity.</small>
              </div>
            </div>
          </div>

          <hr class="my-4 opacity-25">

          <div class="d-flex justify-content-end gap-3">
            <a href="{{ route('admin.permissions.index') }}" class="btn btn-label-secondary btn-premium px-4">Cancel</a>
            <button type="submit" class="btn btn-info btn-premium px-4 text-white">
              <i class="bx bx-save me-1"></i> Update Changes
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
