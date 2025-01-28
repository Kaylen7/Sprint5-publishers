<?php

namespace App\Providers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;
use App\Services\Database\InMemoryDatabaseManager;

class SwaggerDatabaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            return;
        }

        $request = $this->app['request'];

        if ($this->isSwaggerRequest($request)) {
            $this->loadTestingEnvironment();
            $this->overridePassportConfig();

            $this->configureInMemoryDatabase();
        }
    }

    protected function isSwaggerRequest($request)
    {
        $referer = $request->headers;
        return ($referer && Str::contains($referer, ['/api/documentation', '/swagger']) || $request->hasHeader('X-Documentation'));
    }

    protected function configureInMemoryDatabase()
    {
        InMemoryDatabaseManager::getInstance()->configureDatabase();
    }

    protected function overridePassportConfig()
{
    // Override Passport configuration values
    Config::set('passport.password.id', env('PASSWORD_CLIENT_ID'));
    Config::set('passport.password.secret', env('PASSWORD_CLIENT_SECRET'));

    \Log::info('New passport', [
        'id' => env('PASSWORD_CLIENT_ID'),
        'pwd' => env('PASSWORD_CLIENT_SECRET')
    ]);
}

    protected function loadTestingEnvironment(){
        $this->originalEnv = $_ENV;
        
        $envFilePath = base_path('.env.testing');
        if (file_exists($envFilePath)) {
            $lines = file($envFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (strpos(trim($line), '#') === 0) {
                    continue; // Skip comments
                }
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                putenv("$name=$value");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    protected function restoreOriginalEnvironment(){
        // Restore the original environment variables
    foreach ($_ENV as $key => $value) {
        if (!isset($this->originalEnv[$key])) {
            unset($_ENV[$key]);
            putenv($key);
        }
    }
    foreach ($this->originalEnv as $key => $value) {
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
    }
}