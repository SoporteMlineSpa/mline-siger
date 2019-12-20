<template>
    <div>
        <button v-if="button" class="btn btn-primary" @click="showModal">
            <slot></slot>
        </button>
        <a v-if="!button" href="#" @click="showModal">
            <slot></slot>
        </a>
    </div>
</template>
<script>
import Swal from 'sweetalert2'

export default {
    props: {
        title: {
            type: String,
            default: ''
        },
        message: {
        },
        button: {
            type: Boolean,
            default: true
        }
    },
    computed: {
        htmlMessage: function () {
            var msg;
            for (var i = 0; i < this.message.length; i++) {
                if (this.message[i].type === "Object") {
                    var keys = this.message[i].keys;
                    keys.forEach(element => {
                        msg += '<b>' + (element.charAt(0).toUpperCase() + element.slice(1)) + '</b>: ' + this.message[i].data[element] + '<br />'
                    })
                    msg += '<br />'

                }
                if (this.message[i].type === "Array") {
                    var keys = this.message[i].keys
                    var data = this.message[i].data
                    msg += '<div class="container table-responsive">'
                    msg += '<table id="datatable" class="table table-sm text-xs"><thead><tr>'

                    keys.forEach(key => {
                        msg += '<th [scope="col"]>' + (key === 'pivot' ? ((this.message[i].pivot).charAt(0).toUpperCase() + (this.message[i].pivot).slice(1)) : (key.charAt(0).toUpperCase() + key.slice(1))) + '</th>'

                    })
                    msg += '</tr></thead>'

                    msg += '<tbody>'

                    data.forEach(element => {
                        msg += '<tr>'

                        keys.forEach(key => {
                            msg += '<td class="text-left">' + (key === 'pivot' ? element.pivot[this.message[i].pivot] : element[key]) + '</td>'

                        })
                        msg += '</tr>'

                    })
                    msg += '</tr></tbody></table>'
                    msg += '</div>'
                }
            }
            return msg.replace('undefined', '')
        }
    },
    methods: {
        showModal: function() {
            Swal.fire({
                title: this.title,
                html: this.htmlMessage,
            })
        }
    }
}
</script>
<style></style>
