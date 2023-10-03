import './bootstrap';
import { createApp } from 'vue';
import Product from './Product.vue';

const app = createApp(Product);
app.mount("#app");