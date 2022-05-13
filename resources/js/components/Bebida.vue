<template>
    <div>
        <h1 class="text-center">Lista de bebidas</h1>
        <hr>

        <!-- Button to Open the Modal -->
        <button v-if="rol === 'ADMIN' || rol === 'CAJERA'" type="button" class="btn btn-success my-4" @click="modificar=false; abrirModal({}, 1);">
            Nuevo
        </button>

        <!-- The Modal -->
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
                    <input v-model="bebida.nombre" @input="bebida.nombre = $event.target.value.toUpperCase()" type="text" class="form-control" name="nombre" id="nombre" placeholder="Nombre">
                    <span class="text-danger" v-if="errores.nombre">{{errores.nombre[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="contenido">Contenido</label>
                    <input v-model="bebida.contenido" @input="bebida.contenido = $event.target.value.toUpperCase()" type="text" class="form-control" name="contenido" id="contenido" placeholder="Contenido">
                    <span class="text-danger" v-if="errores.contenido">{{errores.contenido[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="stock">Stock</label>
                    <input v-model="bebida.stock" type="number" class="form-control" name="stock" id="stock" placeholder="Stock" min="0">
                    <span class="text-danger" v-if="errores.stock">{{errores.stock[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="precio">Precio</label>
                    <input v-model="bebida.precio" type="number" step=".1" class="form-control" name="precio" id="precio" min="0">
                    <span class="text-danger" v-if="errores.precio">{{errores.precio[0]}}</span>
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
                            La bebida se eliminará permanentemente.
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
                    <th scope="col">Contenido</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Precio</th>
                    <th scope="col" :colspan="col" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="beb in bebidas.data" :key="beb.id">
                    <th scope="row">{{beb.id}}</th>
                    <td >{{beb.nombre}}</td>
                    <td >{{beb.contenido}}</td>
                    <td >{{beb.stock}}</td>
                    <td >{{beb.precio}}</td>
                    <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                        <button class="btn btn-primary" @click="editar(beb);">Deshabilitar</button>
                    </td>
                    <td v-if="rol === 'ADMIN' || rol === 'CAJERA'">
                        <button class="btn btn-warning" @click="modificar=true; abrirModal(beb, 1);">Editar</button>
                    </td>
                    <td v-if="rol === 'ADMIN'">
                        <button class="btn btn-danger" @click="abrirModal(beb, 2);">Eliminar</button>
                    </td>
                    </tr>
                </tbody>
            </table>
            <div class="container">
                <div class="row">
                    <div class="col-4 md-4 text-right">
                        <span class="align-text-top">{{bebidas.from}} - {{bebidas.to}} | total: {{bebidas.total}}</span>
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
                                <li class="page-item" :class="{disabled:pagination.page==bebidas.last_page}">
                                    <a class="page-link" @click="pagination.page++; listar()" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&gt;</span>
                                    </a>
                                </li>
                                <li class="page-item" :class="{disabled:pagination.page==bebidas.last_page}">
                                    <a class="page-link" @click="pagination.page=bebidas.last_page; listar()" href="#" aria-label="Next">
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
            bebidas:[],
            tituloModal:'',
            modal1:0,
            modal2:0,
            modificar:true,
            col:0,
            rol:'',
            bebida:{
                nombre:'',
                contenido:'',
                stock:'',
                precio:'',
            },
            id:0,
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
            const res = await axios.get('/bebida', {params: this.pagination});
            this.bebidas = res.data;
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
            if (fin > this.bebidas.last_page) {
                fin = this.bebidas.last_page;
            }
            for (let i = ini; i <= fin; i++) {
                arrayN.push(i);
            }
            this.paginas = arrayN;
        },
        async eliminar() {
            const res = await axios.delete('/bebida/' + this.id);
            this.cerrarModal(2);
            this.listar();
        },
        async guardar() {
            try {
                if (this.modificar) {
                    const res = await axios.put('/bebida/' + this.id, this.bebida);
                } else {
                    const res = await axios.post('/bebida', this.bebida);
                }
                this.cerrarModal(1);
                this.listar();
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
            }
        },
        async editar(beb) {
            beb.stock = 0;
            try {
                const res = await axios.put('/bebida/' + beb.id, beb);
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
                        this.tituloModal = 'Modificar Bebida';
                        this.bebida.nombre = data.nombre;
                        this.bebida.contenido = data.contenido;
                        this.bebida.stock = data.stock;
                        this.bebida.precio = data.precio;
                    } else {
                        this.id = 0;
                        this.tituloModal = 'Registrar Bebida';
                        this.bebida.nombre = '';
                        this.bebida.contenido = '';
                        this.bebida.stock = '';
                        this.bebida.precio = '';
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
                    this.id = 0;
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