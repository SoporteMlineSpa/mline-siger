<template>
  <button class="btn btn-danger" @click="onDelete">
    <i class="fas fa-times"></i>
  </button>
</template>
<script>
import Swal from 'sweetalert2'

export default {
  props: ['action', 'csrf'],
  methods: {
    onDelete: function () {
      axios
        .delete(this.action)
        .then( (response) => {
          var response = response.data;
          Swal.fire({
            title: response.meta.title,
            html: response.meta.message,
            icon: 'success'
          }).then(response => {
            location.reload()
          })
        })
    }
  }
}
</script>
<style></style>
