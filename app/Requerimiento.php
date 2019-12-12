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
     * @return App\Centro
     */
    public function centro()
    {
        return $this->belongsTo('App\Centro');
    }

    /**
     * Retornar lista de Usuarios relacionados a ese Requerimiento
     *
     * @return \App\User
     */
    public function getUserByRequerimiento()
    {
        $users = [];

        $centro = $this->centro()->firstOrFail();
        $centroUser = $centro->users()->firstOrFail();
        array_push($users, $centroUser);

        $empresa = $centro->empresa()->firstOrFail();
        $empresaUser = $empresa->users()->firstOrFail();
        array_push($users, $empresaUser);

        $compassUsers = \App\User::whereHasMorph(
            'userable',
            ['App\CompassRole'],
            function ($query) {
                $query->where('name', 'like', 'Compras')->orWhere('name', 'like', 'Despacho');
            }
        )->get();
        foreach ($compassUsers as $user) {
            array_push($users, $user);
        }

        return $users;
    }
    
}
