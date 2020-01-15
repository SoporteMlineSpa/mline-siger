<template>
    <div class="row my-1 border-bottom">
        <div class="col-md">
            <input type="hidden" name="empresas[]" :value="empresaId">
            <p class="text-right">
                <slot></slot>
            </p>
        </div>
        <div class="col-md text-center">
            {{ costo }}
        </div>
        <div class="col-md">
            <input type="text" class="form-control" @input="calcularNeto" v-model="ganancia">
        </div>
        <div class="col-md text-center">
            {{ neto }}
        </div>
        <div class="col-md">
            <input type="text" class="form-control" @input="calcularPorcentaje" name="precios[]" v-model="venta">
        </div>
    </div>
</template>
<script>
export default {
    props: ['empresaId', 'precioCosto', 'ventaActual'],
    data() {
        return {
            costo: 0,
            ganancia: 0,
            neto: 0,
            venta: 0
        }
    },
    methods: {
        calcularNeto: function() {
            this.neto = (parseFloat(this.costo) + parseFloat(this.costo * parseFloat(this.ganancia / 100))).toFixed(0);
            this.calcularVenta();
        },
        calcularVenta: function() {
            this.venta = (parseFloat(this.neto) * 1.19).toFixed(0);
        },
        calcularPorcentaje: function() {
            this.ganancia = (parseFloat(100 * this.venta) - parseFloat(119 * this.costo)) / parseFloat(1.19 * this.costo).toFixed(0);
            this.calcularNeto();
        }
    },
    created(){
        this.costo = this.precioCosto;
        this.venta = this.ventaActual;
    }
}
</script>
