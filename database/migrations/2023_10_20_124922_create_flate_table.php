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
        Schema::create('flate', function (Blueprint $table) {
            $table->id();
            $table->integer('flate_no');
            $table->unsignedBigInteger('flore_id');
            $table->foreign('flore_id')->references('id')->on('flores');
            $table->string('owner_name', 100);
            $table->integer('maintenance_area');
            $table->integer('corpate_area');
            $table->integer('builtup_area');
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
        Schema::dropIfExists('flate');
    }
};
