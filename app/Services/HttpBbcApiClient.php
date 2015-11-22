<?php

namespace App\Services;

use App\Programme;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\Facades\Request;

class HttpBbcApiClient implements BbcApiClientContract
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    private $http_client;

    /**
     * @var \App\Services\ProgrammeParserInterface
     */
    private $parser;

    /**
     * The URL of the BBC API
     * @var string
     */
    private $endpoint = 'https://ibl.api.bbci.co.uk/ibl/v1/atoz/';

    /**
     * Create a new instance of the HttpBbcApiClient
     * @param ClientInterface          $http_client
     * @param ProgrammeParserInterface $parser     
     */
    public function __construct(ClientInterface $http_client, ProgrammeParserInterface $parser)
    {
        $this->http_client = $http_client;
        $this->parser = $parser;
    }

    /**
     * Get a collection of programmes listed by letter
     * @param  string $letter The letter for which to query the programmes
     * @return \Illuminate\Support\Collection 
     */
    public function getProgrammes($letter)
    {

        $response = $this->http_client->request('GET', $this->buildUrl($letter));

        $data = json_decode($response->getBody(), true);

        return [
            'pagination' => $this->getPageInfo($data),
            'programmes' => $this->parser->parse($data),
        ];

    }

    /**
     * Build the URL to send the request to
     * @param  string $letter
     * @return string
     */
    protected function buildUrl($letter)
    {
        $page = Request::get('page', 1);
        return $this->endpoint . "$letter/programmes?page=$page";
    }

    /**
     * Get some pagination parameters from the query result
     * @param  Array $data 
     * @return Array
     */
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