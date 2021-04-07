<?php

namespace App\Console\Commands;

use App\Models\RealEstateTx;
use Illuminate\Console\Command;

class StreamParcelGeoJson extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:stream-parcel-geo-json {project_table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Stream parcel geojson for feeding into tippecanoe';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $table = $this->argument('project_table');
        $parcels = \DB::table($table)
            ->select([$table . '.*', 'zoning_land_use.landuse', 'zoning_land_use.bldg_desc'])
            ->leftJoin('atlas_data', 'atlas_data.parcel_id', $table . '.parcel_id')
            ->leftJoin('zoning_land_use', 'zoning_land_use.opa_account_num', 'atlas_data.opa_account_num')
            ->cursor();
        echo '{
        "type": "FeatureCollection",
            "name": "RCO_PARCELS",
            "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
            "features": [
';

        $lastDelim = '';
        foreach ($parcels as $parcel) {
            echo $lastDelim;
            $geoJson = json_decode($parcel->geo_json, true);
            $salePrice = RealEstateTx::leftJoin('atlas_data', 'atlas_data.opa_account_num', 'real_estate_tx.opa_account_num')
                ->where('atlas_data.parcel_id', $parcel->parcel_id)
                ->where('real_estate_tx.sale_price_adj', '>', 0)
                ->orderBy('sale_date', 'DESC')
                ->first();
            $feature = [
                'type' => 'Feature',
                'properties' => [
                    'landuse'  => $parcel->landuse,
                    'bldg_desc' => $parcel->bldg_desc,
                    'parcel_id' => $parcel->parcel_id,
                    'sale_price_adj' => $salePrice->sale_price_adj ?? null,
                ],
                'geometry' => $geoJson
            ];
            echo json_encode($feature);
            $lastDelim = ",\n";
        }

        echo "]}\n";
        return 0;
    }
}
