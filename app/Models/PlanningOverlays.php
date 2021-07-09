<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use MStaack\LaravelPostgis\Eloquent\PostgisTrait;
use MStaack\LaravelPostgis\Geometries\Point;

class PlanningOverlays extends Model
{
    use HasFactory;
    use PostgisTrait;

    public $guarded = [];
    public $table = 'planning_overlays';
    protected $postgisFields = [];
    protected $postgisTypes = [];
}
