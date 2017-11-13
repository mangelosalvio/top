<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoanLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loan_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->bigInteger('collection_id')->unsigned()->nullable();
            $table->integer('loan_id')->unsigned();
            $table->decimal('payment_amount');
            $table->decimal('rebate_amount');
            $table->decimal('outstanding_balance');
            $table->decimal('outstanding_interest');
            $table->decimal('outstanding_rebate');
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
        Schema::dropIfExists('loan_ledgers');
    }
}
