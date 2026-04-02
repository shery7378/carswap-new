<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::orderBy('created_at', 'desc')->get();
        return view('content.dashboard.newsletter.index', compact('subscribers'));
    }

    public function destroy($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }
}
