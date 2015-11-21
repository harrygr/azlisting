<?php

use App\Services\HttpBbcApiClient;
use App\Services\ProgrammeParserInterface;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class HttpBbcApiClientTest extends TestCase
{
    /** @test **/
    public function it_is_instantiable()
    {
        // This just ensure we can resolve the class out of the IoC container without problem
        // This will make sure all dependencies are correctly injected via the IoC container
        $client = app(HttpBbcApiClient::class);
        $this->assertInstanceOf(HttpBbcApiClient::class, $client);
    }

    /** @test **/
    public function it_fetches_some_programmes()
    {
        // We need to mock the BBC Api so we'll ensure a sample snippet of json is returned from the mocked http client
        $mocked_http_response = \Mockery::mock(['getBody' => 'some json']);

        $mocked_http_client = \Mockery::mock(ClientInterface::class);
        $mocked_http_client->shouldReceive('request')->andReturn($mocked_http_response);

        $mocked_programme_parser = \Mockery::mock(ProgrammeParserInterface::class);
        $mocked_programme_parser->shouldReceive('parse')
                                ->with('some json')
                                ->andReturn('foo'); // In reality this would be our collection

        $client = new HttpBbcApiClient($mocked_http_client, $mocked_programme_parser);

        $programmes = $client->getProgrammes('A');
        $this->assertEquals('foo', $programmes);
    }

}
