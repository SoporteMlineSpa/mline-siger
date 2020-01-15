<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    protected $fillable = ['razon_social', 'giro', 'direccion', 'rut'];

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


    /**
     * Los productos asociados a ese Empresa
     *
     * @return App\Productos
     */
    public function productos()
    {
        return $this->belongsToMany('App\Producto')->withPivot('precio');
    }

    /**
     * Retorna el Presupuesto de esa Empresa
     *
     * @return App\Presupuesto
     */
    public function presupuestos()
    {
        return $this->morphMany('App\Presupuesto', 'presupuesteable');
    }

    /**
     * Retorna true si la Empresa tiene un Presupuesto creado
     *
     * @return bool
     */
    public function hasPresupuesto()
    {
        return $this->presupuesto()->get()->isNotEmpty();
    }


    /**
     * Retorna los Requerimientos de esta empresa segun el estado
     *
     * @return App\Requerimiento
     */
    public function getRequerimientoByEstado(String $estado, $date = null)
    {
        $requerimientos = collect([]);
        $centros = $this->centros()->get();

        foreach ($centros as $centro) {
            $requerimientosCentro = $centro->requerimientos()->where('estado', $estado);
            if ($date === null) {
                $requerimientosCentro = $requerimientosCentro->whereYear('created_at', date("Y"))->whereMonth('created_at', date("m"))->get();
            } else {
                $requerimientosCentro = $requerimientosCentro->whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->get();
            }
            if (count($requerimientosCentro) > 0) {
                $requerimientos->push($requerimientosCentro);
            }
        }

        return $requerimientos;

    }

    /**
     * Retorna los Centros de esta Empresa segun el estado
     *
     * @return App\Centro
     */
    public function getCentrosByEstado(Int $estadoId)
    {
        switch ($estadoId) {
        case 0:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'ESPERANDO VALIDACION');
            })->get();
            break;
        case 1:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'VALIDADO');
            })->get();
            break;
        case 2:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'EN PROCESAMIENTO');
            })->get();
            break;
        case 3:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'EN BODEGA');
            })->get();
            break;
        case 4:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'DESPACHADO');
            })->get();
            break;
        case 5:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'ENTREGADO');
            })->get();
            break;
        case 6:
            return $this->centros()->whereHas('requerimientos', function($query) {
                $query->where('estado', 'RECHAZADO');
            })->get();
            break;
        default:
            return $this->centros()->get();
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

        $presupuestoTotal = $this->centros()->get()->map(function($centro) use ($date, $mesId, $year) {
            $query = $centro->presupuestos();
            if ($year !== null) {
                $query = $query->whereYear('fecha_gestion', $date->year);
            }
            if ($mesId !== null) {
                $query = $query->whereMonth('fecha_gestion', $date->month);
            }
            $monto = $query->get()->first()->monto ?? 0;
            return $monto;
        })->reduce(function ($carry, $item) {
            return $carry + $item;
        });

        return $presupuestoTotal;
    }

    /**
     * Retorna los Gastos segun el Mes y el Año
     *
     * @params Int $mesId, Int $year
     * @return void
     */
    public function getGastoByDate($mesId = null, $year = null)
    {
        $date = \Carbon\Carbon::create($year ?? date("Y"), $mesId ?? date("m"));

        $gastoTotal = $this->centros()->get()
                                      ->map(function ($centro) use ($date) {
                                          return $centro->getTotalByMes($date->year);
                                      })
                                      ->filter(function($value) {
                                          return count($value) > 0;
                                      })
                                      ->filter(function($value) use($mesId) {
                                          return $value->has($mesId);
                                      })
                                      ->reduce(function($carry, $item) use($mesId) {
                                          return $carry + $item[$mesId][$mesId];
                                      });

        return $gastoTotal ?? 0;
    }


}
