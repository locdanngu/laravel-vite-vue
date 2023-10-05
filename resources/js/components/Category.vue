<template>
<div class="p-5">
    <div class="d-flex justify-content-between mb-2">
        <h2>Danh sách danh mục:</h2>
        <button class="btn btn-primary" data-toggle="modal" data-target="#addModal"><i class="bi bi-plus-circle"></i> Thêm danh mục</button>
        <input type="text" v-model="searchQuery" placeholder="Tìm kiếm" class="form-control w-25">
    </div>
    <div class="card-body table-responsive p-0">
        <table class="product-table table text-nowrap">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên danh mục</th>
                    <th>Hình ảnh</th>
                    <th>Số lượng sản phẩm</th>
                    <th>Ngày tạo</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="category in categories.data" :key="category.idcategory">
                    <td>{{ category.idcategory }}</td>
                    <td class="font-weight-bold">{{ category.namecategory }}</td>
                    <td><img :src="category.imagecategory" alt="" height="50"></td>
                    <td>{{ category.product_count }}</td>
                    <td>{{ formattedDate(category.created_at) }}</td>
                    <td>
                        <button class="btn btn-warning mr-3" data-toggle="modal" data-target="#changeModal" @click="openChangeModal(category)"><i class="bi bi-pencil"></i> Chỉnh sửa</button>
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

    <!-- Modal add -->
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form class="modal-content" @submit.prevent="addCategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm 1 danh mục mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Tên danh mục</span>
                        <input type="text" name="namecategory" class="form-control" v-model="namecategory" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Ảnh danh mục</span>
                        <input type="file" name="imagecategory" id="" class="form-control" accept="image/*" @change="previewImage" required>
                    </div>
                    <img v-if="previewUrl" :src="previewUrl" alt="Ảnh xem trước" height="100" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Thêm</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal change-->
    <div class="modal fade" id="changeModal" tabindex="-1" aria-labelledby="changeModalLabel" aria-hidden="true" v-if="categoryToChange">
        <div class="modal-dialog" role="document">
            <form class="modal-content" @submit.prevent="changeCategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeModalLabel">Thay đổi thông tin danh mục: <b>{{ categoryToChange.namecategory }}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Tên danh mục</span>
                        <input type="text" name="namecategorychange" v-model="this.namecategorychange" class="form-control">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Ảnh danh mục</span>
                        <input type="file" name="imagecategorychange" id="" class="form-control" accept="image/*" @change="previewImagechange">
                    </div>
                    <img v-if="previewUrlchange" :src="previewUrlchange" alt="" height="100" />
                    <img v-else :src="categoryToChange.imagecategory" alt="" height="100" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal delete -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true" v-if="categoryToDelete">
        <div class="modal-dialog" role="document">
            <form class="modal-content" @submit.prevent="deleteCategory">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa danh mục</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>
                        Bạn có chắc chắn muốn xóa danh mục: <b>{{ categoryToDelete.namecategory }}</b> này?
                    </p>
                    <p class="text-danger font-weight-bold" v-if="categoryToDelete.product_count > 0">
                        Bạn không thể xóa danh mục này vì có sản phẩm liên quan!
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger" :disabled="categoryToDelete.product_count > 0">Xóa</button>
                </div>
            </form>
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
            categoryToDelete: null,
            categoryToChange: null,
            previewUrl: null,
            namecategory: '', // Dữ liệu tên danh mục
            imagecategory: null, // Dữ liệu tệp ảnh danh mục
            previewUrlchange: null,
            namecategorychange: '', // Dữ liệu tên danh mục
            imagecategorychange: null, // Dữ liệu tệp ảnh danh mục
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
        },
        openChangeModal(category) {
            this.categoryToChange = category; // Lưu danh mục muốn xóa vào biến categoryToDelete
            this.namecategorychange = category.namecategory;
        },
        deleteCategory() {
            axios.delete('/api/category/delete?id=' + this.categoryToDelete.idcategory)
                .then(response => {
                    this.fetchCategories();
                    this.showSuccessMessage('Danh mục đã được xóa thành công.');
                })
                .catch(error => {
                    // Xử lý lỗi ở đây
                    console.error('Lỗi khi xóa danh mục:', error);
                });
        },
        showSuccessMessage(message) {
            this.$toastr.success(message, 'Thành công');
        },
        previewImagechange(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.previewUrlchange = e.target.result;
                    this.imagecategorychange = file;
                };

                reader.readAsDataURL(file);
            } else {
                this.previewUrlchange = null;
                this.imagecategorychange = null;
            }
        },
        changeCategory() {
            const formData = new FormData();
            formData.append("_method", "PATCH");
            formData.append('idcategory', this.categoryToChange.idcategory);
            formData.append('namecategory', this.namecategorychange);
            if (this.imagecategorychange) {
                formData.append('imagecategory', this.imagecategorychange);
            }

            // Gửi yêu cầu POST tới API để thêm danh mục mới
            axios.post('/api/category/change', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.fetchCategories();
                    this.showSuccessMessage('Thay đổi danh mục thành công.');
                    this.namecategorychange = '';
                    this.imagecategorychange = null;
                    this.previewUrlchange = null;
                })
                .catch(error => {
                    console.error('Lỗi khi thêm danh mục:', error);
                });
        },
        previewImage(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                    this.imagecategory = file;
                };

                reader.readAsDataURL(file);
            } else {
                this.previewUrl = null;
                this.imagecategory = null;
            }
        },
        addCategory() {
            const formData = new FormData();
            formData.append('namecategory', this.namecategory);
            if (this.imagecategory) {
                formData.append('imagecategory', this.imagecategory);
            }

            axios.post('/api/category/add', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.fetchCategories();
                    this.showSuccessMessage('Thêm danh mục thành công.');
                    this.namecategory = '';
                    this.imagecategory = null;
                    this.previewUrl = null;
                })
                .catch(error => {
                    console.error('Lỗi khi thêm danh mục:', error);
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
