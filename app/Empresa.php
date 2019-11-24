<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
  /**
   * Los usuarios asociados a esa Empresa
   *
   */
  public function users()
  {
    return $this->hasMany('App\User');
  }

  /**
   * El holding asociado a esa Empresa
   *
   */
  public function holding()
  {
    return $this->belongsTo('App\Holding');
  }

  /**
   * Los Puntos de Abastecimientos asociados a esa Empresa
   *
   */
  public function abastecimientos()
  {
    return $this->hasMany('App\Abastecimiento');
  }
  
  
}
