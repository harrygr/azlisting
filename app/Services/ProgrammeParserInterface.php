<?php

namespace App\Services;

interface ProgrammeParserInterface {
    
    /**
     * Parse an array of programmes into a collection of Programme Models
     * @param  Array $content The raw array from the BBC API response
     * @return \Illuminate\Support\Collection     
     */
    public function parse($json);
}

