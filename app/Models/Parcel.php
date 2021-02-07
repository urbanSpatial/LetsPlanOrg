<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parcel extends Model
{
    use HasFactory;
    public $guarded = [];
    public $table = 'parcel';
    protected $casts = [
        'geo_json' => 'array',
    ];

    public static function fromGeoJsonFeature($feature) {
        $prop = $feature->properties;
        $parcel = new static([
            'parcel_id'   => $prop->PARCELID,
            'tenant_code' => $prop->TENCODE,
            'address_1'   => $prop->ADDRESS,
            'owner_1'     => $prop->OWNER1,
            'bldg_code'   => $prop->BLDG_CODE,
            'geo_json'    => $feature->geometry,
        ]);
        return $parcel;
    }
}
