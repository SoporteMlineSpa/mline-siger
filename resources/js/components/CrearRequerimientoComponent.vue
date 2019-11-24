<template>
  <div class="row">
    <div class="col-2">
      <div class="card">
        <div class="card-body">
          <div class="form-group">
            <select v-for="(empresa, index) in abastecimientos" :key="index" id="" name="" class="form-control">
              <option v-for="punto in empresa.punto" :key="punto.id">{{punto.nombre}}</option>
            </select>
          </div>
          <button class="mx-auto btn btn-success">Enviar</button>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <div class="card-body table-responsive">
       </div>
      </div>
    </div>
  </div>
</template>
<script>
export default {
  props: {
    productos: Array,
    abastecimientos: Array
  }
}
</script>
<style></style>
