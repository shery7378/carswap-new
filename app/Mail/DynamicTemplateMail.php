<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DynamicTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $body;
    public $attachmentFiles;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $body, $attachmentFiles = [])
    {
        $this->subject = $subject;
        $this->body = $body;
        $this->attachmentFiles = $attachmentFiles;
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
        $attachments = [];
        
        foreach ($this->attachmentFiles as $file) {
            $attachments[] = \Illuminate\Mail\Mailables\Attachment::fromStorageDisk('public', $file);
        }

        return $attachments;
    }
}
