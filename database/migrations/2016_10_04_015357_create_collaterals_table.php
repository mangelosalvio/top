<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCollateralsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('collaterals', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('customer_id')->unsigned();
            $table->bigInteger('collateral_class_id')->unsigned();

            $table->string('date')->nullable();
            $table->string('make')->nullable();
            $table->string('type')->nullable();
            $table->string('model')->nullable();
            $table->string('motor')->nullable();
            $table->string('serial')->nullable();
            $table->string('plate')->nullable();
            $table->string('odometer')->nullable();
            $table->string('route')->nullable();
            $table->string('insurance')->nullable();
            $table->string('operator')->nullable();
            $table->string('registration')->nullable();
            $table->string('mv_file_no')->nullable();
            $table->string('assembled_by')->nullable();
            $table->string('owner')->nullable();
            $table->decimal('market_value')->nullable();
            $table->decimal('loan_value')->nullable();
            $table->string('od_coverage')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('lto_agency')->nullable();

            $table->integer('paint_condition')->default(2)->nullable();
            $table->text('paint_remarks')->nullable();
            $table->integer('tire_condition')->default(2)->nullable();
            $table->text('tire_remarks')->nullable();
            $table->integer('body_condition')->default(2)->nullable();
            $table->text('body_remarks')->nullable();
            $table->integer('chrome_condition')->default(2)->nullable();
            $table->text('chrome_remarks')->nullable();
            $table->integer('upholstery_condition')->default(2)->nullable();
            $table->text('upholstery_remarks')->nullable();
            $table->integer('engine_condition')->default(2)->nullable();
            $table->text('engine_remarks')->nullable();
            $table->integer('transmission_condition')->default(2)->nullable();
            $table->text('transmission_remarks')->nullable();
            $table->integer('rear_axle_condition')->default(2)->nullable();
            $table->text('rear_axle_remarks')->nullable();
            $table->integer('clutch_condition')->default(2)->nullable();
            $table->text('clutch_remarks')->nullable();
            $table->integer('steering_condition')->default(2)->nullable();
            $table->text('steering_remarks')->nullable();
            $table->integer('brakes_condition')->default(2)->nullable();
            $table->text('brakes_remarks')->nullable();
            $table->integer('accessories_condition')->default(2)->nullable();
            $table->text('accessories_remarks')->nullable();
            $table->integer('glass_condition')->default(2)->nullable();
            $table->text('glass_remarks')->nullable();
            $table->integer('panel_instru_condition')->default(2)->nullable();
            $table->text('panel_instru_remarks')->nullable();

            $table->text('additional_collaterals')->nullable();
            $table->string('appraised_by')->nullable();
            $table->date('appraised_date')->nullable();
            $table->string('place')->nullable();

            $table->timestamps();

            $table->foreign('customer_id')
                ->references('id')->on('customers')
                ->onDelete('cascade');

            $table->foreign('collateral_class_id')
                ->references('id')->on('collateral_classes')
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
        Schema::dropIfExists('collaterals');
    }
}
