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
                    <th>ID</th>
                    <th>Tên sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá cũ</th>
                    <th>Giá mới</th>
                    <th>Danh mục</th>
                    <th>Loại hàng</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in products" :key="product.idproduct">
                    <td>{{ product.idproduct }}</td>
                    <td class="font-weight-bold">{{ product.nameproduct }}</td>
                    <td><img :src="product.imageproduct" alt="" height="50"></td>
                    <td>{{ product.oldprice }}</td>
                    <td class="font-weight-bold text-danger">{{ product.price }}</td>
                    <td>
                        <router-link :to="`/category?search=${product.namecategory.namecategory}`">
                            {{ product.namecategory.namecategory }}
                        </router-link>
                    </td>
                    <td>{{ product.nametype.nametype }}</td>
                    <td>
                        <button class="btn btn-warning mr-3" data-toggle="modal" data-target="#changeModal" @click="openChangeModal(product)"><i class="bi bi-pencil"></i> Chỉnh sửa</button>
                        <button class="btn btn-danger" data-toggle="modal" data-target="#deleteModal" @click="openDeleteModal(product)"><i class="bi bi-trash"></i> Xóa</button>
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

    <div class="modal fade " id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" @submit.prevent="addProduct">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Thêm 1 sản phẩm mới</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Tên sản phẩm</span>
                        <input type="text" name="nameproduct" class="form-control" v-model="nameproduct" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Giá cũ</span>
                        <input type="number" name="oldpirce" class="form-control" v-model="oldprice" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Giá mới</span>
                        <input type="number" name="pirce" class="form-control" v-model="price" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Mô tả sản phẩm</span>
                        <textarea name="detail" class="form-control" v-model="detail" rows="5" required></textarea>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Danh mục</span>
                        <select name="idcategory" v-model="idcategory">
                            <option value="" disabled selected>Chọn danh mục</option>
                            <option :value="category.idcategory" v-for="category in categories.data" :key="category.idcategory">{{ category.namecategory }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Loại hàng</span>
                        <select name="idtype" v-model="idtype">
                            <option value="" disabled selected>Chọn loại hàng</option>
                            <option :value="types.idtype" v-for="types in types.data" :key="types.idtype">{{ types.nametype }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Ảnh sản phẩm</span>
                        <input type="file" name="imageproduct" id="" class="form-control" accept="image/*" @change="previewImage" required>
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

    <div class="modal fade " id="changeModal" tabindex="-1" aria-labelledby="changeModalLabel" aria-hidden="true" v-if="productToChange">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" @submit.prevent="changeProduct">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeModalLabel">Chỉnh sửa thông tin sản phẩm: <b>{{ productToChange.nameproduct }}</b></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body d-flex flex-column align-items-center">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Tên sản phẩm</span>
                        <input type="text" name="nameproduct" class="form-control" v-model="this.nameproductchange" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Giá cũ</span>
                        <input type="number" name="oldpirce" class="form-control" v-model="this.oldpricechange" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Giá mới</span>
                        <input type="number" name="pirce" class="form-control" v-model="this.pricechange" required>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Mô tả sản phẩm</span>
                        <textarea name="detail" class="form-control" v-model="this.detailchange" rows="5" required></textarea>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Danh mục</span>
                        <select name="idcategory" v-model="this.idcategorychange">
                            <option value="" disabled selected>Chọn danh mục</option>
                            <option :value="category.idcategory" v-for="category in categories.data" :key="category.idcategory">{{ category.namecategory }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Loại hàng</span>
                        <select name="idtype" v-model="this.idtypechange">
                            <option value="" disabled selected>Chọn loại hàng</option>
                            <option :value="types.idtype" v-for="types in types.data" :key="types.idtype">{{ types.nametype }}</option>
                        </select>
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Ảnh sản phẩm</span>
                        <input type="file" name="imageproduct" id="" class="form-control" accept="image/*" @change="previewImagechange" required>
                    </div>
                    <img v-if="previewUrlchange" :src="previewUrlchange" alt="" height="100" />
                    <img v-else :src="productToChange.imageproduct" alt="" height="100" />
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Lưu</button>
                </div>
            </form>
        </div>
    </div>



</div>
</template>

<script>
export default {
    data() {
        return {
            products: [],
            categories: [],
            types: [],
            searchQuery: '',
            currentPage: 1, // Trang hiện tại
            lastPage: 1,
            previousSearchQuery: '',
            previewUrl: null,
            nameproduct: '', // Dữ liệu tên danh mục
            oldprice: '',
            price: '',
            detail: '',
            idcategory: 1,
            idtype: 1,
            imageproduct: null, // Dữ liệu tệp ảnh danh mục
            previewUrlchange: null,
            nameproductchange: '', // Dữ liệu tên danh mục
            oldpricechange: '',
            pricechange: '',
            detailchange: '',
            idcategorychange: 1,
            idtypechange: 1,
            imageproductchange: null,
            productToChange: null,
        };
    },
    created() {
        this.fetchProducts();
        this.fetchCategories();
        this.fetchTypes();
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
        fetchCategories() {
            let apiUrl = '/api/category';
            axios.get(apiUrl).then((response) => {
                this.categories = response.data;
            });
        },
        fetchTypes() {
            let apiUrl = '/api/type';
            axios.get(apiUrl).then((response) => {
                this.types = response.data;
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
        previewImage(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.previewUrl = e.target.result;
                    this.imageproduct = file;
                };

                reader.readAsDataURL(file);
            } else {
                this.previewUrl = null;
                this.imageproduct = null;
            }
        },
        addProduct() {
            const formData = new FormData();
            formData.append('nameproduct', this.nameproduct);
            formData.append('oldprice', this.oldprice);
            formData.append('price', this.price);
            formData.append('detail', this.detail);
            formData.append('idcategory', this.idcategory);
            formData.append('idtype', this.idtype);
            if (this.imageproduct) {
                formData.append('imageproduct', this.imageproduct);
            }

            axios.post('/api/product/add', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                .then(response => {
                    this.fetchCategories();
                    this.showSuccessMessage('Thêm sản phẩm thành công.');
                    this.nameproduct = '';
                    this.imageproduct = null;
                    this.previewUrl = null;
                    $('#addModal').modal('hide');
                })
                .catch(error => {
                    console.error('Lỗi khi thêm sản phẩm:', error);
                });
        },
        showSuccessMessage(message) {
            this.$toastr.success(message, 'Thành công');
        },
        openChangeModal(product) {
            this.productToChange = product; // Lưu danh mục muốn xóa vào biến categoryToDelete
            this.nameproductchange = product.nameproduct;
            this.oldpricechange = product.oldprice;
            this.pricechange = product.price;
            this.detailchange = product.detail;
            this.idcategorychange = product.namecategory.idcategory;
            this.idtypechange = product.nametype.idtype;
        },
        previewImagechange(event) {
            const file = event.target.files[0];

            if (file && file.type.startsWith('image/')) {
                const reader = new FileReader();

                reader.onload = (e) => {
                    this.previewUrlchange = e.target.result;
                    this.imageproductchange = file;
                };

                reader.readAsDataURL(file);
            } else {
                this.previewUrlchange = null;
                this.imageproductchange = null;
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
