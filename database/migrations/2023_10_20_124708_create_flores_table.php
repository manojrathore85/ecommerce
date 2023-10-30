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
        Schema::create('flores', function (Blueprint $table) {
            $table->id();
            $table->string('flore_no');
            $table->integer('flore_area');
            $table->integer('no_of_flate');
            $table->string('grount_or_top', );
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
        Schema::dropIfExists('flores');
    }
};
