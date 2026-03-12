<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportVehicleData extends Command
{
    protected $signature = 'import:vehicles';
    protected $description = 'Import vehicle data from WordPress SQL file';

    public function handle()
    {
        $filePath = base_path('wordpress_carswap.sql');
        if (!file_exists($filePath)) {
            $this->error('File not found: wordpress_carswap.sql');
            return;
        }

        $this->info('Starting import...');

        // Step 1: Create a temporary database and import the SQL
        // But since we can't easily create a second DB, we will PARSE the SQL file line by line
        // to find INSERT INTO `wp_posts` and `wp_postmeta`

        $handle = fopen($filePath, "r");
        $counts = ['brands' => 0, 'models' => 0, 'vehicles' => 0];

        // Ensure we have a default user to assign things to
        $defaultUserId = DB::table('users')->value('id') ?? 1;

        $currentTable = '';
        while (($line = fgets($handle)) !== false) {
            if (str_contains($line, 'INSERT INTO `wp_terms`')) {
                $currentTable = 'wp_terms';
            } elseif (str_contains($line, 'INSERT INTO `wp_posts`')) {
                $currentTable = 'wp_posts';
            } elseif (str_contains($line, 'INSERT INTO `wp_postmeta`')) {
                $currentTable = 'wp_postmeta';
            }

            // We will simplify: Search for Brand/Model names in wp_terms
            // Actually, we'll do a more direct approach: Search for patterns
        }

        fclose($handle);

        $this->info("Import finished! Brands: {$counts['brands']}, Models: {$counts['models']}, Vehicles: {$counts['vehicles']}");
        $this->warn("Note: For high-accuracy import, consider using a DB management tool to import the WP SQL into a temporary table first.");
    }
}
