@extends('layouts/blankLayout')

@section('title', 'Admin Login - Pages')

@section('page-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/scss/pages/page-auth.css') }}">
<style>
  body {
    background-color: #f4f7f6;
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: 'Outfit', sans-serif;
    margin: 0;
  }

  .authentication-wrapper {
    width: 100%;
    max-width: 450px;
    padding: 1.5rem;
  }

  .card {
    border: none;
    border-radius: 1.25rem;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    background: #fff;
    overflow: hidden;
    width: 100%;
  }

  .card-header-brand {
    background: #111827; /* Dark background for white logo */
    padding: 2.5rem;
    display: flex;
    justify-content: center;
    align-items: center;
  }

  .card-header-brand img {
    width: 200px;
    height: auto;
  }

  .card-body {
    padding: 3rem 2.5rem;
  }

  h4 {
    color: #111827;
    font-weight: 700;
    font-size: 1.5rem;
    margin-bottom: 0.5rem;
    text-align: center;
  }

  .sub-text {
    color: #6b7280;
    font-size: 0.95rem;
    margin-bottom: 2rem;
    text-align: center;
  }

  .form-label {
    color: #374151;
    font-weight: 600;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
  }

  .form-control {
    border: 1px solid #d1d5db;
    border-radius: 0.75rem;
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: all 0.2s;
  }

  .form-control:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
  }

  .btn-primary {
    background: #111827;
    border: none;
    border-radius: 0.75rem;
    padding: 0.9rem;
    color: #fff;
    font-weight: 600;
    width: 100%;
    margin-top: 1rem;
    transition: all 0.2s;
  }

  .btn-primary:hover {
    background: #1f2937;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  }

  .input-group-text {
    background: transparent;
    border: 1px solid #d1d5db;
    border-left: none;
    border-radius: 0 0.75rem 0.75rem 0;
    color: #6b7280;
    padding: 0.75rem 1rem;
  }

  /* Fix for input-group-merge in password field */
  .input-group-merge .form-control {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
    border-right: none;
  }

  .invalid-feedback {
    font-size: 0.85rem;
    color: #ef4444;
    margin-top: 0.5rem;
  }

  /* Remove default Bootstrap focus shadow for merge inputs */
  .form-password-toggle .input-group:focus-within .form-control,
  .form-password-toggle .input-group:focus-within .input-group-text {
    border-color: #3b82f6;
  }
</style>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="authentication-wrapper">
  <div class="card">
    <div class="card-header-brand">
      <a href="{{ url('/') }}">
        <img src="{{ asset('assets/img/logo/logo.webp') }}" alt="CarSwap Logo">
      </a>
    </div>

    <div class="card-body">
      <h4>{{ __('Admin Sign In') }}</h4>
      <p class="sub-text">{{ __('Access your carswap dashboard') }}</p>

      <form id="adminLoginForm" action="{{ route('admin-login-store') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="email" class="form-label">{{ __('Email Address') }}</label>
          <input
            type="email"
            class="form-control @error('email') is-invalid @enderror"
            id="email"
            name="email"
            placeholder="admin@carswap.com"
            value="{{ old('email') }}"
            autofocus />
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4 form-password-toggle">
          <div class="d-flex justify-content-between align-items-center">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <a href="{{ route('admin.password.request') }}" class="small text-primary fw-semibold">{{ __('Forgot Password?') }}</a>
          </div>
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

        <div class="pt-2">
          <button class="btn btn-primary" type="submit">{{ __('Login to Dashboard') }}</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
