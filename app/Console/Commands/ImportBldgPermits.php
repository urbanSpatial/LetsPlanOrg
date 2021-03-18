<?php

namespace App\Console\Commands;

use App\Models\BldgPermit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use MStaack\LaravelPostgis\Geometries\Point;

class ImportBldgPermits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:import-bldg-permits' .
                           ' {permit-file : permit CSV file relative to storage/app/}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Building Permits';

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
     * "permitnumber","permitdescription","permittype","permitissuedate","status","opa_account_num","mostrecentinsp","lat","lng","year"
     */
    public function handle()
    {
        $dataFile = $this->argument('permit-file');
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
            $bldg_permit = str_getcsv($line);
            $count++;
            if ($count % 1000 == 0) {
                $this->info($count . ' ...');
                \DB::commit();
                \DB::beginTransaction();
                //fclose($f);
            }
            $issAt = null;
            try {
                $issAt = \Carbon\Carbon::parse($bldg_permit[3]);
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            }
            $inpAt = null;
            try {
                $inpAt = \Carbon\Carbon::parse($bldg_permit[6]);
            } catch (\Carbon\Exceptions\InvalidFormatException $e) {
            }
            $opaNum = $bldg_permit[5] == 'NA' ? null
                : $bldg_permit[5];


            // Points are lat, lng
            $bp = new BldgPermit([
                "permit_num"      => $bldg_permit[0],
                "description"     => $bldg_permit[1],
                "permit_type"     => $bldg_permit[2],
                "permit_year"     => $bldg_permit[9],
                "issued_at"       => $issAt,
                "status"          => $bldg_permit[4],
                "opa_account_num" => $opaNum,
                "inspected_at"    => $inpAt,
                "geo_point"       => new Point($bldg_permit[7], $bldg_permit[8])
            ]);
            $q = $bp->save();
        }
        \DB::commit();

        is_resource($f) && @fclose($f);
        return 0;
    }
}
