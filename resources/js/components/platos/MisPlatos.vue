<template>
    <div v-if="rol === 'PARRILLA'">
        <!-- The Modal-->
        <div class="modal" :class="{mostrar:modal}">
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
                            El plato será marcado como entregado.
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="entregar();" type="button" class="btn btn-success" style="width: 90px" data-dismiss="modal">Si</button>
                    <button @click="cerrarModal();" type="button" class="btn btn-danger" style="width: 90px" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
        </div>
        <h3 class="text-center">Mis pedidos pendientes</h3>
        <hr>
        <div v-for="n in cols" :key="n" class="row">
            <div v-if="((n * 6)-6) < cantidad" class="col-sm-2">
                <div class="card text-white mb-3" :class="{'bg-primary':detalles[(n * 6)-6].tipo == 'EN MESA', 'bg-success':detalles[(n * 6)-6].tipo == 'PARA LLEVAR'}" style="max-width: 18rem; min-width: 10rem;height: 17rem;">
                    <div v-if="detalles[(n * 6)-6].tipo == 'EN MESA'" class="card-header">MESA #{{detalles[(n * 6)-6].pedido.numero_mesa}}</div>
                    <div v-if="detalles[(n * 6)-6].tipo == 'PARA LLEVAR'" class="card-header">PARA LLEVAR: {{detalles[(n * 6)-6].pedido.nombre_cliente}}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{detalles[(n * 6)-6].cantidad}} {{detalles[(n * 6)-6].plato.nombre}} <span v-if="detalles[(n * 6)-6].medio"> - <b>MEDIO</b></span></h5>
                        <p class="card-text">{{detalles[(n * 6)-6].detalle_coccion}}</p>
                    </div>
                    <div class="card-footer text-center">
                        <button @click="abrirModal(detalles[(n * 6)-6].id)" class="btn btn-dark" type="button">ENTREGAR</button>
                    </div>
                </div>
            </div>
            <div v-if="((n * 6)-5) < cantidad" class="col-sm-2">
                <div class="card text-white mb-3" :class="{'bg-primary':detalles[(n * 6)-5].tipo == 'EN MESA', 'bg-success':detalles[(n * 6)-5].tipo == 'PARA LLEVAR'}" style="max-width: 18rem; min-width: 10rem;height: 17rem;">
                    <div v-if="detalles[(n * 6)-5].tipo == 'EN MESA'" class="card-header">MESA #{{detalles[(n * 6)-5].pedido.numero_mesa}}</div>
                    <div v-if="detalles[(n * 6)-5].tipo == 'PARA LLEVAR'" class="card-header">PARA LLEVAR: {{detalles[(n * 6)-5].pedido.nombre_cliente}}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{detalles[(n * 6)-5].cantidad}} {{detalles[(n * 6)-5].plato.nombre}} <span v-if="detalles[(n * 6)-5].medio"> - <b>MEDIO</b></span></h5>
                        <p class="card-text">{{detalles[(n * 6)-5].detalle_coccion}}</p>
                    </div>
                    <div class="card-footer text-center">
                        <button @click="abrirModal(detalles[(n * 6)-5].id)" class="btn btn-dark" type="button">ENTREGAR</button>
                    </div>
                </div>
            </div>
            <div v-if="((n * 6)-4) < cantidad" class="col-sm-2">
                <div class="card text-white mb-3" :class="{'bg-primary':detalles[(n * 6)-4].tipo == 'EN MESA', 'bg-success':detalles[(n * 6)-4].tipo == 'PARA LLEVAR'}" style="max-width: 18rem; min-width: 10rem;height: 17rem;">
                    <div v-if="detalles[(n * 6)-4].tipo == 'EN MESA'" class="card-header">MESA #{{detalles[(n * 6)-4].pedido.numero_mesa}}</div>
                    <div v-if="detalles[(n * 6)-4].tipo == 'PARA LLEVAR'" class="card-header">PARA LLEVAR: {{detalles[(n * 6)-4].pedido.nombre_cliente}}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{detalles[(n * 6)-4].cantidad}} {{detalles[(n * 6)-4].plato.nombre}} <span v-if="detalles[(n * 6)-4].medio"> - <b>MEDIO</b></span></h5>
                        <p class="card-text">{{detalles[(n * 6)-4].detalle_coccion}}</p>
                    </div>
                    <div class="card-footer text-center">
                        <button @click="abrirModal(detalles[(n * 6)-4].id)" class="btn btn-dark" type="button">ENTREGAR</button>
                    </div>
                </div>
            </div>
            <div v-if="((n * 6)-3) < cantidad" class="col-sm-2">
                <div class="card text-white mb-3" :class="{'bg-primary':detalles[(n * 6)-3].tipo == 'EN MESA', 'bg-success':detalles[(n * 6)-3].tipo == 'PARA LLEVAR'}" style="max-width: 18rem; min-width: 10rem;height: 17rem;">
                    <div v-if="detalles[(n * 6)-3].tipo == 'EN MESA'" class="card-header">MESA #{{detalles[(n * 6)-3].pedido.numero_mesa}}</div>
                    <div v-if="detalles[(n * 6)-3].tipo == 'PARA LLEVAR'" class="card-header">PARA LLEVAR: {{detalles[(n * 6)-3].pedido.nombre_cliente}}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{detalles[(n * 6)-3].cantidad}} {{detalles[(n * 6)-3].plato.nombre}} <span v-if="detalles[(n * 6)-3].medio"> - <b>MEDIO</b></span></h5>
                        <p class="card-text">{{detalles[(n * 6)-3].detalle_coccion}}</p>
                    </div>
                    <div class="card-footer text-center">
                        <button @click="abrirModal(detalles[(n * 6)-3].id)" class="btn btn-dark" type="button">ENTREGAR</button>
                    </div>
                </div>
            </div>
            <div v-if="((n * 6)-2) < cantidad" class="col-sm-2">
                <div class="card text-white mb-3" :class="{'bg-primary':detalles[(n * 6)-2].tipo == 'EN MESA', 'bg-success':detalles[(n * 6)-2].tipo == 'PARA LLEVAR'}" style="max-width: 18rem; min-width: 10rem;height: 17rem;">
                    <div v-if="detalles[(n * 6)-2].tipo == 'EN MESA'" class="card-header">MESA #{{detalles[(n * 6)-2].pedido.numero_mesa}}</div>
                    <div v-if="detalles[(n * 6)-2].tipo == 'PARA LLEVAR'" class="card-header">PARA LLEVAR: {{detalles[(n * 6)-2].pedido.nombre_cliente}}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{detalles[(n * 6)-2].cantidad}} {{detalles[(n * 6)-2].plato.nombre}} <span v-if="detalles[(n * 6)-2].medio"> - <b>MEDIO</b></span></h5>
                        <p class="card-text">{{detalles[(n * 6)-2].detalle_coccion}}</p>
                    </div>
                    <div class="card-footer text-center">
                        <button @click="abrirModal(detalles[(n * 6)-2].id)" class="btn btn-dark" type="button">ENTREGAR</button>
                    </div>
                </div>
            </div>
            <div v-if="((n * 6)-1) < cantidad" class="col-sm-2">
                <div class="card text-white mb-3" :class="{'bg-primary':detalles[(n * 6)-1].tipo == 'EN MESA', 'bg-success':detalles[(n * 6)-1].tipo == 'PARA LLEVAR'}" style="max-width: 18rem; min-width: 10rem;height: 17rem;">
                    <div v-if="detalles[(n * 6)-1].tipo == 'EN MESA'" class="card-header">MESA #{{detalles[(n * 6)-1].pedido.numero_mesa}}</div>
                    <div v-if="detalles[(n * 6)-1].tipo == 'PARA LLEVAR'" class="card-header">PARA LLEVAR: {{detalles[(n * 6)-1].pedido.nombre_cliente}}</div>
                    <div class="card-body">
                        <h5 class="card-title">{{detalles[(n * 6)-1].cantidad}} {{detalles[(n * 6)-1].plato.nombre}} <span v-if="detalles[(n * 6)-1].medio"> - <b>MEDIO</b></span></h5>
                        <p class="card-text">{{detalles[(n * 6)-1].detalle_coccion}}</p>
                    </div>
                    <div class="card-footer text-center">
                        <button @click="abrirModal(detalles[(n * 6)-1].id)" class="btn btn-dark" type="button">ENTREGAR</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data(){
        return {
            timerCount: 1,
            detalles:[],
            cantidad: 0,
            cols:0,
            id:0,
            modal:0,
            rol:'',
        }
    },
    watch: {
        timerCount: {
            handler(value){
                if (value > 0) {
                    setTimeout(() => {
                        this.listar();
                        this.timerCount++;
                    }, 1000);
                }
            },
            immediate: true
        },
    },
    methods: {
        async listar(){
            const res1 = await axios.get('/usuario/my/auth');
            this.rol = res1.data.rol;
            const res = await axios.get('/detallecarne');
            this.detalles = res.data;
            this.cantidad = this.detalles.length;
            this.cols = Math.ceil(this.cantidad / 6);
        },
        abrirModal(id){
            this.id = id;
            this.modal = 1;
        },
        cerrarModal(){
            this.id = 0;
            this.modal = 0;
        },
        async entregar(){
            try {
                const res = await axios.put('/entregar/'+ this.id);
                this.cerrarModal();
                this.listar();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
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