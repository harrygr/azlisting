<?php

namespace App\Services;

use App\Episode;
use App\Programme;
use Carbon\Carbon;

class ProgrammeParser implements ProgrammeParserInterface {

    private $image_recipe = '406x228';

    public function parse($json)
    {
        $content = json_decode($json, true);
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

    private function parseEpisodes($episodes)
    {
        $episodes = collect($episodes);
        return $episodes->map(function($episode) {
            return new Episode([
                'title'         => $episode['title'],
                'subtitle'      => $episode['subtitle'],
                'synopsis'      => $episode['synopses']['medium'],
                'release_date'  => $episode['release_date_time'],
                ]);
        });
    }

    public function getImageUrl($url)
    {
        return str_replace('{recipe}', $this->image_recipe, $url);
    }

}