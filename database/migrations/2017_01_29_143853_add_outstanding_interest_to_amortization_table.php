<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOutstandingInterestToAmortizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('amortization_tables', function (Blueprint $table) {
            $table->decimal('outstanding_interest');
            $table->decimal('outstanding_rebate');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('amortization_tables', function (Blueprint $table) {
            //
        });
    }
}
