<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetallePedido extends Model
{
    use HasFactory;

     protected $table = 'detalle_pedidos';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cantidad',
        'pedido_id',
        'plato_id',
        'medio',
        'detalle_coccion',
        'guarnicion_id',
        'extra',
        'bebida_id',
        'comentario',
        'tipo',
        'estado'
    ];

    public function pedido()
    {
        return $this->belongsTo(Pedido::class);
    }

    public function plato()
    {
        return $this->belongsTo(Plato::class);
    }

    public function guarnicion()
    {
        return $this->belongsTo(Guarnicion::class);
    }

    public function Bebida()
    {
        return $this->belongsTo(Bebida::class);
    }
}
