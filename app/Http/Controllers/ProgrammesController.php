<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Services\BbcApiClientContract;
use Illuminate\Http\Request;

class ProgrammesController extends Controller
{
    public function index($letter, Request $request, BbcApiClientContract $bbc_client)
    {
        $programmes = $bbc_client->getProgrammes($letter);
        return $programmes;
    }
}
