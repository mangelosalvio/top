<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionOtherDetailAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_add_accounts', function(Blueprint $table) {
            $table->bigInteger('collection_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->decimal('amount');

            $table->primary(['collection_id', 'account_id']);
            $table->foreign('account_id')->references('id')
                ->on('chart_of_accounts');
            $table->foreign('collection_id')->references('id')
                ->on('collections');
        });

        Schema::create('collection_less_accounts', function(Blueprint $table) {
            $table->bigInteger('collection_id')->unsigned();
            $table->integer('account_id')->unsigned();
            $table->decimal('amount');

            $table->primary(['collection_id', 'account_id']);
            $table->foreign('account_id')->references('id')
                ->on('chart_of_accounts');
            $table->foreign('collection_id')->references('id')
                ->on('collections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('collection_add_accounts');
        Schema::dropIfExists('collection_less_accounts');
    }
}
