<?php

namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\DynamicMail;

class EmailService
{
    /**
     * Send a dynamically rendered email using a database template.
     *
     * @param string|array $to The recipient email address(es)
     * @param string $templateSlug The slug of the EmailTemplate to use (e.g., 'test-drive', 'trade-in')
     * @param array $data Data array for shortcode replacement (e.g., ['first_name' => 'John', 'car' => 'Civic'])
     * @param string|null $fallbackHtml Optional static HTML to use if the template is deleted/missing
     */
    public static function send($to, $templateSlug, $data = [], $fallbackHtml = null)
    {
        try {
            Mail::to($to)->send(new DynamicMail($templateSlug, $data, $fallbackHtml));
            return true;
        } catch (\Exception $e) {
            \Log::error("Failed to send dynamic email [{$templateSlug}] to {$to}: " . $e->getMessage());
            return false;
        }
    }
}
