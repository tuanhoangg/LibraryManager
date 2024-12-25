<template lang="">
    <div class="container-fluid">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Danh sách mượn trả</h1>
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
                            placeholder="Tìm kiếm mã sách"
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
                            <th>Tên sách</th>
                            <th>Mã sách</th>
                            <th>Ngày mượn</th>
                            <th>Hạn trả</th>
                            <!-- <th>
                            Ngày trả
                        </th> -->
                            <th>Trạng thái</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-if="borrowList.length == 0">
                            <td colspan="7" class="text-center">
                                Không có dữ liệu
                            </td>
                        </tr>
                        <tr v-for="(borrow, index) in borrowList">
                            <td>{{ borrow.book.title }}</td>
                            <td class="text-wrap">{{ borrow.book_code }}</td>
                            <td class="text-wrap">
                                {{ formatDate(borrow.borrow_date) }}
                            </td>
                            <td class="text-wrap">
                                {{ formatDate(borrow.due_date) }}
                            </td>
                            <!-- <td class="text-wrap">{{ borrow.returned_at ? formatDate(borrow.returned_at) : '' }}</td> -->
                            <td class="text-wrap">
                                <span
                                    :class="convertStatusColor(borrow.status)"
                                    >{{ convertStatus(borrow.status) }}</span
                                >
                            </td>
                            <td class="text-center">
                                <a
                                    v-if="borrow.status == 'pending'"
                                    href="#"
                                    @click="currentBook = borrow"
                                    data-toggle="modal"
                                    data-target="#cancel-borrow-status-modal"
                                    class="btn btn-primary"
                                    >Hủy mượn</a
                                >
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
    </div>
    <confirm-cancel-borrow
        :status-borrow="JSON.parse(listStatus)"
        :book-detail="currentBook"
        @load-items="loadItems"
    ></confirm-cancel-borrow>
</template>
<script>
import axios from "axios";
import ConfirmCancelBorrow from "../Modal/ConfirmCancelBorrow.vue";
import _ from "lodash";

export default {
    props: {
        optionStatus: String,
        listStatus: String,
    },
    components: {
        ConfirmCancelBorrow,
    },
    data() {
        return {
            borrowList: [],
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            loading: true,
            searchKey: "book_code",
            searchValue: "",
            currentOption: {},
            sortKey: {
                key: "",
                type: "",
            },
            currentBook: [],
            option: JSON.parse(this.optionStatus),
        };
    },
    methods: {
        async loadItems() {
            // console.log(JSON.parse(this.optionStatus));
            this.loading = true;

            const response = await axios.get(
                `/borrow-histories/api/history?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage
                }&searchValue=${this.searchValue}&sortBy=${this.sortKey.key
                }&orderBy=${this.sortKey.type}&searchBy=${this.searchKey}`
            );
            console.log(response, "res");
            this.borrowList = response.data.data;
            console.log(this.borrowList);
            this.totalRows = response.data.total;

            this.loading = false;
        },
        formatDate(date) {
            return moment(String(date)).format("DD/MM/YYYY");
        },
        convertStatus(status) {
            return JSON.parse(this.listStatus)[status].label;
        },
        convertStatusColor(status) {
            return JSON.parse(this.listStatus)[status].class;
        },
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
    },
    mounted() {
        this.loadItems();
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
    }
};
</script>
<style lang=""></style>
