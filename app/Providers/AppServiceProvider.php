<?php

namespace App\Providers;

use App\Services\DeepSeekService;
use Illuminate\Http\Client\Factory as HttpClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(DeepSeekService::class, function ($app) {
            return new DeepSeekService(
                http: $app->make(HttpClient::class),
                apiKey: config('services.deepseek.key'),
                model: config('services.deepseek.model', 'deepseek-chat'),
            );
        });
    }

    public function boot(): void
    {
        //
    }
}
