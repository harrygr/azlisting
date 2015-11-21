<?php

namespace App\Services;

use App\Programme;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Request;

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

        $data = json_decode($response->getBody(), true);

        return [
            'pagination' => $this->getPageInfo($data),
            'programmes' => $this->parser->parse($data),
        ];

    }

    protected function buildUrl($letter)
    {
        $page = Request::get('page', 1);
        return $this->endpoint . "$letter/programmes?page=$page";
    }

    protected function getPageInfo($data)
    {
        $page = $data['atoz_programmes']['page'];
        $total_items = $data['atoz_programmes']['count'];
        $per_page = $data['atoz_programmes']['per_page'];
        $first_item = (($page - 1) * $per_page) + 1;
        $last_item = $page * $per_page;
        $last_page = intval($data['atoz_programmes']['count'] / $per_page) + 1;

        return compact('page', 'total_items', 'per_page', 'last_page', 'first_item', 'last_item');
    }
}