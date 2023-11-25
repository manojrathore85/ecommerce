<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flates', function (Blueprint $table) {
            $table->id();
            $table->integer('flate_no');
            $table->unsignedBigInteger('flore_id');
            $table->foreign('flore_id')->references('id')->on('flores');
            $table->string('owner_name', 100);
            $table->float('maintenance_area',8, 2)->nullable();
            $table->float('maintenance_rate',8, 2)->nullable();
            $table->float('maintenance_amount',8, 2)->nullable();
            $table->float('superbuiltup_area',8, 2)->nullable();
            $table->float('builtup_area',8, 2)->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('flates');
    }
};
