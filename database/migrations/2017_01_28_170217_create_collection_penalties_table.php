<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollectionPenaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collection_penalties', function (Blueprint $table) {
            $table->bigInteger('collection_id')->unsigned();
            $table->date('due_date');
            $table->integer('days_delayed');
            $table->decimal('arrears');
            $table->decimal('penalty');
            $table->timestamps();

            $table->primary([
                'collection_id',
                'due_date'
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
        Schema::dropIfExists('collection_penalties');
    }
}
