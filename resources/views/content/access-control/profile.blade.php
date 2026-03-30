@extends('layouts/contentNavbarLayout')

@section('title', 'My Profile Settings')

@section('content')
<div class="row">
    <div class="col-md-12">
        <h4 class="fw-bold py-3 mb-4">
            <span class="text-muted fw-light">Access Control /</span> Account Settings
        </h4>

        <div class="row">
            <!-- Profile Details -->
            <div class="col-md-12">
                <div class="card mb-4">
                    <h5 class="card-header">Profile Details</h5>
                    <!-- Account -->
                    <div class="card-body">
                        <form id="formAccountSettings" method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex align-items-start align-items-sm-center gap-4 border-bottom pb-4 mb-4">
                                @if($user->profile_picture)
                                    <img src="{{ $user->getAvatarUrl() }}" alt="user-avatar" class="d-block rounded-circle" height="100" width="100" id="uploadedAvatar" style="object-fit: cover; border: 3px solid #e9ecef;" />
                                @else
                                    <img src="{{ $user->getAvatarUrl() }}" alt="user-avatar" class="d-block rounded-circle" height="100" width="100" id="uploadedAvatar" style="object-fit: cover; border: 3px solid #e9ecef;" />
                                    <div id="placeholderAvatar" class="d-none"></div>
                                @endif

                                <div class="button-wrapper">
                                    <label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">
                                        <span class="d-none d-sm-block">Upload new photo</span>
                                        <i class="bx bx-upload d-block d-sm-none"></i>
                                        <input type="file" id="upload" name="profile_picture" class="account-file-input" hidden accept="image/png, image/jpeg" />
                                    </label>
                                    <button type="button" class="btn btn-outline-secondary account-image-reset mb-4">
                                        <i class="bx bx-reset d-block d-sm-none"></i>
                                        <span class="d-none d-sm-block">Reset</span>
                                    </button>
                                    <p class="text-muted mb-0">Allowed JPG, GIF or PNG. Max size of 800K</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-md-6">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input class="form-control" type="text" id="first_name" name="first_name" value="{{ old('first_name', $user->first_name) }}" autofocus required />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input class="form-control" type="text" name="last_name" id="last_name" value="{{ old('last_name', $user->last_name) }}" required />
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input class="form-control" type="email" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="john.doe@example.com" required />
                                    @error('email')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Gender</label>
                                    <select name="gender" class="form-select">
                                        <option value="male" {{ ($user->gender ?? 'male') === 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ ($user->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>
                                <div class="mb-3 col-md-3">
                                    <label class="form-label">Role</label>
                                    <input class="form-control bg-light" type="text" value="{{ $user->roles->pluck('name')->join(', ') }}" readonly />
                                    <small class="text-muted italic">Role cannot be changed by yourself.</small>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary me-2 shadow-sm">Save changes</button>
                                <button type="reset" class="btn btn-outline-secondary">Cancel</button>
                            </div>
                        </form>
                    </div>
                    <!-- /Account -->
                </div>

                <!-- Password Change -->
                <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        <form id="formPasswordSettings" method="POST" action="{{ route('admin.profile.update-password') }}">
                            @csrf
                            <div class="row">
                                <div class="mb-3 col-md-4">
                                    <label for="current_password" class="form-label">Current Password</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="password" name="current_password" id="current_password" placeholder="············" required />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    @error('current_password')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="password" class="form-label">New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="password" name="password" id="password" placeholder="············" required />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                    @error('password')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                                </div>
                                <div class="mb-3 col-md-4">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="············" required />
                                        <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-2">
                                <button type="submit" class="btn btn-warning me-2 shadow-sm">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function (e) {
    (function () {
        const deactivateAcc = document.querySelector('#formAccountDeactivation');

        // Update/reset user image of account page
        let accountUserImage = document.getElementById('uploadedAvatar');
        const fileInput = document.querySelector('.account-file-input'),
            resetFileInput = document.querySelector('.account-image-reset'),
            placeholderAvatar = document.getElementById('placeholderAvatar');

        if (accountUserImage) {
            const resetImage = accountUserImage.src;
            fileInput.onchange = () => {
                if (fileInput.files[0]) {
                    accountUserImage.src = window.URL.createObjectURL(fileInput.files[0]);
                    accountUserImage.classList.remove('d-none');
                    if (placeholderAvatar) placeholderAvatar.classList.add('d-none');
                }
            };
            resetFileInput.onclick = () => {
                fileInput.value = '';
                accountUserImage.src = resetImage;
                if (resetImage === '' || resetImage.includes('undefined')) {
                    accountUserImage.classList.add('d-none');
                    if (placeholderAvatar) placeholderAvatar.classList.remove('d-none');
                }
            };
        }
    })();
});
</script>
@endsection
