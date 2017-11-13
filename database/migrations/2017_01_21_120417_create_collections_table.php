<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('account_code');
            $table->integer('loan_id')->unsigned();

            $table->decimal('current_balance');
            $table->decimal('uii_balance');
            $table->decimal('rff_balance');
            $table->decimal('ar_balance');

            $table->decimal('principal_amount')->nullable();
            $table->decimal('rff_debit')->default(0)->nullable();
            $table->decimal('rff_credit')->default(0)->nullable();
            $table->decimal('uii_debit')->default(0)->nullable();
            $table->decimal('interest_income_credit')->default(0)->nullable();
            $table->decimal('total_amount_due')->default(0)->nullable();

            $table->string('or_no');
            $table->string('or_date');
            $table->decimal('cash_amount')->default(0)->nullable();
            $table->decimal('check_amount')->default(0)->nullable();
            $table->decimal('total_payment_amount')->default(0);
            $table->string('bank_branch')->nullable();
            $table->string('check_no')->nullable();
            $table->date('check_date')->nullable();

            $table->boolean('is_penalty_computed')->default(0);
            $table->date('last_transaction_date')->nullable();
            $table->decimal('penalty_rate')->default(5)->nullable();
            $table->date('penalty_as_of_date')->nullable();
            $table->integer('days_allowance')->default(5)->nullable();

            $table->decimal('total_penalty')->default(0)->nullable();
            $table->decimal('penalty_disc_rate')->default(0)->nullable();
            $table->decimal('penalty_disc_amount')->default(0)->nullable();
            $table->decimal('net_penalty_due')->default(0)->nullable();

            $table->softDeletes();
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
        Schema::dropIfExists('collections');
    }
}
