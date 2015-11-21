<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Repository as Cache;

class CacheBbcApiClient implements BbcApiClientContract {

    private $bbc_api_client;
    private $cache;

    public function __construct(BbcApiClientContract $bbc_api_client, Cache $cache)
    {
        $this->bbc_api_client = $bbc_api_client;
        $this->cache = $cache;
    }

    public function getProgrammes($letter)
    {
        
        return $this->cache->remember("bbc.programmes.$letter", 10, function() use ($letter) {
            return $this->bbc_api_client->getProgrammes($letter);
        });

    }


}