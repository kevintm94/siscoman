<template>
    <div>
        <h1 class="text-center">Lista de platos</h1>
        <hr>

        <!-- Button to Open the Modal -->
        <button v-if="rol === 'ADMIN' || rol === 'CAJERA'" type="button" class="btn btn-success my-4" @click="modificar=false; abrirModal({}, 1);">
            Nuevo
        </button>

        <!-- The Modal 1-->
        <div v-if="rol === 'ADMIN' || rol === 'CAJERA'" class="modal" :class="{mostrar:modal1}">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">{{tituloModal}}</h4>
                <button @click="cerrarModal(1);" type="button" class="btn-close" data-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="my-4">
                    <label for="nombre">Nombre</label>
                    <input v-model="plato.nombre" @input="plato.nombre = $event.target.value.toUpperCase()" type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
                    <span class="text-danger" v-if="errores.nombre">{{errores.nombre[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="detalle">Detalle</label>
                    <input v-model="plato.detalle" @input="plato.detalle = $event.target.value.toUpperCase()" type="text" class="form-control" name="detalle" id="detalle" placeholder="Detalle">
                    <span class="text-danger" v-if="errores.detalle">{{errores.detalle[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="precio">Precio</label>
                    <input v-model="plato.precio" type="number" step=".1" class="form-control" name="precio" id="precio" min="0">
                    <span class="text-danger" v-if="errores.precio">{{errores.precio[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="precio_medio">Precio de medio plato</label>
                    <input v-model="plato.precio_medio" type="number" step=".1" class="form-control" name="precio_medio" id="precio_medio" min="0">
                    <span class="text-danger" v-if="errores.precio_medio">{{errores.precio_medio[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="stock">Stock</label>
                    <input v-model="plato.stock" type="number" class="form-control" name="stock" id="stock" placeholder="Stock" min="0">
                    <span class="text-danger" v-if="errores.stock">{{errores.stock[0]}}</span>
                </div>
            </div>

            <!-- Modal footer -->
            <div class="modal-footer">
                <button @click="cerrarModal(1);" type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button @click="guardar();" type="button" class="btn btn-success" data-dismiss="modal">Guardar</button>
            </div>

            </div>
        </div>
        </div>
        <!-- The Modal 2-->
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
                            El plato se eliminará permanentemente.
                        </div>
                    </div>
                </div>

                <!-- Modal footer -->
                <div class="modal-footer">
                    <button @click="eliminar();" type="button" class="btn btn-success" style="width: 90px" data-dismiss="modal">Si</button>
                    <button @click="cerrarModal(2);" type="button" class="btn btn-danger" style="width: 90px" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
        </div>

        <div v-if="rol === 'ADMIN' || rol === 'CAJERA'" class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Detalle</th>
                    <th scope="col">Precio(Bs.)</th>
                    <th scope="col">Precio Medio Plato(Bs.)</th>
                    <th scope="col">Carne Disponible(Pzas.)</th>
                    <th scope="col" :colspan="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="plat in platos.data" :key="plat.id">
                    <th scope="row">{{plat.id}}</th>
                    <td >{{plat.nombre}}</td>
                    <td >{{plat.detalle}}</td>
                    <td >{{plat.precio}}</td>
                    <td >{{plat.precio_medio}}</td>
                    <td >{{plat.stock}}</td>
                    <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                        <button class="btn btn-primary" @click="editar(plat);">Deshabilitar</button>
                    </td>
                    <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                        <button class="btn btn-warning" @click="modificar=true; abrirModal(plat, 1);">Editar</button>
                    </td>
                    <td v-if="rol === 'ADMIN'">
                        <button class="btn btn-danger" @click="abrirModal(plat, 2);">Eliminar</button>
                    </td>
                    </tr>
                </tbody>
            </table>
            <div class="container">
                <div class="row">
                    <div class="col-4 md-4 text-right">
                        <span class="align-text-top">{{platos.from}} - {{platos.to}} | total: {{platos.total}}</span>
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
                                <li class="page-item" :class="{disabled:pagination.page==platos.last_page}">
                                    <a class="page-link" @click="pagination.page++; listar()" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&gt;</span>
                                    </a>
                                </li>
                                <li class="page-item" :class="{disabled:pagination.page==platos.last_page}">
                                    <a class="page-link" @click="pagination.page=platos.last_page; listar()" href="#" aria-label="Next">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            platos:[],
            tituloModal:'',
            modal1:0,
            modal2:0,
            modificar:true,
            plato:{
                nombre:'',
                detalle:'',
                cantidad_guarniciones:3,
                precio:0,
                precio_medio:0,
                stock:1
            },
            id:0,
            col: 0,
            rol: '',
            errores:{},
            pagination:{
                page:1,
                per_page:5,
            },
            paginas:[],
        }
    },
    methods: {
        async listar() {
            const res = await axios.get('/plato', {params: this.pagination});
            this.platos = res.data;
            const res1 = await axios.get('/usuario/my/auth');
            this.rol = res1.data.rol;
            switch (this.rol) {
                case 'ADMIN':
                    this.col = 3;
                    break;
                case 'CAJERA':
                    this.col = 2;
                    break;
            }
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
            if (fin > this.platos.last_page) {
                fin = this.platos.last_page;
            }
            for (let i = ini; i <= fin; i++) {
                arrayN.push(i);
            }
            this.paginas = arrayN;
        },
        async eliminar() {
            const res = await axios.delete('/plato/' + this.id);
            this.cerrarModal(2);
            this.listar();
        },
        async guardar() {
            try {
                if (this.modificar) {
                    const res = await axios.put('/plato/' + this.id, this.plato);
                } else {
                    const res = await axios.post('/plato', this.plato);
                }
                this.cerrarModal(1);
                this.listar();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editar(pla) {
            pla.stock = 0;
            try {
                const res = await axios.put('/plato/' + pla.id, pla);
                this.listar();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        abrirModal(data = {}, n) {
            switch (n) {
                case 1:
                    this.modal1 = 1;
                    if (this.modificar) {
                        this.id = data.id;
                        this.tituloModal = 'Modificar Plato';
                        this.plato.nombre = data.nombre;
                        this.plato.detalle = data.detalle;
                        this.plato.cantidad_guarniciones = data.cantidad_guarniciones;
                        this.plato.precio = data.precio;
                        this.plato.precio_medio = data.precio_medio;
                        this.plato.stock = data.stock;
                    } else {
                        this.id = 0;
                        this.tituloModal = 'Registrar Plato';
                        this.plato.nombre = '';
                        this.plato.detalle = '';
                        this.plato.cantidad_guarniciones = 0;
                        this.plato.precio = 0;
                        this.plato.precio_medio = 0;
                        this.plato.stock = 0;
                    }
                    break;
                case 2:
                    this.modal2 = 1;
                    this.id = data.id;
                    break;
            }
            
        },
        cerrarModal(n) {
            switch (n) {
                case 1:
                    this.modal1 = 0;
                    this.errores = {};
                    break;
                case 2:
                    this.modal2 = 0;
                    this.errores = {};
                    break;
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