<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    /**
     * Generate social sharing URLs for a given URL and title.
     */
    public function index(Request $request)
    {
        $request->validate([
            'url' => 'required|url',
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'image' => 'nullable|url',
        ]);

        $url = $request->url;
        $title = $request->title ?? 'Check this out!';
        $description = $request->description ?? '';
        $image = $request->image ?? '';

        $encodedUrl = urlencode($url);
        $encodedTitle = urlencode($title);
        $encodedDesc = urlencode($description);
        $encodedImage = urlencode($image);

        $shareLinks = [
            [
                'name' => 'Facebook',
                'icon' => 'bx bxl-facebook-circle',
                'color' => '#1877F2',
                'share_url' => "https://www.facebook.com/sharer/sharer.php?u={$encodedUrl}"
            ],
            [
                'name' => 'Twitter (X)',
                'icon' => 'bx bxl-twitter',
                'color' => '#000000',
                'share_url' => "https://twitter.com/intent/tweet?url={$encodedUrl}&text={$encodedTitle}"
            ],
            [
                'name' => 'WhatsApp',
                'icon' => 'bx bxl-whatsapp',
                'color' => '#25D366',
                'share_url' => "https://api.whatsapp.com/send?text={$encodedTitle}%20{$encodedUrl}"
            ],
            [
                'name' => 'LinkedIn',
                'icon' => 'bx bxl-linkedin-square',
                'color' => '#0A66C2',
                'share_url' => "https://www.linkedin.com/sharing/share-offsite/?url={$encodedUrl}"
            ],
            [
                'name' => 'Telegram',
                'icon' => 'bx bxl-telegram',
                'color' => '#26A5E4',
                'share_url' => "https://t.me/share/url?url={$encodedUrl}&text={$encodedTitle}"
            ],
            [
                'name' => 'Messenger',
                'icon' => 'bx bxl-messenger',
                'color' => '#006AFF',
                'share_url' => "fb-messenger://share/?link={$encodedUrl}&app_id=123456789" // Requires app_id for web, but this is a common scheme
            ],
            [
                'name' => 'Pinterest',
                'icon' => 'bx bxl-pinterest',
                'color' => '#BD081C',
                'share_url' => "https://pinterest.com/pin/create/button/?url={$encodedUrl}&media={$encodedImage}&description={$encodedTitle}"
            ],
            [
                'name' => 'Reddit',
                'icon' => 'bx bxl-reddit',
                'color' => '#FF4500',
                'share_url' => "https://www.reddit.com/submit?url={$encodedUrl}&title={$encodedTitle}"
            ],
            [
                'name' => 'Email',
                'icon' => 'bx bx-envelope',
                'color' => '#7F7F7F',
                'share_url' => "mailto:?subject={$encodedTitle}&body={$encodedDesc}%20{$encodedUrl}"
            ],
            [
                'name' => 'Gmail',
                'icon' => 'bx bxl-gmail',
                'color' => '#EA4335',
                'share_url' => "https://mail.google.com/mail/?view=cm&fs=1&tf=1&to=&su={$encodedTitle}&body={$encodedDesc}%20{$encodedUrl}&ui=2&tf=1"
            ],
            [
                'name' => 'Bluesky',
                'icon' => 'bx bx-cloud', // Bluesky doesn't have a default boxicon yet usually, using cloud as placeholder
                'color' => '#0085FF',
                'share_url' => "https://bsky.app/intent/compose?text={$encodedTitle}%20{$encodedUrl}"
            ],
            [
                'name' => 'Mastodon',
                'icon' => 'bx bxl-mastodon',
                'color' => '#6364FF',
                'share_url' => "https://mastodonshare.com/?url={$encodedUrl}&text={$encodedTitle}"
            ],
            [
                'name' => 'Pocket',
                'icon' => 'bx bxl-pocket',
                'color' => '#EF4056',
                'share_url' => "https://getpocket.com/save?url={$encodedUrl}&title={$encodedTitle}"
            ],
            [
                'name' => 'SMS / Message',
                'icon' => 'bx bx-message-rounded-dots',
                'color' => '#4CD964',
                'share_url' => "sms:?body={$encodedTitle}%20{$encodedUrl}"
            ],
            [
                'name' => 'Viber',
                'icon' => 'bx bxl-viber', // If exists
                'color' => '#7360F2',
                'share_url' => "viber://forward?text={$encodedTitle}%20{$encodedUrl}"
            ],
            [
                'name' => 'Copy Link',
                'icon' => 'bx bx-copy',
                'color' => '#000000',
                'share_url' => $url // Handled by frontend clipboard API
            ],
        ];

        return response()->json([
            'success' => true,
            'data' => $shareLinks
        ]);
    }
}
