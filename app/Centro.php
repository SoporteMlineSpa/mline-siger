<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
  /**
   * Los usuarios asociados a ese Centro
   *
   */
  public function users()
  {
    return $this->morphMany('App\User', 'userable');
  }

  /**
   * La Empresa asociada a ese Centro
   *
   */
  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  /**
   * Los Requerimientos asociados a ese Centro
   *
   * @return App\Requerimiento
   */
  public function requerimientos()
  {
    return $this->hasMany('App\Requerimiento');
  }
  
}
