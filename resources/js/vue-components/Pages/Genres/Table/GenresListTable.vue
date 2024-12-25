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
                        <th style="width: 600px" @click="sortOrder('name')">
                            Tên thể loại
                            <div class="float-right" v-if="sortKey.key == 'name'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 20px" class='text-center'>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="genresList.length == 0">          
                        <td colspan="7" class='text-center'>Không có dữ liệu</td>
                    </tr>
                    <tr v-for="(genres, index) in genresList">
                        <td>{{ genres.id }}</td>
                        <td class="text-wrap">{{ genres.name }}</td>
                        <td class="text-center">
                            <a href="#" data-target="#edit-view-genres-modal" data-toggle="modal" @click="selectGenres(genres, false)" class="btn btn-success"
                                ><i class="fas fa-pen"></i
                            ></a>
                            <!-- <a href="#" class="btn btn-primary ml-2" data-toggle="modal" @click="selectGenres(genres, false)" data-target="#edit-view-genres-modal"
                                ><i class="fas fa-eye"></i
                            ></a> -->
                            <a href="#" data-toggle="modal" data-target="#confirm-delete-modal" @click="selectGenres(genres, false)" class="btn btn-danger ml-2"
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
    <confirm-delete-modal :genres="currentGenres" @loadItems="loadItems"></confirm-delete-modal>
    <edit-view-genres-modal :genres="currentGenres" :isEdit="isEditGenres"  @loadItems="loadItems"></edit-view-genres-modal>
    <add-genres-modal @loadItems="loadItems"></add-genres-modal>
</template>
<script>
import axios from "axios";
import Preloader from "../../../Preloader.vue";
import ConfirmDeleteModal from "../Modals/ConfirmDeleteModal.vue";
import EditViewGenresModal from "../Modals/EditViewGenresModal.vue";
import AddGenresModal from "../Modals/AddGenresModal.vue";
import _ from 'lodash';
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            genresList: [],
            loading: true,
            searchKey: 'name',
            searchValue: '',
            currentGenres: {},
            sortKey: {
                key: '',
                type: ''
            },
            isEditGenres: false,
        };
    },
    components: {
        Preloader,
        ConfirmDeleteModal,
        EditViewGenresModal,
        AddGenresModal
    },
    methods: {
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
        async loadItems() {
            this.loading = true;
            const response = await axios.get(`/genres/api/get-genres?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage}&searchValue=${this.searchValue}&sortBy=${this.sortKey.key}&orderBy=${this.sortKey.type}`);
            this.genresList = response.data.data;
            this.totalRows = response.data.total;
            // console.log(this.genresList, 'res');

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
        selectGenres(genres, isEdit) {
            this.currentGenres = genres;
            this.isEditGenres = isEdit;
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
