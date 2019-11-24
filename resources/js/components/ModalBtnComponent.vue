<template>
  <button class="btn btn-primary" @click="showModal">
    <slot></slot>
  </button>
</template>
<script>
import Swal from 'sweetalert2'

export default {
  props: {
    title: String,
    message: String
  },
  methods: {
    showModal: function() {
      Swal.fire({
        title: this.title,
        html: this.message
      })
    }
  }
}
</script>
<style></style>
