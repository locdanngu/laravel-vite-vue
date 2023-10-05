<!-- App.vue -->
<template>
<div>
    <router-link to="/product">Sản phẩm</router-link>
    <router-link to="/category">Danh mục</router-link>

    <router-view></router-view>
</div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import $ from 'jquery';

// Thiết lập các thư viện và biến toàn cục ở đây
window.axios = axios;
window.moment = moment;
window.$ = $;


export default {
    created() {
        // Lắng nghe sự kiện route thay đổi
        this.$router.afterEach((to) => {
            // Lấy meta title từ route hiện tại
            const pageTitle = to.meta.title;

            // Cập nhật title của trang HTML
            document.title = pageTitle;
        });
    },
};
</script>

<style scoped>
/* CSS đặc thù cho thành phần App */
</style>
