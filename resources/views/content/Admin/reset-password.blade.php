@extends('layouts/blankLayout')

@section('title', 'Reset Password - Admin')

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
      <h4>Reset Password 🛡️</h4>
      <p class="sub-text">Please enter your new password below</p>

      <form action="{{ route('admin.password.update') }}" method="POST">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        
        <div class="mb-4">
          <label for="email" class="form-label">Email Address</label>
          <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $email ?? old('email') }}" readonly />
          @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4 form-password-toggle">
          <label class="form-label" for="password">New Password</label>
          <div class="input-group input-group-merge">
            <input type="password" id="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="············" autofocus />
          </div>
          @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
          @enderror
        </div>

        <div class="mb-4 form-password-toggle">
          <label class="form-label" for="password_confirmation">Confirm Password</label>
          <div class="input-group input-group-merge">
            <input type="password" id="password_confirmation" class="form-control" name="password_confirmation" placeholder="············" />
          </div>
        </div>

        <button class="btn btn-primary d-grid w-100">Reset Password</button>
      </form>
    </div>
  </div>
</div>
@endsection
