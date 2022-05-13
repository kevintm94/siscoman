<template>
  <div v-if="rol === 'ADMIN'">
        <h1 class="text-center">Reporte de pedidos</h1>
        <hr>
        <div class="row">
            <div class="col-sm-3">
            <label for="fecha_ini">Fecha Inicial</label>
            <input v-model="fecha_ini" type="date" class="form-control" name="fecha_ini" id="fecha_ini">
            <span class="text-danger" v-if="errores.fecha_inicial">{{errores.fecha_inicial[0]}}</span>
            <span class="text-danger" v-if="errores.fecha_ini">{{errores.fecha_ini}}</span>
            </div>
            <div class="col-sm-3">
            <label for="fecha_fin">Fecha final</label>
            <input v-model="fecha_fin" type="date" class="form-control" name="fecha_fin" id="fecha_fin">
            <span class="text-danger" v-if="errores.fecha_final">{{errores.fecha_final[0]}}</span>
            </div>
            <div class="col-sm-3">
            <label for="estado">Estado de los pedidos</label>
            <select v-model="estado" name="estado" class="form-control" id="estado">
                <option selected disabled>Seleccione una opción..</option>
                <option :value="1">PEDIDOS EN PROCESO</option>
                <option :value="2">PEDIDOS PAGADOS</option>
                <option :value="3">PEDIDOS ANULADOS</option>
                <option :value="4">TODOS</option>
            </select>
            <span class="text-danger" v-if="errores.estado">{{errores.estado[0]}}</span>
            </div>
            <div class="col-sm-3">
            <label for="orden">Orden por fecha</label>
            <select v-model="orden" name="orden" class="form-control" id="estado">
                <option selected disabled>Seleccione una opción..</option>
                <option :value="1">ASCENDENTE</option>
                <option :value="2">DESCENDENTE</option>
            </select>
            <span class="text-danger" v-if="errores.orden">{{errores.orden[0]}}</span>
            </div>
        </div>
        <div class="row justify-content-md-center py-3">
            <div class="col-sm-4">
            <button class="btn btn-secondary form-control" :class="{disabled:cargar}" @click="listarP();">Generar reporte</button>
            </div>
        </div>
        <div v-if="cargar" class="position-relative">
            <div class="position-absolute top-50 start-50">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <div v-if="error" class="text-center">
            <span class="text-danger">{{msg}}</span>
        </div>
  </div>
</template>

<script>
import { jsPDF } from "jspdf";
import 'jspdf-autotable';
export default {
    data(){
        return{
            fecha_ini:null,
            fecha_fin:null,
            estado:4,
            orden:1,
            errores:{},
            rol:'',
            cargar:false,
            body:[],
            msg:'',
            error:false,
        }
    },
    methods:{
        async listarP(){
            var pedidos;
            this.errores = {};
            this.cargar = true;
            this.error = false;
            
            if (this.fecha_ini > this.fecha_fin) {
                this.errores['fecha_ini'] = "El campo fecha inicial no puede ser mayor a la fecha final"
            }

            var est;
            switch (this.estado) {
                case 1:
                    est = 'PEDIDO EN PROCESO';
                    break;
                case 2:
                    est = 'PEDIDO PAGADO';
                    break;
                case 3:
                    est = 'PEDIDO ANULADO';
                    break;
                case 4:
                    est = 'TODOS';
                    break;
            }
            
            var ord;
            if (this.orden == 1) {
                ord = 'asc';
            } else {
                ord = 'desc';
            }
            
            var data = {
                fecha_inicial: this.fecha_ini, 
                fecha_final: this.fecha_fin, 
                estado: est, 
                orden: ord
            };
            if (Object.entries(this.errores).length === 0) {
                try {
                    const res = await axios.post('/reportep', data);
                    pedidos = res.data;
                    this.generarPDF(pedidos);
                } catch (error) {
                    if (error.response.data) {
                        this.errores = error.response.data.errors;
                    }
                    this.cargar = false;
                }    
            } else {
                this.cargar = false;
            }
        },
        async verificar(){
            const res1 = await axios.get('/usuario/my/auth');
            this.rol = res1.data.rol;
        },
        generarPDF(pedidos){
            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            if (pedidos.length > 0) {
                var data = [];
                //this.body = pedidos;
                pedidos.forEach(row => {
                    var fecha = row.created_at.split('T');
                    var detalle = "";
                    row.detalle_pedidos.forEach(det => {
                        if (det.plato != null) {
                            if (det.medio) {
                                detalle = detalle + det.cantidad + " - "+ det.plato.nombre + "(MEDIO)\n";
                            } else {
                                detalle = detalle + det.cantidad + " - "+ det.plato.nombre + "\n";
                            }
                        }
                    });
                    row.detalle_pedidos.forEach(det => {
                        if (det.guarnicion != null) {
                            if (det.extra) {
                                detalle = detalle + det.cantidad + " - "+ det.guarnicion.nombre + "(EXTRA)\n";
                            }
                        }
                    });
                    row.detalle_pedidos.forEach(det => {
                        if (det.bebida != null) {
                            detalle = detalle + det.cantidad + " - "+ det.bebida.nombre + "\n";
                        }
                    });
                    var r = [fecha[0], row.estado, row.numero_mesa, row.nombre_cliente, detalle, row.descuento, (row.total_pagar - row.descuento), row.user.usuario];
                    data.push(r);
                });
                const doc = new jsPDF({
                    orientation: "portrait",
                    format: 'letter'
                });

                doc.setFontSize(20).text("RETAURANTE JEGRILL", 108, 15, {align: "center"});
                doc.text("REPORTE DE COMANDAS", 108, 25, {align: "center"});
                doc.setFontSize(12).text("Periódo: " + this.fecha_ini + " a " + this.fecha_fin, 15, 33);
                doc.setFontSize(12).text("Fecha: " + date, 201, 33, {align: "right"});
                doc.line(15, 35, 201, 35);
                doc.autoTable({
                    theme: 'grid',
                    headStyles: {fillColor: [65,65,65]},
                    head: [['Fecha', 'Estado', 'Nro Mesa', 'Nombre Cliente', 'Detalle', 'Descuento (Bs)', 'Total a Pagar (Bs)', 'usuario']],
                    body: data,
                    startY: 40,
                });
                

                var string = doc.output('datauristring');
                var embed = "<embed width='100%' height='100%' src='" + string + "'/>";
                this.cargar = false;
                var x = window.open();
                x.document.open();
                x.document.write(embed);
                x.document.close();
            } else {
                this.cargar = false;
                this.error = true;
                this.msg = "No se encontraron registros con los parametros ingresados"
            }
        },
    },
    created(){
        this.verificar();
    },
}
</script>

<style>

</style>