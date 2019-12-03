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
    return $this->morphMany('App\User', 'userable');
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
   * Los Centros asociados a esa Empresa
   *
   */
  public function centros()
  {
    return $this->hasMany('App\Centro');
  }
  
  
}
