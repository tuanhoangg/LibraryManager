<template lang="">
    <div class="card">
        <div class="row d-flex justify-content-end">
            <div class="form-group m-0 mr-4">
                <div class="my-3 mx-3 row col-md-12">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <label
                                class="input-group-text"
                                for="inputGroupSelect02"
                                ><i class="fas fa-filter"></i
                            ></label>
                        </div>
                        <select class="custom-select" v-model="status">
                            <option selected value="">Tất cả</option>
                            <option
                                v-for="(status, key) in JSON.parse(filterStatus)"
                                :value="key"
                            >
                                {{ status.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group m-0">
                <div class="my-3 mx-3 row">
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
        </div>
        {{optionStatus['pending']}}
        <div class="card-body" style="height: fit-content">
            <table class="table text-nowrap table-bordered">
                <thead>
                    <tr>
                        <th>Tên người dùng</th>
                        <th>Mã hội viên</th>
                        <th>Mã sách</th>
                        <th>Tên sách</th>
                        <th>Loại phạt</th>
                        <th>Phí phạt</th>
                        <th>Trạng thái</th>
                        <th class='text-center'>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="lateReturn.length == 0">
                        <td colspan="8" class="text-center">
                            Không có dữ liệu
                        </td>
                    </tr>
                    <tr v-for="(borrow, index) in lateReturn">
                        <td>{{borrow.user?.name}}</td>
                        <td>{{borrow.user?.member_code}}</td>
                        <td>{{ borrow.borrow.book_code }}</td>
                        <td class="text-wrap">
                            {{ borrow.borrow.book.title }}
                        </td>
                        <td class="text-wrap">
                            {{ convertStatus(borrow.fine_type) }}
                        </td>
                        <td class="text-wrap">
                            {{ borrow.amount }}
                        </td>
                        <td>{{ convertFilterStatus(borrow.status) }}</td>
                        <!-- <td class="text-center">
                            <a v-if="borrow.status == 0 && borrow.borrow.status == 'returned'" href="#" @click="payment(borrow.id)"  data-toggle="modal" data-target="#cancel-borrow-status-modal"  
                            class="btn btn-primary"
                                ><i class="fas fa-money-bill-wave-alt"></i></a>
                        </td> -->
                        <td>
                            <button
                                class="btn btn-success ml-3"
                                @click="sendMailFines(borrow)"
                                v-if="borrow.status == 0"
                            >
                                <i class="fas fa-envelope"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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

</template>
<script>
import axios from "axios";
import _ from "lodash";
import Preloader from "../../../Preloader.vue";

export default {
    props: {
        optionStatus: String,
        listStatus: String,
        filterStatus: String,
    },
    components: { Preloader },
    data() {
        return {
            lateReturn: [],
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            loading: true,
            searchKey: "name",
            searchValue: "",
            currentOption: {},
            sortKey: {
                key: "",
                type: "",
            },
            currentBook: [],
            status: "",
            option: JSON.parse(this.optionStatus),
        };
    },
    methods: {
        async loadItems() {
            this.loading = true;

            const response =
                await axios.get(`fines/api/get-fines-list?limit=${this.perPage
                    }&offset=${(this.currentPage - 1) * this.perPage}&searchValue=${this.searchValue
                    }
            &sortBy=${this.sortKey.key}&orderBy=${this.sortKey.type
                    }&borrowStatus=${this.status}`);
            console.log(response, "res");
            this.lateReturn = response.data.data;
            console.log(this.option['pending']);
            this.totalRows = response.data.total;

            this.loading = false;
        },
        async payment(id) {
            console.log(id, "id");
            this.loading = true;

            const response = await axios.post(`/fines/api/payment`, {
                id: id,
                payment_method: 2,
            });
            console.log(response, "res");
            window.location.href = response.data.payUrl;

            this.loading = false;
        },
        formatDate(date) {
            return moment(String(date)).format("DD/MM/YYYY");
        },
        convertStatus(status) {
            console.log(status, 'convertStatus', JSON.parse(this.listStatus));

            return JSON.parse(this.listStatus)[status].label;
        },
        convertFilterStatus(status) {
            console.log(status, 'convertStatus', JSON.parse(this.listStatus));

            return JSON.parse(this.filterStatus)[status].label;
        },
        async sendMailFines(data) {
            this.loading = true;

            const response = await axios.post(`/send-mail/fines`, {
                data: data,
            });
            toastr.success("Gửi email thông báo thành công");

            this.loading = false;
        },
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
    },
    computed: {
        optionSelect(status) {
            return this.optionStatus[status];
        },
    },
    watch: {
        status() {
            this.loadItems();
        },
        searchValue: {
            handler() {
                this.currentPage = 1;
                return this.debounceLoadItems();
            },
            deep: true,
        },
    },
    mounted() {
        this.loadItems();
    },
};
</script>
<style lang=""></style>
