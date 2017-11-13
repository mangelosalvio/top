<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRemToCollaterals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaterals', function (Blueprint $table) {
            $table->char('collateral_type')->default('CHATTEL')->nullable();
            $table->string('title_no')->nullable();
            $table->string('lot_no')->nullable();
            $table->string('area')->nullable();
            $table->string('registered_owner')->nullable();
            $table->date('date_issued')->nullable();
            $table->text('lot_location')->nullable();
            $table->text('building_description')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->string('building_type')->nullable();
            $table->string('no_of_story')->nullable();
            $table->string('frame_formation')->nullable();
            $table->string('walling')->nullable();
            $table->string('partitions')->nullable();
            $table->string('roofing')->nullable();
            $table->string('beams_and_trusses')->nullable();
            $table->string('ceilings')->nullable();
            $table->string('flooring')->nullable();
            $table->string('door')->nullable();
            $table->string('windows')->nullable();
            $table->string('toilet_and_bath')->nullable();
            $table->string('floor_area')->nullable();
            $table->string('maintenance')->nullable();
            $table->date('year_constructed')->nullable();
            $table->text('other_improvements')->nullable();

            $table->decimal('land_market_value')->default(0)->nullable();
            $table->decimal('land_appraised_value')->default(0)->nullable();

            $table->decimal('building_market_value')->default(0)->nullable();
            $table->decimal('building_appraised_value')->default(0)->nullable();

            $table->decimal('other_improvements_market_value')->default(0)->nullable();
            $table->decimal('other_improvements_appraised_value')->default(0)->nullable();

            $table->decimal('total_market_value')->default(0)->nullable();
            $table->decimal('total_appraised_value')->default(0)->nullable();

            $table->decimal('bir_zonal_value')->default(0)->nullable();
            $table->decimal('appraisers_association_value')->default(0)->nullable();
            $table->decimal('market_value_of_neighborhood')->default(0)->nullable();
            $table->decimal('reproduction_cost_of_building')->default(0)->nullable();
            $table->decimal('assessed_value')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collaterals', function (Blueprint $table) {
            //
        });
    }
}
