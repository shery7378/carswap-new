<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;
use App\Models\User;

class VerifyEmailLink extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $token;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
        
        $template = EmailTemplate::where('slug', 'email-verification-link')->first();
        
        $verificationLink = env('FRONTEND_URL', 'http://localhost:3000') . '/verify-email?token=' . $this->token . '&email=' . urlencode($this->user->email);
        // Or if the link should go directly to the backend:
        // $verificationLink = route('api.verify-email', ['token' => $this->token]);
        // The user said "click on a link", usually backend handles it and redirects to frontend.
        // I will use a backend route for direct activation.
        $verificationLink = route('api.verify-email', ['token' => $this->token]);

        if ($template) {
            $rendered = $template->render([
                'first_name' => $this->user->first_name,
                'verification_link' => $verificationLink
            ]);
            $this->subject = $rendered['subject'];
            $this->body = $rendered['body'];
        } else {
            $this->subject = 'Verify your email address - CarSwap';
            $this->body = '<p>Hello ' . $this->user->first_name . ',</p><p>Please click the link below to verify your email:</p><p><a href="' . $verificationLink . '">' . $verificationLink . '</a></p>';
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.dynamic_template',
            with: [
                'body' => $this->body,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
