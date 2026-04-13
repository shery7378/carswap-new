<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contacts = Contact::orderBy('id', 'desc')->paginate(10);
        return view('content.dashboard.contacts.index', compact('contacts'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        
        // Mark as read if it was unread
        if ($contact->status === 'unread') {
            $contact->update(['status' => 'read']);
        }
        
        return view('content.dashboard.contacts.show', compact('contact'));
    }

    /**
     * Update the status of the contact.
     */
    public function updateStatus(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Contact status updated successfully.');
    }

    /**
     * Send a reply email to the contact.
     */
    public function sendReply(Request $request, $id)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $contact = Contact::findOrFail($id);

        try {
            \Illuminate\Support\Facades\Mail::to($contact->email)->send(
                new \App\Mail\DynamicTemplateMail($request->subject, $request->message)
            );

            // Update status to replied
            $contact->update(['status' => 'replied']);

            return redirect()->back()->with('success', 'Reply email sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('admin.contacts.index')->with('success', 'Contact deleted successfully.');
    }
}
