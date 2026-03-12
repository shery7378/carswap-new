<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $resetUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = env('FRONTEND_URL') . '/reset-password?email=' . urlencode($user->email) . '&token=' . $token;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $htmlContent = "
        <!DOCTYPE html>
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                h2 { color: #333; }
                .reset-button { 
                    background-color: #dcb377; 
                    color: white; 
                    padding: 12px 20px; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    display: inline-block; 
                    margin: 20px 0;
                }
                .footer { color: #666; font-size: 12px; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <h2>Reset Your Password</h2>
                <p>Hello {$this->user->first_name},</p>
                <p>We received a request to reset the password for your CarSwap account. Click the button below to reset your password:</p>
                
                <p>
                    <a href='{$this->resetUrl}' class='reset-button'>Reset Password</a>
                </p>
                
                <p>This link will expire in <strong>1 hour</strong>.</p>
                <p>If you didn't request a password reset, please ignore this email or contact support.</p>
                
                <div class='footer'>
                    <p>If you're having trouble clicking the link, copy and paste this URL into your browser:</p>
                    <p><small>{$this->resetUrl}</small></p>
                    <p><strong>Never share this link with anyone.</strong></p>
                    <hr>
                    <p>CarSwap Team - Password Reset Request</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->subject('Password Reset Request - CarSwap')
                    ->html($htmlContent);
    }
}
