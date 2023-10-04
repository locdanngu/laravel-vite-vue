// route.js
import { createRouter, createWebHistory } from 'vue-router';
import Product from './components/Product.vue';
import Category from './components/Category.vue';
import Homepage from './components/Homepage.vue';

const routes = [
  { path: '/', component: Homepage , meta: { title: 'Trang chủ' }, },
  { path: '/product', component: Product , meta: { title: 'Danh sách sản phẩm' }, },
  { path: '/category', component: Category , meta: { title: 'Danh sách danh mục' }, },
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
