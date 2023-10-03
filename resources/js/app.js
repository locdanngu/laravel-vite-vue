import './bootstrap';
import { createApp } from 'vue';
import Product from './Product.vue';

const product = createApp(Product);
product.mount("#product_list");