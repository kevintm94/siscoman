<template>
    <div>
        <h3 v-if="rol === 'MESERO'" class="text-center">Mis pedidos pendientes</h3>
        <h1 v-else class="text-center">Lista de pedidos</h1>
        <hr>

        <router-link v-if="rol === 'ADMIN' || rol === 'CAJERA' || rol === 'MESERO'" :to="{name: 'PedidosCreate'}" class="btn btn-success">Nuevo</router-link>
        <div v-if="rol === 'ADMIN' || rol === 'CAJERA'" class="float-end">
            <label for="filtro">Filtro pedidos:</label>
            <select v-model="filtro" name="filtro" id="filtro">
                <option selected disabled>Seleccione una opción..</option>
                <option :value="1">PEDIDOS EN PROCESO</option>
                <option :value="2">PEDIDOS PAGADOS</option>
                <option :value="3">PEDIDOS ANULADOS</option>
                <option :value="4">TODOS</option>
            </select>
        </div>

        <!-- The Modal -->
        <div class="modal" :class="{mostrar:modal}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Detalle</h4>
                    <button @click="cerrarModal(1);" type="button" class="btn-close" data-dismiss="modal"></button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="my-2">
                        <label><b>Nro de mesa:</b> {{pedido.numero_mesa}} </label>
                    </div>
                    <div class="my-2">
                        <label><b>Nombre Cliente:</b> {{pedido.nombre_cliente}}</label>
                    </div>
                    <div class="my-2">
                        <label><b>Nit:</b> {{pedido.nit_cliente}}</label>
                    </div>
                    <div class="my-2">
                        <label><b>Total a Pagar:</b> {{pedido.total_pagar}} Bs.</label>
                        <label><b>Descuento:</b> {{pedido.descuento}} Bs.</label>
                    </div>
                

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                <th scope="col">#</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Comentario</th>
                                <th scope="col">Tipo</th>
                                <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="det in detalles" :key="det.id">
                                    <th scope="row">{{det.id}}</th>
                                    <td>{{det.cantidad}}</td>
                                    <td v-if="det.plato_id !== null" >{{det.plato.nombre}} - {{det.medio?'MEDIO PLATO':'PLATO COMPLETO'}}</td>
                                    <td v-if="det.guarnicion_id !== null">{{det.guarnicion.nombre}}{{det.extra?' - EXTRA':''}}</td>
                                    <td v-if="det.bebida_id !== null">{{det.bebida.nombre}}</td>
                                    <td>{{det.comentario}}</td>
                                    <td>{{det.tipo}}</td>
                                    <td v-if="det.bebida_id === null">{{det.estado}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="cerrarModal(1);" type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
        </div>
        <!-- The Modal 2-->
        <div v-if="rol === 'ADMIN' || rol === 'CAJERA'" class="modal" :class="{mostrar:modal1}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="container">
                        <div class="text-center py-3">
                            <span class="text-warning"><i class="fas fa-exclamation-triangle fa-7x"></i></span>
                        </div>
                        <div class="text-center">
                            <h3>¿Esta seguro?</h3>
                            El pedido se anulará permanentemente.
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="anularPedido();" type="button" class="btn btn-success" style="width: 90px" data-dismiss="modal">Si</button>
                    <button @click="cerrarModal(2);" type="button" class="btn btn-danger" style="width: 90px" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
        </div>
        <!-- The Modal 3-->
        <div v-if="rol === 'ADMIN'" class="modal" :class="{mostrar:modal2}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="container">
                        <div class="text-center py-3">
                            <span class="text-warning"><i class="fas fa-exclamation-triangle fa-7x"></i></span>
                        </div>
                        <div class="text-center">
                            <h3>¿Esta seguro?</h3>
                            El pedido se eliminará permanentemente.
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="eliminar();" type="button" class="btn btn-success" style="width: 90px" data-dismiss="modal">Si</button>
                    <button @click="cerrarModal(3);" type="button" class="btn btn-danger" style="width: 90px" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
        </div>
        <!-- The Modal 4-->
        <div v-if="rol === 'ADMIN' || rol === 'CAJERA'" class="modal" :class="{mostrar:modal3}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <!-- Modal body -->
                <div class="modal-body">
                    <div class="container">
                        <div class="text-center py-3">
                            <span class="text-warning"><i class="fas fa-exclamation-triangle fa-7x"></i></span>
                        </div>
                        <div class="text-center">
                            <h3>¿Esta seguro?</h3>
                            El pedido seleccionado será marcado como pagado.
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="pagarPedido();" type="button" class="btn btn-success" style="width: 90px" data-dismiss="modal">Si</button>
                    <button @click="cerrarModal(4);" type="button" class="btn btn-danger" style="width: 90px" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
        </div>

        <div v-if="mostrar" class="alert alert-success" role="alert">
            {{mensaje}}
            <button @click="cerrarMensaje()" type="button" class="btn-close float-end" aria-label="Close"></button>
        </div>

        <section>
        <div v-if="rol === 'ADMIN' || rol === 'CAJERA' || rol === 'MESERO'" class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Número Mesa</th>
                    <th scope="col">Nombre de Cliente</th>
                    <th scope="col">Nit</th>
                    <th scope="col">Total(Bs.)</th>
                    <th scope="col">Descuento(Bs.)</th>
                    <th scope="col">Total a Pagar(Bs.)</th>
                    <th v-if="rol === 'ADMIN' || rol === 'CAJERA'" scope="col">Estado</th>
                    <th scope="col" :colspan="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="ped in pedidos.data" :key="ped.id">
                        <th scope="row">{{ped.id}}</th>
                        <td>{{ped.numero_mesa}}</td>
                        <td>{{ped.nombre_cliente}}</td>
                        <td>{{ped.nit_cliente}}</td>
                        <td>{{ped.total_pagar}}</td>
                        <td>{{ped.descuento}}</td>
                        <td>{{ped.total_pagar - ped.descuento}}</td>
                        <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">{{ped.estado}}</td>
                        <td>
                            <button class="btn btn-primary" @click="abrirModal(ped, 1)">Detalle</button>
                        </td>
                        <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                            <button class="btn btn-dark" :class="{disabled:cargar, disabled:ped.estado=='PEDIDO PAGADO'||ped.estado=='PEDIDO ANULADO'}" @click="abrirModal(ped, 2);" type="button" >Anular Cuenta</button>
                        </td>
                        <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                            <button class="btn btn-info" :class="{disabled:cargar, disabled:ped.estado=='PEDIDO PAGADO'||ped.estado=='PEDIDO ANULADO'}" @click="abrirModal(ped, 4);" type="button" >Pagar Cuenta</button>
                        </td>
                        <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                            <button class="btn btn-secondary" :class="{disabled:cargar}" @click="crearPDF(ped);" type="button" >Imprimir Cuenta</button>
                        </td>
                        <td v-if="rol === 'ADMIN' || rol === 'CAJERA' || rol === 'MESERO'">
                            <router-link :to="{name: 'PedidosEdit', params: {id: ped.id}}" class="btn btn-warning" :class="{disabled:ped.estado=='PEDIDO PAGADO'||ped.estado=='PEDIDO ANULADO'}">Editar</router-link>
                        </td>
                        <td v-if="rol === 'ADMIN'">
                            <button class="btn btn-danger" @click="abrirModal(ped, 3);">Eliminar</button>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="container">
                <div class="row" >
                    <div class="col-4 md-4 text-right">
                        <span class="align-text-top">{{pedidos.from}} - {{pedidos.to}} | total: {{pedidos.total}}</span>
                    </div>
                    <div class="col-2 md-2">
                        <select class="custom-select" v-model="pagination.per_page" @change="listar();">
                            <option value="3">3</option>
                            <option value="5">5</option>
                            <option value="8">8</option>
                        </select>
                    </div>
                    <div class="col-6 md-6">
                        <nav>
                            <ul class="pagination">
                                <li class="page-item" :class="{disabled:pagination.page==1}">
                                    <a class="page-link" @click="pagination.page=1; listar()" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <li class="page-item" :class="{disabled:pagination.page==1}">
                                    <a class="page-link" @click="pagination.page--; listar()" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&lt;</span>
                                    </a>
                                </li>
                                <li class="page-item" v-for="n in paginas" :key="n" :class="{active:pagination.page==n}"><a class="page-link" @click="pagination.page=n; listar()" href="#">{{n}}</a></li>
                                <li class="page-item" :class="{disabled:pagination.page==pedidos.last_page}">
                                    <a class="page-link" @click="pagination.page++; listar()" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&gt;</span>
                                    </a>
                                </li>
                                <li class="page-item" :class="{disabled:pagination.page==pedidos.last_page}">
                                    <a class="page-link" @click="pagination.page=pedidos.last_page; listar()" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
        </section>
    </div>
</template>
<script>
import { jsPDF } from "jspdf";
export default {
    data() {
        return {
            pedidos:[],
            detalles:[],
            modal:0,
            modal1:0,
            modal2:0,
            modal3:0,
            pedido:{
                numero_mesa:'',
                nombre_cliente:'',
                nit_cliente:'',
                estado:'',
                total_pagar:'',
                descuento:'',
                usuario_id:''
            },
            id:0,
            rol:"",
            col: 0,
            errores:{},
            pagination:{
                page:1,
                per_page:5,
                filtro:'PEDIDO EN PROCESO',
                u_id:0,
            },
            paginas:[],
            mostrar: false,
            mensaje: '',
            filtro: 1,
            cargar: false,
            res:{},
        }
    },
    watch:
    {
        filtro(){
            switch (this.filtro) {
                case 1:
                    this.pagination.filtro = 'PEDIDO EN PROCESO';
                    break;
                case 2:
                    this.pagination.filtro = 'PEDIDO PAGADO';
                    break;
                case 3:
                    this.pagination.filtro = 'PEDIDO ANULADO';
                    break;
                case 4:
                    this.pagination.filtro = 'TODOS';
                    break;
            }
            this.listar();
        }
    },
    methods: {
        async listar() {
            const res1 = await axios.get('/usuario/my/auth');
            this.pagination.u_id = res1.data.id;
            this.rol = res1.data.rol;
            switch (this.rol) {
                case 'ADMIN':
                    this.col = 6;
                    break;
                case 'CAJERA':
                    this.col = 5;
                    break;
                case 'MESERO':
                    this.col = 2;
                    break;
            }
            const res = await axios.get('/pedido', {params: this.pagination});
            this.pedidos = res.data;
            this.listarPaginas();
        },
        listarPaginas(){
            const n = 2;
            let arrayN = [];
            let ini = this.pagination.page - 2;
            if (ini < 1) {
                ini = 1;
            }
            let fin = this.pagination.page + 2;
            if (fin > this.pedidos.last_page) {
                fin = this.pedidos.last_page;
            }
            for (let i = ini; i <= fin; i++) {
                arrayN.push(i);
            }
            this.paginas = arrayN;
        },
        async eliminar() {
            const res = await axios.delete('/pedido/' + this.id);
            this.mostrar = true;
            this.mensaje = 'Pedido eliminado correctamente';
            this.cerrarModal(3);
            this.listar();
        },
        cerrarMensaje(){
            this.mostrar = false;
            this.mensaje = '';
        },
        async abrirModal(data = {}, n) {
            switch (n) {
                case 1:
                    this.modal = 1;
                    this.id = data.id;
                    this.pedido.numero_mesa = data.numero_mesa;
                    this.pedido.nombre_cliente = data.nombre_cliente;
                    this.pedido.nit_cliente = data.nit_cliente;
                    this.pedido.estado = data.estado;
                    this.pedido.total_pagar = data.total_pagar;
                    this.pedido.descuento = data.descuento;
                    this.pedido.usuario_id = data.usuario_id;
        
                    const res = await axios.get('/detalle/' + data.id);
                    this.detalles = res.data;
                    break;
            
                case 2:
                    this.modal1 = 1
                    this.id = data.id;
                    this.pedido.numero_mesa = data.numero_mesa;
                    this.pedido.nombre_cliente = data.nombre_cliente;
                    this.pedido.nit_cliente = data.nit_cliente;
                    this.pedido.estado = 'PEDIDO ANULADO';
                    this.pedido.total_pagar = data.total_pagar;
                    this.pedido.descuento = data.descuento;
                    this.pedido.usuario_id = data.usuario_id;
                    break;
                case 3:
                    this.modal2 = 1;
                    this.id = data.id;
                    break;
                case 4:
                    this.modal3 = 1
                    this.id = data.id;
                    this.pedido.numero_mesa = data.numero_mesa;
                    this.pedido.nombre_cliente = data.nombre_cliente;
                    this.pedido.nit_cliente = data.nit_cliente;
                    this.pedido.estado = 'PEDIDO PAGADO';
                    this.pedido.total_pagar = data.total_pagar;
                    this.pedido.descuento = data.descuento;
                    this.pedido.usuario_id = data.usuario_id;
                    break;
            }
        },
        cerrarModal(n) {
            switch (n) {
                case 1:
                    this.modal = 0;
                    this.errores = {};
                    this.detalles = [];
                    this.pedido.numero_mesa = '';
                    this.pedido.nombre_cliente = '';
                    this.pedido.nit_cliente = '';
                    this.pedido.estado = '';
                    this.pedido.total_pagar = '';
                    this.pedido.descuento = '';
                    this.pedido.usuario_id = '';
                    this.id = 0;
                    break;
                case 2:
                    this.modal1 = 0;
                    this.errores = {};
                    this.id = 0;
                    this.pedido.numero_mesa = '';
                    this.pedido.nombre_cliente = '';
                    this.pedido.nit_cliente = '';
                    this.pedido.estado = '';
                    this.pedido.total_pagar = '';
                    this.pedido.descuento = '';
                    this.pedido.usuario_id = '';
                    break;
                case 3:
                    this.modal2 = 0;
                    this.errores = {};
                    this.id = 0;
                    break;
                case 4:
                    this.modal3 = 0;
                    this.errores = {};
                    this.id = 0;
                    this.pedido.numero_mesa = '';
                    this.pedido.nombre_cliente = '';
                    this.pedido.nit_cliente = '';
                    this.pedido.estado = '';
                    this.pedido.total_pagar = '';
                    this.pedido.descuento = '';
                    this.pedido.usuario_id = '';
                    break;
            }
        },
        async anularPedido() {
            try {
                const res = await axios.put('/pedidocab/' + this.id, this.pedido);
                this.cerrarModal(2);
                this.listar();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async pagarPedido() {
            try {
                const res = await axios.put('/pedidocab/' + this.id, this.pedido);
                this.cerrarModal(4);
                this.listar();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async crearPDF(data) {
            this.cargar = true;
            const res = await axios.get('/detalle/' + data.id);
            const doc = new jsPDF({
                orientation: "portrait",
                unit: "mm",
                format: [80, 279]
            });

            doc.setFontSize(20).text("RETAURANTE JEGRILL", 40, 10, {align: "center", maxWidth: 70});
            doc.setFontSize(12).text("CUENTA", 40, 28, {align: "center"});
            doc.line(5, 30, 75, 30);
            doc.text("Nro mesa: " + data.numero_mesa, 5, 35);
            if (data.nombre_cliente == null) {
                doc.text("Cliente:", 5, 40,);
            } else {
                doc.text("Cliente: " + data.nombre_cliente, 5, 40);
            }
            if (data.nit_cliente == null) {
                doc.text("Nit o CI:", 5, 45,);
            } else {
                doc.text("Nit o CI: " + data.nit_cliente, 5, 45);
            }
            doc.text("DETALLE", 40, 53, {align: "center"});
            doc.line(5, 55, 75, 55);
            doc.setFontSize(10).text("UDS.", 14, 60, {align: "right"});
            doc.text("DESCRIPCION", 17, 60);
            doc.text("SUB TOT.", 75, 60, {align: "right"});
            doc.line(5, 62, 75, 62);
            var y = 67;
            res.data.forEach(det => {
                if (det.plato_id != null) {
                    if (det.medio) {
                        doc.text(det.cantidad + "", 14, y, {align: "right"});
                        doc.text(det.plato.nombre + " - MEDIO", 17, y);
                        var n = det.cantidad * det.plato.precio_medio;
                        n = n.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
                        doc.text(n + "", 75, y, {align: "right"});
                        y += 5;
                    } else {
                        doc.text(det.cantidad + "", 14, y, {align: "right"});
                        doc.text(det.plato.nombre, 17, y);
                        var n = det.cantidad * det.plato.precio;
                        n = n.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
                        doc.text(n + "", 75, y, {align: "right"});
                        y += 5;
                    }
                }
            });
            res.data.forEach(det => {
                if (det.guarnicion_id != null) {
                    if (det.extra) {
                        doc.text(det.cantidad + "", 14, y, {align: "right"});
                        doc.text(det.guarnicion.nombre + " - EXTRA", 17, y);
                        var n = det.cantidad * det.guarnicion.precio;
                        n = n.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
                        doc.text(n + "", 75, y, {align: "right"});
                        y += 5;
                    }
                }
            });
            res.data.forEach(det => {
                if (det.bebida_id != null) {
                    doc.text(det.cantidad + "", 14, y, {align: "right"});
                    doc.text(det.bebida.nombre, 17, y);
                    var n = det.cantidad * det.bebida.precio;
                    n = n.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
                    doc.text(n + "", 75, y, {align: "right"});
                    y += 5;
                }
            });
            doc.line(5, y, 75, y);
            y += 5;
            doc.text("TOTAL(Bs.):", 60, y, {align: "right"});
            var tot = data.total_pagar.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
            doc.text(tot + "", 75, y, {align: "right"});
            y += 5;
            doc.text("DESC.(Bs.):", 60, y, {align: "right"});
            var desc = data.descuento.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
            doc.text(desc + "", 75, y, {align: "right"});
            y += 5;
            doc.text("TOTAL A PAGAR(Bs.):", 60, y, {align: "right"});
            var tota = data.total_pagar - data.descuento;
            tota = tota.toLocaleString('es-ES', {maximumFractionDigits: 2, minimumFractionDigits: 2});
            doc.text(tota + "", 75, y, {align: "right"});
            y += 5;
            //doc.save("a4.pdf");            
            var string = doc.output('datauristring');
            var embed = "<embed width='100%' height='100%' src='" + string + "'/>"
            this.cargar = false;
            var x = window.open();
            x.document.open();
            x.document.write(embed);
            x.document.close();
        },
    },
    created() {
        
        this.listar();
    },
}
</script>
<style>
    .mostrar {
        display: list-item;
        opacity: 1;
        background: rgba(85, 85, 87, 0.76);
    }
</style>