<?php

namespace App\Console\Commands;

use App\Models\PlanningOverlays;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use MStaack\LaravelPostgis\Geometries\Point;

class ImportPlanningOverlays extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:import-planning_overlays' .
                           ' {zoning-file : zoning CSV file relative to storage/app/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Zoning Land Use';

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
     */
    public function handle()
    {
        $dataFile = $this->argument('zoning-file');
        if (!Storage::disk('local')->exists($dataFile)) {
            $this->error('Cannot find [' . $dataFile . '] locally, existing ... ');
            return 1;
        }

        $this->info('Found [' . $dataFile . '] locally, proceeding to import ... ');
        $path = Storage::path($dataFile);
        $f = fopen($path, 'r');
        if (!$f) {
            $this->error('Unable to open import file.  quitting.');
            return 1;
        }
        $headers = fgets($f, 4096);
        $count = 0;
        \DB::beginTransaction();
        while (is_resource($f) && !feof($f)) {
            $line = fgets($f, 4096);
            if (trim($line) == '') {
                continue;
            }
            $data_row = str_getcsv($line);
            $count++;
            if ($count % 1000 == 0) {
                $this->info($count . ' ...');
                \DB::commit();
                \DB::beginTransaction();
                //fclose($f);
            }
            $issAt = null;

           // 1 "opa_account_num","zoning","landuse","number_stories","total_livable_area","bldg_desc","lat","lng"
           // 2 "011012000","RSA5 ","Single Family",3,1465,"ROW 3 STY MASONRY",39.9302486735636,-75.1487657003407
           // 3 "011012100","RSA5 ","Single Family",3,1465,"ROW 3 STY MASONRY",39.9302560204392,-75.1488218259425
            // Points are lat, lng
            $bp = new ZoningLandUse([
                "opa_account_num"    => $data_row[0] == 'NA' ? null : $data_row[0],
                "zoning"             => $data_row[1] == 'NA' ? null : $data_row[1],
                "landuse"            => $data_row[2] == 'NA' ? null : $data_row[2],
                "number_stories"     => $data_row[3] == 'NA' ? null : $data_row[3],
                "total_livable_area" => $data_row[4] == 'NA' ? null : $data_row[4],
                "bldg_desc"          => $data_row[5] == 'NA' ? null : $data_row[5],
                "geo_point"          => new Point($data_row[6], $data_row[7])
            ]);
            $q = $bp->save();
        }
        \DB::commit();

        is_resource($f) && @fclose($f);
        return 0;
    }
}
