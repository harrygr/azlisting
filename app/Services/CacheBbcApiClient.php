<?php

namespace App\Services;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Support\Facades\Request;

class CacheBbcApiClient implements BbcApiClientContract {

    private $bbc_api_client;
    private $cache;

    /**
     * Create a new instance of the CacheBbcApiClient
     * @param BbcApiClientContract $bbc_api_client Another implementation of CacheBbcApiClient
     * @param Cache                $cache          An implementation of the Cache Repository contract
     */
    public function __construct(BbcApiClientContract $bbc_api_client, Cache $cache)
    {
        $this->bbc_api_client = $bbc_api_client;
        $this->cache = $cache;
    }

    /**
     * Get a collection of programmes listed by letter, using a cached record if it exists
     * @param  string $letter The letter for which to query the programmes
     * @return \Illuminate\Support\Collection 
     */
    public function getProgrammes($letter)
    {
        $page = Request::get('page', 1);
        return $this->cache->remember("bbc.programmes.$letter.page.$page", \Config::get('cache.remember_time'), function() use ($letter) {
            return $this->bbc_api_client->getProgrammes($letter);
        });

    }


}