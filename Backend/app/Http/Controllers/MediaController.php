<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image; // Assuming v3

class MediaController extends Controller
{
    /**
     * Upload an image from the Tiptap editor.
     */
    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120', // 5MB limit
        ]);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::random(20) . '.' . ($extension ?: 'png');
            $path = 'media/' . $filename;

            // Ensure the directory exists
            if (!Storage::disk('public')->exists('media')) {
                Storage::disk('public')->makeDirectory('media');
            }

            // TRY to optimize if the library and extensions are available
            // Check for both the class and common extensions (gd or imagick)
            $canOptimize = class_exists('\Intervention\Image\Laravel\Facades\Image') && 
                          (extension_loaded('gd') || extension_loaded('imagick'));

            if ($canOptimize) {
                try {
                    $webpFilename = Str::random(20) . '.webp';
                    $webpPath = 'media/' . $webpFilename;
                    
                    $img = Image::read($file);
                    $img->scale(width: 1200);
                    $encoded = $img->encode(new \Intervention\Image\Encoders\WebpEncoder(80));
                    
                    Storage::disk('public')->put($webpPath, (string) $encoded);
                    
                    return response()->json([
                        'url' => asset('storage/' . $webpPath),
                        'optimized' => true
                    ]);
                } catch (\Exception $e) {
                    // Log the error but continue to fallback
                    \Log::warning('Image optimization failed, falling back to standard upload: ' . $e->getMessage());
                }
            }

            // Fallback: Just save the raw file if optimization is not possible
            $path = $file->storeAs('media', $filename, 'public');

            return response()->json([
                'url' => asset('storage/' . $path),
                'optimized' => false,
                'warning' => $canOptimize ? null : 'Optimization skipped (GD/Imagick extension missing).'
            ]);
        }

        return response()->json(['error' => 'No image uploaded'], 400);
    }
}
