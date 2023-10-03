import './bootstrap';
import { createApp } from 'vue';
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import Product from './Product.vue';
import Category from './Category.vue';

const product = createApp(Product);
product.mount("#product_list");

const category = createApp(Category);
category.mount("#category_list");

