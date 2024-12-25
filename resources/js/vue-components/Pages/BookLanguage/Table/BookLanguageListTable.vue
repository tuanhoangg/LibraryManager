<template lang="">
    <div class="card">
        <div class="form-group m-0">
        <div class="float-right my-3 mx-3 row">
            <div class="col-sm-6">
             </div>
            <div class="input-group">
                <input
                    name="search-text"
                    placeholder="Tìm kiếm"
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
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 600px" @click="sortOrder('language_code')">
                            Mã ngôn ngữ
                            <div class="float-right" v-if="sortKey.key == 'language_code'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 600px" @click="sortOrder('language_name')">
                            Tên ngôn ngữ
                            <div class="float-right" v-if="sortKey.key == 'language_name'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 20px" class='text-center'>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="bookLanguageList.length == 0">          
                        <td colspan="7" class='text-center'>Không có dữ liệu</td>
                    </tr>
                    <tr v-for="(bookLanguage, index) in bookLanguageList">
                        <td>{{ bookLanguage.id }}</td>
                        <td class="text-wrap">{{ bookLanguage.language_code }}</td>
                        <td class="text-wrap">{{ bookLanguage.language_name }}</td>
                        <td class="text-center">
                            <a href="#" data-target="#edit-view-book-language-modal" data-toggle="modal" @click="selectBookLanguage(bookLanguage, false)" class="btn btn-success"
                                ><i class="fas fa-pen"></i
                            ></a>

                            <a href="#" data-toggle="modal" data-target="#confirm-delete-modal" @click="selectBookLanguage(bookLanguage, false)" class="btn btn-danger ml-2"
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
    <preloader :loading="loading"></preloader>
    <confirm-delete-modal :bookLanguage="currentBookLanguage" @loadItems="loadItems"></confirm-delete-modal>
    <edit-view-book-language-modal :bookLanguage="currentBookLanguage" :isEdit="isEditBookLanguage"  @loadItems="loadItems"></edit-view-book-language-modal>
    <add-book-language-modal @loadItems="loadItems"></add-book-language-modal>
</template>
<script>
import axios from "axios";
import Preloader from "../../../Preloader.vue";
import ConfirmDeleteModal from "../Modals/ConfirmDeleteModal.vue";
import EditViewBookLanguageModal from "../Modals/EditViewBookLanguageModal.vue";
import AddBookLanguageModal from "../Modals/AddBookLanguageModal.vue";
import _ from 'lodash';
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            bookLanguageList: [],
            loading: true,
            searchKey: 'name',
            searchValue: '',
            currentBookLanguage: {},
            sortKey: {
                key: '',
                type: ''
            },
            isEditBookLanguage: false,
        };
    },
    components: {
        Preloader,
        ConfirmDeleteModal,
        EditViewBookLanguageModal,
        AddBookLanguageModal
    },
    methods: {
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
        async loadItems() {
            this.loading = true;
            const response = await axios.get(`/book-language/api/get-book-language?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage}&searchValue=${this.searchValue}&sortBy=${this.sortKey.key}&orderBy=${this.sortKey.type}`);
            this.bookLanguageList = response.data.data;
            this.totalRows = response.data.total;
            // console.log(this.bookLanguageList, 'res');

            this.loading = false;
        },

        sortOrder(name) {

            if (!this.sortKey.type || this.sortKey.key != name) {
                this.sortKey.type = 'ASC';
            } else if (this.sortKey.type == 'ASC') {
                this.sortKey.type = 'DESC';
            } else {
                this.sortKey.type = 'ASC';
            }
            this.sortKey.key = name;

        },
        selectBookLanguage(bookLanguage, isEdit) {
            this.currentBookLanguage = bookLanguage;
            this.isEditBookLanguage = isEdit;
        }
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
