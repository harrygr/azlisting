<?php

namespace App\Providers;

use App\Services\BbcApiClientContract;
use App\Services\CacheBbcApiClient;
use App\Services\HttpBbcApiClient;
use App\Services\ProgrammeParser;
use App\Services\ProgrammeParserInterface;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Contracts\Cache\Repository;
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

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ClientInterface::class, Client::class);
        $this->app->bind(ProgrammeParserInterface::class, ProgrammeParser::class);

        $this->app->bind(BbcApiClientContract::class, function() {
            return new CacheBbcApiClient(
                 $this->app->make(HttpBbcApiClient::class),
                 $this->app->make(Repository::class)
                 );
        });
    }
}
