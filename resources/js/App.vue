<!-- <template>
Hello, World!
</template> -->

<template>
    <div>
        <h1>Hello, World!</h1>
        <h2>Danh sách sản phẩm:</h2>
        <table class="product-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá cũ</th>
                    <th>Giá mới</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products.data" :key="product.idproduct">
                    <td>{{ product.idproduct }}</td>
                    <td>{{ product.nameproduct }}</td>
                    <td>{{ product.oldprice }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.detail }}</td>
                </tr>
            </tbody>
        </table>
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
                this.products = response.data;
            });
        },
    },
};
</script>

  
<style>
/* Kiểu dáng bảng */
.product-table {
    width: 100%;
    border-collapse: collapse;
}

.product-table th,
.product-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
}

.product-table th {
    background-color: #f2f2f2;
}

/* Định dạng dòng chẵn và lẻ của bảng */
.product-table tr:nth-child(even) {
    background-color: #f2f2f2;
}

.product-table tr:nth-child(odd) {
    background-color: #fff;
}
</style>
