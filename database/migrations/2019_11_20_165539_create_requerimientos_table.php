<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequerimientosTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('requerimientos', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('nombre');
      $table->unsignedInteger('estado')->default(0);
      $table->unsignedBigInteger('abastecimiento_id');
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
    Schema::dropIfExists('requerimientos');
  }
}
