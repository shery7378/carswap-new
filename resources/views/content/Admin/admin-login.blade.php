@extends('layouts/blankLayout')

@section('title', 'Admin Login - Pages')

@section('page-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/scss/pages/page-auth.css') }}">
@endsection

@section('content')
<div class="container-xxl">
  <div class="authentication-wrapper authentication-basic container-p-y">
    <div class="authentication-inner">
      <div class="card px-sm-6 px-0">
        <div class="card-body">
          <!-- <div class="app-brand justify-content-center">
            <a href="{{ url('/') }}" class="app-brand-link gap-2">
              <span class="app-brand-logo demo">@include('_partials.macros')</span>
              <span class="app-brand-text demo text-heading fw-bold">{{ config('variables.templateName') }}</span>
            </a>
          </div> -->

          <h4 class="mb-1">Admin Sign In</h4>
          <p class="mb-6">Sign in to access the admin dashboard.</p>

          <form id="adminLoginForm" class="mb-6" action="{{ route('admin-login-store') }}" method="POST">
            @csrf
            <div class="mb-6">
              <label for="email" class="form-label">Admin Email</label>
              <input
                type="email"
                class="form-control @error('email') is-invalid @enderror"
                id="email"
                name="email"
                placeholder="admin@example.com"
                value="{{ old('email') }}"
                autofocus />
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-6 form-password-toggle">
              <label class="form-label" for="password">Password</label>
              <div class="input-group input-group-merge">
                <input
                  type="password"
                  id="password"
                  class="form-control @error('password') is-invalid @enderror"
                  name="password"
                  placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                  aria-describedby="password" />
                <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                @error('password')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>

            <div class="mb-6">
              <button class="btn btn-primary d-grid w-100" type="submit">Admin Login</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
