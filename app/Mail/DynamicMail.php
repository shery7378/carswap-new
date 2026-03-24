<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\EmailTemplate;

class DynamicMail extends Mailable
{
    use Queueable, SerializesModels;

    public $templateSlug;
    public $data;
    public $fallbackHtml;

    /**
     * Create a new message instance.
     *
     * @param string $templateSlug Database slug for the template
     * @param array $data Data for shortcode replacements
     * @param string|null $fallbackHtml Optional fallback HTML if DB template is missing
     */
    public function __construct($templateSlug, $data = [], $fallbackHtml = null)
    {
        $this->templateSlug = $templateSlug;
        $this->data = $data;
        $this->fallbackHtml = $fallbackHtml;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        $template = EmailTemplate::where('slug', $this->templateSlug)->first();

        if ($template) {
            $rendered = $template->render($this->data);
            
            return $this->subject($rendered['subject'])
                        ->html($rendered['body']);
        }

        // Fallback if template missing
        $subject = ucfirst(str_replace('-', ' ', $this->templateSlug));
        $html = $this->fallbackHtml ?? "<h1>{$subject}</h1><p>Please configure this email template in the admin panel.</p>";

        return $this->subject($subject)->html($html);
    }
}
