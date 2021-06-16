<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\BldgPermit;
use App\Models\RealEstateTx;
use Illuminate\Http\Request;

class ParcelInfo extends Controller
{
    public function index(Request $request, $parcelId)
    {
        $table = 'project_parcels_2';

        $landUse = \DB::table($table)
            ->select([
                \DB::raw("RTRIM(zoning_land_use.zoning) as zoning"),
            ])
            ->leftJoin('atlas_data', 'atlas_data.parcel_id', $table . '.parcel_id')
            ->where('atlas_data.parcel_id', '=', $parcelId)
            ->leftJoin('zoning_land_use', 'zoning_land_use.opa_account_num', 'atlas_data.opa_account_num')
            ->first();

        $parcel = Parcel::where('parcel_id', $parcelId)->first();

        $salePrice = RealEstateTx::leftJoin('atlas_data', 'atlas_data.opa_account_num', 'real_estate_tx.opa_account_num')
            ->where('atlas_data.parcel_id', $parcel->parcel_id)
            ->where('real_estate_tx.sale_price_adj', '>', 0)
            ->orderBy('sale_date', 'DESC')
            ->firstOrNew();

        $permits = BldgPermit::leftJoin('atlas_data', 'atlas_data.opa_account_num', 'bldg_permit.opa_account_num')
            ->select(\DB::raw('COUNT(bldg_permit.id) AS total_permits'))
            ->where('atlas_data.parcel_id', $parcel->parcel_id)
            ->groupBy('bldg_permit.opa_account_num')
            ->firstOrNew();

        return response()->json([
            'data' => [
                'type' => 'parcel',
                'id'   => $parcelId,
                'attributes' => array_merge(
                    $parcel->getAttributes(),
                    (array) $landUse,
                    $salePrice->getAttributes(),
                    $permits->getAttributes()
                ),
            ]
        ]);
    }
}
