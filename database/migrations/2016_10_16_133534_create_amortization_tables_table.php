<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAmortizationTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amortization_tables', function (Blueprint $table) {
            $table->integer('loan_id')->unsigned();
            $table->integer('term');
            $table->date('due_date');
            $table->decimal('installment_amount',12,2);
            $table->decimal('rebate_amount',12,2);
            $table->decimal('interest_amount',12,2);
            $table->decimal('outstanding_balance',12,2);
            $table->timestamps();

            $table->foreign('loan_id')
                ->references('id')->on('loans')
                ->onDelete('cascade');

            $table->primary([
                'term','loan_id'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('amortization_tables');
    }
}
