<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAtlasDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('atlas_data', function (Blueprint $table) {
            $table->id();

            $table->string('parcel_id', 12);
            $table->string('opa_account_num', 12);
            $table->json('response');
            $table->unique('parcel_id');
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
        Schema::dropIfExists('atlas_data');
    }
}
