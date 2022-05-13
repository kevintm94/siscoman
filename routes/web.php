<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PlatoController;
use App\Http\Controllers\GuarnicionController;
use App\Http\Controllers\BebidaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\DetallePedidoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/', function () {
    if (Auth::guest()) {
        return redirect('login');
    } else {
        return redirect('inicio');
    }
});
Route::get('inicio', function () {
    return view('inicio');
})->middleware('auth');
Route::view('login', 'usuario.login')->name('login')->middleware('guest');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);

Route::apiResource('usuario', UserController::class)->middleware('auth');
Route::get('usuario/my/auth', [UserController::class, 'userAuth'])->middleware('auth');
Route::apiResource('plato', PlatoController::class)->middleware('auth');
Route::get('listap', [PlatoController::class, 'listar'])->middleware('auth');
Route::get('reportepla/{ord}', [PlatoController::class, 'reporte'])->middleware('auth');
Route::apiResource('guarnicion', GuarnicionController::class)->middleware('auth');
Route::get('listag', [GuarnicionController::class, 'listar'])->middleware('auth');
Route::get('reportegua/{ord}', [GuarnicionController::class, 'reporte'])->middleware('auth');
Route::apiResource('bebida', BebidaController::class)->middleware('auth');
Route::get('listab', [BebidaController::class, 'listar'])->middleware('auth');
Route::get('reportebeb/{ord}', [BebidaController::class, 'reporte'])->middleware('auth');
Route::apiResource('pedido', PedidoController::class)->middleware('auth');
Route::get('detalle/{id}', [PedidoController::class, 'detalle'])->name('detalle');
Route::put('pedidocab/{id}', [PedidoController::class, 'actualizarCabecera']);
Route::post('reportep', [PedidoController::class, 'reporte'])->middleware('auth');
Route::apiResource('detallepedido', DetallePedidoController::class)->middleware('auth');
Route::get('detallecarne', [DetallePedidoController::class, 'detallecarne'])->middleware('auth');
Route::get('detalleguarnicion', [DetallePedidoController::class, 'detalleguarnicion'])->middleware('auth');
Route::put('entregar/{id}', [DetallePedidoController::class, 'entregar'])->middleware('auth');

Route::view('usuarios', 'usuario.user')->middleware('auth');
Route::view('platos', 'plato.plato')->middleware('auth');
Route::view('guarniciones', 'guarnicion.guarnicion')->middleware('auth');
Route::view('bebidas', 'bebida.bebida')->middleware('auth');
Route::view('pedidos', 'pedido.pedido')->middleware('auth');
Route::view('reporte/pedidos', 'pedido.reporte')->middleware('auth');
Route::view('reporte/platos', 'plato.reporte')->middleware('auth');
Route::view('reporte/guaniciones', 'guarnicion.reporte')->middleware('auth');
Route::view('reporte/bebidas', 'bebida.reporte')->middleware('auth');
Route::view('my/guarniciones', 'guarnicion.mis')->middleware('auth');
Route::view('my/platos', 'plato.mis')->middleware('auth');

Route::get('/clear-cache', function () {
    echo Artisan::call('config:clear');
    echo Artisan::call('config:cache');
    echo Artisan::call('cache:clear');
    echo Artisan::call('route:clear');
 });

Route::get('{any}', function () {
    return view('pedido.pedido');
})->where('any', '.*')->middleware('auth');