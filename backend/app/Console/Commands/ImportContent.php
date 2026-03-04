<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportContent extends Command
{
    protected $signature = 'app:import-content
        {path : Path to the JSON export file}
        {--force : Skip confirmation prompt}';

    protected $description = 'Import content from a JSON export file (replaces existing content)';

    /**
     * Tables in dependency order (children first for truncation, parents first for insertion).
     */
    private const TABLES_INSERT_ORDER = [
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

    private const TABLES_TRUNCATE_ORDER = [
        'documents',
        'hero_slides',
        'site_settings',
        'corporate_bodies',
        'document_categories',
        'paginas',
        'eventos',
        'noticias',
        'surfers',
    ];

    public function handle(): int
    {
        $path = $this->argument('path');
        $fullPath = base_path($path);

        if (! file_exists($fullPath)) {
            $this->error("File not found: {$fullPath}");

            return self::FAILURE;
        }

        $data = json_decode(file_get_contents($fullPath), true);

        if (! $data || ! isset($data['tables'])) {
            $this->error('Invalid export file format.');

            return self::FAILURE;
        }

        $this->info("Export from: {$data['exported_at']}");
        $this->newLine();

        foreach ($data['tables'] as $table => $rows) {
            $this->line("  {$table}: " . count($rows) . ' rows');
        }

        $this->newLine();

        if (! $this->option('force') && ! $this->confirm('This will REPLACE all content in the database. Continue?')) {
            $this->info('Aborted.');

            return self::SUCCESS;
        }

        $driver = DB::connection()->getDriverName();

        DB::transaction(function () use ($data, $driver) {
            // Disable foreign key checks
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=0');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = OFF');
            }

            // Clear tables in reverse dependency order
            // Use DELETE instead of TRUNCATE for MySQL (TRUNCATE causes implicit commit)
            foreach (self::TABLES_TRUNCATE_ORDER as $table) {
                if (isset($data['tables'][$table])) {
                    if ($driver === 'mysql') {
                        DB::table($table)->delete();
                    } else {
                        DB::table($table)->truncate();
                    }
                }
            }

            // Insert in dependency order
            foreach (self::TABLES_INSERT_ORDER as $table) {
                if (! isset($data['tables'][$table]) || empty($data['tables'][$table])) {
                    continue;
                }

                $rows = $data['tables'][$table];

                // Insert in chunks to avoid query size limits
                foreach (array_chunk($rows, 100) as $chunk) {
                    DB::table($table)->insert($chunk);
                }

                $this->info("  Imported {$table}: " . count($rows) . ' rows');
            }

            // Re-enable foreign key checks
            if ($driver === 'mysql') {
                DB::statement('SET FOREIGN_KEY_CHECKS=1');
            } elseif ($driver === 'sqlite') {
                DB::statement('PRAGMA foreign_keys = ON');
            }
        });

        $this->newLine();
        $this->info('Import completed successfully.');

        return self::SUCCESS;
    }
}
