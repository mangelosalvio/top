<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trans_types', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->timestamps();
        });

        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('collateral_id')->unsigned();
            $table->integer('trans_type_id')->unsigned();
            $table->string('unit');
            $table->text('purpose');
            $table->date('date');
            $table->string('plate_no');
            $table->string('comaker');
            $table->string('salesman');
            $table->string('dealer');
            $table->decimal('amount');
            $table->decimal('term');
            $table->decimal('interest_rate');
            $table->decimal('rebate_rate');

            $table->text('credit_verification');
            $table->string('credit_investigator');
            $table->string('manager');
            $table->string('approving_signatory');
            $table->string('res_cert_no');
            $table->string('res_cert_place');
            $table->string('res_cert_date');

            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');

            $table->foreign('collateral_id')
                ->references('id')->on('collaterals')
                ->onDelete('cascade');

            $table->foreign('trans_type_id')
                ->references('id')->on('trans_types')
                ->onDelete('cascade');

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
        Schema::dropIfExists('trans_types');
        Schema::dropIfExists('loans');
    }
}
