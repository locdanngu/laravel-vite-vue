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
                    <th rowspan="3" class="text-center">Hình ảnh</th>
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
            <tbody class="align-middle">
                <tr v-for="product in products" :key="product.idproduct">
                    <td>{{ product.idproduct }}</td>
                    <td>{{ product.nameproduct }}</td>
                    <td class="text-center"><img :src="product.imageproduct" alt="" height="50"></td>
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

    <div class="pagination w-100 d-flex justify-content-center align-items-center">
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
                console.log(this.previousSearchQuery);
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
