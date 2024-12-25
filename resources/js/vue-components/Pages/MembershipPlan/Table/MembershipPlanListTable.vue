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
                            Tên gói
                            <div class="float-right" v-if="sortKey.key == 'name'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 600px" @click="sortOrder('price')">
                            Giá
                            <div class="float-right" v-if="sortKey.key == 'price'">
                                <i  v-if="sortKey.type === 'ASC'"  class="fas fa-arrow-up"></i>
                                <i  v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 600px">Thời hạn</th>
                        <th style="width: 20px" class='text-center'>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="membershipPLanList.length == 0">          
                        <td colspan="7" class='text-center'>Không có dữ liệu</td>
                    </tr>
                    <tr v-for="(plan, index) in membershipPLanList">
                        <td>{{ plan.id }}</td>
                        <td class="text-wrap">{{ plan.name }}</td>
                        <td class="text-wrap">{{ plan.price }}</td>
                        <td class="text-wrap">{{ plan.frequency }} tháng</td>
                        <td class="text-center">
                            <a href="#" data-target="#edit-plan-modal" data-toggle="modal" @click="selectPlan(plan, false)" class="btn btn-success"
                                ><i class="fas fa-pen"></i
                            ></a>
                            <a href="#" data-toggle="modal" data-target="#confirm-delete-modal" @click="selectPlan(plan, false)" class="btn btn-danger ml-2"
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
    <confirm-delete-modal :plan="currentPlan" @loadItems="loadItems"></confirm-delete-modal>
    <edit-membership-plan-modal :plan="currentPlan" :isEdit="isEditPlan"  @loadItems="loadItems" :isModalOpen="isModalOpen" :close-modal="isModalOpen = false"></edit-membership-plan-modal>
    <add-membership-plan-modal @loadItems="loadItems"></add-membership-plan-modal>
</template>
<script>
import axios from "axios";
import Preloader from "../../../Preloader.vue";
import ConfirmDeleteModal from "../Modals/ConfirmDeleteModal.vue";
import EditMembershipPlanModal from "../Modals/EditMembershipPlanModal.vue";
import AddMembershipPlanModal from "../Modals/AddMembershipPlanModal.vue";
import _ from 'lodash';
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            membershipPLanList: [],
            loading: true,
            searchKey: 'name',
            searchValue: '',
            currentPlan: {},
            sortKey: {
                key: '',
                type: ''
            },
            isEditPlan: false,
            isModalOpen: false,
        };
    },
    components: {
        Preloader,
        ConfirmDeleteModal,
        EditMembershipPlanModal,
        AddMembershipPlanModal
    },
    methods: {
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
        async loadItems() {
            this.loading = true;
            const response = await axios.get(`/membership-plans/api/get-membership-plan?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage}&searchValue=${this.searchValue}&sortBy=${this.sortKey.key}&orderBy=${this.sortKey.type}`);
            this.membershipPLanList = response.data.data;
            this.totalRows = response.data.total;
            // console.log(this.membershipPLanList, 'res');

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
        selectPlan(plan, isEdit) {
            this.currentPlan = plan;
            this.isEditPlan = isEdit;
            this.isModalOpen = true;
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
