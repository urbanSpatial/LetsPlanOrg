<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZoningLandUseTable extends Migration
{
    /**
     * Run the migrations.
     *
     *  1 "opa_account_num","zoning","landuse","number_stories","total_livable_area","bldg_desc","lat","lng"
     *  2 "011012000","RSA5 ","Single Family",3,1465,"ROW 3 STY MASONRY",39.9302486735636,-75.1487657003407
     *  3 "011012100","RSA5 ","Single Family",3,1465,"ROW 3 STY MASONRY",39.9302560204392,-75.1488218259425
     * @return void
     */
    public function up()
    {
        Schema::create('zoning_land_use', function (Blueprint $table) {
            $table->id();

            $table->char('opa_account_num', 12)->nullable();
            $table->char('zoning', 10)->nullable();
            $table->string('landuse', 35)->nullable();
            $table->tinyInteger('number_stories')->unsigned()->nullable();
            $table->integer('total_livable_area')->unsigned()->nullable();
            $table->string('bldg_desc', 75)->nullable();

            //$table->column('geography', 'geo_point', ['geomtype'=>'GEOGRAPHY', 'srid'=>'4326']);
            $table->point('geo_point');
            $table->index('opa_account_num');


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zoning_land_use');
    }
}
