<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCompulationSlipToLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->boolean('is_approved')->default(false);

            $table->decimal('doc_stamp')->default(0);
            $table->decimal('notarial_fees')->default(0);
            $table->decimal('misc_fees')->default(0);
            $table->decimal('science_stamps')->default(0);

            $table->decimal('mortgage_fees')->default(0);
            $table->decimal('legal_and_research_fees')->default(0);
            $table->decimal('deed_of_assignment_fees')->default(0);
            $table->decimal('total_rod_charges')->default(0);

            $table->decimal('transfer_fees')->default(0);
            $table->decimal('mortgage_and_assignment_fees')->default(0);
            $table->decimal('misc_lto_fees')->default(0);
            $table->decimal('total_lto_charges')->default(0);

            $table->decimal('total_doc_fees')->default(0);

            $table->decimal('service_fees')->default(0);
            $table->decimal('od_insurance_fees')->default(0);
            $table->decimal('net_proceeds')->default(0);

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
