<template lang="">
    <div class="container-fluid">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Danh sách mượn trả</h1>
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
                                        class="dropdown-item"
                                        data-toggle="modal"
                                        data-target="#add-borrow-modal"
                                        href="#"
                                        >Tạo mượn</a
                                    >
                                    <a
                                        class="dropdown-item"
                                        @click="sendAllMailLateReturn"
                                        >Gửi thông báo trả muộn</a
                                    >
                                    <a
                                        class="dropdown-item"
                                        @click="exportExcel"
                                        >Xuất excel thống kê</a
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.container-fluid -->
        </div>
        <div class="card">
            <div class="row d-flex justify-content-end">
                <div class="form-group m-0">
                    <div class="my-3 row">
                        <button class="btn btn-primary mr-5" @click="reset">
                            Reset
                        </button>
                    </div>
                </div>
                <div class="form-group m-0">
                    <div class="my-3 mx-3 row">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">
                                    <i class="far fa-calendar-alt"></i>
                                </span>
                            </div>
                            <input
                                type="text"
                                class="form-control float-right"
                                id="reservation"
                                v-model="dateRange"
                                placeholder="Chọn thời gian"
                            />
                        </div>
                    </div>
                </div>
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
                                    v-for="(status, key) in JSON.parse(
                                        listStatus
                                    )"
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
                                <select
                                    class="form-control"
                                    id="inputGroupSelect02"
                                    v-model="searchKey"
                                >
                                    <option value="member_code">
                                        Tên người mượn
                                    </option>
                                    <option value="book_code">Mã sách</option>
                                </select>
                            </div>
                            <div class="input-group-append">
                                <span class="input-group-text"
                                    ><i class="fa fa-search"></i
                                ></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-end"></div>
            <div class="card-body" style="height: fit-content">
                <table class="table text-nowrap table-bordered">
                    <thead>
                        <tr>
                            <th>Tên sách</th>
                            <th>Mã sách</th>
                            <th @click="sortOrder('borrow_date')">
                                Ngày mượn
                                <div
                                    class="float-right"
                                    v-if="sortKey.key == 'borrow_date'"
                                >
                                    <i
                                        v-if="sortKey.type === 'ASC'"
                                        class="fas fa-arrow-up"
                                    ></i>
                                    <i v-else class="fas fa-arrow-down"></i>
                                </div>
                            </th>
                            <th @click="sortOrder('due_date ')">
                                Hạn trả
                                <div
                                    class="float-right"
                                    v-if="sortKey.key == 'due_date'"
                                >
                                    <i
                                        v-if="sortKey.type === 'ASC'"
                                        class="fas fa-arrow-up"
                                    ></i>
                                    <i v-else class="fas fa-arrow-down"></i>
                                </div>
                            </th>
                            <th>Ngày trả</th>
                            <th>Người mượn</th>
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
                            <td class="text-wrap">
                                {{
                                    borrow.returned_at
                                        ? formatDate(borrow.returned_at)
                                        : ""
                                }}
                            </td>
                            <td class="text-wrap">{{ borrow.user?.name }}</td>
                            <td class="text-wrap">
                                <span
                                    :class="convertStatusColor(borrow.status)"
                                    >{{ convertStatus(borrow.status) }}</span
                                >
                            </td>
                            <td class="text-center">
                                <a
                                    href="#"
                                    @click="currentBook = borrow"
                                    data-toggle="modal"
                                    data-target="#update-borrow-status-modal"
                                    class="btn btn-primary"
                                    ><i class="fas fa-pen"></i
                                ></a>
                                <!-- <button
                                    class="btn btn-success ml-3"
                                    @click="sendMailLateReturn(borrow)"
                                    :disabled="borrow.status != 'overdue'"
                                >
                                    <i class="fas fa-envelope"></i>
                                </button> -->
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

    <add-borrow-modal @load-items="loadItems"></add-borrow-modal>
    <update-borrow-status-modal
        :book-detail="currentBook"
        :option-status="option"
        @load-items="loadItems"
    ></update-borrow-status-modal>
    <preloader :loading="loading"></preloader>
</template>
<script>
import axios from "axios";
import moment from "moment";
import AddBorrowModal from "../Modal/AddBorrowHistoryModal.vue";
import UpdateBorrowStatusModal from "../Modal/UpdateBorrowStatusModal.vue";
import Preloader from "../../../Preloader.vue";
import _ from "lodash";

