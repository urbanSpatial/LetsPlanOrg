<?php

namespace App\Console\Commands;

use App\Models\Rco;
use Illuminate\Console\Command;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

class ImportRco extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:import-rco';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Open Philly data to storage/app/, import rco definitions.';

    /**
     * @var string
     */
    protected $dataDir  = 'com.arcgis.opendata.data-phl';
    protected $dataFile = 'efbff0359c3e43f190e8c35ce9fa71d6_0.geojson';


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        if (!Storage::disk('local')->exists($this->dataDir . '/' . $this->dataFile)) {
            $this->info('Cannot find [' . $this->dataDir . '/' . $this->dataFile . '] locally, proceeding to download (approx 2-3 Mb) ... ');
            $f = fopen('http://data-phl.opendata.arcgis.com/datasets/' . $this->dataFile, 'r');
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
        while (is_resource($f) && !feof($f)) {
            $line = fgets($f, 8192);
            $count++;
            if ($count % 1000 == 0) {
                $this->info($count . ' ...');
                //fclose($f);
            }
            $feature = $this->transformGeoJsonFeature($line);
            if (!$feature) {
                continue;
            }
            $parcel = Rco::fromGeoJsonFeature($feature);
            Rco::insertOrIgnore(
                $parcel->getAttributes()
            );
        }

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
