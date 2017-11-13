<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateCollectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('collections', function (Blueprint $table) {
            $table->string('account_code')->nullable()->change();
            $table->integer('loan_id')->unsigned()->nullable()->change();
            $table->decimal('current_balance',12,2)->default(0)->nullable()->change();
            $table->decimal('uii_balance',12,2)->default(0)->nullable()->change();
            $table->decimal('rff_balance',12,2)->default(0)->nullable()->change();
            $table->decimal('ar_balance',12,2)->default(0)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('collections', function (Blueprint $table) {
            //
        });
    }
}
