<template>
    <div class="container">
        <div class="table-responsive">
            <div class="form-group form-row">
                <label class="col-md-1 col-form-label text-md-right">Año:</label>
                <div class="col-md-2">
                    <select class="form-control" v-model="year">
                        <option v-for="(index) in 5" :value="getFullYear() + index">{{getFullYear() + index}}</option>
                    </select>
                </div>
                <div class="col-md-2"><button class="btn btn-success" @click="submit">Guardar</button></div>
            </div>
            <table class="table table-sm">
                <thead>
                    <th scope="col"></th>
                    <th v-for="(index) in 12" scope="col">{{ getMonthName(index - 1) }}</th>
                    <th scope="col">Total</th>
                </thead>
                <tbody>
                    <tr v-for="(centro, index) in items">
                        <th scope="row">{{centro.nombre}}</th>
                        <td  v-for="(mes) in 12">
                            <input
                                class="form-control form-control-sm"
                                type="text"
                                v-model="presupuesto[index][mes]"
                                @input="calcularTotales(index, mes)"
                                :key="index"
                                />
                        </td>
                        <td scope="row">
                            <input class="form-control form-control-sm" v-model="totalCentro[index]" :key="index" @input="totalPorCentro(index, $event)" type="text">
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Total</th>
                        <td  v-for="(index) in 12" scope="row">
                            <input class="form-control form-control-sm" v-model="totalMes[index]"  :key="index" @input="totalPorMes(index, $event)" type="text">
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</template>
<script charset="utf-8">
export default {
    props: {
        items: {
            type: Array,
            default: []
        },
        action: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            presupuesto: [],
            totalCentro: [],
            totalMes: [],
            year: null
        }
    },
    methods: {
        getFullYear: function() {
            var date = new Date();
            return date.getFullYear();
        },
        getMonthName: function(id) {
            const date = new Date(2020, id, 1);
            return date.toLocaleString('default', { month: 'long' });
        },
        totalPorCentro: function(centroId, evt) {
            let total = evt.target.value;
            let subtotal = total / 12;
            for (var i = 0; i < 13; i++) {
                this.presupuesto[centroId][i] = subtotal.toFixed(2);
            }
        },
        totalPorMes: function(mesId, evt) {
            let total = evt.target.value;
            let subtotal = total / this.items.length;
            for (var i = 0; i < this.items.length; i++) {
                this.presupuesto[i][mesId] = subtotal.toFixed(2);
            }
        },
        calcularTotales: function(centroId, mesId) {
            let totalCentro = 0;
            let centroArr = this.presupuesto[centroId].map(function(x) {
                if(!(x === undefined)) {
                    totalCentro += parseFloat(x)
                }
            });
            let totalMes = 0;
            let mesArr = this.presupuesto.map(function(x) {
                if (!(x[mesId] === undefined)) {
                    totalMes += parseFloat(x[mesId]);
                }
            });
            this.totalCentro[centroId] = totalCentro.toFixed(2);
            this.totalMes[mesId] = totalMes.toFixed(2);
        },
        submit: function() {
            //TODO: Agregar animacion para cargar al momento de hacer el post
            let data = [];
            var self = this;
            this.presupuesto.map(function(x, index) {
                data.push({
                    'centro': self.items[index],
                    'presupuesto': x
                })
            });
            axios
                .post(this.action, {
                    input: data,
                    year: this.year
                })
                .then(function (response) {
                    let data = response.data.meta;
                    Swal.fire({
                        title: data.title,
                        text: data.msg,
                        icon: 'success'
                    });
                })
                .catch(function (error) {
                    console.error(error)
                })
        }
    },
    created() {
        this.totalCentro = new Array(this.items.length);
        this.totalMes = new Array(12);
        for (var i = 0, len = this.items.length; i < len; i++) {
            this.presupuesto.push(new Array(12));
        }
    }
}
</script>
