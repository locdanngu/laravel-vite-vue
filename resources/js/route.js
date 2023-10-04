// route.js
import { createRouter, createWebHistory } from 'vue-router';
import Product from './components/Product.vue';
import Category from './components/Category.vue';
import Homepage from './components/Homepage.vue';

const routes = [
  { path: '/', component: Homepage },
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
