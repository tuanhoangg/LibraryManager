<template lang="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Quản lý sách</h1>
                </div>
                <!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <div class="btn-group dropleft">
                            <button
                                type="button"
                                class="btn btn-primary dropdown-toggle"
                                data-toggle="dropdown"
                                aria-expanded="false"
                            >
                                Thao tác
                            </button>
                            <div class="dropdown-menu">
                                <a
                                    href="/books/create"
                                    class="dropdown-item"
                                >
                                    Thêm sách
                                </a>
                                <a
                                    class="dropdown-item"
                                    @click="exportExcelBook"
                                    >Xuất excel thống kê</a
                                >

                                <a
                                    class="dropdown-item"
                                    data-toggle="modal"
                                    data-target="#import-book-modal"
                                    >Import excel</a
                                >
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </div>
    <div class="card">
        <div class="form-group m-0">
            <div class="float-right my-3 mx-3 row">
                <div class="input-group">
                    <input
                        name="search-text"
                        placeholder="Tìm kiếm tên sách"
                        type="search"
                        class="form-control"
                        v-model="searchValue"
                    />
                    <div class="input-group-append">
                        <span class="input-group-text"
                            ><i class="fa fa-search"></i
                        ></span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body" style="height: fit-content">
            <table class="table text-nowrap table-bordered">
                <thead>
                    <tr>
                        <th style="width: 70px" @click="sortOrder('id')">
                            #
                            <div class="float-right" v-if="sortKey.key == 'id'">
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th>Ảnh</th>
                        <th style="width: 300px" @click="sortOrder('isbn_code')">
                            Mã ISBN
                            <div
                                class="float-right"
                                v-if="sortKey.key == 'isbn_code'"
                            >
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th @click="sortOrder('title')">
                            Tên sách
                            <div
                                class="float-right"
                                v-if="sortKey.key == 'title'"
                            >
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th>Tác giả</th>
                        <th style="width: 20px" class="text-center">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="bookList.length == 0">
                        <td colspan="7" class="text-center">
                            Không có dữ liệu
                        </td>
                    </tr>
                    <tr v-for="(book, index) in bookList">
                        <td>{{ book.id }}</td>
                        <td>
                            <img
                                :src="book.image"
                                style="width: 80px; height: 105px"
                            />
                        </td>
                        <td class="text-wrap">{{ book.isbn_code }}</td>
                        <td class="text-wrap">{{ book.title }}</td>
                        <td class="text-wrap">{{ book.author?.name }}</td>
                        <td class="text-center">
                            <a
                                :href="'/books/' + book.id + '/edit'"
                                class="btn btn-success"
                                ><i class="fas fa-pen"></i
                            ></a>
                            <a
                                href="#"
                                data-toggle="modal"
                                data-target="#confirm-delete-modal"
                                @click="selectBook(book, false)"
                                class="btn btn-danger ml-2"
                                ><i class="fas fa-trash"></i
                            ></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- /.card-body -->
        <div class="card-footer clearfix float-right">
            <b-pagination
                class="float-right"
                v-model="currentPage"
                :total-rows="totalRows"
                :per-page="perPage"
            ></b-pagination>
        </div>
    </div>
    <import-book-modal @loading="(status) => {loading = status}" 
        @loadItems="loadItems">
    </import-book-modal>
    <preloader :loading="loading"></preloader>
    <confirm-delete-modal
        :book="currentBook"
        @loadItems="loadItems"
    ></confirm-delete-modal>
</template>
<script>
import axios from "axios";
import Preloader from "../../../Preloader.vue";
import ConfirmDeleteModal from "../Modal/ConfirmDeleteModal.vue";
import ImportBookModal from "../Modal/ImportBookModal.vue"
import _ from "lodash";
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            bookList: [],
            loading: true,
            searchKey: "name",
            searchValue: "",
            currentBook: {},
            sortKey: {
                key: "",
                type: "",
            },
            isEditAuthor: false,
            file: null,
            successMessage: null,
        };
    },
    components: {
        Preloader,
        ConfirmDeleteModal,
        ImportBookModal,
        // EditViewAuthorModal,
        // AddAuthorModal
    },
    methods: {
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
        async loadItems() {
            this.loading = true;
            const response = await axios.get(
                `/books/api/get-books?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage
                }&searchValue=${this.searchValue}&sortBy=${this.sortKey.key
                }&orderBy=${this.sortKey.type}`
            );
            console.log(response, "res");
            this.bookList = response.data.data;
            this.totalRows = response.data.total;

            this.loading = false;
        },
        async exportExcelBook() {
            this.loading = true;
            const response = await axios.get('/books/export', {
                responseType: 'blob'
            });
            const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'Thống kê sách.xlsx';

            document.body.appendChild(link);
            link.click();


            console.log(response, 'response');
            this.loading = false;
        },
        sortOrder(name) {
            if (!this.sortKey.type || this.sortKey.key != name) {
                this.sortKey.type = "ASC";
            } else if (this.sortKey.type == "ASC") {
                this.sortKey.type = "DESC";
            } else {
                this.sortKey.type = "ASC";
            }
            this.sortKey.key = name;
        },
        selectBook(book, isEdit) {
            console.log(book, "book");
            this.currentBook = book;
            this.isEditAuthor = isEdit;
        },
    },
    watch: {
        currentPage() {
            this.loadItems();
        },
        searchValue: {
            handler() {
                this.currentPage = 1;
                return this.debounceLoadItems();
            },
            deep: true,
        },
        sortKey: {
            handler() {
                this.currentPage = 1;
                return this.loadItems();
            },
            deep: true,
        },
    },
    created() {
        this.loadItems();
    },
};
</script>
<style lang=""></style>
