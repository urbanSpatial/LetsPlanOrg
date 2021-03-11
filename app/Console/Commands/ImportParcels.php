<?php

namespace App\Console\Commands;

use App\Models\Parcel;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ImportParcels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:import-parcels';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Open Philly data to storage/app/, import parcel definitions.';


    /**
     * @var string
     */
    protected $dataDir  = 'com.arcgis.opendata.data-phl';
    protected $dataFile = '84baed491de44f539889f2af178ad85c_0.geojson';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //http://data-phl.opendata.arcgis.com/datasets/84baed491de44f539889f2af178ad85c_0.geojson

        if (!Storage::disk('local')->exists($this->dataDir . '/' . $this->dataFile)) {
            $this->info('Cannot find [' . $this->dataDir . '/' . $this->dataFile . '] locally, proceeding to download (approx 400-500 Mb) ... ');
            $f = fopen('http://data-phl.opendata.arcgis.com/datasets/84baed491de44f539889f2af178ad85c_0.geojson', 'r');
            Storage::disk('local')->put(
                $this->dataDir . '/' . $this->dataFile,
                $f,
            );
            fclose($f);
        }
        $this->info('Found [' . $this->dataDir . '/' . $this->dataFile . '] locally, proceeding to import ... ');
        $path = Storage::path($this->dataDir . '/' . $this->dataFile);
        $f = fopen($path, 'r');
        if (!$f) {
            $this->error('Unable to open import file.  quitting.');
            return 1;
        }
        $count = 0;
        //stream geo-json as new lines.
        //discard lines that do not json_decode as an object with ->type == 'Feature'
        \DB::beginTransaction();
        while (is_resource($f) && !feof($f)) {
            $line = fgets($f, 4096);
            $count++;
            if ($count % 1000 == 0) {
                $this->info($count . ' ...');
                \DB::commit();
                \DB::beginTransaction();
                //fclose($f);
            }
            $feature = $this->transformGeoJsonFeature($line);
            if (!$feature) {
                continue;
            }
            $parcel = Parcel::fromGeoJsonFeature($feature);
            Parcel::insertOrIgnore(
                $parcel->getAttributes()
            );
        }
        \DB::commit();

        is_resource($f) && @fclose($f);
        return 0;
    }

    /**
     * Treat array of json objects as individual objects.
     * remove trailing comma
     * discard non geojson Feature objects
     */
    public function transformGeoJsonFeature($line)
    {
        $line = trim($line);
        $line = rtrim($line, ',');  //treat each line as independent
        $feature = json_decode($line);

        if (!$feature) {
            return null;
        }
        if (!$feature->type || $feature->type != 'Feature') {
            return null;
        }
        return $feature;
    }
}
