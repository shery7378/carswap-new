<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:newsletter_subscribers,email',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        $subscriber = NewsletterSubscriber::create([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json(['success' => true, 'message' => 'Successfully subscribed to the newsletter!', 'data' => $subscriber]);
    }
}
