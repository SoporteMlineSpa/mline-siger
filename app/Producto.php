<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
  /**
   * Devuelve los requerimientos que contienen a ese Producto
   *
   * @return App\Requerimiento
   */
  public function requerimientos()
  {
    return $this->belongsToMany('App\Requerimiento')->withPivot('cantidad', 'real', 'observacion');
  }

  /**
   * Retorna las Empresas para ese producto
   *
   * @return App\Empresas
   */
  public function Empresas()
  {
      return $this->belongsToMany('App\Empresa');
  }
  
  
}
