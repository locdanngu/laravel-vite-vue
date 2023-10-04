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
        };
    },
    created() {
        this.fetchCategories();
    },
    methods: {
        fetchCategories() {
            let apiUrl = '/api/category';

            if (this.searchQuery) {
                apiUrl += `?search=${this.searchQuery}`;
            }

            axios.get(apiUrl).then((response) => {
                this.categories = response.data;
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
            this.fetchCategories();
        },
    },
};
</script>

<style>
/* Kiểu dáng bảng */

</style>
