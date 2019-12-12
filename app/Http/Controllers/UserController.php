<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($tipo)
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
                Auth::logout();
                break;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
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
