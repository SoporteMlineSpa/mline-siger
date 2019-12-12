<?php

namespace App\Http\Controllers;

use App\Centro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CentroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::user()->userable instanceof \App\Empresa) {
            $centros = Auth::user()->userable->centros()->get();
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $centros = Centro::all();
        } else {
            $centros = [];
        }

        return view('centro.index')->with(compact('centros'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->userable instanceof \App\Empresa) {
            $empresas = Auth::user()->userable;
        } elseif (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = \App\Empresa::all();
        } else {
            $empresa = [];
        }

        return view('centro.create')->with(compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function show(Centro $centro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function edit(Centro $centro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Centro $centro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Centro  $centro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Centro $centro)
    {
        //
    }
}
