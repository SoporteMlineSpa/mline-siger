<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductoRequerimientoTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('producto_requerimiento', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->bigInteger('producto_id');
      $table->bigInteger('requerimiento_id');
      $table->bigInteger('cantidad')->nullable();
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
    Schema::dropIfExists('producto_requerimiento');
  }
}
