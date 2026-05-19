<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'subject',
        'body',
        'shortcodes',
        'category',
    ];

    protected $casts = [
        'shortcodes' => 'array',
    ];

    public function render($data)
    {
        $subject = $this->subject;
        $body = $this->body;

        // First, strip any HTML tags and zero-width spaces that might be *inside* the brackets due to WYSIWYG editor formatting
        // e.g., [<span>first_name</span>] becomes [first_name]
        $cleaner = function ($matches) {
            // strip tags, decode html entities, and then trim regular spaces and non-breaking spaces
            $cleaned = strip_tags($matches[1]);
            $cleaned = html_entity_decode($cleaned, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            return '[' . trim($cleaned, " \t\n\r\0\x0B\xC2\xA0") . ']';
        };
        
        $body = preg_replace_callback('/\[(.*?)\]/', $cleaner, $body);
        $subject = preg_replace_callback('/\[(.*?)\]/', $cleaner, $subject);

        foreach ($data as $key => $value) {
            // Standard replacement
            $placeholder = '[' . $key . ']';
            $subject = str_replace($placeholder, $value ?? '', $subject);
            $body = str_replace($placeholder, $value ?? '', $body);
        }

        return [
            'subject' => $subject,
            'body' => $body,
        ];
    }
}
