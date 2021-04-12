<?php

namespace App\Http\Controllers;

use App\Models\Parcel;
use App\Models\BldgPermit;
use App\Models\RealEstateTx;
use Illuminate\Http\Request;

class ChartInfo extends Controller
{
    public function zoningChartInfo(Request $request, $rcoId)
    {
        $table = 'project_parcels_2';
        if ($rcoId == null) {
            $table = 'project_parcels_2';
        }
        $parcels = \DB::table($table)
            ->select([
                \DB::raw("COUNT($table.id) as cnt"),
                \DB::raw("RTRIM(zoning_land_use.zoning) as zoning"),
//                \DB::raw("(CASE RTRIM(\"zoning\") WHEN 'RSA5', 'RDA1' THEN 1 END) as \"residential\" ")
            ])
            ->leftJoin('atlas_data', 'atlas_data.parcel_id', $table . '.parcel_id')
            ->leftJoin('zoning_land_use', 'zoning_land_use.opa_account_num', 'atlas_data.opa_account_num')
            ->groupBy([
                'zoning_land_use.zoning',
            ])
            ->cursor();

        $residential = [
            'RSD1', 'RSD2', 'RSD3', 'RMX1', 'RMX2', 'RSA', 'RSA1', 'RSA2', 'RSA3', 'RS3', 'RSA4', 'RSA5', 'RTA1',
        ];
        $residentialHigh = [
            'RM1', 'RM2', 'RM3', 'RM4', 'RMX3',
        ];
        $industrial = [
            'IRMX', 'ICMX', 'I1', 'I2', 'I3', 'IP',
        ];
        $commercial = [
            'CMX1', 'CMX2', 'CMX25'
        ];
        $commercialHigh = [
            'CMX3', 'CMX4', 'CMX5'
        ];
        $special = [
            'CA1', 'CA2', 'SPINS', 'SPENT', 'SPSTA', 'SPPOP', 'SPPOA', 'SPAIR', '12', 'SC', 'SP', '2002'
        ];
        //total count of all properties that have a zoning value
        $totalCount = $parcels->sum(function($item) {
            return $item->zoning ? $item->cnt : 0;
        });
        $industrialPct = $parcels->sum(function($item) use ($industrial) {
            return in_array($item->zoning, $industrial) ? $item->cnt : 0;
        });
        $industrialPct = round($industrialPct / $totalCount * 100) / 100;

        $residentialLowPct = $parcels->sum(function($item) use ($residential) {
            return in_array($item->zoning, $residential) ? $item->cnt : 0;
        });
        $residentialLowPct = round($residentialLowPct / $totalCount * 100) / 100;

        $residentialHighPct = $parcels->sum(function($item) use ($residentialHigh) {
            return in_array($item->zoning, $residentialHigh) ? $item->cnt : 0;
        });
        $residentialHighPct = round($residentialHighPct / $totalCount * 100) / 100;

        $commercialLowPct = $parcels->sum(function($item) use ($commercial) {
            return in_array($item->zoning, $commercial) ? $item->cnt : 0;
        });
        $commercialLowPct = round($commercialLowPct / $totalCount * 100) / 100;

        $commercialHighPct = $parcels->sum(function($item) use ($commercialHigh) {
            return in_array($item->zoning, $commercialHigh) ? $item->cnt : 0;
        });
        $commercialHighPct = round($commercialHighPct / $totalCount * 100) / 100;

        $specialPct = $parcels->sum(function($item) use ($special) {
            return in_array($item->zoning, $special) ? $item->cnt : 0;
        });
        $specialPct = round($specialPct / $totalCount * 100) / 100;


        return response()->json([
            'data' => [
                'type' => 'dataset',
                'id'   => 'zoning-x-axis',
                'attributes' => array_merge(
                    [
                        'labels' => ['Industrial', 'Lo Res', 'Hi Res', 'Lo Com', 'Hi Com', 'Special'],
                        'data' => [
                            $industrialPct,
                            $residentialLowPct,
                            $residentialHighPct,
                            $commercialLowPct,
                            $commercialHighPct,
                            $specialPct
                        ],
                    ]
                ),
            ]
        ]);
    }
}
