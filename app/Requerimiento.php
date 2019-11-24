<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
  /**
   * Devulve los productos relacionados a ese Requerimietno
   *
   * @return App\Producto
   */
  public function productos()
  {
    return $this->belongsToMany('App\Producto');
  }

  /**
   * Devuelve el Punto de Abastecimiento al que pertenece ese requerimiento
   *
   * @return App\Abastecimiento
   */
  public function abastecimiento()
  {
    return $this->belongsTo('App\Abastecimiento');
  }
  
  
}
