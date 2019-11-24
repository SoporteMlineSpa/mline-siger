<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
  use Notifiable;

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * Holding asociados a ese usuario
   *
   */
  public function holding()
  {
    return $this->belongsTo('App\Holding');
  }

  /**
   * Empresa asociados a ese usuario
   *
   */
  public function empresa()
  {
    return $this->belongsTo('App\Empresa');
  }

  /**
   * Abastecimiento asociados a ese usuario
   *
   */
  public function abastecimiento()
  {
    return $this->belongsTo('App\Abastecimiento');
  }
  
}
