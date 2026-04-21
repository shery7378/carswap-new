<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EmailTemplate;

class EmailVerificationLinkTemplateSeeder extends Seeder
{
    public function run()
    {
        EmailTemplate::updateOrCreate(
            ['slug' => 'email-verification-link'],
            [
                'name' => 'Email Verification Link',
                'subject' => 'Verify your email address - CarSwap',
                'body' => '
                    <div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #eee; border-radius: 10px;">
                        <h2 style="color: #4A90E2; text-align: center;">Verify Your Email</h2>
                        <p>Hello [first_name],</p>
                        <p>Thank you for joining CarSwap! Please click the button below to verify your email address and activate your account:</p>
                        <div style="text-align: center; margin: 30px 0;">
                            <a href="[verification_link]" style="background-color: #4A90E2; color: #fff; padding: 12px 25px; text-decoration: none; border-radius: 5px; font-weight: bold; display: inline-block;">Verify Email Address</a>
                        </div>
                        <p style="font-size: 14px; color: #777;">If you did not create an account, no further action is required.</p>
                        <p style="font-size: 14px; color: #777; border-top: 1px solid #eee; padding-top: 20px;">
                            Best regards,<br>
                            The CarSwap Team
                        </p>
                    </div>
                ',
                'shortcodes' => ['first_name', 'verification_link'],
                'category' => 'Auth',
            ]
        );
    }
}
