// route.js
import { createRouter, createWebHashHistory } from 'vue-router';
import Product from './Product.vue';
import Category from './Category.vue';

const routes = [
  { path: '/product', component: Product },
  { path: '/category', component: Category },
];

const router = createRouter({
  history: createWebHashHistory(),
  routes,
});

export default router;
