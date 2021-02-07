<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParcelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parcel', function (Blueprint $table) {
            $table->id();
            $table->string('parcel_id', 12);
            $table->string('tenant_code', 12)->nullable();
            $table->string('address_1', 55)->nullable();
            $table->string('owner_1', 55)->nullable();
            $table->string('bldg_code', 20)->nullable();
            $table->json('geo_json');
            $table->unique('parcel_id');
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
        Schema::dropIfExists('parcel');
    }
}
