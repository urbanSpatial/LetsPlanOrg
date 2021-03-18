<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRealEstateTxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * "","opa_account_num","zip_code","assessed_value","sale_price","sale_date","parcel_id","street_address","property_count","document_type","document_id","lat","lng","property_type","basements","central_air","fireplaces","garage_spaces","garage_type","number_of_bathrooms","number_of_bedrooms","number_stories","number_of_rooms","total_livable_area","year_built","exterior_condition","assessment_date","year","adjusted_sale_price"
"1",351084600,19120,NA,75000,"2010-01-04","140N190013","626 ALLENGROVE ST",1,"DEED",52162343,40.0381461604154,-75.1030921999736,"Single Family","D","N",0,0,"F",1,3,2,6,1024,"1927",4,NA,2010,63185.31
     * @return void
     */
    public function up()
    {
        Schema::create('real_estate_tx', function (Blueprint $table) {
            $table->id();

            $table->char('opa_account_num', 12)->nullable();
            $table->char('zip_code', 10);
            $table->integer('assessed_value')->nullable();
            $table->integer('sale_price')->nullable();
            $table->date('sale_date')->nullable();
            $table->string('parcel_id', 12);
            $table->string('street_address', 55);
            $table->tinyInteger('property_count')->default(1);
            $table->string('document_type', 35)->default('');
            $table->string('document_id', 25)->default('');
            $table->string('property_type', 25)->default('');
            $table->char('basements', 1)->default('D');
            $table->char('central_air', 1)->default('N');
            $table->tinyInteger('fireplaces')->unsigned()->default(0);
            $table->tinyInteger('garage_spaces')->unsigned()->default(0);
            $table->char('garage_type', 1)->default('');
            $table->tinyInteger('bathrooms')->unsigned()->default(1);
            $table->tinyInteger('bedrooms')->unsigned()->default(1);
            $table->tinyInteger('stories')->unsigned()->default(1);
            $table->tinyInteger('rooms')->unsigned()->default(1);
            $table->integer('livable_area')->unsigned()->default(1);
            $table->integer('year_built')->unsigned()->nullable();
            $table->char('exterior_condition', 1)->nullable();
            $table->date('assessment_date')->nullable();
            $table->integer('sale_year')->unsigned();
            $table->integer('sale_price_adj');

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
        Schema::dropIfExists('real_estate_tx');
    }
}
