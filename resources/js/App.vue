<!-- <template>
Hello, World!
</template> -->

<template>
    <div>
        <h1>Hello, World!</h1>
        <h2>Danh sách sản phẩm:</h2>
        <ul>
            <li v-for="product in products" :key="product.id">{{ product.name }}</li>
        </ul>
    </div>
</template>

  
<script>
import axios from 'axios';

export default {
    data() {
        return {
            products: [],
        };
    },
    created() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            axios.get('/api/product').then((response) => {
                this.products = response.data.products;
            });
        },
    },
};
</script>

  
<style>
/* Thêm kiểu CSS của bạn tại đây */
</style>
