import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import Product from './Product.vue';


const product = createApp(Product);
product.mount("#product_list");

