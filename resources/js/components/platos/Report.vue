<template>
  <div v-if="rol === 'ADMIN'">
        <h1 class="text-center">Reporte de platos</h1>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <label for="orden">Orden por nombre</label>
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
            orden:1,
            errores:{},
            cargar:false,
            body:[],
            msg:'',
            error:false,
            rol:'',
        }
    },
    methods:{
        async listarP(){
            var platos;
            this.errores = {};
            this.cargar = true;
            this.error = false;
            
            var ord;
            if (this.orden == 1) {
                ord = 'asc';
            } else {
                ord = 'desc';
            }

            try {
                const res = await axios.get('/reportepla/'+ ord);
                platos = res.data;
                this.generarPDF(platos);
            } catch (error) {
                if (error.response.data) {
                    this.errores = error.response.data.errors;
                }
                this.cargar = false;
            }
        },
        async verificar(){
            const res1 = await axios.get('/usuario/my/auth');
            this.rol = res1.data.rol;
        },
        generarPDF(platos){
            var today = new Date();
            var date = today.getFullYear()+'-'+(today.getMonth()+1)+'-'+today.getDate();
            if (platos.length > 0) {
                var data = [];
                platos.forEach(row => {
                    var r = [row.nombre, row.detalle, row.precio, row.precio_medio, row.stock];
                    data.push(r);
                });
                const doc = new jsPDF({
                    orientation: "portrait",
                    format: 'letter'
                });

                doc.setFontSize(20).text("RETAURANTE JEGRILL", 108, 15, {align: "center"});
                doc.text("REPORTE DE PLATOS", 108, 25, {align: "center"});
                doc.setFontSize(12).text("Fecha: " + date, 201, 30, {align: "right"});
                doc.line(15, 35, 201, 35);
                doc.autoTable({
                    theme: 'grid',
                    headStyles: {fillColor: [65,65,65]},
                    head: [['Nombre', 'Detalle', 'Precio (Bs)', 'Precio Medio Plato (Bs)', 'Pzas de Carne Disponible']],
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