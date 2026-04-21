<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailVerificationOtpTemplateSeeder extends Seeder
{
    public function run()
    {
        EmailTemplate::updateOrCreate(
            ['slug' => 'email-verification-otp'],
            [
                'name' => 'Email Verification OTP',
                'subject' => 'Verify your email address - CarSwap',
                'body' => '
                    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
                        <h2>Email Verification</h2>
                        <p>Hello,</p>
                        <p>Thank you for registering with CarSwap. To complete your registration, please use the following 6-digit verification code:</p>
                        <div style="background-color: #f4f4f4; padding: 20px; text-align: center; font-size: 24px; font-weight: bold; letter-spacing: 5px; border-radius: 5px;">
                            [otp]
                        </div>
                        <p>This code will expire in 15 minutes.</p>
                        <p>If you did not request this code, please ignore this email.</p>
                        <p>Best regards,<br>The CarSwap Team</p>
                    </div>
                ',
                'shortcodes' => ['otp'],
                'category' => 'Auth',
            ]
        );
    }
}
