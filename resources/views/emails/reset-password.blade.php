@component('mail::message')
# Reset Your Password

Hello {{ $user->first_name }},

We received a request to reset the password for your CarSwap account. Click the link below to reset your password:

@component('mail::button', ['url' => $resetUrl])
Reset Password
@endcomponent

This link will expire in 1 hour. If you didn't request a password reset, please ignore this email.

For security reasons, never share this link with anyone.

Thanks,<br>
{{ config('app.name') }}

---

*If you're having trouble clicking the "Reset Password" button, copy and paste the link below into your browser:*
{{ $resetUrl }}
@endcomponent
