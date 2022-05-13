<template>
    <div>
        <h1 class="text-center">Lista de empleados</h1>
        <hr>

        <!-- Button to Open the Modal -->
        <button v-if="rol === 'ADMIN'" type="button" class="btn btn-success my-4" @click="modificar=false; abrirModal({}, 1);">
            Nuevo
        </button>

        <!-- The Modal -->
        <div v-if="rol === 'ADMIN'" class="modal" :class="{mostrar:modal1}">
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
                    <label for="nombres">Nombres</label>
                    <input v-model="usuario.nombres" @input="usuario.nombres = $event.target.value.toUpperCase()" type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombres">
                    <span class="text-danger" v-if="errores.nombres">{{errores.nombres[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="apellidos">Apellidos</label>
                    <input v-model="usuario.apellidos" @input="usuario.apellidos = $event.target.value.toUpperCase()" type="text" class="form-control" name="apellidos" id="apellidos" placeholder="Apellidos">
                    <span class="text-danger" v-if="errores.apellidos">{{errores.apellidos[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="celular">Celular</label>
                    <input v-model="usuario.celular" type="text" class="form-control" name="celular" id="celular" placeholder="Celular">
                    <span class="text-danger" v-if="errores.celular">{{errores.celular[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="direccion">Dirección</label>
                    <input v-model="usuario.direccion" @input="usuario.direccion = $event.target.value.toUpperCase()" type="text" class="form-control" name="direccion" id="direccion" placeholder="Direccion">
                    <span class="text-danger" v-if="errores.direccion">{{errores.direccion[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="usuario">Usuario</label>
                    <input v-model="usuario.usuario" type="text" class="form-control" name="usuario" id="usuario" placeholder="Usuario">
                    <span class="text-danger" v-if="errores.usuario">{{errores.usuario[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="password">Contraseña</label>
                    <input v-model="usuario.password" type="password" class="form-control" name="password" id="password" placeholder="Contraseña">
                    <span class="text-danger" v-if="errores.password">{{errores.password[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="rol">Rol</label>
                    <select v-model="usuario.rol" class="form-control" name="rol" id="rol">
                        <option selected disabled>--SELECCIONE ROL--</option>
                        <option>ADMIN</option>
                        <option>CAJERA</option>
                        <option>MESERO</option>
                        <option>PARRILLA</option>
                        <option>COCINA</option>
                    </select>
                    <span class="text-danger" v-if="errores.rol">{{errores.rol[0]}}</span>
                </div>
                <div class="my-4">
                    <label for="estado">Estado</label>
                    <select v-model="usuario.estado" class="form-control" name="estado" id="estado" placeholder="Seleccione rol">
                        <option value="1">HABILITADO</option>
                        <option value="0">DESHABILITADO</option>
                    </select>
                    <span class="text-danger" v-if="errores.estado">{{errores.estado[0]}}</span>
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
                            El empleado se eliminará permanentemente.
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

        <section>
        <div v-if="rol === 'ADMIN'" class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-dark">
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Celular</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Rol</th>
                    <th scope="col">Estado</th>
                    <th scope="col" colspan="2" class="text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="usu in usuarios.data" :key="usu.id">
                    <th scope="row">{{usu.id}}</th>
                    <td >{{usu.nombres + " " + usu.apellidos}}</td>
                    <td >{{usu.celular}}</td>
                    <td >{{usu.usuario}}</td>
                    <td >{{usu.rol}}</td>
                    <td >{{usu.estado == 1 ? "Habilitado" : "Deshabilitado"}}</td>
                    <td>
                        <button class="btn btn-warning" @click="modificar=true; abrirModal(usu, 1);">Editar</button>
                    </td>
                    <td>
                        <button class="btn btn-danger" @click="abrirModal(usu, 2);">Eliminar</button>
                    </td>
                    </tr>
                </tbody>
            </table>
            <div class="container">
                <div class="row">
                    <div class="col-4 md-4 text-right">
                        <span class="align-text-top">{{usuarios.from}} - {{usuarios.to}} | total: {{usuarios.total}}</span>
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
                                <li class="page-item" :class="{disabled:pagination.page==usuarios.last_page}">
                                    <a class="page-link" @click="pagination.page++; listar()" href="#" aria-label="Previous">
                                        <span aria-hidden="true">&gt;</span>
                                    </a>
                                </li>
                                <li class="page-item" :class="{disabled:pagination.page==usuarios.last_page}">
                                    <a class="page-link" @click="pagination.page=usuarios.last_page; listar()" href="#" aria-label="Next">
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
export default {
    data() {
        return {
            usuarios:[],
            tituloModal:'',
            modal1:0,
            modal2:0,
            modificar:true,
            usuario:{
                nombres:'',
                apellidos:'',
                celular:'',
                direccion:'',
                usuario:'',
                rol:'',
                estado:'',
                password:''
            },
            id:0,
            rol:'',
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
            const res = await axios.get('/usuario', {params: this.pagination});
            this.usuarios = res.data;
            const res1 = await axios.get('/usuario/my/auth');
            this.rol = res1.data.rol;
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
            if (fin > this.usuarios.last_page) {
                fin = this.usuarios.last_page;
            }
            for (let i = ini; i <= fin; i++) {
                arrayN.push(i);
            }
            this.paginas = arrayN;
        },
        async eliminar() {
            const res = await axios.delete('/usuario/' + this.id);
            this.cerrarModal(2);
            this.listar();
        },
        async guardar() {
            try {
                if (this.modificar) {
                    const res = await axios.put('/usuario/' + this.id, this.usuario);
                } else {
                    const res = await axios.post('/usuario', this.usuario);
                }
                this.cerrarModal(1);
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
                        this.tituloModal = 'Modificar Empleado';
                        this.usuario.nombres = data.nombres;
                        this.usuario.apellidos = data.apellidos;
                        this.usuario.celular = data.celular;
                        this.usuario.direccion = data.direccion;
                        this.usuario.usuario = data.usuario;
                        this.usuario.password = '';
                        this.usuario.rol = data.rol;
                        this.usuario.estado = data.estado;
                    } else {
                        this.id = 0;
                        this.tituloModal = 'Registrar Empleado';
                        this.usuario.nombres = '';
                        this.usuario.apellidos = '';
                        this.usuario.celular = '';
                        this.usuario.direccion = '';
                        this.usuario.usuario = '';
                        this.usuario.rol = '';
                        this.usuario.estado = 1;
                        this.usuario.password = '';
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
                    this.id= 0;
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