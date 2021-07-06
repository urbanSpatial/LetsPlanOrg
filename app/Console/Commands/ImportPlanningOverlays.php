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
                           ' {planning-file : zoning CSV file relative to storage/app/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Planning Overlays';

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
        $dataFile = $this->argument('planning-file');
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

           // 1 "opa_account_num","dev_index", "pres_index", "sum"
           // 2 "11007810", 93, "NA", "NA"
           // 3 "11008200", 2, "NA", "NA"
            $bp = new PlanningOverlays([
                "opa_account_num"    => $data_row[0] == 'NA' ? null : $data_row[0],
                "dev_index"          => $data_row[1] == 'NA' ? null : $data_row[1],
                "pres_index"         => $data_row[2] == 'NA' ? null : $data_row[2],
                "sum"                => $data_row[3] == 'NA' ? null : $data_row[3],
            ]);
            $q = $bp->save();
        }
        \DB::commit();

        is_resource($f) && @fclose($f);
        return 0;
    }
}
