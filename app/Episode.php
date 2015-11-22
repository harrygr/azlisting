<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    /**
     * The attributes that cannot be mass-assigned
     * @var array
     */
    protected $guarded = [];

    /**
     * Parse the date into a carbon instance when setting it on the mode
     * @param string $date
     */
    protected function setReleaseDateAttribute($date)
    {
        $this->attributes['release_date'] = Carbon::parse($date);
    }
}
