<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('marital_status');
            $table->string('spouse');
            $table->text('current_address');
            $table->text('previous_address');
            $table->text('source_of_income');
            $table->text('present_address_code');
            $table->string('mobile_number');
            $table->string('gender');
            $table->integer('no_of_dependents');
            $table->integer('years_of_stay');
            $table->integer('age');
            $table->text('references');
            $table->string('res_cert_no');
            $table->date('res_cert_date');
            $table->string('res_cert_place');

            $table->string('prop_real');
            $table->string('prop_appliance');
            $table->string('prop_chattel');
            $table->string('prop_deposit');

            $table->string('credit_dealings_1');
            $table->string('credit_dealings_2');
            $table->string('credit_dealings_3');
            $table->string('credit_dealings_4');

            $table->string('ica_court_files');
            $table->text('general_information');
            $table->text('residense');


            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
