<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanningOverlays extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planning_overlays', function (Blueprint $table) {
            $table->id();

            $table->char('opa_account_num', 12)->nullable();
            $table->integer('dev_index')->nullable();
            $table->integer('pres_index')->nullable();
            $table->integer('sum')->nullable();

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
        Schema::dropIfExists('planning_overlays');
    }
}
