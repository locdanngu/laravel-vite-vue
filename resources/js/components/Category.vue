<template>
<div class="p-5">
    <div class="d-flex justify-content-between mb-2">
        <h2>Danh sách danh mục:</h2>
        <input type="text" v-model="searchQuery" placeholder="Tìm kiếm" class="form-control w-25">
    </div>
    <div class="card-body table-responsive p-0">
        <table class="product-table table text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th class="text-center">Hình ảnh</th>
                    <th class="text-center">Số lượng sản phẩm</th>
                    <th>Ngày tạo</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                <tr v-for="category in categories.data" :key="category.idcategory">
                    <td>{{ category.idcategory }}</td>
                    <td class="fw-bold">{{ category.namecategory }}</td>
                    <td class="text-center"><img :src="category.imagecategory" alt="" height="50"></td>
                    <td class="text-center">{{ category.product_count }}</td>
                    <td>{{ formattedDate(category.created_at) }}</td>
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
            categories: [],
            searchQuery: '',
            currentPage: 1, // Trang hiện tại
            lastPage: 1,
            previousSearchQuery: '',
        };
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            const currentPage = parseInt(this.$route.query.page) || 1;

            let apiUrl = '/api/category';

            if (this.searchQuery) {
                if (this.searchQuery !== this.previousSearchQuery) {
                    this.currentPage = 1;
                }
                apiUrl += `?search=${this.searchQuery}&page=${this.currentPage}`;
            } else {
                apiUrl += `?page=${this.currentPage}`;
            }

            axios.get(apiUrl).then((response) => {
                this.categories = response.data;
                this.lastPage = response.data.last_page;
                this.previousSearchQuery = this.searchQuery;
                this.$router.replace({
                    query: {
                        page: this.currentPage
                    }
                });
            });
        },
        previousPage() {
            if (this.currentPage > 1) {
                this.currentPage--;
                this.fetchCategories();
            }
        },
        nextPage() {
            if (this.currentPage < this.lastPage) {
                this.currentPage++;
                this.fetchCategories();
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
            this.fetchCategories();
        },
    },
};
</script>

<style>
/* Kiểu dáng bảng */
</style>
