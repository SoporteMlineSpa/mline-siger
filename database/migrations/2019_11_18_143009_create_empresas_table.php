<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmpresasTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('empresas', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('razon_social');
      $table->string('rut');
      $table->string('direccion');
      $table->unsignedBigInteger('holding_id')->nullable();
      $table->unsignedBigInteger('abastecimiento_id')->nullable();
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
    Schema::dropIfExists('empresas');
  }
}
