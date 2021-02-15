<?php

namespace App\Console\Commands;

use App\Models\RealEstateTx;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use MStaack\LaravelPostgis\Geometries\Point;

class ImportReTransfers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:import-re-transfers' .
                           ' {data-file : real estate transfers csv}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Real Estate Transfers';

    /**
     * Execute the console command.
     *
     * "","opa_account_num","zip_code","assessed_value","sale_price","sale_date","parcel_id","street_address","property_count","document_type","document_id","lat","lng","property_type","basements","central_air","fireplaces","garage_spaces","garage_type","number_of_bathrooms","number_of_bedrooms","number_stories","number_of_rooms","total_livable_area","year_built","exterior_condition","assessment_date","year","adjusted_sale_price"
"1",351084600,19120,NA,75000,"2010-01-04","140N190013","626 ALLENGROVE ST",1,"DEED",52162343,40.0381461604154,-75.1030921999736,"Single Family","D","N",0,0,"F",1,3,2,6,1024,"1927",4,NA,2010,63185.31
     * @return int
     */
    public function handle()
    {
        $dataFile = $this->argument('data-file');
        if (!Storage::disk('local')->exists($dataFile)) {
            $this->error('Cannot find ['.$dataFile.'] locally, existing ... ');
            return 1;
        }

        $this->info('Found ['.$dataFile.'] locally, proceeding to import ... ');
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
            if (trim($line) == '' ) {
                continue;
            }
            $data_row = str_getcsv($line);
            $count++;
            if ($count % 1000 == 0) {
                $this->info($count.' ...');
                \DB::commit();
                \DB::beginTransaction();
            }
            $saleDt = null;
            try {
                $saleDt = \Carbon\Carbon::parse($data_row[5]);
            } catch ( \Carbon\Exceptions\InvalidFormatException $e) {
            }
            $assDt = null;
            try {
                $assDt = \Carbon\Carbon::parse($data_row[26]);
            } catch ( \Carbon\Exceptions\InvalidFormatException $e) {
            }
            $opaNum = $data_row[1] == 'NA' ? null
                : $data_row[1];

            $assVal = $data_row[3] == 'NA' ? null
                : intval(round($data_row[3]*100)/100);

            $salPri = $data_row[4] == 'NA' ? null
                : intval(round($data_row[4]*100)/100);

			if ($salPri > pow(2,9)) {
				// 5 billion dollar sales are not supported
				continue;
			}
            // Points are lat, lng
            $bp = new RealEstateTx([
                "opa_account_num"    => $opaNum,
                "zip_code"           => $data_row[2],
                "assessed_value"     => $assVal,
                "sale_price"         => $salPri,
                "sale_date"          => $saleDt,
                "parcel_id"          => substr($data_row[6], 0, 12),
                "street_address"     => $data_row[7],
                "property_count"     => $data_row[8],
                "document_type"      => $data_row[9],
                "document_id"        => $data_row[10],
                "geo_point"          => new Point($data_row[11], $data_row[12]),
                "property_type"      => $data_row[13],
                "basements"          => $data_row[14] == 'NA' ? '' : $data_row[14],
                "central_air"        => $data_row[15] == 'NA' ? '' : $data_row[15],
                "fireplaces"         => (int)$data_row[16],
                "garage_spaces"      => (int)$data_row[17],
                "garage_type"        => $data_row[18] == 'NA' ? '' : $data_row[18],
                "bathrooms"          => (int)$data_row[19],
                "bedrooms"           => (int)$data_row[20],
                "stories"            => (int)$data_row[21],
                "rooms"              => (int)$data_row[22],
                "livable_area"       => (int)$data_row[23],
                "year_built"         => $data_row[24] == 'NA' ? null : $data_row[24],
                "exterior_condition" => (int)$data_row[25],
                "assessment_date"    => $assDt,
                "sale_year"          => $data_row[27],
                "sale_price_adj"     => intval(round($data_row[28]*100)/100),
            ]);
            try {
                $q = $bp->save();
				if (!$q) {
					$this->error("Q was not true");
                	\DB::rollback();
				}
            } catch (\Illuminate\Database\QueryException $e) {
                // at least one property sold for 5.975e+09, which is too high for
                // a signed integer sale price columne
                $this->error($e->getMessage());

                \DB::rollback();
                //\DB::commit();
                //\DB::beginTransaction();
            }
        }
        \DB::commit();

        is_resource($f) && @fclose($f);
        return 0;
    }
}
