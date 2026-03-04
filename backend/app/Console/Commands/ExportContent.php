<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ExportContent extends Command
{
    protected $signature = 'app:export-content
        {path : Path to write the JSON export file}';

    protected $description = 'Export all content tables to a JSON file for migration to another environment';

    /**
     * Tables to export, in order respecting foreign key dependencies.
     */
    private const TABLES = [
        'surfers',
        'noticias',
        'eventos',
        'paginas',
        'hero_slides',
        'document_categories',
        'documents',
        'corporate_bodies',
        'site_settings',
    ];

    public function handle(): int
    {
        $path = $this->argument('path');

        $this->info('Exporting content tables...');

        $export = [
            'exported_at' => now()->toIso8601String(),
            'tables' => [],
        ];

        foreach (self::TABLES as $table) {
            $rows = DB::table($table)->get()->map(fn ($row) => (array) $row)->all();
            $export['tables'][$table] = $rows;
            $this->line("  {$table}: " . count($rows) . ' rows');
        }

        $json = json_encode($export, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $fullPath = base_path($path);
        $dir = dirname($fullPath);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        file_put_contents($fullPath, $json);

        $this->newLine();
        $this->info("Exported to: {$fullPath}");
        $this->info('Total tables: ' . count(self::TABLES));

        return self::SUCCESS;
    }
}
