<?php

use App\Services\BbcApiClientContract;
use App\Services\CacheBbcApiClient;
use App\Services\HttpBbcApiClient;
use Illuminate\Contracts\Cache\Repository as Cache;

class CacheBbcApiClientTest extends TestCase
{
    /** @test **/
    public function it_is_instantiable()
    {
        $client = new CacheBbcApiClient(
                     $this->app->make(HttpBbcApiClient::class),
                     $this->app->make(Cache::class)
                     );
        $this->assertInstanceOf(BbcApiClientContract::class, $client);
    }

    /** @test **/
    public function it_fetches_some_programmes()
    {
        $mocked_bbc_client = \Mockery::mock(BbcApiClientContract::class);

        $mocked_cache = \Mockery::mock(Cache::class);
        $mocked_cache->shouldReceive('remember')->once()->andReturn('foo');

        $client = new CacheBbcApiClient($mocked_bbc_client, $mocked_cache);

        $programmes = $client->getProgrammes('A');
        $this->assertEquals('foo', $programmes);
    }

}
