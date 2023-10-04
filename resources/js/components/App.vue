<!-- App.vue -->
<template>
<div>
    <router-link to="/product">Sản phẩm</router-link>
    <router-link to="/category">Danh mục</router-link>

    <router-view></router-view>
</div>
</template>

<script>
export default {
    // ...
};
</script>

<style scoped>
/* CSS đặc thù cho thành phần App */
</style>
