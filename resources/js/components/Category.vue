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
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody class="align-middle">
                <tr v-for="category in categories.data" :key="category.idcategory">
                    <td>{{ category.idcategory }}</td>
                    <td class="fw-bold">{{ category.namecategory }}</td>
                    <td class="text-center"><img :src="category.imagecategory" alt="" height="50"></td>
                    <td class="text-center">{{ category.product_count }}</td>
                    <td>{{ formattedDate(category.created_at) }}</td>
                    <td class="text-center">
                        <button class="btn btn-warning mr-3"><i class="bi bi-pencil"></i> Chỉnh sửa</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" @click="openDeleteModal(category)"><i class="bi bi-trash"></i> Xóa</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="pagination w-100 d-flex justify-content-center align-items-center mt-5">
        <button class="btn btn-primary" @click="previousPage" :disabled="currentPage === 1">Lùi</button>
        <span class="ml-3 mr-3">Trang {{ currentPage }} của tổng số {{ lastPage }}</span>
        <button class="btn btn-primary" @click="nextPage" :disabled="currentPage === lastPage">Tiếp</button>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" :class="{ 'show': showDeleteModal }">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa danh mục</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa danh mục này?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" @click="deleteCategory">Xóa</button>
                </div>
            </div>
        </div>
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
            showDeleteModal: false,
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
        openDeleteModal(category) {
            
            this.categoryToDelete = category; // Lưu danh mục muốn xóa vào biến categoryToDelete
            this.showDeleteModal = true; // Hiển thị modal
            console.log(this.categoryToDelete.idcategory);
        },
        closeDeleteModal() {
            this.showDeleteModal = false; // Đóng modal
        },
        deleteCategory() {
            // Thực hiện xóa danh mục ở đây
            // Sau khi xóa xong, đóng modal:
            this.showDeleteModal = false;
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
