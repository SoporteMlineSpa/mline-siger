<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $fillable = ['nombre', 'direccion', 'comuna', 'ciudad'];

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

    /**
     * Retorna el Presupuesto de ese Centro
     *
     * @return App\Presupuesto
     */
    public function presupuestos()
    {
        return $this->morphMany('App\Presupuesto', 'presupuesteable');
    }

    /**
     * Retorna los Requerimientos de ese Centro segun el Id de Estado
     *
     * @return \App\Requerimiento
     */
    public function getRequerimientosByEstado($estadoId = null)
    {
        if ($estadoId === null) {
            $estadoId = 7;
        }
        switch ($estadoId) {
            case 0:
                return $this->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get();
                break;
            case 1:
                return $this->requerimientos()->where('estado', 'VALIDADO')->get();
                break;
            case 2:
                return $this->requerimientos()->where('estado', 'EN PROCESAMIENTO')->get();
                break;
            case 3:
                return $this->requerimientos()->where('estado', 'EN BODEGA')->get();
                break;
            case 4:
                return $this->requerimientos()->where('estado', 'DESPACHADO')->get();
                break;
            case 5:
                return $this->requerimientos()->where('estado', 'ENTREGADO')->get();
                break;
            case 6:
                return $this->requerimientos()->where('estado', 'RECHAZADO')->get();
                break;
            case 7:
                return $this->requerimientos;
                break;
        }
    }

    /**
     * Retorna el Total del Presupuesto segun el Mes y el Año
     *
     * @return Int
     */
    public function getTotalPresupuestoByDate($mesId = null, $year = null)
    {
        $date = \Carbon\Carbon::create($year ?? date("Y"), $mesId ?? date("m"));
        $query = $this->presupuestos();
        if ($year !== null) {
            $query = $query->whereYear('fecha_gestion', $date->year);
        }
        if ($mesId !== null) {
            $query = $query->whereMonth('fecha_gestion', $date->month);
        }

        return $query->get()->first();
    }

    /**
     * Retornar el total de todos los Requerimientos segun el Mes y el Año
     *
     * @return Collection[int]
     */
    public function getTotalByMes($year = null)
    {
        if (is_null($year)) {
            $requerimientos = $this->requerimientos()->whereYear('created_at', date("Y"))->get()->groupBy(function($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        } else {
            $requerimientos = $this->requerimientos()->whereYear('created_at', $year)->get()->groupBy(function($d) {
                return \Carbon\Carbon::parse($d->created_at)->format('m');
            });
        }
        $total = $requerimientos->map(function ($mes, $index) {
            $totalMes = $mes->reduce(function($carry, $requerimiento) {
                return $carry + ($requerimiento->getTotal() ?? 0);
            });
            return [$index => $totalMes];
        });
        return $total;
    }

}
