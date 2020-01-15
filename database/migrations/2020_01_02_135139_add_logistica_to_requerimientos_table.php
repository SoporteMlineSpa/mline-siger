<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLogisticaToRequerimientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requerimientos', function (Blueprint $table) {
            $table->string('nombre_transportista')->nullable();
            $table->string('rut_transportista')->nullable();
            $table->string('contacto_transportista')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requerimientos', function (Blueprint $table) {
            $table->dropColumn('nombre_transportista');
            $table->dropColumn('rut_transportista');
            $table->dropColumn('contacto_transportista');
        });
    }
}
