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
    computed: {
        data: function() {
            let self = this;
            let data = this.presupuesto.map((x, index) => {
                return {'centro': self.items[index], 'presupuesto': x};
            });

            return data;
        }
    },
    methods: {
        getFullYear: function() {
            let date = new Date();
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
                this.presupuesto[centroId][i] = Math.round(subtotal);
            }
        },
        totalPorMes: function(mesId, evt) {
            let total = evt.target.value;
            let subtotal = total / this.items.length;
            for (var i = 0; i < this.items.length; i++) {
                this.presupuesto[i][mesId] = Math.round(subtotal);
            }
        },
        calcularTotales: function(centroId, mesId) {
            let totalCentro = this.presupuesto[centroId].filter((item) => !(item === undefined)).reduce((carry, x) => parseInt(carry) + parseInt(x));
            let totalMes = this.presupuesto.filter((item) => !(item[mesId] === undefined)).reduce((carry, x) => parseInt(carry) + parseInt(x[mesId]));

            this.totalCentro[centroId] = Math.round(totalCentro);
            this.totalMes[mesId] = Math.round(totalMes);
        },
        submit: function() {
            let self = this;
            Swal.fire({
                title: 'Guardando Presupuesto',
                text: '¿Deseas guardar este Presupuesto?',
                showCancelButton: true,
                confirmButtonText: 'Guardar',
                cancelButtonText: 'Cancelar',
                showLoaderOnConfirm: true,
                preConfirm: () => {
                    return axios
                        .post(self.action, {
                            input: self.data,
                            year: self.year
                        })
                        .then(response => {
                            console.log({response})
                            if (!response.ok) {
                                throw new Error(response.statusText)
                            }
                            return response.data
                        })
                        .catch(error => {
                            Swal.showValidationMessage(
                                `Error Guardando Presupuesto: ${error}`
                            )
                        })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                if (result.meta) {
                    Swal.fire({
                        title: result.meta.title,
                        html: result.meta.msg
                    })
                }
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
