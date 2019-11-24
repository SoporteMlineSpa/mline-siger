<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abastecimiento extends Model
{
  /**
   * Los usuarios asociados a ese Punto de Abastecimiento
   *
   */
  public function users()
  {
    return $this->hasMany('App\User');
  }

  /**
   * La Empresa asociada a ese Punto de Abastecimiento
   *
   */
  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  /**
   * Los Requerimientos asociados a ese Punto de Abastecimiento
   *
   * @return App\Requerimiento
   */
  public function requerimientos()
  {
    return $this->hasMany('App\Requerimiento');
  }
  
  
}
