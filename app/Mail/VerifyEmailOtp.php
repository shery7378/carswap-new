<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class VerifyEmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $subject;
    public $body;

    /**
     * Create a new message instance.
     */
    public function __construct($otp)
    {
        $this->otp = $otp;
        
        $template = EmailTemplate::where('slug', 'email-verification-otp')->first();
        
        if ($template) {
            $rendered = $template->render(['otp' => $this->otp]);
            $this->subject = $rendered['subject'];
            $this->body = $rendered['body'];
        } else {
            $this->subject = 'Verify your email address - CarSwap';
            $this->body = '<p>Your verification code is: <strong>' . $this->otp . '</strong></p>';
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
