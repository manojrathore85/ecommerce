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
        Schema::table('flates',function(Blueprint $table){
            $table->float('op_balance')->nullable();
            $table->string('drcr',2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('flates', function(Blueprint $table){
            $table->dropColumn('op_balance');
            $table->dropColumn('drcr');
        });
    }
};
