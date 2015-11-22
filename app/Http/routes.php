<?php

get('/', ['uses' => 'HomeController@index']);

get('api/programmes/{letter}', ['uses' => 'ProgrammesController@index']);