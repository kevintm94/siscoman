<?php

namespace App\Http\Controllers;

use App\Models\Guarnicion;
use Illuminate\Http\Request;

class GuarnicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page;
        return Guarnicion::paginate($per_page);
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
            'precio'=>'required|numeric|min:0',
            'stock'=>'required|numeric|min:0',
        ]);
        $guarnicion = new Guarnicion;
        $guarnicion->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Guarnicion  $guarnicion
     * @return \Illuminate\Http\Response
     */
    public function show(Guarnicion $guarnicion)
    {
        return $guarnicion;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Guarnicion  $guarnicion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Guarnicion $guarnicion)
    {
        $request->validate([
            'nombre'=>'required|min:2|max:50',
            'precio'=>'required|numeric|min:0',
            'stock'=>'required|numeric|min:0',
        ]);
        $guarnicion->update($request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Guarnicion  $guarnicion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Guarnicion $guarnicion)
    {
        $guarnicion->delete();
    }

    public function listar(){
        return Guarnicion::all();
    }

    public function reporte($ord){
        return Guarnicion::orderBy('nombre', $ord)->get();
    }
}
