<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use Laravel\Fortify\Fortify;
use Illuminate\Support\Facades\Storage;
use Masbug\Flysystem\GoogleDriveAdapter;
use Google\Client;
use Google\Service\Drive;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
{    
    // After login redirect fix
    \Illuminate\Support\Facades\URL::forceRootUrl(config('app.url'));

    // Breeze ko batao login ke baad kahan jaana hai
    \Illuminate\Routing\Router::macro('home', function() {
        return '/student/dashboard';
    });
    Storage::extend('gdrive', function ($app, $config) {

        $client = new Client();
        $client->setAuthConfig(storage_path('app/your-json-file.json'));
        $client->addScope(Drive::DRIVE);

        $service = new Drive($client);

        $adapter = new GoogleDriveAdapter($service, $config['folderId']);

        return new \League\Flysystem\Filesystem($adapter);
    });
}
}
