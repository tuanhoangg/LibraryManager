<template lang="">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách người dùng</h1>
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
                                    href="/users/create"
                                    type="button"
                                    class="dropdown-item"
                                >
                                    Thêm người dùng
                                </a>
                                <a
                                    class="dropdown-item"
                                    @click="exportExcelUser"
                                    >Xuất excel thống kê</a
                                >

                                <a
                                    class="dropdown-item"
                                    data-toggle="modal"
                                    data-target="#import-user-modal"
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
                <div class="col-sm-6"></div>
                <div class="input-group">
                    <input
                        name="search-text"
                        placeholder="Search"
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
                        <th style="width: 200px" @click="sortOrder('name')">
                            Họ và tên
                            <div
                                class="float-right"
                                v-if="sortKey.key == 'name'"
                            >
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 50px" @click="sortOrder('email')">
                            Email
                            <div
                                class="float-right"
                                v-if="sortKey.key == 'email'"
                            >
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th style="width: 200px">Gói hội viên</th>
                        <th style="width: 200px">Mã hội viên</th>
                        <th style="width: 200px">Đang mượn</th>
                        <th style="width: 200px">Số điện thoại</th>
                        <th style="width: 100px">Trạng thái</th>
                        <th style="width: 200px" class="text-center">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-if="userList.length == 0">
                        <td colspan="7" class="text-center">
                            Không có dữ liệu
                        </td>
                    </tr>
                    <tr v-for="(user, index) in userList">
                        <td>{{ user.id }}</td>
                        <td class="text-wrap">{{ user.name }}</td>
                        <td class="text-wrap">{{ user.email }}</td>
                        <td>{{ user.members?.membership_plan?.name ?? 'Không đăng ký hội viên' }}</td>
                        <td>{{ user.members?.member_code ?? '' }}</td>
                        <td>{{ user.borrow_count ?? '' }}</td>
                        <td>{{ user.phone }}</td>
                        <td
                            class="text-center align-middle"
                            v-if="user.email_verified_at != null"
                        >
                            <button class="btn btn-success" disabled>
                                <i class="fas fa-check-circle"></i>
                            </button>
                        </td>
                        <td class="text-center align-middle" v-else>
                            <button
                                class="btn btn-success"
                                @click="resendEmail(user.id)"
                            >
                                <i class="fas fa-envelope"></i>
                            </button>
                        </td>
                        <!-- <td></td> -->
                        <td class="text-center">
                            <!-- <div class="btn-group "> -->
                            <a
                                :href="'/users/' + user.id + '/edit'"
                                class="btn btn-success"
                                ><i class="fas fa-pen"></i
                            ></a>
                            <a
                                :href="'/users/' + user.id"
                                class="btn btn-primary ml-2"
                                ><i class="fas fa-eye"></i
                            ></a>
                            <a
                                href="#"
                                data-toggle="modal"
                                data-target="#confirm-delete-modal"
                                @click="deleteUser = user"
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
    <preloader :loading="loading"></preloader>
    <import-user @loading="(status) => {loading = status}" 
        @loadItems="loadItems"></import-user>
    <confirm-delete-modal
        :user="deleteUser"
        @loadItems="loadItems"
    ></confirm-delete-modal>
</template>
<script>
import axios from "axios";
import Preloader from "../../../Preloader.vue";
import ConfirmDeleteModal from "../Modals/ConfirmDeleteModal.vue";
import ImportUser from "../Modals/ImportUser.vue";
import _ from "lodash";
export default {
    data() {
        return {
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            userList: [],
            loading: true,
            searchKey: "name",
            searchValue: "",
            deleteUser: [],
            sortKey: {
                key: "",
                type: "",
            },
            file: null,
            successMessage: null,
        };
    },
    components: {
        Preloader,
        ConfirmDeleteModal,
        ImportUser,
    },
    methods: {
        handleFileUpload(event) {
            this.file = event.target.files[0];
        },
        async uploadFile() {
            this.loading = true;

            if (!this.file) {
                alert('Please select a file first.');
                this.loading = false;
                return;
            }

            const formData = new FormData();
            formData.append('file', this.file);

            try {
                const response = await axios.post('/users/import-users', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });
                console.log('response', response);
                const jobId = response.data.jobId; // Assuming the response includes a jobId

                // Check the status of the job
                this.pollJobStatus(jobId);

            } catch (error) {
                console.error('Error uploading file:', error.response.data);
                toastr.error(error.response.data.message || 'An error occurred');
                this.loading = false;
            }
            this.file = null;
        },

        async pollJobStatus(jobId) {
            try {
                const response = await axios.get(`/users/import-status/${jobId}`);
                const data = response.data;

                if (data.status === 'success') {
                    toastr.success(data.message);
                    this.loading = false;
                } else if (data.status === 'error') {
                    toastr.error(data.message);
                    this.loading = false;
                } else {
                    // If still processing, check again after a delay
                    setTimeout(() => this.pollJobStatus(jobId), 5000); // Check again after 5 seconds
                }

            } catch (error) {
                console.error('Error checking job status:', error);
                toastr.error('Có lỗi trong quá trình xử lý');
                this.loading = false;
            }
        }

        ,
        debounceLoadItems: _.debounce(function () {
            this.loadItems();
        }, 400),
        async loadItems() {
            this.loading = true;
            const response = await axios.get(
                `/users/api/get-users?limit=${this.perPage}&offset=${(this.currentPage - 1) * this.perPage
                }&searchValue=${this.searchValue}&sortBy=${this.sortKey.key
                }&orderBy=${this.sortKey.type}`
            );
            this.userList = response.data.data.data;
            this.totalRows = response.data.data.total;

            this.loading = false;
        },
        async resendEmail(id) {
            console.log(id);
            this.loading = true;
            const res = await axios.post(`/users/re-send/verify`, {
                id: id,
            });
            if (res.data.alertType == "success") {
                toastr.success(res.data.msg);
                // $('#confirm-delete-modal').modal('hide');
                // this.$emit('loadItems');
            } else {
                toastr.error(res.data.msg);
            }

            this.loading = false;
        },
        async exportExcelUser() {
            this.loading = true;
            const response = await axios.get('/export/users', {
                responseType: 'blob'
            });
            const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'Thống kê người dùng.xlsx';

            document.body.appendChild(link);
            link.click();

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
