<?php

namespace App\Services;

use App\Programme;
use GuzzleHttp\ClientInterface;

class HttpBbcApiClient implements BbcApiClientContract
{
    private $http_client;
    private $parser;
    private $endpoint = 'https://ibl.api.bbci.co.uk/ibl/v1/atoz/';

    public function __construct(ClientInterface $http_client, ProgrammeParserInterface $parser)
    {
        $this->http_client = $http_client;
        $this->parser = $parser;
    }

    public function getProgrammes($letter)
    {
        
        $response = $this->http_client->request('GET', $this->buildUrl($letter));
            
        return $this->parser->parse($response->getBody());

    }

    protected function buildUrl($letter)
    {
        return $this->endpoint . "{$letter}/programmes";
    }
}