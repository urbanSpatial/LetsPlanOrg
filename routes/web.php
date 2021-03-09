<?php

use Illuminate\Support\Facades\Route;

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
    return redirect()->route('about');
})->name('home');
Route::inertia('/about', 'About')->name('about');
Route::inertia('/explore', 'Explore')->name('explore');
Route::inertia('/layers', 'Layers')->name('layers');

Route::get('/{layer}/{z}/{x}/{y}.pbf', 'TileLayer@index');
