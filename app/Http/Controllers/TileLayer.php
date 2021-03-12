<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TileLayer extends Controller
{
	public function index(Request $request, $layer, $z, $x, $y) {
      $flip = true;
      if ($flip) {
        $y = pow(2, $z) - 1 - $y;
      }

      $result =\DB::connection('mbtiles')
          ->table('tiles')
          ->select('tile_data')
          ->where('zoom_level', $z )
          ->where('tile_column', $x )
          ->where('tile_row', $y)
          ->first();

      if (!$result) {
          return response('', 404);
      }
      $headers = [
          'Content-Encoding' => 'gzip',
          'Content-Type' => 'application/x-protobuf',
          //'Content-Type' => 'text/html',
          'Access-Control-Allow-Origin' => '*',
      ];
      foreach ($headers as $_key => $_val) {
          header($_key.': '. $_val);
      }
      echo $result->tile_data;
      exit();
    }
}
