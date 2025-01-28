<?php

namespace App\Services\Database;
use Illuminate\Support\Facades\Config;
use Database\Seeders\DocsSeeder;
use Illuminate\Support\Facades\DB;

class InMemoryDatabaseManager
{
    private static $instance = null;
    private static $isConfigured = false;

    private function __construct()
    {
        //
    }

    public static function getInstance(){
        if(self::$instance === null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function configureDatabase(){
        if (self::$isConfigured){
            \Log::info('Database already configured, skipping setup');
            return;
        }

        try {
            Config::set('database.default', 'sqlite_testing');

            //DB::purge('sqlite_testing');
            //DB::reconnect('sqlite_testing');

            \Artisan::call('migrate', [
                '--database' => 'sqlite_testing',
                '--force' => true
            ]);

            \Artisan::call('db:seed', [
                '--class' => DocsSeeder::class,
                '--database' => 'sqlite_testing'
            ]);

            self::$isConfigured = true;

            \Log::info('Switched to in-memory database', [
                'connection' => DB::connection()->getName(),
                'database' => DB::connection()->getDatabaseName(),
            ]);
        } catch (\Exception $e){
            \Log::error('Failed to configure in-memory database', [
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    private function __clone(){}
    public function __wakeup(){}
}
