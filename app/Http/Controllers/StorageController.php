<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StorageController extends Controller
{
    /**
     * Serve files from storage/app/public
     */
    public function serveFile($path)
    {
        // Security: prevent directory traversal
        $path = str_replace(['../', '..\\'], '', $path);
        
        $fullPath = 'public/' . $path;

        if (!Storage::exists($fullPath)) {
            return response()->json([
                'success' => false,
                'message' => 'File not found'
            ], 404);
        }

        $mimeType = Storage::mimeType($fullPath);
        
        return response()->file(Storage::path($fullPath), [
            'Content-Type' => $mimeType,
            'Cache-Control' => 'public, max-age=31536000',
        ]);
    }
}
