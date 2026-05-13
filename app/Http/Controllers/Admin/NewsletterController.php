<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = NewsletterSubscriber::orderBy('id', 'desc')->get();
        return view('content.dashboard.newsletter.index', compact('subscribers'));
    }

    public function exportCsv(): StreamedResponse
    {
        $fileName = 'newsletter-subscribers-' . now()->format('Y-m-d_H-i-s') . '.csv';
        $subscribers = NewsletterSubscriber::orderBy('id', 'desc')->get(['id', 'name', 'email', 'created_at']);

        return response()->streamDownload(function () use ($subscribers) {
            $handle = fopen('php://output', 'w');

            fputcsv($handle, ['ID', 'Name', 'Email', 'Subscribed At']);

            foreach ($subscribers as $subscriber) {
                fputcsv($handle, [
                    $subscriber->id,
                    $subscriber->name,
                    $subscriber->email,
                    optional($subscriber->created_at)->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function destroy($id)
    {
        $subscriber = NewsletterSubscriber::findOrFail($id);
        $subscriber->delete();
        return redirect()->back()->with('success', 'Subscriber deleted successfully.');
    }
}
