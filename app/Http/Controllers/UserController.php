<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     *  
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo = null)
    {
        switch ($tipo) {
        case 'h':
            $users = User::whereHasMorph(
                'userable',
                ['App\Holding'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Holding';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        case 'e':
            $users = User::whereHasMorph(
                'userable',
                ['App\Empresa'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Empresa';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        case 'c':
            $users = User::whereHasMorph(
                'userable',
                ['App\Centro'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Centro';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        case 'r':
            $users = User::whereHasMorph(
                'userable',
                ['App\CompassRole'],
                function ($query) {
                    $query->where('id', '>', 0);
                }
            )->get();
            $type = 'Compass';
            return view('usuario.index')->with(compact('users', 'type'));
            break;

        default:
            $users = User::all();
            return view('usuario.index')->with(compact('users'));
            break;
        }
    }


    /**
     * Muestra el cuadro con los usuarios sin asignar
     *
     * @return \Illuminate\Http\Response
     */
    public function usuariosSinAsignar()
    {
        $users = User::where('userable_id', null)->get();
        return view('usuario.asignar')->with(compact('users'));
    }

    /**
     * Muestra informacion del usuario y del tipo a asignar
     *
     * @return \Illuminate\Http\Response
     */
    public function asignar($userId, $tipo)
    {
        $user = User::findOrFail($userId);
        switch ($tipo) {
        case 'h':
            $asignacion = \App\Holding::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        case 'e':
            $asignacion = \App\Empresa::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        case 'c':
            $asignacion = \App\Centro::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        case 'r':
            $asignacion = \App\CompassRole::all();
            return view('usuario.asignacion')->with(compact('user', 'asignacion'));
            break;
        default:
            Auth::logout();
            break;
        }
    }

    /**
     * Asigna Usuario a un Rol
     *
     * @return \Illuminate\Http\Response
     */
    public function asignacion(Request $request)
    {
        $user = User::findOrFail($request->input('userId'));
        $class = $request->input('asignacionType');
        $asignacion = $class::find($request->input('asignacion'));
        $user->userable_id = $asignacion->id;
        $user->userable_type = get_class($asignacion);

        if ($user->saveOrFail()) {
            
            $msg = [
                'meta' => [
                    'title' => '¡Usuario asignado exitosamente!',
                    'message' => "El Usuario $user->name fue asignado con exito a".($asignacion->nombre ?? $asignacion->name ?? $asignacion->razon_social)
                ]
            ];

            return view('usuario.index')->with(compact('msg'));
        }

    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
