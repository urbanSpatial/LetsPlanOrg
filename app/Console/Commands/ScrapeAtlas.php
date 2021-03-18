<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ScrapeAtlas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'lp:scrape-atlas
                            {--key= : gatekeeper key}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape Atlas API for PWD Parcel ID to OPA Account Number';
    protected $apiEndpoint = 'https://api.phila.gov/ais/v1/search/%s?gatekeeperKey=%s';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gatekeeperKey = $this->option('key');

        // get parcel_ids missing from atlas table
        DB::table('parcel')
            ->select('parcel.*')
            ->leftJoin('atlas_data', 'parcel.parcel_id', '=', 'atlas_data.parcel_id')
            ->where('atlas_data.parcel_id', null)
            ->cursor()
            ->each(function ($item) use ($gatekeeperKey) {
                $this->info("Querying parcel id " . $item->parcel_id . "... ");
                $this->info(sprintf($this->apiEndpoint, $item->parcel_id, $gatekeeperKey));
                try {
                    $json = file_get_contents(sprintf($this->apiEndpoint, $item->parcel_id, $gatekeeperKey));
                    $response = json_decode($json);
                    $opaAcctNum = $response->features[0]->properties->opa_account_num;
                    $this->info("got opa_acct_num " . $opaAcctNum);
                    \DB::table('atlas_data')
                        ->insert([
                            'parcel_id' => $item->parcel_id,
                            'opa_account_num' => $opaAcctNum,
                            'response' => $json,
                        ]);
                } catch (\ErrorException $e) {
                    $this->error("404, parcel ID not found:  [" . $item->parcel_id . "]");
                }
                usleep(200);
            });

        return 0;
    }
}
