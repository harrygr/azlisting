<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $guarded = [];

    protected function setReleaseDateAttribute($date)
    {
        $this->attributes['release_date'] = Carbon::parse($date);
    }
}
