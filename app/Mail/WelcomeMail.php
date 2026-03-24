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
        $template = \App\Models\EmailTemplate::where('slug', 'welcome')->first();

        if ($template) {
            $rendered = $template->render([
                'first_name' => $this->user->first_name,
                'frontend_url' => $this->frontendUrl,
                'login_url' => $this->frontendUrl . '/login',
            ]);

            return $this->subject($rendered['subject'])
                        ->html($rendered['body']);
        }

        // Fallback to hardcoded template if DB template is missing
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
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h2>Welcome to CarSwap! 🎉</h2>
                </div>
                <div class='content'>
                    <p>Hello <strong>{$this->user->first_name}</strong>,</p>
                    <p>Thank you for joining CarSwap! We're excited to have you as part of our community.</p>
                </div>
                <p style='text-align: center;'>
                    <a href='" . $this->frontendUrl . "' class='cta-button'>Start Browsing</a>
                </p>
                <div class='footer'>
                    <p>CarSwap Team - Welcome Aboard!</p>
                </div>
            </div>
        </body>
        </html>
        ";
        
        return $this->subject('Welcome to CarSwap! 🎉')
                    ->html($htmlContent);
    }
}
