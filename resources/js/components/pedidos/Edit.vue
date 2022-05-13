<template>
    <div v-if="rol === 'ADMIN' || rol === 'CAJERA' || rol === 'MESERO'">
        
        <h1 class="text-center">Editar Pedido</h1>
        <hr>
        <section>
        <div class="row">
            <div class="col-sm-6">
            <label for="numero_mesa">Nro de mesa</label>
            <input v-model="pedido.numero_mesa" type="number" class="form-control" name="numero_mesa" id="numero_mesa" min="1">
            <span class="text-danger" v-if="errores.numero_mesa">{{errores.numero_mesa[0]}}</span>
            </div>
            <div class="col-sm-6">
            <label for="nombre_cliente">Nombre del cliente</label>
            <input v-model="pedido.nombre_cliente" @input="pedido.nombre_cliente = $event.target.value.toUpperCase()" type="text" class="form-control" name="nombre_cliente" id="nombre_cliente" placeholder="Nombre del cliente">
            <span class="text-danger" v-if="errores.nombre_cliente">{{errores.nombre_cliente[0]}}</span>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
            <label for="nit_cliente">NIT</label>
            <input v-model="pedido.nit_cliente" type="text" class="form-control" name="nit_cliente" id="nit_cliente" placeholder="NIT">
            <span class="text-danger" v-if="errores.nit_cliente">{{errores.nit_cliente[0]}}</span>
            </div>
            <div class="col-sm-6">
            <label for="descuento">Descuento(Bs.)</label>
            <input v-model="pedido.descuento" type="number" step=".1" class="form-control" name="descuento" id="descuento" min="0">
            <span class="text-danger" v-if="errores.descuento">{{errores.descuento[0]}}</span>
            </div>
        </div>
        <hr>
        <h4 class="text-center">Detalle de pedido</h4>
        <div class="row text-center">
            <div class="col-4 mx-auto">
                <button @click="abrirModal(1);" type="button" class="btn btn-success">Platos</button>
            </div>
            <div class="col-4 mx-auto">
                <button @click="abrirModal(2);" type="button" class="btn btn-info">Guarniciones</button>
            </div>
            <div class="col-4 mx-auto">
                <button @click="abrirModal(3);" type="button" class="btn btn-warning">Bebidas</button>
            </div>
        </div>

        <!-- The Modal Plato -->
        <div class="modal" :class="{mostrar:modal1}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Agregar Plato</h4>
                <button @click="cerrarModal(1);" type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <label for="cantidad">Cantidad <small class="text-muted" v-if="can > 0">(Cantidad disponible del plato seleccionado: {{can1}})</small></label>
                <div class="input-group mb-3">
                    <input v-model="detallePedido.cantidad" type="number" class="form-control" name="cantidad" id="cantidad" min="1">
                    <button class="btn btn-outline-secondary btn-danger text-white" type="button" @click="reducir()"><i class="fas fa-minus"></i></button>
                    <button class="btn btn-outline-secondary btn-success text-white" type="button" @click="detallePedido.cantidad++"><i class="fas fa-plus"></i></button>
                </div>
                <span class="text-danger" v-if="errores.cantidad">{{errores.cantidad}}</span><br>
                
                <label for="plato_id">Plato</label>
                <div class="input-group mb-3">
                    <select @change="onChangeP($event);" v-model="detallePedido.plato_id" class="form-select" name="plato_id" id="plato_id">
                        <option selected disabled value=null>SELECCIONE UN PLATO...</option>
                        <option v-for="pla in platos" :key="pla.id" :value="pla.id">{{pla.nombre}}</option>
                    </select>
                    <div class="input-group-text">
                        <label for="medio">Medio</label>
                        <input @change="checked($event);" v-model="detallePedido.medio" class="form-check-input mt-0 ms-1" type="checkbox" name="medio" :disabled="disabled == 1">
                    </div>
                </div>
                <span class="text-danger" v-if="errores.plato">{{errores.plato}}</span>
                
                <div class="my-4">
                    <label for="tipo">Tipo de detalle</label>
                    <select v-model="detallePedido.tipo" class="form-select" name="tipo" id="tipo">
                        <option selected disabled value=null>SELECCIONE UN TIPO...</option>
                        <option>EN MESA</option>
                        <option>PARA LLEVAR</option>
                    </select>
                    <span class="text-danger" v-if="errores.tipo">{{errores.tipo}}</span>
                </div>
                
                <div class="my-4">
                    <label for="detalle_coccion">Detalle Cocción</label>
                    <textarea v-model="detallePedido.detalle_coccion" @input="detallePedido.detalle_coccion = $event.target.value.toUpperCase()" class="form-control" name="detalle_coccion" id="detalle_coccion"  rows="3"></textarea>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button @click="cerrarModal(1);" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button @click="agregarPlato();" type="button" class="btn btn-success" data-dismiss="modal">Agregar</button>
            </div>

            </div>
        </div>
        </div>

        <!-- The Modal Guarnición -->
        <div class="modal" :class="{mostrar:modal2}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Agregar Guarnicion</h4>
                <button @click="cerrarModal(2);" type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <label for="cantidad">Cantidad <small class="text-muted" v-if="can > 0">(Cantidad disponible de la guarnición seleccionada: {{can}})</small></label>
                <div class="input-group mb-3">
                    <input v-model="detallePedido.cantidad" type="number" class="form-control" name="cantidad" id="cantidad" min="1">
                    <button class="btn btn-outline-secondary btn-danger text-white" type="button" @click="reducir()"><i class="fas fa-minus"></i></button>
                    <button class="btn btn-outline-secondary btn-success text-white" type="button" @click="detallePedido.cantidad++"><i class="fas fa-plus"></i></button>
                </div>
                <span class="text-danger" v-if="errores.cantidad">{{errores.cantidad}}</span><br>
                
                <label for="guarnicion_id">Guarnicion</label>
                <div class="input-group mb-3">
                    <select @change="onChangeG($event);" v-model="detallePedido.guarnicion_id" class="form-select" name="guarnicion_id" id="guarnicion_id">
                        <option selected disabled value=null>SELECCIONE UN GUARNICIÓN...</option>
                        <option v-for="gua in guarniciones" :key="gua.id" :value="gua.id">{{gua.nombre}}</option>
                    </select>
                    <div class="input-group-text">
                        <label for="extra">Extra</label>
                        <input v-model="detallePedido.extra" class="form-check-input mt-0 ms-1" type="checkbox" name="extra">
                    </div>
                </div>
                <span class="text-danger" v-if="errores.guarnicion">{{errores.guarnicion}}</span>
                
                <div class="my-4">
                    <label for="tipo">Tipo de detalle</label>
                    <select v-model="detallePedido.tipo" class="form-select" name="tipo" id="tipo">
                        <option selected disabled value=null>SELECCIONE UN TIPO...</option>
                        <option>EN MESA</option>
                        <option>PARA LLEVAR</option>
                    </select>
                    <span class="text-danger" v-if="errores.tipo">{{errores.tipo}}</span>
                </div>
                
                <div class="my-4">
                    <label for="comentario">Comentario</label>
                    <textarea v-model="detallePedido.comentario" @input="detallePedido.comentario = $event.target.value.toUpperCase()" class="form-control" name="comentario" id="comentario"  rows="3"></textarea>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button @click="cerrarModal(2);" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button @click="agregarGuarnicion();" type="button" class="btn btn-success" data-dismiss="modal">Agregar</button>
            </div>

            </div>
        </div>
        </div>

        <!-- The Modal Bebida -->
        <div class="modal" :class="{mostrar:modal3}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Agregar Bebida</h4>
                <button @click="cerrarModal(3);" type="button" class="btn-close" data-dismiss="modal">&times;</button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <label for="cantidad">Cantidad <small class="text-muted" v-if="can > 0">(Cantidad disponible de la bebida seleccionada: {{can}})</small></label>
                <div class="input-group mb-3">
                    <input v-model="detallePedido.cantidad" type="number" class="form-control" name="cantidad" id="cantidad" min="1">
                    <button class="btn btn-outline-secondary btn-danger text-white" type="button" @click="reducir()"><i class="fas fa-minus"></i></button>
                    <button class="btn btn-outline-secondary btn-success text-white" type="button" @click="detallePedido.cantidad++"><i class="fas fa-plus"></i></button>
                </div>
                <span class="text-danger" v-if="errores.cantidad">{{errores.cantidad}}</span><br>
                
                <div class="my-4">
                    <label for="bebida_id">Bebida</label>
                    <select @change="onChangeB($event);" v-model="detallePedido.bebida_id" class="form-select" name="bebida_id" id="bebida_id">
                        <option selected disabled value=null>SELECCIONE UN BEBIDA..</option>
                        <option v-for="beb in bebidas" :key="beb.id" :value="beb.id">{{beb.nombre}} {{beb.contenido}}</option>
                    </select>
                    <span class="text-danger" v-if="errores.bebida">{{errores.bebida}}</span>
                </div>

                <div class="my-4">
                    <label for="tipo">Tipo de detalle</label>
                    <select v-model="detallePedido.tipo" class="form-select" name="tipo" id="tipo">
                        <option selected disabled value=null>SELECCIONE UN TIPO..</option>
                        <option>EN MESA</option>
                        <option>PARA LLEVAR</option>
                    </select>
                    <span class="text-danger" v-if="errores.tipo">{{errores.tipo}}</span>
                </div>

                <div class="my-4">
                    <label for="comentario">Comentario</label>
                    <textarea v-model="detallePedido.comentario" @input="detallePedido.comentario = $event.target.value.toUpperCase()" class="form-control" name="comentario" id="comentario"  rows="3"></textarea>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button @click="cerrarModal(3);" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button @click="agregarBebida();" type="button" class="btn btn-success" data-dismiss="modal">Agregar</button>
            </div>

            </div>
        </div>
        </div>

        <span class="text-danger" v-if="errores.detalle_pedido">Debe seleccionar al menos un item en el detalle</span>

        <!-- Tabla detalle -->
        <table class="table table-sm table-striped table-hover">
            <thead>
                <tr>
                    <th scope="col">Detalle</th>
                    <th scope="col">Cantidad</th>
                    <th scope="col">Comentario</th>
                    <th scope="col">Borrar</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(detalle, index) in detallePedidos" :key="detalle.id">
                    <td v-if="detalle.plato !== null">{{detalle.plato.nombre}}</td>
                    <td v-if="detalle.guarnicion !== null">{{detalle.guarnicion.nombre}}</td>
                    <td v-if="detalle.bebida !== null">{{detalle.bebida.nombre}} {{detalle.bebida.contenido}}</td>
                    <td>{{detalle.cantidad}}</td>
                    <td v-if="detalle.plato !== null">{{detalle.detalle_coccion}}<span v-if="detalle.medio"> - MEDIO(S) PLATO(S)</span></td>
                    <td v-if="detalle.guarnicion !== null">{{detalle.comentario}}<span v-if="detalle.extra"> - EXTRA</span></td>
                    <td v-if="detalle.bebida !== null">{{detalle.comentario}}</td>
                    <td><button @click="quitarDetalle(index, detalle)" type="button" class="btn btn-danger"><i class="fas fa-trash"></i></button></td>
                </tr>
                <tr>
                    <td colspan="4" class="table-dark text-end">Total: {{pedido.total_pagar}} Bs.</td>
                </tr>
            </tbody>
        </table>

        <div class="row">
            <dir class="d-grid gap-2">
                <button @click="actualizar();" type="button" class="btn btn-success text-white">Guardar</button>
            </dir>
        </div>
        <div class="row">
            <dir class="d-grid gap-2">
                <router-link :to="{name: 'PedidosIndex'}" class="btn btn-danger text-white">Cancelar</router-link>
            </dir>
        </div>
        </section>
    </div>
