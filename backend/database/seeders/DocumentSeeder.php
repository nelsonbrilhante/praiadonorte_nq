<?php

namespace Database\Seeders;

use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DocumentSeeder extends Seeder
{
    public function run(): void
    {
        $categories = DocumentCategory::all()->keyBy('slug');

        if ($categories->isEmpty()) {
            $this->command->warn('No document categories found. Run DocumentCategorySeeder first.');
            return;
        }

        $baseDir = 'documentos';
        $disk = Storage::disk('public');

        if (! $disk->exists($baseDir)) {
            $this->command->warn("Directory {$baseDir}/ not found in public storage. Run: php artisan app:download-documents");
            return;
        }

        $totalCreated = 0;

        foreach ($categories as $slug => $category) {
            $catDir = "{$baseDir}/{$slug}";

            if (! $disk->exists($catDir)) {
                continue;
            }

            $files = collect($disk->files($catDir))
                ->filter(fn ($f) => str_ends_with(strtolower($f), '.pdf'))
                ->sort()
                ->values();

            foreach ($files as $index => $filePath) {
                $basename = pathinfo($filePath, PATHINFO_FILENAME);

                // Derive a human-readable title from the filename
                $title = str_replace(['-', '_'], ' ', $basename);
                $title = mb_convert_case($title, MB_CASE_TITLE, 'UTF-8');

                // Extract year for published_at
                $publishedAt = null;
                if (preg_match('/\b(20\d{2})\b/', $basename, $matches)) {
                    $publishedAt = $matches[1] . '-01-01';
                }

                Document::updateOrCreate(
                    ['file' => $filePath],
                    [
                        'document_category_id' => $category->id,
                        'title' => ['pt' => $title],
                        'published_at' => $publishedAt,
                        'order' => $index + 1,
                    ]
                );

                $totalCreated++;
            }
        }

        $this->command->info("DocumentSeeder: {$totalCreated} documents seeded from existing PDFs.");
    }
}
