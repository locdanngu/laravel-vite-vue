<template>
    <div class="p-5">
        <div class="d-flex justify-content-between mb-2">
            <h2>Danh sách sản phẩm:</h2>
            <input type="text" v-model="searchQuery" placeholder="Tìm kiếm" class="form-control w-25">
        </div>
        <div class="card-body table-responsive p-0">
            <table class="product-table table text-nowrap">
                <thead>
                    <tr>
                        <th rowspan="3">ID</th>
                        <th rowspan="3">Tên sản phẩm</th>
                        <th rowspan="3">Giá cũ</th>
                        <th rowspan="3">Giá mới</th>
                        <th colspan="3" class="text-center">Danh mục</th>
                        <th colspan="3" class="text-center">Loại hàng</th>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center">ID</th>
                        <th rowspan="2" class="text-center">Danh mục</th>
                        <th rowspan="2" class="text-center">Ngày tạo</th>
                        <th rowspan="2" class="text-center">ID</th>
                        <th rowspan="2" class="text-center">Loại</th>
                        <th rowspan="2" class="text-center">Ngày tạo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products.data" :key="product.idproduct">
                        <td>{{ product.idproduct }}</td>
                        <td>{{ product.nameproduct }}</td>
                        <td>{{ product.oldprice }}</td>
                        <td>{{ product.price }}</td>
                        <td class="text-center">{{ product.namecategory.idcategory }}</td>
                        <td class="text-center">{{ product.namecategory.namecategory }}</td>
                        <td class="text-center">{{ formattedDate(product.namecategory.created_at) }}</td>
                        <td class="text-center">{{ product.nametype.idtype }}</td>
                        <td class="text-center">{{ product.nametype.nametype }}</td>
                        <td class="text-center">{{ formattedDate(product.nametype.created_at) }}</td>

                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';

export default {
    data() {
        return {
            products: [],
            searchQuery: '',
        };
    },
    created() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            let apiUrl = '/api/product';

            if (this.searchQuery) {
                apiUrl += `?search=${this.searchQuery}`;
            }

            axios.get(apiUrl).then((response) => {
                this.products = response.data;
            });
        },
    },
    computed: {
        formattedDate() {
            return (date) => moment(date).format('DD/MM/YYYY');
        },
    },
    watch: {
        searchQuery: function (newQuery, oldQuery) {
            this.fetchProducts();
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
</style>
