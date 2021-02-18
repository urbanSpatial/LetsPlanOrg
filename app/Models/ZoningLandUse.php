<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\Point;

class ZoningLandUse extends Model
{
    use HasFactory, PostgisTrait;

    public $guarded = [];
    public $table = 'zoning_land_use';
    protected $postgisFields = [
        'geo_point',
        Point::class
    ];
    protected $postgisTypes = [
        'geo_point' => [
            'geomtype' => 'geography',
            'srid' => '4326'
        ],
    ];
}