export default {
    props: {
        optionStatus: String,
        listStatus: String,
    },
    components: {
        AddBorrowModal,
        UpdateBorrowStatusModal,
        Preloader,
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
            status: "",
            searchDate: "",
            dateRange: "",
        };
    },
    methods: {
        async loadItems() {
            this.loading = true;

            const response =
                await axios.get(`/borrow-histories/api/history?limit=${this.perPage
                    }&offset=${(this.currentPage - 1) * this.perPage}
                                            &searchBy=${this.searchKey
                    }&searchValue=${this.searchValue
                    }&sortBy=${this.sortKey.key}
                                            &orderBy=${this.sortKey.type
                    }&borrowStatus=${this.status
                    }&dateRange=${this.dateRange}`);
            console.log(response, "res");
            this.borrowList = response.data.data;
            this.totalRows = response.data.total;

            this.loading = false;
        },
        async sendMailLateReturn(data) {
            this.loading = true;

            const response = await axios.post(`/send-mail/late-return`, {
                data: data,
            });
            toastr.success("Gửi email thông báo thành công");

            console.log(response, "response");
            this.loading = false;
        },
        async sendAllMailLateReturn() {
            this.loading = true;

            const response = await axios.post(
                `/borrow-histories/api/send-email-ovedue`
            );
            toastr.success("Gửi email thông báo thành công");

            this.loading = false;
        },
        async exportExcel() {
            this.loading = true;
            const response = await axios.get(`export/borrow?limit=${this.perPage
                }&offset=${(this.currentPage - 1) * this.perPage}
                                            &searchBy=${this.searchKey
                }&searchValue=${this.searchValue
                }&sortBy=${this.sortKey.key}
                                            &orderBy=${this.sortKey.type
                }&borrowStatus=${this.status
                }&dateRange=${this.dateRange}`, {
                responseType: "blob",
            });
            const blob = new Blob([response.data], {
                type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",
            });

            const link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "Thống kê mượn trả.xlsx";

            document.body.appendChild(link);
            link.click();

            console.log(response, "response");
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
        initDateRangePicker() {
            const vm = this;
            const startDate = moment().subtract(1, "months");
            const endDate = moment();

            $("#reservation")
                .daterangepicker(
                    {
                        startDate: startDate,
                        endDate: endDate,
                        locale: {
                            format: "YYYY-MM-DD",
                        },
                    }
                    // function (start, end) {
                    //     vm.dateRange = `${start.format("YYYY-MM-DD")} - ${end.format(
                    //         "YYYY-MM-DD"
                    //     )}`;
                    // }
                )
                .on("apply.daterangepicker", function (e, picker) {
                    // vm.dateRange = `${startDate.format(
                    //     "YYYY-MM-DD"
                    // )} - ${endDate.format("YYYY-MM-DD")}`;
                    vm.dateRange = `${picker.startDate.format("YYYY-MM-DD")} - ${picker.endDate.format("YYYY-MM-DD")}`;

                });

            // Set initial value of dateRange
            // vm.dateRange = `${startDate.format("YYYY-MM-DD")} - ${endDate.format("YYYY-MM-DD")}`;
        },
        reset() {
            this.dateRange = "";
            this.status = "";
            this.searchValue = "";
            this.sortKey = {
                key: "",
                type: "",
            };

            this.loadItems();
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
    },
    mounted() {
        this.loadItems();
        console.log();
        this.initDateRangePicker();
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
        status() {
            this.loadItems();
        },
        dateRange() {
            if (this.dateRange != "") {
                this.loadItems();
            }
        },
        sortKey: {
            handler() {
                this.currentPage = 1;
                return this.loadItems();
            },
            deep: true,
        },
    },
};
</script>
<style lang="css">
#inputGroupSelect02 {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background: none;
    padding-right: 1.75rem;
}

#inputGroupSelect02::-ms-expand {
    display: none;
}

.input-group-prepend #inputGroupSelect02 {
    border-top-right-radius: 0;
    border-bottom-right-radius: 0;
}

.input-group-prepend #inputGroupSelect02+.form-control {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 0;
}
</style>
