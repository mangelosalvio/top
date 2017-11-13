<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLoanAccounts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('loans', function(Blueprint $table) {

            /**
             * for posting accounts
             */

            $table->string('customer_code');
            $table->integer('lr_account_id')->unsigned()->nullable();
            $table->integer('uii_account_id')->unsigned()->nullable();
            $table->integer('rff_account_id')->unsigned()->nullable();
            $table->integer('ar_account_id')->unsigned()->nullable();

            /**
             * for balance forwarding
             */
            $table->boolean('is_balance_forwarded')->default(0);
            $table->decimal('balance_forwarded')->default(0);
        });

        Schema::table('chart_of_accounts', function(Blueprint $table){
            $table->integer('main_account_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
