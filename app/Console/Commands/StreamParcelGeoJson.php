<?php

namespace App\Console\Commands;

use App\Models\RealEstateTx;
use App\Models\ZoningLandUse;
use App\Models\BldgPermit;
use App\Models\Traits\DefinesLandUseZones;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StreamParcelGeoJson extends Command
{
    use DefinesLandUseZones;

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
        ob_start();
        $table = $this->argument('project_table');
        $parcels = \DB::table($table)
            ->select([
                $table . '.parcel_id',
                $table . '.geo_json',
                'zoning_land_use.landuse',
                'zoning_land_use.bldg_desc',
            ])
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
            $salePrice = RealEstateTx::leftJoin(
                    'atlas_data',
                    'atlas_data.opa_account_num',
                    'real_estate_tx.opa_account_num'
                )
                ->where('atlas_data.parcel_id', $parcel->parcel_id)
                ->where('real_estate_tx.sale_price_adj', '>', 0)
                ->orderBy('sale_date', 'DESC')
                ->first();
            $landUse = ZoningLandUse::leftJoin(
                    'atlas_data',
                    'atlas_data.opa_account_num',
                    'zoning_land_use.opa_account_num'
                )
                ->select('zoning_land_use.bldg_desc', 'zoning_land_use.zoning')
                ->where('atlas_data.parcel_id', $parcel->parcel_id)
                ->first();

            $alterPermit = BldgPermit::leftJoin(
                    'atlas_data',
                    'atlas_data.opa_account_num',
                    'bldg_permit.opa_account_num'
                )
                ->selectRaw('count(bldg_permit.id) as permit_count')
                ->where('atlas_data.parcel_id', $parcel->parcel_id)
                ->where('bldg_permit.permit_type', 'BP_ALTER  ')
                ->first();

            $constPermit = BldgPermit::leftJoin(
                    'atlas_data',
                    'atlas_data.opa_account_num',
                    'bldg_permit.opa_account_num'
                )
                ->selectRaw('count(bldg_permit.id) as permit_count')
                ->where('atlas_data.parcel_id', $parcel->parcel_id)
                ->where('bldg_permit.permit_type', 'BP_NEWCNST')
                ->first();


            $feature = [
                'type' => 'Feature',
                'properties' => [
                    'landuse'  => $parcel->landuse,
                    'bldg_desc' => $parcel->bldg_desc,
                    'parcel_id' => $parcel->parcel_id,
                    'sale_price_adj' => $salePrice->sale_price_adj ?? null,
                    'alter_permits' => $alterPermit->permit_count ?? null,
                    'const_permits' => $constPermit->permit_count ?? null,
                    'zoning' => $this->transformLandUse($landUse),
                ],
                'geometry' => $geoJson
            ];
            echo json_encode($feature);
            $lastDelim = ",\n";
        }

        echo "]}\n";
        $this->getOutput()->write(ob_get_contents());
        ob_end_clean();
        return 0;
    }

    protected function transformLandUse($landUse)
    {
        if ($landUse === null) {
            return null;
        }
        $zoning = trim($landUse->zoning);
        if (in_array($zoning, self::$industrial)) {
            return 'industrial';
        }
        if (in_array($zoning, self::$residential)) {
            return 'residential';
        }
        if (in_array($zoning, self::$residentialHigh)) {
            return 'residential-high';
        }
        if (in_array($zoning, self::$commercial)) {
            return 'commercial';
        }
        if (in_array($zoning, self::$commercialHigh)) {
            return 'commercial-high';
        }
        if (in_array($zoning, self::$special)) {
            return 'special';
        }
        return '';
    }
}
