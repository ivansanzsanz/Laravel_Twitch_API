<?php

namespace App\Providers;

use App\Services\ApiClient;
use App\Services\GetStreamsService;
use App\Services\StreamsDataManager;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        /*$this->app->bind(ApiClient::class, function ($app) {
            return new ApiClient();
        });

        $this->app->bind(StreamsManager::class, function ($app) {
            return new StreamsManager($app->make(ApiClient::class));
        });

        $this->app->bind(GetStreamsService::class, function ($app) {
            return new GetStreamsService($app->make(StreamsManager::class));
        });*/
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
