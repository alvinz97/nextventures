<?php

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PDO;

class InitializeProject extends Command
{
    protected $signature = 'app:init
                {--super-key= : To run in admin mode}
                {--force : Do not ask for user confirmation }';

    protected $description = 'Run & Install dummy data for the Application';

    public function handle(): void
    {
        Cache::flush();

        try {
            $defaultConfig = config('database.connections.mysql');
            $databaseName = $defaultConfig['database'];

            $pdo = new PDO(
                "mysql:host={$defaultConfig['host']};port={$defaultConfig['port']}",
                $defaultConfig['username'],
                $defaultConfig['password']
            );

            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$databaseName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

            Log::channel('commands')->debug("Database '$databaseName' ensured.");
        } catch (Exception $e) {
            Log::channel('commands')->error('Database creation check failed: ' . $e->getMessage());
        }

        try {
            DB::connection()->getPdo();
            Log::debug('Database connection successful.');
        } catch (Exception $e) {
            Log::channel('commands')->error('Database connection failed: ' . $e->getMessage());
        }

        if ($this->option('force') || $this->confirm('This command will delete all current data and install dummy data. Are you sure?')) {
            $this->proceed();
        }
    }

    protected function proceed(): void
    {
        $this->call('optimize:clear');
        $this->call('migrate:fresh', [
            '--seed' => true,
            '--force' => true,
        ]);

        $link = public_path('storage');
        if (is_link($link)) {
            unlink($link);
            $this->info('Old storage link removed.');
        }

        $this->call('storage:link');
        $this->info('Storage folder linked successfully.');

        Log::channel('commands')->info('Required all data successfully installed!');
        $this->info('Required all data successfully installed!');
    }
}
