@extends('layouts/blankLayout')

@section('title', 'Forgot Password - Admin')

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
  .authentication-wrapper { width: 100%; max-width: 450px; padding: 1.5rem; }
  .card { border: none; border-radius: 1.25rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08); background: #fff; overflow: hidden; }
  .card-header-brand { background: #111827; padding: 2.5rem; display: flex; justify-content: center; }
  .card-header-brand img { width: 200px; height: auto; }
  .card-body { padding: 3rem 2.5rem; }
  h4 { color: #111827; font-weight: 700; font-size: 1.5rem; margin-bottom: 0.5rem; text-align: center; }
  .sub-text { color: #6b7280; font-size: 0.95rem; margin-bottom: 2rem; text-align: center; }
  .form-label { color: #374151; font-weight: 600; font-size: 0.875rem; margin-bottom: 0.5rem; }
  .form-control { border: 1px solid #d1d5db; border-radius: 0.75rem; padding: 0.75rem 1rem; }
  .btn-primary { background: #111827; border: none; border-radius: 0.75rem; padding: 0.9rem; font-weight: 600; width: 100%; margin-top: 1rem; }
  .btn-primary:hover { background: #1f2937; }
</style>
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="authentication-wrapper">
  <div class="card">
    <div class="card-header-brand">
      <a href="{{ url('/') }}"><img src="{{ asset('assets/img/logo/logo.webp') }}" alt="Logo"></a>
    </div>
    <div class="card-body">
      <h4>{{ __('Forgot Password?') }} 🔒</h4>
      <p class="sub-text">{{ __('Enter your email and we\'ll send you instructions to reset your password') }}</p>

      @if (session('status'))
        <div class="alert alert-success alert-dismissible" role="alert">
          {{ session('status') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <form action="{{ route('admin.password.email') }}" method="POST">
        @csrf
        <div class="mb-4">
          <label for="email" class="form-label">{{ __('Email Address') }}</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter your email" value="{{ old('email') }}" autofocus />
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <button class="btn btn-primary d-grid w-100">{{ __('Send Reset Link') }}</button>
      </form>
      <div class="text-center mt-4">
        <a href="{{ route('login') }}" class="d-flex align-items-center justify-content-center text-primary fw-semibold">
          <i class="bx bx-chevron-left scaleX-n1-rtl bx-sm"></i> {{ __('Back to login') }}
        </a>
      </div>
    </div>
  </div>
</div>
@endsection
