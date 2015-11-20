<?php

use App\Services\BbcApiClient;
use App\Services\ProgrammeParserInterface;
use GuzzleHttp\ClientInterface;
use Illuminate\Cache\Repository;
use Psr\Http\Message\ResponseInterface;

class BbcApiClientTest extends TestCase
{
    /** @test **/
    public function it_is_instantiable()
    {
        // This just ensure we can resolve the class out of the IoC container without problem
        // This will make sure all dependencies are correctly injected via the IoC container
        $client = app(BbcApiClient::class);
        $this->assertInstanceOf(BbcApiClient::class, $client);
    }

    /** @test **/
    public function it_fetches_some_programmes()
    {
        //$mocked_cache = \Mockery::mock(Repository::class);
        $mocked_cache = app(Repository::class);

        // We need to mock the BBC Api so we'll ensure a sample snippet of json is returned from the mocked http client
        $mocked_http_response = \Mockery::mock(['getContent' => 'some json']);

        $mocked_http_client = \Mockery::mock(ClientInterface::class);
        $mocked_http_client->shouldReceive('request')->andReturn($mocked_http_response);

        $mocked_programme_parser = \Mockery::mock(ProgrammeParserInterface::class);
        $mocked_programme_parser->shouldReceive('parse')->andReturn('foo'); // In reality this would be our collection

        $client = new BbcApiClient($mocked_http_client, $mocked_programme_parser, $mocked_cache);

        $programmes = $client->getProgrammes('A');
    }

    /** @test **/
    public function it_fetches_cached_results()
    {
        // Get a real cache repo - this must be set to something that actually caches in phpunit.xml
        $cache = app(Repository::class);
        $cache->flush(); // clear the cache before proceeding

        $mocked_programme_parser = \Mockery::mock(ProgrammeParserInterface::class, function($mock) {
            $mock->shouldReceive('parse');
        });

        $mocked_http_client = \Mockery::mock(ClientInterface::class);
        $mocked_http_client->shouldReceive('request')->once()->andReturn(\Mockery::mock(['getContent' => 'foo']));

        $client = new BbcApiClient($mocked_http_client, $mocked_programme_parser, $cache);

        $programmes = $client->getProgrammes('A');

        // call it a second time
        $programmes2 = $client->getProgrammes('A');
    }
}
