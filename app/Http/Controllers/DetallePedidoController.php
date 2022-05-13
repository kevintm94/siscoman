<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DetallePedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return DetallePedido::all();
    }

    public function detallecarne()
    {
        $today = today();
        return DetallePedido::with('plato')->with('pedido')->where('plato_id', '!=', null)->where('created_at','>=', $today)->where('estado', 'COCINANDO')->orderBy('pedido_id', 'asc')->get();
    }
    
    public function detalleguarnicion()
    {
        $today = today();
        return DetallePedido::with('guarnicion')->with('pedido')->where('guarnicion_id', '!=', null)->where('created_at','>=', $today)->orderBy('pedido_id', 'asc')->where('estado', 'COCINANDO')->get();
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
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function show(DetallePedido $detallePedido)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DetallePedido $detallePedido)
    {
        //
    }

    public function entregar($id){
        $detallePedido = DetallePedido::find($id);
        $detallePedido->estado = 'ENTREGADO';
        $detallePedido->save();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DetallePedido  $detallePedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(DetallePedido $detallePedido)
    {
        //
    }
}
