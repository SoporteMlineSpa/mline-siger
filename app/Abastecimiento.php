<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abastecimiento extends Model
{
  /**
   * Devuelve las empresas asociadas a ese Punto de Abastecimiento
   *
   * @return 
   */
  public function empresas()
  {
    return $this->hasMany('App\Empresa');
  }
  
  
}
