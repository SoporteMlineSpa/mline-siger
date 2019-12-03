<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requerimiento extends Model
{
  /**
   * Retorna los productos relacionados a ese Requerimietno
   *
   * @return App\Producto
   */
  public function productos()
  {
    return $this->belongsToMany('App\Producto')->withPivot('cantidad');
  }

  /**
   * Retorna el Centro al que pertenece ese requerimiento
   *
   * @return App\Abastecimiento
   */
  public function centro()
  {
    return $this->belongsTo('App\Abastecimiento');
  }
  
  
}
