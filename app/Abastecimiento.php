<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abastecimiento extends Model
{
    protected $fillable = ['nombre'];
    /**
     * Retorna los despachos hacia ese Abastecimiento
     *
     * @return \App\Transporte
     */
    public function transportes()
    {
        return $this->hasMany('App\Transporte');
    }
    
}
