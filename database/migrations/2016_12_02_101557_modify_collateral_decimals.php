<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyCollateralDecimals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collaterals', function (Blueprint $table) {
            $table->decimal('market_value',12,2)->change();
            $table->decimal('loan_value',12,2)->change();

            $table->decimal('land_market_value',12,2)->nullable()->default(0)->change();
            $table->decimal('land_appraised_value',12,2)->nullable()->default(0)->change();
            $table->decimal('building_market_value',12,2)->nullable()->default(0)->change();
            $table->decimal('building_appraised_value',12,2)->nullable()->default(0)->change();
            $table->decimal('other_improvements_market_value',12,2)->nullable()->default(0)->change();
            $table->decimal('other_improvements_appraised_value',12,2)->nullable()->default(0)->change();

            $table->decimal('total_market_value',12,2)->nullable()->default(0)->change();
            $table->decimal('total_appraised_value',12,2)->nullable()->default(0)->change();
            $table->decimal('bir_zonal_value',12,2)->nullable()->default(0)->change();
            $table->decimal('appraisers_association_value',12,2)->nullable()->default(0)->change();
            $table->decimal('market_value_of_neighborhood',12,2)->nullable()->default(0)->change();
            $table->decimal('reproduction_cost_of_building',12,2)->nullable()->default(0)->change();
            $table->decimal('assessed_value',12,2)->nullable()->default(0)->change();
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
