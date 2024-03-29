<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('engage');
})->name('home');

Route::get('/sprucehill', function () {
    return redirect()->route('engage');
})->name('sprucehill');

Route::inertia('/sprucehill/engage', 'Engage')->name('engage');
// Route::inertia('/sprucehill/explore', 'Explore')->name('explore');
Route::get('/sprucehill/explore', function () {
    return redirect()->route('explore', ['pane' => 'sales']);
});
Route::get('/sprucehill/explore/{pane?}', 'ExploreController@index')->name('explore');
Route::inertia('/sprucehill/survey', 'Survey')->name('survey');
Route::inertia('/sprucehill/about', 'About')->name('about');

Route::get('/{layer}/{z}/{x}/{y}.pbf', 'TileLayer@index');
Route::get('/parcel/{parcelId}', 'ParcelInfo@index');
Route::get('/chart/zoning/{projectId}', 'ChartInfo@zoningChartInfo');
Route::get('/chart/sales/{projectId}', 'ChartInfo@salesChartInfo');
Route::get('/chart/permits-c/{projectId}', 'ChartInfo@permitsChartInfo');
Route::get('/chart/permits-a/{projectId}', 'ChartInfo@adjustmentPermitsChartInfo');


Route::get('/mbtile', function (Request $request) {
    $tippecanoeCmd = 'tippecanoe';
    $output = [];
    $result = 0;
    exec('which ' . $tippecanoeCmd, $output, $result);
    if ($result !== 0) {
        return 'cannot find tippecanoe';
    }

    $output = new \Symfony\Component\Console\Output\BufferedOutput();
    $exitCode = \Illuminate\Support\Facades\Artisan::call('lp:stream-parcel-geo-json', [
        'project_table' => 'project_parcels_2',
        'output_file' => storage_path('app/mbtiles.geojson'),
    ], $output);

    exec(
        sprintf(
            $tippecanoeCmd . ' -z20 -Z8 -f --name=urban-areas -l urban-areas --output=%s %s',
            storage_path('app/urban_area.mbtiles'),
            storage_path('app/mbtiles.geojson')
        ),
        $output,
        $result
    );
    if ($result !== 0) {
        return "failure to run tippecanoe. " . $tippecanoeCmd;
    }
    return 'done';
});
