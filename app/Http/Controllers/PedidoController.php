<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\DetallePedido;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $per_page = $request->per_page;
        $filtro = $request->filtro;
        $user = User::find($request->u_id);
        $today = today();
        switch ($user->rol) {
            case 'ADMIN':
                if ($filtro != 'TODOS') {
                    return Pedido::where('estado',$filtro)->orderBy('id', 'desc')->paginate($per_page);
                } else {
                    return Pedido::orderBy('id', 'desc')->paginate($per_page);
                }
                break;
            case 'CAJERA':
                if ($filtro != 'TODOS') {
                    return Pedido::where('estado',$filtro)->where('created_at','>=', $today)->orderBy('id', 'desc')->paginate($per_page);
                } else {
                    return Pedido::orderBy('id', 'desc')->where('created_at','>=', $today)->paginate($per_page);
                }
                break;
            case 'MESERO':
                return Pedido::where('estado','PEDIDO EN PROCESO')->where('created_at','>=', $today)->where('usuario_id', $user->id)->orderBy('id', 'desc')->paginate($per_page);
                break;
        }
    }

    public function detalle($id)
    {
        return DetallePedido::where('pedido_id', $id)->with('plato')->with('bebida')->with('guarnicion')->get();
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
            'numero_mesa'=>'required',
            'nombre_cliente'=>'max:50',
            'nit_cliente'=>'max:20',
            'total_pagar'=>'required|numeric|min:0',
            'usuario_id'=>'required',
            'descuento' =>'required|numeric|min:0',
            'detalle_pedido'=>'required|array'
        ]);
        $pedido = new Pedido;
        $pedido->numero_mesa = $request->input('numero_mesa');
        $pedido->nombre_cliente = $request->input('nombre_cliente');
        $pedido->nit_cliente = $request->input('nit_cliente');
        $pedido->estado = $request->input('estado');
        $pedido->total_pagar = $request->input('total_pagar');
        $pedido->descuento = $request->input('descuento');
        $pedido->usuario_id = $request->input('usuario_id');
        $pedido->save();
        
        $pedido->detallePedidos()->createMany($request->input('detalle_pedido'));
        return response()->json($pedido);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function show(Pedido $pedido)
    {
        return $pedido;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Pedido $pedido)
    {
        $request->validate([
            'numero_mesa'=>'required',
            'nombre_cliente'=>'max:50',
            'nit_cliente'=>'max:20',
            'total_pagar'=>'required|min:0',
            'usuario_id'=>'required',
            'descuento' =>'required|numeric|min:0',
            'detalle_pedido'=>'required|array'
        ]);
        
        $pedido->numero_mesa = $request->input('numero_mesa');
        $pedido->nombre_cliente = $request->input('nombre_cliente');
        $pedido->nit_cliente = $request->input('nit_cliente');
        $pedido->estado = $request->input('estado');
        $pedido->total_pagar = $request->input('total_pagar');
        $pedido->descuento = $request->input('descuento');
        $pedido->usuario_id = $request->input('usuario_id');
        $pedido->save();

        $pedido->detallePedidos()->delete();
        $pedido->detallePedidos()->createMany($request->input('detalle_pedido'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Pedido  $pedido
     * @return \Illuminate\Http\Response
     */
    public function destroy(Pedido $pedido)
    {
        $pedido->detallePedidos()->delete();
        $pedido->delete();
    }

    public function reporte(Request $request){
        $request->validate([
            'fecha_inicial'=>'required',
            'fecha_final'=>'required',
            'estado'=>'required',
            'orden'=>'required'
        ]);
        if ($request->estado !== 'TODOS') {
            return Pedido::where('estado', $request->estado)
                        ->where('created_at', '>=', $request->fecha_inicial)
                        ->where('created_at', '<=', $request->fecha_final)
                        ->orderBy('created_at', $request->orden)
                        ->with('user')
                        ->with('detallePedidos')
                        ->with('detallePedidos.plato')
                        ->with('detallePedidos.bebida')
                        ->with('detallePedidos.guarnicion')
                        ->get();
        } else {
            return Pedido::where('created_at', '>=', $request->fecha_inicial)
                        ->where('created_at', '<=', $request->fecha_final)
                        ->orderBy('created_at', $request->orden)
                        ->with('user')
                        ->with('detallePedidos')
                        ->with('detallePedidos.plato')
                        ->with('detallePedidos.bebida')
                        ->with('detallePedidos.guarnicion')
                        ->get();
        }
    }

    public function actualizarCabecera($id, Request $request){
        $pedido = Pedido::find($id);
        $pedido->numero_mesa = $request->input('numero_mesa');
        $pedido->nombre_cliente = $request->input('nombre_cliente');
        $pedido->nit_cliente = $request->input('nit_cliente');
        $pedido->estado = $request->input('estado');
        $pedido->total_pagar = $request->input('total_pagar');
        $pedido->descuento = $request->input('descuento');
        $pedido->usuario_id = $request->input('usuario_id');
        $pedido->save();
    }

}
