<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request  $request)
    {
        $per_page = $request->per_page;
        return User::paginate($per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        $user = new User;
        $password = Hash::make($request->input('password'));
        $request->merge(['password' => $password]);
        $user->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $user = User::find($id);
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $request->validate([
            'nombres'=>'required|min:2|max:30',
            'apellidos'=>'required|min:2|max:40',
            'celular'=>'required|min:8|max:10',
            'direccion'=>'required|min:2|max:100',
            'usuario'=>'required|min:4|max:15',
            'rol'=>'required|max:15',
            'estado'=>'required'
        ]);
        $user = User::find($id);
        if($request->input('password') == null) {
            $user->update($request->except('password'));
        } else {
            $password = Hash::make($request->input('password'));
            $request->merge(['password' => $password]);
            $user->update($request->all());
        }
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();
    }

    public function userAuth()
    {
        return Auth::user();
    }
}
