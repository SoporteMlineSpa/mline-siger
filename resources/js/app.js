/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap')
import * as Tabs from 'vue-slim-tabs'
import Swal from 'sweetalert2'
import Chart from 'chart.js'

var Vue = window.Vue = require('vue')

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('form-component', require('./components/FormComponent.vue').default)
Vue.component('delete-btn-component', require('./components/DeleteBtnComponent.vue').default)
Vue.component('modal-btn-component', require('./components/ModalBtnComponent.vue').default)
Vue.component('dropdown-component', require('./components/DropdownComponent.vue').default)
Vue.component('bar-chart-component', require('./components/BarChartComponent.vue').default)

Vue.use(Tabs)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
})

$(document).ready(function () {
    $('#datatable').DataTable({
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf'
        ],
        language: {
            url: '//cdn.datatables.net/plug-ins/1.10.20/i18n/Spanish.json'
        }
    })
})
