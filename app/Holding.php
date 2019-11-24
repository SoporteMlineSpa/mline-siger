<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Holding extends Model
{

  /**
   * Los usuarios asociados a ese Holding
   *
   */
  public function users()
  {
    return $this->hasMany('App\User');
  }

  /**
   * Las Empresas asociadas a ese Holding
   *
   */
  public function empresas()
  {
    return $this->hasMany('App\Empresa');
  }
  
  
}
