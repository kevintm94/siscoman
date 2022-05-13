<?php

namespace App\Http\Controllers;

use App\Models\Plato;
use Illuminate\Http\Request;

class PlatoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page;
        return Plato::paginate($per_page);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'=>'required|min:2|max:50',
            'detalle'=>'max:100',
            'cantidad_guarniciones'=>'required|numeric',
            'precio'=>'required|numeric|min:0|max:9000',
            'stock'=>'required|numeric|min:0|max:9000',
        ]);
        $plato = new Plato;
        $plato->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $plato = Plato::find($id);
        return $plato;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre'=>'required|min:2|max:50',
            'detalle'=>'max:100',
            'cantidad_guarniciones'=>'required|numeric',
            'precio'=>'required|numeric|min:0|max:9000',
            'stock'=>'required|numeric|min:0|max:9000',
        ]);
        $plato = Plato::find($id);
        $plato->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $plato = Plato::find($id);
        $plato->delete();
    }

    public function listar(){
        return Plato::all();
    }
    
    public function reporte($ord){
        return Plato::orderBy('nombre', $ord)->get();
    }
}
