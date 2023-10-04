import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.css';
import 'bootstrap-vue/dist/bootstrap-vue.css';
import 'bootstrap-icons/font/bootstrap-icons.css';
import 'bootstrap/dist/js/bootstrap.bundle';
import App from './components/App.vue'; // Tên tệp gốc của ứng dụng Vue.js
import router from './route.js';

const app = createApp(App);
app.use(router); // Kết nối Vue Router với ứng dụng Vue
app.mount('#app');
