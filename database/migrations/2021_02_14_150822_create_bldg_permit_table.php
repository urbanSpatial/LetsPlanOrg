<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBldgPermitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //"permitnumber","permitdescription","permittype","permitissuedate","status","opa_account_num","mostrecentinsp","lat","lng","year"
        //"1010162","ALTERATION PERMIT","BP_ALTER",2019-10-07,"COMPLETED","183051900","2020-03-04T15:56:00Z",39.9753347192597,-75.1332891432334,2019
        Schema::create('bldg_permit', function (Blueprint $table) {
            $table->id();
            $table->char('permit_num', 10);
            $table->char('description', 25);
            $table->char('permit_type', 10);
            $table->char('permit_year', 4);
            $table->char('status', 10);
            $table->char('opa_account_num', 12)->nullable();
            //$table->column('geography', 'geo_point', ['geomtype'=>'GEOGRAPHY', 'srid'=>'4326']);
            $table->point('geo_point');
            $table->datetime('inspected_at')->nullable();
            $table->date('issued_at')->nullable();
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
        Schema::dropIfExists('bldg_permit');
    }
}
