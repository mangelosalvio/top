<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLoansTableV2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->decimal('interest_amount',12,2)->default(0)->change();
            $table->decimal('rebate_amount',12,2)->default(0)->change();
            $table->decimal('installment_first',12,2)->default(0)->change();
            $table->decimal('installment_second',12,2)->default(0)->change();
            $table->decimal('rebate_first',12,2)->default(0)->change();
            $table->decimal('rebate_second',12,2)->default(0)->change();
            $table->decimal('net_first',12,2)->default(0)->change();
            $table->decimal('net_second',12,2)->default(0)->change();
            $table->decimal('net_amount',12,2)->default(0)->change();
            $table->decimal('pn_amount',12,2)->default(0)->change();
            $table->decimal('cash_out',12,2)->default(0)->change();
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
            //
        });
    }
}
