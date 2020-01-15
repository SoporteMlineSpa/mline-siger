<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddComunaCiudadToCentrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('centros', function (Blueprint $table) {
            $table->string('comuna');
            $table->string('ciudad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('centros', function (Blueprint $table) {
            $table->dropColumn('comuna');
            $table->dropColumn('ciudad');
        });
    }
}
