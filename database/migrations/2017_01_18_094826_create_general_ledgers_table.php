<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeneralLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('journal_id')->unsigned();
            $table->date('date');
            $table->text('particulars')->nullable();
            $table->string('reference')->nullable();
            $table->string('column_header')->nullable();
            $table->integer('column_header_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chart_of_account_general_ledger', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('general_ledger_id')->unsigned();
            $table->integer('chart_of_account_id')->unsigned();
            $table->decimal('debit')->default(0);
            $table->decimal('credit')->default(0);
            $table->text('description');

            $table->foreign('general_ledger_id')
                ->references('id')->on('general_ledgers')
                ->onDelete('cascade');

            $table->foreign('chart_of_account_id')
                ->references('id')->on('chart_of_accounts')
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
        Schema::dropIfExists('general_ledgers');
        Schema::dropIfExists('chart_of_account_general_ledger');
    }
}
