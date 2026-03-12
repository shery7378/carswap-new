<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $frontendUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->frontendUrl = env('FRONTEND_URL', 'https://hexafume.com');
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
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #dcb377; color: white; padding: 20px; text-align: center; border-radius: 5px; }
                .header h2 { margin: 0; }
                .content { padding: 20px; background-color: #f9f9f9; margin: 20px 0; border-radius: 5px; }
                .content p { line-height: 1.6; }
                .feature-list { margin: 15px 0; }
                .feature-item { padding: 10px; background-color: white; margin: 8px 0; border-left: 4px solid #dcb377; border-radius: 3px; }
                .cta-button { 
                    background-color: #dcb377; 
                    color: white; 
                    padding: 12px 25px; 
                    text-decoration: none; 
                    border-radius: 5px; 
                    display: inline-block; 
                    margin: 20px 0;
                }
                .footer { color: #666; font-size: 12px; margin-top: 30px; border-top: 1px solid #ddd; padding-top: 15px; text-align: center; }
                .social-links { margin: 15px 0; text-align: center; }
                .social-links a { margin: 0 10px; color: #dcb377; text-decoration: none; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Welcome to CarSwap! 🎉</h2>
                </div>
                
                <div class='content'>
                    <p>Hello <strong>{$this->user->first_name}</strong>,</p>
                    
                    <p>Thank you for joining CarSwap! We're excited to have you as part of our community. Whether you're here to buy, sell, or trade vehicles, you're in the right place.</p>
                    
                    <h3>Get Started with CarSwap:</h3>
                    <div class='feature-list'>
                        <div class='feature-item'>
                            <strong>📋 Complete Your Profile</strong><br>
                            Add a profile picture and detailed information to attract serious buyers and sellers.
                        </div>
                        <div class='feature-item'>
                            <strong>🚗 Browse Listings</strong><br>
                            Explore thousands of vehicles from verified sellers in your area.
                        </div>
                        <div class='feature-item'>
                            <strong>🔔 Create Alerts</strong><br>
                            Get notified when new listings matching your preferences are added.
                        </div>
                        <div class='feature-item'>
                            <strong>💬 Connect with Traders</strong><br>
                            Message sellers and buyers directly through our secure platform.
                        </div>
                    </div>
                </div>
                
                <p style='text-align: center;'>
                    <a href='" . $this->frontendUrl . "' class='cta-button'>Start Browsing</a>
                </p>
                
                <div class='footer'>
                    <p><strong>Quick Tips:</strong></p>
                    <ul style='text-align: left; display: inline-block;'>
                        <li>Keep your profile information up to date</li>
                        <li>Respond quickly to inquiries</li>
                        <li>Use WhatsApp or Viber for faster communication</li>
                        <li>Be honest about your vehicle condition</li>
                    </ul>
                    
                    <div class='social-links'>
                        <p>Follow us or contact support</p>
                        <a href='#'>Facebook</a> | 
                        <a href='#'>Instagram</a> | 
                        <a href='#'>Twitter</a>
                    </div>
                    
                    <hr>
                    <p>Questions? Contact our support team at <strong>support@carswap.com</strong></p>
                    <p>CarSwap Team - Welcome Aboard!</p>
                    <p style='font-size: 10px; color: #999;'>
                        © 2026 CarSwap. All rights reserved.<br>
                        This is an automated message. Please do not reply to this email.
                    </p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->subject('Welcome to CarSwap! 🎉')
                    ->html($htmlContent);
    }
}
