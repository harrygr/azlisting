<?php

namespace App\Services;

use App\Programme;
use GuzzleHttp\ClientInterface;
use Illuminate\Cache\Repository as Cache;

class BbcApiClient
{
    private $http_client;
    private $cache;
    private $parser;
    private $endpoint = 'https://ibl.api.bbci.co.uk/ibl/v1/atoz/';

    public function __construct(ClientInterface $http_client, ProgrammeParserInterface $parser, Cache $cache)
    {
        $this->http_client = $http_client;
        $this->cache = $cache;
        $this->parser = $parser;
    }

    public function getProgrammes($letter)
    {
        return $this->cache->remember("programmes.$letter", 10, function() use ($letter) {
            $response = $this->http_client->request('GET', $this->buildUrl($letter));
            
            return $this->parser->parse($response->getContent());
        });
    }

    protected function buildUrl($letter)
    {
        return $this->endpoint . "{$letter}/programmes";
    }
}