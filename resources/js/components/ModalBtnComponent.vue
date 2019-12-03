<template>
  <button class="btn btn-primary" @click="showModal">
    <slot></slot>
  </button>
</template>
<script>
import Swal from 'sweetalert2'

export default {
  props: ['title', 'message'] ,
  computed: {
    htmlMessage: function () {
      var msg;
      for (var i = 0; i < this.message.length; i++) {
        if (this.message[i].type === "Object") {
          var keys = this.message[i].keys;
          keys.forEach(element => {
            msg += '<b>' + element + '</b>: ' + this.message[i].data[element] + '<br />'
          })
            
        }
        if (this.message[i].type === "Array") {
          var keys = this.message[i].keys
          var data = this.message[i].data
          console.log(data)
          msg += '<table id="datatable" class="table"><thead><tr>'
            
          keys.forEach(key => {
            msg += '<th [scope="col"]>' + (key === 'pivot' ? this.message[i].pivot : key) + '</th>'
            
          })
          msg += '</tr></thead>'
            
          msg += '<tbody>'
            
          data.forEach(element => {
            msg += '<tr>'
            
            keys.forEach(key => {
              msg += '<td>' + (key === 'pivot' ? element.pivot[this.message[i].pivot] : element[key]) + '</td>'
            
            })
            msg += '</tr>'
            
          })
          msg += '</tr></tbody></table>'
        }
      }
      return msg.replace('undefined', '')
    }
  },
  mounted() {
  },
  methods: {
    showModal: function() {
      Swal.fire({
        title: this.title,
        html: this.htmlMessage
      })
    }
  }
}
</script>
<style></style>
