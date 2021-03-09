<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rco extends Model
{
    use HasFactory;
    public $guarded = [];
    public $table = 'rco';
    protected $casts = [
        'geo_json' => 'array',
    ];

	/**
	 * +"OBJECTID": 23628
     * +"ORGANIZATION_NAME": "Asian American Federation of the United States"
     * +"ORGANIZATION_ADDRESS": """
     *   1118 Buttonwood Street, Unit A\r\n
     *   Philadelphia, PA 19123
     *   """
     * +"MEETING_LOCATION_ADDRESS": """
     *   1118 Buttonwood Street, Unit A\r\n
     *   Philadelphia, PA 19123
     *   """
     * +"ORG_TYPE": "Other"
     * +"PREFFERED_CONTACT_METHOD": "Email"
     * +"PRIMARY_NAME": "Mahn S. Park"
     * +"PRIMARY_ADDRESS": "1735 Stanwood Street, 19152"
     * +"PRIMARY_EMAIL": "mahnpark@gmail.com"
     * +"PRIMARY_PHONE": "2159090936"
     * +"P_PHONE_EXT": null
     * +"ALTERNATE_NAME": "Jack Xiao"
     * +"ALTERNATE_ADDRESS": "1354 St. Vincent Street, 19111"
     * +"ALTERNATE_EMAIL": "GLJack@live.com"
     * +"ALTERNATE_PHONE": "2672413388"
     * +"A_PHONE_EXT": null
     * +"EXPIRATIONYEAR": 2021
     * +"EFFECTIVE_DATE": "1970-01-01T00:00:00Z"
     * +"LNI_ID": 110
     * +"Shape__Area": 1489562.4414062
     * +"Shape__Length": 4934.3990676634
	 */
    public static function fromGeoJsonFeature($feature) {
        $prop = $feature->properties;
        $obj = new static([
            'object_id'   => $prop->OBJECTID,
            'org_name'    => $prop->ORGANIZATION_NAME,
            'geo_json'    => $feature->geometry,
        ]);
        return $obj;
    }
}
