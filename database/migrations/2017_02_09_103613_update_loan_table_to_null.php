<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLoanTableToNull extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->string('unit')->nullable()->change();
            $table->text('purpose')->nullable()->change();
            $table->string('plate_no')->nullable()->change();
            $table->string('comaker')->nullable()->change();
            $table->string('salesman')->nullable()->change();
            $table->string('dealer')->nullable()->change();
            $table->text('credit_verification')->nullable()->change();
            $table->string('credit_investigator')->nullable()->change();
            $table->string('manager')->nullable()->change();
            $table->string('approving_signatory')->nullable()->change();
            $table->string('res_cert_no')->nullable()->change();
            $table->string('res_cert_place')->nullable()->change();
            $table->string('res_cert_date')->nullable()->change();
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
