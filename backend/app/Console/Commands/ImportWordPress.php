<?php

namespace App\Console\Commands;

use App\Models\Noticia;
use App\Models\Surfer;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImportWordPress extends Command
{
    protected $signature = 'app:import-wordpress
        {dump-path : Path to the WordPress JSON dump file (phpMyAdmin JSON export)}
        {--surfers : Import surfers only}
        {--news : Import news/notícias only}
        {--all : Import everything (surfers + news)}
        {--skip-media : Skip downloading media files}
        {--wp-url=https://praiadonortenazare.pt : WordPress site URL for media downloads}
        {--news-author=3 : WordPress author ID for news posts}
        {--no-clear : Skip clearing existing data (append mode)}';

    protected $description = 'Import content from a WordPress JSON dump into Laravel (surfers + news)';

    private string $wpUrl;

    private int $surferCount = 0;

    private int $newsCount = 0;

    private int $mediaDownloaded = 0;

    private int $mediaFailed = 0;

    // Parsed WordPress tables (loaded from JSON)
    private array $wpPosts = [];

    private array $wpPostmeta = [];

    private array $wpTerms = [];

    private array $wpTermTaxonomy = [];

    private array $wpTermRelationships = [];

    private array $wpUsers = [];

    public function handle(): int
    {
        $dumpPath = $this->argument('dump-path');
        $this->wpUrl = rtrim($this->option('wp-url'), '/');

        // Resolve relative paths from storage/app
        if (! str_starts_with($dumpPath, '/')) {
            $dumpPath = storage_path("app/{$dumpPath}");
        }

        if (! file_exists($dumpPath)) {
            $this->error("Dump file not found: {$dumpPath}");

            return self::FAILURE;
        }

        // Require at least one import option
        if (! $this->option('surfers') && ! $this->option('news') && ! $this->option('all')) {
            $this->error('Please specify --surfers, --news, or --all');

            return self::FAILURE;
        }

        $this->info('=== WordPress Content Import ===');
        $this->newLine();

        // Step 1: Parse the JSON dump
        $this->info('[1/3] Parsing JSON dump...');
        if (! $this->parseJsonDump($dumpPath)) {
            return self::FAILURE;
        }

        // Step 2: Delete existing data (unless --no-clear)
        if (! $this->option('no-clear')) {
            $this->info('[2/3] Clearing existing data...');
            $this->clearExistingData();
        } else {
            $this->info('[2/3] Skipping clear (--no-clear mode)...');
        }

        // Step 3: Import content
        $this->info('[3/3] Importing content...');

        if ($this->option('surfers') || $this->option('all')) {
            $this->importSurfers();
        }

        if ($this->option('news') || $this->option('all')) {
            $this->importNews();
        }

        // Summary
        $this->newLine();
        $this->info('=== Import Complete ===');
        $this->table(
            ['Metric', 'Count'],
            [
                ['Surfers imported', $this->surferCount],
                ['News imported', $this->newsCount],
                ['Media downloaded', $this->mediaDownloaded],
                ['Media failed', $this->mediaFailed],
            ]
        );

        // Free memory
        $this->wpPosts = [];
        $this->wpPostmeta = [];
        $this->wpTerms = [];
        $this->wpTermTaxonomy = [];
        $this->wpTermRelationships = [];
        $this->wpUsers = [];

        return self::SUCCESS;
    }

    private function parseJsonDump(string $dumpPath): bool
    {
        $this->info('  File: ' . basename($dumpPath) . ' (' . round(filesize($dumpPath) / 1024 / 1024, 1) . ' MB)');

        // Tables we need
        $neededTables = [
            'wp_posts', 'wp_postmeta', 'wp_terms',
            'wp_term_taxonomy', 'wp_term_relationships', 'wp_users',
        ];

        $handle = fopen($dumpPath, 'r');
        if (! $handle) {
            $this->error('Cannot open dump file');

            return false;
        }

        $currentTable = null;
        $inData = false;
        $rowCount = 0;

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);

            // Detect table header
            if (str_contains($line, '"type":"table"')) {
                $cleaned = rtrim($line, ',');
                if (preg_match('/"name":"(wp_\w+)"/', $cleaned, $matches)) {
                    $tableName = $matches[1];
                    if (in_array($tableName, $neededTables)) {
                        $currentTable = $tableName;
                        $inData = false;
                        $this->line("  Loading: {$tableName}...");
                    } else {
                        $currentTable = null;
                    }
                }

                continue;
            }

            // Detect start of data array
            if ($currentTable && $line === '[') {
                $inData = true;

                continue;
            }

            // Detect end of data array
            if ($inData && ($line === ']' || $line === ']}')) {
                $inData = false;
                $currentTable = null;

                continue;
            }

            // Parse data rows
            if ($inData && $currentTable) {
                $cleaned = rtrim($line, ',');
                if (empty($cleaned) || $cleaned === '[' || $cleaned === ']') {
                    continue;
                }

                $row = json_decode($cleaned, true);
                if (! $row) {
                    continue;
                }

                $this->storeRow($currentTable, $row);
                $rowCount++;
            }
        }

        fclose($handle);

        $this->info("  Parsed {$rowCount} rows total");
        $this->line('  wp_posts: ' . count($this->wpPosts));
        $this->line('  wp_postmeta: ' . count($this->wpPostmeta));
        $this->line('  wp_terms: ' . count($this->wpTerms));
        $this->line('  wp_term_taxonomy: ' . count($this->wpTermTaxonomy));
        $this->line('  wp_term_relationships: ' . count($this->wpTermRelationships));
        $this->line('  wp_users: ' . count($this->wpUsers));

        if (empty($this->wpPosts)) {
            $this->error('No wp_posts data found in dump');

            return false;
        }

        return true;
    }

    private function storeRow(string $table, array $row): void
    {
        switch ($table) {
            case 'wp_posts':
                $this->wpPosts[$row['ID']] = $row;
                break;
            case 'wp_postmeta':
                $postId = $row['post_id'];
                if (! isset($this->wpPostmeta[$postId])) {
                    $this->wpPostmeta[$postId] = [];
                }
                $this->wpPostmeta[$postId][$row['meta_key']] = $row['meta_value'];
                break;
            case 'wp_terms':
                $this->wpTerms[$row['term_id']] = $row;
                break;
            case 'wp_term_taxonomy':
                $this->wpTermTaxonomy[$row['term_taxonomy_id']] = $row;
                break;
            case 'wp_term_relationships':
                $objectId = $row['object_id'];
                if (! isset($this->wpTermRelationships[$objectId])) {
                    $this->wpTermRelationships[$objectId] = [];
                }
                $this->wpTermRelationships[$objectId][] = $row['term_taxonomy_id'];
                break;
            case 'wp_users':
                $this->wpUsers[$row['ID']] = $row;
                break;
        }
    }

    private function clearExistingData(): void
    {
        if ($this->option('surfers') || $this->option('all')) {
            $surferCount = Surfer::count();
            Surfer::query()->delete();
            $this->info("  Deleted {$surferCount} surfers");

            if (Storage::disk('public')->exists('surfers')) {
                $files = Storage::disk('public')->files('surfers');
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                }
                $this->info('  Cleared surfer media files (' . count($files) . ' files)');
            }
        }

        if ($this->option('news') || $this->option('all')) {
            $newsCount = Noticia::count();
            Noticia::query()->delete();
            $this->info("  Deleted {$newsCount} noticias");

            if (Storage::disk('public')->exists('noticias')) {
                $files = Storage::disk('public')->files('noticias');
                foreach ($files as $file) {
                    Storage::disk('public')->delete($file);
                }
                $this->info('  Cleared noticia media files (' . count($files) . ' files)');
            }
        }
    }

    private function importSurfers(): void
    {
        $this->newLine();
        $this->info('--- Importing Surfers ---');

        // Find all published surfer posts
        $surfers = collect($this->wpPosts)
            ->filter(fn ($p) => $p['post_type'] === 'surfer' && $p['post_status'] === 'publish')
            ->sortBy('post_date')
            ->values();

        if ($surfers->isEmpty()) {
            $this->warn('  No surfer posts found.');

            return;
        }

        $this->info("  Found {$surfers->count()} surfer posts");

        $order = 1;
        foreach ($surfers as $wpSurfer) {
            $name = html_entity_decode($wpSurfer['post_title'], ENT_QUOTES, 'UTF-8');
            $slug = $wpSurfer['post_name'];
            $this->line("  [{$order}] {$name} ({$slug})");

            // Get bio + quotes from post content and ACF meta fields
            $meta = $this->wpPostmeta[$wpSurfer['ID']] ?? [];

            $bioPt = $this->cleanWordPressHtml($wpSurfer['post_content']);
            $bioEn = $this->cleanWordPressHtml($meta['descricao_en'] ?? '');
            $quotePt = $this->cleanWordPressHtml($meta['comentario_pt'] ?? '');
            $quoteEn = $this->cleanWordPressHtml($meta['comentario_en'] ?? '');

            // Extract aka and social media from ACF meta
            $aka = trim($meta['alcunha_nickname'] ?? '');

            $socialMedia = array_filter([
                'instagram' => ltrim($meta['instagram'] ?? '', '@'),
                'facebook' => $meta['facebook'] ?? '',
                'twitter' => $meta['twitter'] ?? '',
            ]);

            // Download featured image
            $photo = null;
            if (! $this->option('skip-media')) {
                $photo = $this->downloadFeaturedImage($wpSurfer['ID'], 'surfers', $slug);
            }

            // Download board image (ACF attachment ID)
            $boardImage = null;
            if (! $this->option('skip-media') && ! empty($meta['imagem_da_prancha'])) {
                $boardImage = $this->downloadAttachment($meta['imagem_da_prancha'], 'surfers/boards', $slug);
            }

            // Create surfer — first 6 are featured
            Surfer::create([
                'name' => $name,
                'aka' => $aka ?: null,
                'slug' => $slug,
                'bio' => [
                    'pt' => $bioPt ?: "<p>{$name}</p>",
                    'en' => $bioEn,
                ],
                'quote' => [
                    'pt' => $quotePt,
                    'en' => $quoteEn,
                ],
                'photo' => $photo,
                'social_media' => ! empty($socialMedia) ? $socialMedia : null,
                'board_image' => $boardImage,
                'featured' => $order <= 6,
                'order' => $order,
            ]);

            $order++;
            $this->surferCount++;
        }

        $this->info("  Imported {$this->surferCount} surfers");
    }

    private function importNews(): void
    {
        $this->newLine();
        $this->info('--- Importing News ---');

        $newsAuthor = $this->option('news-author');

        // Find published posts by the specified author
        $posts = collect($this->wpPosts)
            ->filter(fn ($p) => $p['post_type'] === 'post'
                && $p['post_status'] === 'publish'
                && $p['post_author'] === (string) $newsAuthor)
            ->sortByDesc('post_date')
            ->values();

        if ($posts->isEmpty()) {
            $this->warn("  No published posts found for author {$newsAuthor}.");
            // Show available authors
            $authors = collect($this->wpPosts)
                ->where('post_type', 'post')
                ->where('post_status', 'publish')
                ->groupBy('post_author')
                ->map->count();
            $this->warn('  Posts per author: ' . $authors->toJson());

            return;
        }

        $this->info("  Found {$posts->count()} published posts by author {$newsAuthor}");

        foreach ($posts as $index => $post) {
            $num = $index + 1;
            $title = html_entity_decode($post['post_title'], ENT_QUOTES, 'UTF-8');
            $this->line("  [{$num}] {$title}");

            // Get categories
            $categories = $this->getPostTerms($post['ID'], 'category');
            $category = ! empty($categories) ? $categories[0] : 'Geral';

            // Get tags
            $tags = $this->getPostTerms($post['ID'], 'post_tag');

            // Clean content
            $content = $this->cleanWordPressHtml($post['post_content']);

            // Clean excerpt
            $excerpt = ! empty($post['post_excerpt'])
                ? strip_tags(html_entity_decode($post['post_excerpt'], ENT_QUOTES, 'UTF-8'))
                : Str::limit(strip_tags($content), 200);

            // Generate unique slug
            $slug = $post['post_name'];
            if (Noticia::where('slug', $slug)->exists()) {
                $slug = $slug . '-' . Str::random(4);
            }

            // Download featured image
            $coverImage = null;
            if (! $this->option('skip-media')) {
                $coverImage = $this->downloadFeaturedImage($post['ID'], 'noticias', $slug);
            }

            // Get author name
            $author = $this->getAuthorName($post['post_author']);

            Noticia::create([
                'title' => ['pt' => $title, 'en' => ''],
                'slug' => $slug,
                'content' => ['pt' => $content, 'en' => ''],
                'excerpt' => ['pt' => $excerpt, 'en' => ''],
                'cover_image' => $coverImage,
                'author' => $author,
                'category' => $category,
                'entity' => 'praia-norte',
                'tags' => $tags,
                'featured' => $index < 3,
                'published_at' => $post['post_date'],
            ]);

            $this->newsCount++;
        }

        $this->info("  Imported {$this->newsCount} news articles");
    }

    private function getPostTerms(string $postId, string $taxonomy): array
    {
        $termTaxIds = $this->wpTermRelationships[$postId] ?? [];
        $terms = [];

        foreach ($termTaxIds as $ttId) {
            $tt = $this->wpTermTaxonomy[$ttId] ?? null;
            if ($tt && $tt['taxonomy'] === $taxonomy) {
                $term = $this->wpTerms[$tt['term_id']] ?? null;
                if ($term) {
                    $terms[] = html_entity_decode($term['name'], ENT_QUOTES, 'UTF-8');
                }
            }
        }

        return $terms;
    }

    private function getAuthorName(string $authorId): string
    {
        $user = $this->wpUsers[$authorId] ?? null;

        return $user
            ? html_entity_decode($user['display_name'], ENT_QUOTES, 'UTF-8')
            : 'Redação';
    }

    private function downloadFeaturedImage(string $postId, string $folder, string $slug): ?string
    {
        $meta = $this->wpPostmeta[$postId] ?? [];
        $thumbnailId = $meta['_thumbnail_id'] ?? null;

        if (! $thumbnailId) {
            return null;
        }

        // Get attachment post
        $attachment = $this->wpPosts[$thumbnailId] ?? null;
        if (! $attachment || $attachment['post_type'] !== 'attachment') {
            return null;
        }

        $imageUrl = $attachment['guid'] ?? null;
        if (! $imageUrl) {
            return null;
        }

        // Ensure absolute URL
        if (! str_starts_with($imageUrl, 'http')) {
            $imageUrl = $this->wpUrl . '/' . ltrim($imageUrl, '/');
        }

        // Determine file extension
        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = "{$slug}.{$extension}";

        return $this->downloadMedia($imageUrl, $folder, $filename);
    }

    private function downloadAttachment(string $attachmentId, string $folder, string $slug): ?string
    {
        $attachment = $this->wpPosts[$attachmentId] ?? null;
        if (! $attachment || $attachment['post_type'] !== 'attachment') {
            return null;
        }

        $imageUrl = $attachment['guid'] ?? null;
        if (! $imageUrl) {
            return null;
        }

        if (! str_starts_with($imageUrl, 'http')) {
            $imageUrl = $this->wpUrl . '/' . ltrim($imageUrl, '/');
        }

        $extension = pathinfo(parse_url($imageUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
        $filename = "{$slug}.{$extension}";

        return $this->downloadMedia($imageUrl, $folder, $filename);
    }

    private function downloadMedia(string $url, string $folder, string $filename): ?string
    {
        try {
            Storage::disk('public')->makeDirectory($folder);

            $response = Http::withOptions([
                'allow_redirects' => true,
                'timeout' => 60,
                'connect_timeout' => 15,
            ])->withHeaders([
                'User-Agent' => 'Mozilla/5.0 (compatible; PraiaNorte-Migration/1.0)',
                'Accept' => 'image/*,*/*',
            ])->get($url);

            if ($response->successful() && strlen($response->body()) > 100) {
                $path = "{$folder}/{$filename}";
                Storage::disk('public')->put($path, $response->body());
                $size = round(strlen($response->body()) / 1024);
                $this->line("    Media: {$filename} ({$size} KB)");
                $this->mediaDownloaded++;

                return $path;
            }

            $this->warn("    Media failed: {$url} (HTTP {$response->status()})");
            $this->mediaFailed++;
        } catch (\Exception $e) {
            $this->warn("    Media failed: " . Str::limit($url, 80) . " ({$e->getMessage()})");
            $this->mediaFailed++;
        }

        usleep(200000); // 200ms polite delay

        return null;
    }

    private function cleanWordPressHtml(string $html): string
    {
        $html = html_entity_decode($html, ENT_QUOTES, 'UTF-8');

        // Remove WordPress shortcodes
        $html = preg_replace('/\[\/?\w+[^\]]*\]/', '', $html);

        // Remove inline styles, classes, data attributes, ids
        $html = preg_replace('/\s*style="[^"]*"/', '', $html);
        $html = preg_replace('/\s*class="[^"]*"/', '', $html);
        $html = preg_replace('/\s*data-[\w-]+="[^"]*"/', '', $html);
        $html = preg_replace('/\s*id="[^"]*"/', '', $html);

        // Remove empty spans/divs
        $html = preg_replace('/<span\s*>\s*<\/span>/', '', $html);
        $html = preg_replace('/<div\s*>\s*<\/div>/', '', $html);

        // Convert div to p
        $html = preg_replace('/<div([^>]*)>/', '<p$1>', $html);
        $html = str_replace('</div>', '</p>', $html);

        // Remove consecutive empty paragraphs
        $html = preg_replace('/(<p\s*>\s*<\/p>\s*){2,}/', '', $html);

        // Remove caption wrappers
        $html = preg_replace('/\[caption[^\]]*\](.*?)\[\/caption\]/s', '$1', $html);

        // Clean whitespace
        $html = preg_replace('/\n{3,}/', "\n\n", $html);
        $html = trim($html);

        // Wrap plain text in paragraphs
        if ($html && ! preg_match('/<[a-z][\s\S]*>/i', $html)) {
            $paragraphs = array_filter(explode("\n\n", $html));
            $html = '<p>' . implode('</p><p>', $paragraphs) . '</p>';
        }

        return $html;
    }
}
