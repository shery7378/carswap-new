<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\EmailTemplate;
use App\Mail\DynamicTemplateMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Store a new contact message.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:4000',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $contact = Contact::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'unread',
        ]);

        // Send Email Notification to Admin
        $adminEmail = config('settings.storeEmail') 
                      ?? config('settings.contactEmail') 
                      ?? config('settings.mail_from_address') 
                      ?? config('mail.from.address') 
                      ?? 'admin@carswap.com';
        
        $template = EmailTemplate::where('slug', 'contact-us')->first();
        if ($template) {
            $data = [
                'sender_name' => $request->name,
                'sender_email' => $request->email,
                'sender_phone' => $request->phone ?? 'N/A',
                'subject' => $request->subject,
                'message_content' => $request->message,
            ];
            $rendered = $template->render($data);
            Mail::to($adminEmail)->send(new DynamicTemplateMail($rendered['subject'], $rendered['body']));
        } else {
            // Fallback simple mail if template doesn't exist
            Mail::raw("New Contact Form Submission:\n\nName: {$request->name}\nEmail: {$request->email}\nPhone: {$request->phone}\nSubject: {$request->subject}\n\nMessage:\n{$request->message}", function($message) use ($adminEmail, $request) {
                $message->to($adminEmail)
                        ->subject("New Contact: " . $request->subject);
            });
        }

        return response()->json([
            'success' => true, 
            'message' => 'Your message has been sent successfully.', 
            'data' => $contact
        ], 201);
    }
}
