<template>
    <div>
        
        <!-- Accion Nuevo Origen -->
        <div class="row">
            <div class="col text-left">
                <button 
                    @click="clearView(1)"
                    class="btn btn-sm btn-dark" 
                    id="nuevo_origen" 
                    name="button" 
                    type="button" 
                >NUEVO ORIGEN</button>
            </div>
        </div>

        <br>
        
        <!-- Formulario de Carga, Edicion de Origen -->
        <form-origins-component
          :listOrigins="listOrigins" 
          :operation = this.operation
          :registry = 'dataReg'
          @updateView = "updateView"
          @clearView="clearView"
          @pushData="pushData"
          v-if="this.operation > 0"
        ></form-origins-component>

        <br>
        
        <!-- Muestreo de Errores de validacion -->
        <div class="container-fluid" v-if="errors.length">
            <div class="alert alert-danger" role="alert" v-for="error in errors">
                <b>{{ error }}</b>
            </div>
        </div>
        
        <!-- Filtro de Datos -->
        <div class="row">
            <div class="col">
                <input 
                    autofocus=""
                    class="form-control input-sm" 
                    name="busqueda" 
                    placeholder="Filtrar Datos" 
                    type="text" 
                    v-model="term" 
                >
            </div>
        </div>
        
        <br>

        <!-- Listado de elementos cargados -->
        <div class="row">
            
            <div class="col">
            
                <table class="table table-origins">
                    
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-left">Nombre</th>
                            <th class="text-center" width="200px">Tipo</th>
                            <th class="text-center" width="170px">Creado</th>
                            <th class="text-center" width="170px">Estado</th>
                            <th class="text-center" width="300px">Acciones</th>
                        </tr>
                    </thead>

                    <tbody v-if="success" >
                        <tr v-for="(element, index) in arrayFilter" :key="element.id">
                            <td class="text-left">{{element.name}}</td>
                            <td class="text-center">{{element.misc_name}}</td>
                            <td class="text-center">{{ element.created_at | formatDate }}</td>
                            
                            <td class="text-center" v-if="element.state === 'active'">Activo</td>
                            
                            <td class="text-center" v-else>Inactivo</td>

                            <td class="text-center">
                                <input 
                                    type="button" 
                                    class="btn btn-sm btn-width"
                                    @click="changeStateOrigin(element, index)"
                                    v-bind:value="(element.state === 'active') ?  'Desactivar' : 'Activar'"
                                    v-bind:class="(element.state === 'active') ?  'btn-success' : 'btn-dark' "
                                >

                                <input 
                                    type="button" 
                                    class="btn btn-sm btn-primary btn-width" 
                                    @click="editOrigin(element, index)" 
                                    value="Editar"
                                >
                                <input 
                                    type="button" 
                                    class="btn btn-sm btn-danger btn-width"
                                    @click="deleteOrigin(element, index)"
                                    value="Borrar"
                                >

                            </td>
                        </tr>
                    </tbody>

                    <tfoot v-else>
                        <tr>
                            <th colspan="5" class="text-center">
                                Sin resultados para mostrar
                                <a href="#" @click="clearView(1)">Cargar Uno</a>
                            </th>
                        </tr>
                    </tfoot>

                </table>

            </div>

        </div>

        <hr>

    </div>

</template>

<script>

    export default 
    {
        data()
        {
            return {
                success: false,         // true <- muestra listado | false <- muestra mjs sin resultados.
                operation: 0,           // 0 listado, 1 alta, 2 edita.
                dataReg: [],            // Datos del origen nuevo/edicion. 'registry', 
                listOrigins: [],        // Listado de origenes.
                term: '',               // Termino de Busqueda.
                errors: [],             // Registra errores, para la validacion.
            }
        },

        mounted() 
        { 
            this.loadList();

        }, // Mounted.

        computed: 
        {
            // Filtro del Listado, por Apellido y Nombre.
            arrayFilter: function(){
              return this.listOrigins.filter(
                (element) => element.name.toLowerCase().includes(this.term.toLowerCase())
                || element.misc_name.toLowerCase().includes(this.term.toLowerCase())
              )
            },

        }, // Computed.


        methods: 
        {
            // Carga el listado inicial desde la db. 
            loadList: function(){

                axios
                .get('api/origin')
                .then(listado => {
                    var countObj = Object.keys(listado.data).length;
                    if (countObj > 0) {
                        this.success = true;
                        for (var i = 0; i < countObj; i++) {
                            this.listOrigins.push(listado.data[i]);
                        }
                    }
                })
            },
            // Cambiar el estado del Origen, para que pueda ser utilizado o no.
            changeStateOrigin:  function(data, index){
                axios
                .post('api/changeState/' + data.id)
                .then(response => {
                    if (response.data.success) {
                        this.listOrigins[index].state = response.data.element.state;
                    }
                })
                .catch(error => console.log(error))
            },
            // Editar un registro especifico.
            editOrigin: function(data, index){
                this.dataReg = data;
                this.operation = 2;
            },
            // Elimina un elemento de la DB y de la vista.
            deleteOrigin: function(data, index){
              if (confirm("Desea eliminar este Origen?")) {
                axios
                .delete('api/origin/' + data.id)
                .then( response => {
                    this.errors = [];
                    if (response.data.success) {
                        this.listOrigins.splice(index, 1);
                        this.term = '';
                        if (this.listOrigins.length == 0) {
                            this.success = false;
                        }
                        alert(response.data.msj);
                    }else{
                        this.errors.push(response.data.errors[0]);
                    }
                })
              }
            },
            // Carga en la vista un nuevo elemento y lo muestra.
            pushData(data){
              this.listOrigins.push(data);
              this.success = true;
              this.operation = 0;
              document.getElementById('nuevo_origen').focus();
            },
            // Establece la vista segun se indique.
            clearView(param){
              this.dataReg = [];
              this.operation = param;
            },
            // Refresca la vista luego de actualizar un elemento especifico, recibe el elemento actualizado.
            updateView(){
                this.listOrigins[this.dataReg.index] = this.dataReg;
                this.operation = 0;
            },
        }, // Methods.

    }
</script>

<style type="text/css">
    .btn-width{
        width: 80px;
    }

    .table-origins tbody tr:hover {
        background: #eeee;
    }

    .ptop{
        padding-top: 5px;
    }

</style>
