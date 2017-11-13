<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('check_vouchers', function (Blueprint $table) {

            /**
             * cv #
             * payee
             * date
             * amount
             * bank
             * check #
             *
             * details
             * chart_of_account_id
             * debit
             * credit
             */
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->unsigned();
            $table->integer('loan_id')->unsigned()->nullable();
            $table->date('date');
            $table->string('check_no');
            $table->integer('bank_id');
            $table->decimal('amount',12);

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('check_voucher_details', function (Blueprint $table) {

            $table->bigIncrements('id');
            $table->bigInteger('check_voucher_id')->unsigned();
            $table->bigInteger('chart_of_account_id')->unsigned();
            $table->decimal('debit',12)->default(0);
            $table->decimal('credit',12)->default(0);
            $table->timestamps();

            $table->foreign('check_voucher_id')
                ->references('id')->on('check_vouchers')
                ->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('check_vouchers');
        Schema::dropIfExists('check_voucher_details');
    }
}
