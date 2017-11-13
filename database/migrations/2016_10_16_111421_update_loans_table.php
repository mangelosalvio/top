<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->double('interest_amount')->default(0);
            $table->double('rebate_amount')->default(0);
            $table->double('installment_first')->default(0);
            $table->double('installment_second')->default(0);
            $table->double('rebate_first')->default(0);
            $table->double('rebate_second')->default(0);
            $table->double('net_first')->default(0);
            $table->double('net_second')->default(0);
            $table->double('net_amount')->default(0);
            $table->double('pn_amount')->default(0);
            $table->double('cash_out')->default(0);
            $table->date('date_purchased');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn([
                'interest_amount',
                'rebate_amount',
                'installment_first',
                'installment_second',
                'rebate_first',
                'rebate_second',
                'net_first',
                'net_second',
                'net_amount',
                'pn_amount',
                'cash_out',
                'date_purchased'
            ]);
        });
    }
}
