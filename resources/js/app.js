import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'toastr/build/toastr.min.css';
import 'bootstrap/dist/js/bootstrap.bundle';
import App from './components/App.vue'; // Tên tệp gốc của ứng dụng Vue.js
import router from './route.js';
import Toastr from 'toastr';
import $ from 'jquery'; // Import jQuery


const app = createApp(App);
app.use(router); // Kết nối Vue Router với ứng dụng Vue
app.config.globalProperties.$toastr = Toastr;
app.config.globalProperties.$ = $;

app.mount('#app');
