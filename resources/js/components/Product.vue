<template>
<div class="p-5">
    <div class="d-flex justify-content-between mb-2">
        <h2>Danh sách sản phẩm:</h2>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal"><i class="bi bi-plus-circle"></i> Thêm sản phẩm</button>
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
                    <th colspan="2">Danh mục</th>
                    <th colspan="2">Loại hàng</th>
                </tr>
                <tr>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Danh mục</th>
                    <th rowspan="2">ID</th>
                    <th rowspan="2">Loại</th>
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
                    <td>{{ product.nametype.idtype }}</td>
                    <td>{{ product.nametype.nametype }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination w-100 d-flex justify-content-center align-items-center mt-5">
        <button class="btn btn-primary" @click="previousPage" :disabled="currentPage === 1">Lùi</button>
        <span class="ml-3 mr-3">Trang {{ currentPage }} của tổng số {{ lastPage }}</span>
        <button class="btn btn-primary" @click="nextPage" :disabled="currentPage === lastPage">Tiếp</button>
    </div>

    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm 1 sản phẩm mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Tên sản phẩm</span>
                        <input type="text" name="namecategory" class="form-control" v-model="namecategory">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Ảnh danh mục</span>
                        <input type="file" name="imagecategory" id="" class="form-control" accept="image/*" @change="previewImage">
                    </div>
                    <img v-if="previewUrl" :src="previewUrl" alt="Ảnh xem trước" height="100" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" data-dismiss="modal" @click="addCategory">Thêm</button>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script>
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
                if (this.searchQuery !== this.previousSearchQuery) {
                    this.currentPage = 1;
                }
                apiUrl += `?search=${this.searchQuery}&page=${this.currentPage}`;
            } else {
                apiUrl += `?page=${this.currentPage}`;
            }

            axios.get(apiUrl).then((response) => {
                this.products = response.data.data;
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
