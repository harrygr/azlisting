<?php

namespace App\Services;

use App\Episode;
use App\Programme;
use Carbon\Carbon;

class ProgrammeParser implements ProgrammeParserInterface {

    /**
     * The default dimensions for images
     * @var string
     */
    private $image_recipe = '406x228';

    /**
     * Parse an array of programmes into a collection of Programme Models
     * @param  Array $content The raw array from the BBC API response
     * @return \Illuminate\Support\Collection     
     */
    public function parse($content)
    {
        $programmes = collect($content['atoz_programmes']['elements']);

        return $programmes->map(function($programme) {
            return new Programme([
                'title'     => $programme['title'],
                'synopsis'  => $programme['synopses']['medium'],
                'image'     => $this->getImageUrl($programme['images']['standard']),
                'episodes'  => $this->parseEpisodes($programme['initial_children']),
                ]);
        });
    }

    /**
     * Parse an array of episodes into a collection of Episode models
     * @param  Array $episodes The array of episodes to parse
     * @return \Illuminate\Support\Collection 
     */
    private function parseEpisodes($episodes)
    {
        $episodes = collect($episodes);
        return $episodes->map(function($episode) {
            return new Episode([
                'title'         => $episode['title'],
                'subtitle'      => isset($episode['subtitle']) ? $episode['subtitle'] : null,
                'synopsis'      => $episode['synopses']['medium'],
                'release_date'  => $episode['release_date_time'],
                ]);
        });
    }

    /**
     * Process the url for an image using the dimension recipe
     * @param  string $url The URL with the {recipe} placeholder
     * @return string
     */
    private function getImageUrl($url)
    {
        return str_replace('{recipe}', $this->image_recipe, $url);
    }

}