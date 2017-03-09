<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Map extends Model
{
    protected $casts = [
        'geojson' => 'object',
    ];
}
