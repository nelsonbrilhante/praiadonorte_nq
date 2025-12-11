<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

trait DownloadsImages
{
    /**
     * Download an image from URL and save to storage.
     *
     * @param string $url The image URL
     * @param string $folder The storage folder (e.g., 'noticias', 'eventos', 'surfers')
     * @param string $filename The filename to save as
     * @return string|null The relative path to the saved image, or null on failure
     */
    protected function downloadImage(string $url, string $folder, string $filename): ?string
    {
        try {
            // Ensure the folder exists
            Storage::disk('public')->makeDirectory($folder);

            // Download the image
            $response = Http::timeout(30)->get($url);

            if ($response->successful()) {
                $path = "{$folder}/{$filename}";
                Storage::disk('public')->put($path, $response->body());
                return $path;
            }
        } catch (\Exception $e) {
            // Log error but don't fail the seeder
            echo "Warning: Could not download image from {$url}: {$e->getMessage()}\n";
        }

        return null;
    }

    /**
     * Get an Unsplash image URL with specific dimensions.
     *
     * @param int $width
     * @param int $height
     * @param string $keywords Search keywords
     * @return string
     */
    protected function getUnsplashUrl(int $width, int $height, string $keywords = ''): string
    {
        $base = "https://source.unsplash.com/random/{$width}x{$height}";
        return $keywords ? "{$base}/?{$keywords}" : $base;
    }

    /**
     * Get a Lorem Picsum image URL (more reliable for seeding).
     *
     * @param int $width
     * @param int $height
     * @param int $seed Seed for consistent image selection
     * @return string
     */
    protected function getPicsumUrl(int $width, int $height, int $seed): string
    {
        return "https://picsum.photos/seed/{$seed}/{$width}/{$height}";
    }
}
