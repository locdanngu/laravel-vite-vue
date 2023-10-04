// route.js
import { createRouter, createWebHistory } from 'vue-router';
import Product from './Product.vue';
import Category from './Category.vue';

const routes = [
  { path: '/product', component: Product },
  { path: '/category', component: Category },
];

// const router = createRouter({
//   history: createWebHashHistory(),
//   routes,
// });

const router = createRouter({
  history: createWebHistory(),
  routes,
});


export default router;
