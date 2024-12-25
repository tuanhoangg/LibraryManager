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
                            Tên nhà xuất bản
                            <div class="float-right" v-if="sortKey.key == 'name'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 600px" @click="sortOrder('phone')">
                            Số điện thoại
                            <div class="float-right" v-if="sortKey.key == 'phone'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 600px" @click="sortOrder('address')">
                            Địa chỉ
                            <div class="float-right" v-if="sortKey.key == 'address'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 20px" class='text-center'>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="publiserList.length == 0">          
                        <td colspan="7" class='text-center'>Không có dữ liệu</td>
                    </tr>
                    <tr v-for="(publiser, index) in publiserList">
                        <td>{{ publiser.id }}</td>
                        <td class="text-wrap">{{ publiser.name }}</td>
                        <td class="text-wrap">{{ publiser.phone }}</td>
                        <td class="text-wrap">{{ publiser.address }}</td>
                        <td class="text-center">
                            <a href="#" data-target="#edit-view-publiser-modal" data-toggle="modal" @click="selectPubliser(publiser, false)" class="btn btn-success"
                                ><i class="fas fa-pen"></i
                            ></a>
                            <!-- <a href="#" class="btn btn-primary ml-2" data-toggle="modal" @click="selectpubliser(publiser, false)" data-target="#edit-view-publiser-modal"
                                ><i class="fas fa-eye"></i
                            ></a> -->
                            <a href="#" data-toggle="modal" data-target="#confirm-delete-modal" @click="selectPubliser(publiser, false)" class="btn btn-danger ml-2"
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
    <confirm-delete-modal :publiser="currentPubliser" @loadItems="loadItems"></confirm-delete-modal>
    <edit-view-publiser-modal :publiser="currentPubliser" :isEdit="isEditPubliser" @loadItems="loadItems"></edit-view-publiser-modal>
    <add-publiser-modal @loadItems="loadItems"></add-publiser-modal>
</template>
<script>
import axios from "axios";
import Preloader from "../../../Preloader.vue";
import ConfirmDeleteModal from "../Modals/ConfirmDeleteModal.vue";
import EditViewPubliserModal from "../Modals/EditViewPubliserModal.vue";
import AddPubliserModal from "../Modals/AddPubliserModal.vue";
import _ from 'lodash';
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            publiserList: [],
            loading: true,
            searchKey: 'name',
            searchValue: '',
            currentPubliser: {},
            sortKey: {
                key: '',
                type: ''
            },
            isEditPubliser: false,
        };
    },
    components: {
        Preloader,
        ConfirmDeleteModal,
        EditViewPubliserModal,
        AddPubliserModal
    },
    methods: {
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
        async loadItems() {
            this.loading = true;
            const response = await axios.get(`/publisers/api/get-publiser?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage}&searchValue=${this.searchValue}&sortBy=${this.sortKey.key}&orderBy=${this.sortKey.type}`);
            this.publiserList = response.data.data;
            this.totalRows = response.data.total;
            // console.log(this.publiserList, 'res');

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
        selectPubliser(publiser, isEdit) {
            this.currentPubliser = publiser;
            this.isEditPubliser = isEdit;
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
