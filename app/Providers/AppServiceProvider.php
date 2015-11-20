<?php

namespace App\Providers;

use App\Services\ProgrammeParser;
use App\Services\ProgrammeParserInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(ProgrammeParserInterface::class, ProgrammeParser::class);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
