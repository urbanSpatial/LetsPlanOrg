<?php

namespace App\Console\Commands;

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
        $parcels = \DB::table($table)->cursor();
        echo '{
        "type": "FeatureCollection",
            "name": "RCO_PARCELS",
            "crs": { "type": "name", "properties": { "name": "urn:ogc:def:crs:OGC:1.3:CRS84" } },
            "features": [
';

        $lastDelim = '';
        foreach ($parcels as $parcel) {
            echo $lastDelim;
            echo $parcel->geo_json;
            $lastDelim = ",\n";
        }

        echo "]}\n";
        return 0;
    }
}
