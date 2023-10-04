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
                    <th rowspan="3">Hình ảnh</th>
                    <th rowspan="3">Giá cũ</th>
                    <th rowspan="3">Giá mới</th>
                    <th colspan="3">Danh mục</th>
                    <th colspan="3">Loại hàng</th>
                </tr>
                <tr>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Danh mục</th>
                    <th rowspan="2">Ngày tạo</th>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Loại</th>
                    <th rowspan="2">Ngày tạo</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.idproduct">
                    <td>{{ product.idproduct }}</td>
                    <td class="font-weight-bold">{{ product.nameproduct }}</td>
                    <td><img :src="product.imageproduct" alt="" height="50"></td>
                    <td>{{ product.oldprice }}</td>
                    <td>{{ product.price }}</td>
                    <td>{{ product.namecategory.idcategory }}</td>
                    <td>{{ product.namecategory.namecategory }}</td>
                    <td>{{ formattedDate(product.namecategory.created_at) }}</td>
                    <td>{{ product.nametype.idtype }}</td>
                    <td>{{ product.nametype.nametype }}</td>
                    <td>{{ formattedDate(product.nametype.created_at) }}</td>

                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination w-100 d-flex justify-content-center align-items-center mt-5">
        <button class="btn btn-primary" @click="previousPage" :disabled="currentPage === 1">Lùi</button>
        <span class="ms-3 me-3">Trang {{ currentPage }} của tổng số {{ lastPage }}</span>
        <button class="btn btn-primary" @click="nextPage" :disabled="currentPage === lastPage">Tiếp</button>
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
            currentPage: 1, // Trang hiện tại
            lastPage: 1,
            previousSearchQuery: '',
        };
    },
    created() {
        this.fetchProducts();
    },
    methods: {
        fetchProducts() {
            const currentPage = parseInt(this.$route.query.page) || 1;
            
            let apiUrl = '/api/product';

            if (this.searchQuery) {
                if(this.searchQuery !== this.previousSearchQuery){
                    this.currentPage = 1;
                }
                apiUrl += `?search=${this.searchQuery}&page=${this.currentPage}`;
            }else{
                apiUrl += `?page=${this.currentPage}`;
            }
            
            axios.get(apiUrl).then((response) => {
                this.products = response.data.data;
                this.lastPage = response.data.last_page;
                this.previousSearchQuery = this.searchQuery;
                this.$router.replace({ query: { page: this.currentPage } });
            });
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchProducts();
            }
        },
        nextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.fetchProducts();
            }
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

</style>
