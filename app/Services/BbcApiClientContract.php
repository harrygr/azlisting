<?php

namespace App\Services;


interface BbcApiClientContract {

    /**
     * Get a collection of programmes listed by letter
     * @param  string $letter The letter for which to query the programmes
     * @return \Illuminate\Support\Collection 
     */
    public function getProgrammes($letter);
}