</template>

<script>
export default {
    data() {
        return{
            pedido:{
                id: null,
                numero_mesa:null,
                nombre_cliente:'',
                nit_cliente:'',
                estado:'',
                total_pagar:0,
                descuento:0,
                usuario_id:null,
            },
            detalle:{},
            detallePedido:{
                cantidad:1,
                pedido_id:null,
                plato_id:null,
                medio:false,
                detalle_coccion:null,
                guarnicion_id:null,
                extra:false,
                bebida_id:null,
                comentario:null,
                tipo:'EN MESA',
                estado:'COCINANDO'
            },
            nombrePedido:'',
            detallePedidos:[],
            platos:[],
            guarniciones:[],
            bebidas:[],
            modal1:0,
            modal2:0,
            modal3:0,
            errores:{},
            disabled:1,
            can:0,
            can1:0,
            rol:'',
        }
    },
    methods: {
        async listar(){
            const res = await axios.get('/usuario/my/auth');
            this.pedido.usuario_id = res.data.id;
            this.rol = res.data.rol;
            this.listarp();
            this.listarg();
            this.listarb();
        },
        async listarp(){
            this.platos = [];
            const res = await axios.get('/listap');
            res.data.forEach(pla => {
                if (pla.stock > 0) {
                    this.platos.push(pla);
                }
            });
        },
        async listarg(){
            this.guarniciones = [];
            const res = await axios.get('/listag');
            res.data.forEach(gua => {
                if (gua.stock > 0) {
                    this.guarniciones.push(gua);
                }
            });
        },
        async listarb(){
            this.bebidas = [];
            const res = await axios.get('/listab');
            res.data.forEach(beb => {
                if (beb.stock > 0) {
                    this.bebidas.push(beb);
                }
            });
        },
        async getPedido(){
            try {
                
                const res = await axios.get('/pedido/' + this.$route.params.id)
                this.pedido.id = res.data.id;
                this.pedido.numero_mesa = res.data.numero_mesa;
                this.pedido.nombre_cliente = res.data.nombre_cliente;
                this.pedido.nit_cliente = res.data.nit_cliente;
                this.pedido.estado = res.data.estado;
                this.pedido.total_pagar = res.data.total_pagar;
                this.pedido.descuento = res.data.descuento;
                this.pedido.usuario_id = res.data.usuario_id;
                const res2 = await axios.get('/detalle/' + this.pedido.id);
                this.detalle = res2.data;
                this.detalle.forEach(det => {
                    var data = {
                        cantidad: det.cantidad,
                        pedido_id: det.pedido_id,
                        plato_id: det.plato_id,
                        medio: det.medio,
                        detalle_coccion: det.detalle_coccion,
                        guarnicion_id: det.guarnicion_id,
                        extra: det.extra,
                        bebida_id: det.bebida_id,
                        comentario: det.comentario,
                        tipo: det.tipo,
                        estado: det.estado,
                        plato: det.plato,
                        guarnicion: det.guarnicion,
                        bebida: det.bebida
                    };
                    this.detallePedidos.push(data);               
                });
                
            } catch (error) {
                this.errores = error.data;
            }
        },
        abrirModal(nro) {
            switch (nro) {
                case 1:
                    this.modal1 = 1;
                    break;
            
                case 2:
                    this.modal2 = 1;
                    break;
                case 3:
                    this.modal3 = 1;
                    break;
            }
        },
        cerrarModal(nro) {
            switch (nro) {
                case 1:
                    this.modal1 = 0;
                    break;
                case 2:
                    this.modal2 = 0;
                    break;
                case 3:
                    this.modal3 = 0;
                    break;
            }
            this.limpiar();            
        },
        reducir(){
            if(this.detallePedido.cantidad > 1){
                this.detallePedido.cantidad--;
            }
        },
        limpiar(){
            this.detallePedido.cantidad=1;
            this.detallePedido.pedido_id=null;
            this.detallePedido.plato_id=null;
            this.detallePedido.medio=false;
            this.detallePedido.detalle_coccion=null;
            this.detallePedido.guarnicion_id=null;
            this.detallePedido.extra=false;
            this.detallePedido.bebida_id=null;
            this.detallePedido.comentario=null;
            this.detallePedido.tipo='EN MESA';
            this.detallePedido.estado='COCINANDO';
            this.errores = {};
            this.can = 0;
            this.can1 = 0;
        },
        agregarPlato(){
            this.errores = {};
            if (isNaN(this.detallePedido.cantidad)) {
                this.errores['cantidad'] = "La cantidad debe ser un valor numérico.";
            }
            if (this.detallePedido.cantidad % 1 != 0) {
                this.errores['cantidad'] = "La cantidad debe ser un valor entero.";
            }
            if (this.detallePedido.cantidad < 1) {
                this.errores['cantidad'] = "La cantidad no puede ser menor a 1.";
            }
            if (this.detallePedido.cantidad > this.can1) {
                this.errores['cantidad'] = "La cantidad no puede ser mayor a la cantidad disponible.";
            }
            if (this.detallePedido.plato_id === null) {
                this.errores['plato']= "Seleccione un plato";
            }
            if (this.detallePedido.tipo === null) {
                this.errores['tipo'] = "Seleccione un tipo";
            }
            if (Object.entries(this.errores).length === 0) {
                var index = this.platos.findIndex(x=>x.id == this.detallePedido.plato_id);
                var data = {
                    cantidad:this.detallePedido.cantidad,
                    pedido_id:this.detallePedido.pedido_id,
                    plato_id:this.detallePedido.plato_id,
                    medio:this.detallePedido.medio,
                    detalle_coccion:this.detallePedido.detalle_coccion,
                    guarnicion_id:this.detallePedido.guarnicion_id,
                    extra:this.detallePedido.extra,
                    bebida_id:this.detallePedido.bebida_id,
                    comentario:this.detallePedido.comentario,
                    tipo:this.detallePedido.tipo,
                    estado:this.detallePedido.estado,
                    plato: this.platos[index],
                    guarnicion: null,
                    bebida: null
                };
                if (this.detallePedido.medio && data.plato.precio_medio === null) {
                    this.errores = Object.assign({plato: "El plato seleccionado no tiene definido precio de medio plato."});
                }else{
                    var pzasCarne = parseInt(data.plato.detalle);
                    var cant = 0;
                    if (data.medio) {
                        this.pedido.total_pagar += data.plato.precio_medio * data.cantidad;
                        cant = data.plato.stock - data.cantidad;
                    } else {
                        this.pedido.total_pagar += data.plato.precio * data.cantidad;
                        cant = data.plato.stock - (data.cantidad * pzasCarne);
                    }
                    this.limpiar();
                    this.editarP(data.plato, cant);
                    this.detallePedidos.push(data);
                    this.modal1 = 0;
                }
            }
        },
        agregarGuarnicion(){
            this.errores = {};
            if (isNaN(this.detallePedido.cantidad)) {
                this.errores['cantidad'] = "La cantidad debe ser un valor numérico.";
            }
            if (this.detallePedido.cantidad % 1 != 0) {
                this.errores['cantidad'] = "La cantidad debe ser un valor entero.";
            }
            if (this.detallePedido.cantidad < 1) {
                this.errores['cantidad'] = "La cantidad no puede ser menor a 1.";
            }
            if (this.detallePedido.cantidad > this.can) {
                this.errores['cantidad'] = "La cantidad no puede ser mayor a la cantidad disponible.";
            }
            if (this.detallePedido.guarnicion_id === null) {
                this.errores['guarnicion'] = "Seleccione una guarnición.";
            }
            if (this.detallePedido.tipo === null) {
                this.errores['tipo'] = "Seleccione un tipo";
            }
            if (Object.entries(this.errores).length === 0) {
                var index = this.guarniciones.findIndex(x=>x.id == this.detallePedido.guarnicion_id);
                var data = {
                    cantidad:this.detallePedido.cantidad,
                    pedido_id:this.detallePedido.pedido_id,
                    plato_id:this.detallePedido.plato_id,
                    medio:this.detallePedido.medio,
                    detalle_coccion:this.detallePedido.detalle_coccion,
                    guarnicion_id:this.detallePedido.guarnicion_id,
                    extra:this.detallePedido.extra,
                    bebida_id:this.detallePedido.bebida_id,
                    comentario:this.detallePedido.comentario,
                    tipo:this.detallePedido.tipo,
                    estado:this.detallePedido.estado,
                    plato: null,
                    guarnicion: this.guarniciones[index],
                    bebida: null
                };
                if (data.extra) {
                    this.pedido.total_pagar += data.guarnicion.precio * data.cantidad;
                }
                var cant = 0;
                cant = data.guarnicion.stock - data.cantidad;
                this.limpiar();
                this.editarG(data.guarnicion, cant);
                this.detallePedidos.push(data);
                this.modal2 = 0;
            }
        },
        agregarBebida(){
            this.errores = {};
            if (isNaN(this.detallePedido.cantidad)) {
                this.errores['cantidad'] = "La cantidad debe ser un valor numérico.";
            }
            if (this.detallePedido.cantidad % 1 != 0) {
                this.errores['cantidad'] = "La cantidad debe ser un valor entero.";
            }
            if (this.detallePedido.cantidad < 1) {
                this.errores['cantidad'] = "La cantidad no puede ser menor a 1.";
            }
            if (this.detallePedido.cantidad > this.can) {
                this.errores['cantidad'] = "La cantidad no puede ser mayor a la cantidad disponible.";
            }
            if (this.detallePedido.bebida_id === null) {
                this.errores['bebida']= "Seleccione una bebida.";
            }
            if (this.detallePedido.tipo === null) {
                this.errores['tipo'] = "Seleccione un tipo";
            }
            if (Object.entries(this.errores).length === 0) {
                var index = this.bebidas.findIndex(x=>x.id == this.detallePedido.bebida_id);
                var data = {
                    cantidad:this.detallePedido.cantidad,
                    pedido_id:this.detallePedido.pedido_id,
                    plato_id:this.detallePedido.plato_id,
                    medio:this.detallePedido.medio,
                    detalle_coccion:this.detallePedido.detalle_coccion,
                    guarnicion_id:this.detallePedido.guarnicion_id,
                    extra:this.detallePedido.extra,
                    bebida_id:this.detallePedido.bebida_id,
                    comentario:this.detallePedido.comentario,
                    tipo:this.detallePedido.tipo,
                    estado:'-',
                    plato: null,
                    guarnicion: null,
                    bebida: this.bebidas[index]
                };
                this.pedido.total_pagar += data.bebida.precio * data.cantidad;
                var cant = 0;
                cant = data.bebida.stock - data.cantidad;
                this.limpiar();
                this.editarB(data.bebida, cant);
                this.detallePedidos.push(data);
                this.modal3 = 0;
            }
        },
        quitarDetalle(index, detalle){
            var can = 0;
            if (detalle.plato != null) {
                var pzasCarne = parseInt(detalle.plato.detalle);
                if (detalle.medio) {
                    this.pedido.total_pagar -= detalle.plato.precio_medio * detalle.cantidad;
                    can = detalle.cantidad;
                } else {
                    this.pedido.total_pagar -= detalle.plato.precio * detalle.cantidad;                   
                    can = detalle.cantidad * pzasCarne; 
                }
                this.editarP2(detalle.plato_id, can);
            }
            if (detalle.guarnicion != null) {
                if (detalle.extra) {
                    this.pedido.total_pagar -= detalle.guarnicion.precio * detalle.cantidad;
                }
                this.editarG2(detalle.guarnicion_id, detalle.cantidad);
            }
            if (detalle.bebida != null) {
                this.pedido.total_pagar -= detalle.bebida.precio * detalle.cantidad;
                this.editarB2(detalle.bebida_id, detalle.cantidad);
            }
            this.detallePedidos.splice(index, 1);
        },
        onChangeP(event) {
            var index = this.platos.findIndex(x=>x.id == event.target.value);
            var plato = this.platos[index];
            var pzasCarne = parseInt(plato.detalle);
            this.can = plato.stock;
            if (plato.precio_medio > 0) {
                this.disabled = 0;
                this.can1 = Math.floor(this.can / pzasCarne);
            } else {
                this.detallePedido.medio = false;
                this.disabled = 1;
                this.can1 = this.can;
            }
        },
        onChangeG(event) {
            var index = this.guarniciones.findIndex(x=>x.id == event.target.value);
            var guar = this.guarniciones[index];
            this.can = guar.stock;
        },
        onChangeB(event) {
            var index = this.bebidas.findIndex(x=>x.id == event.target.value);
            var beb = this.bebidas[index];
            this.can = beb.stock;
        },
        async checked(event) {
            var i = this.platos.findIndex(x=>x.id == this.detallePedido.plato_id)
            var plato = this.platos[i];
            var pzasCarne = parseInt(plato.detalle);
            if (this.detallePedido.medio) {
                this.can1 = this.can;
            }else{
                this.can1 = Math.floor(this.can / pzasCarne);
            }
        },
        async actualizar(){
            try {
                var data = {
                    numero_mesa:this.pedido.numero_mesa,
                    nombre_cliente:this.pedido.nombre_cliente,
                    nit_cliente:this.pedido.nit_cliente,
                    estado:this.pedido.estado,
                    total_pagar:this.pedido.total_pagar,
                    descuento:this.pedido.descuento,
                    usuario_id:this.pedido.usuario_id,
                    detalle_pedido: this.detallePedidos
                };
                const res = await axios.put('/pedido/' + this.pedido.id, data);
                this.$router.push({name: 'PedidosIndex'});
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editarP(pla, cant) {
            pla.stock = cant;
            try {
                const res = await axios.put('/plato/' + pla.id, pla);
                this.listarp();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editarP2(plato_id, cant) {
            const res1 = await axios.get('/plato/' + plato_id);
            var pla = res1.data;
            pla.stock = pla.stock + cant;
            try {
                const res = await axios.put('/plato/' + pla.id, pla);
                this.listarp();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editarG(gua, cant) {
            gua.stock = cant;
            try {
                const res = await axios.put('/guarnicion/' + gua.id, gua);
                this.listarp();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editarG2(gua_id, cant) {
            const res1 = await axios.get('/guarnicion/' + gua_id);
            var gua = res1.data;
            gua.stock = gua.stock + cant;
            try {
                const res = await axios.put('/guarnicion/' + gua.id, gua);
                this.listarg();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editarB(beb, cant) {
            beb.stock = cant;
            try {
                const res = await axios.put('/bebida/' + beb.id, beb);
                this.listarp();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editarB2(beb_id, cant) {
            const res = await axios.get('/bebida/' + beb_id);
            var beb = res.data;
            beb.stock = beb.stock + cant;
            try {
                const res = await axios.put('/bebida/' + beb.id, beb);
                this.listarb();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
    },
    created() {
        this.listar();
        this.getPedido();
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