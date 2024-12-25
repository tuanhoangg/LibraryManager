<template lang="">
    <div
        class="modal fade"
        id="update-borrow-status-modal"
        style="display: none1"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title">Cập nhật</h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- {{bookDetail}} -->
                    <div class="row">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="return_date">Mã ISBN</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="isbn_code"
                                    disabled
                                    :value="borrowDetail?.isbn_code"
                                />
                            </div>
                            <div class="form-group">
                                <label for="return_date">Mã sách</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="book_code"
                                    disabled
                                    :value="borrowDetail?.book_code"
                                />
                            </div>
                            <div class="form-group">
                                <label for="return_date">Mã hội viên</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    name="member_code"
                                    disabled
                                    :value="
                                        borrowDetail?.user?.members?.member_code
                                    "
                                />
                            </div>
                            <div class="form-group" v-if="optionSelect">
                                <label for="return_date">Trạng thái</label>
                                <select class="form-control" v-model="selected">
                                    <option
                                        v-for="(option, key) in optionSelect"
                                        :hidden="key == borrowDetail?.status"
                                        :value="key"
                                    >
                                        {{ option }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-5 text-center">
                            <img
                                :src="borrowDetail?.book?.image ?? ''"
                                style="height: 140px; width: 110px"
                                alt="user-avatar"
                                class="Wimg-fluid"
                            />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-dismiss="modal"
                    >
                        Trở lại
                    </button>
                    <button
                        type="submit"
                        class="btn btn-primary"
                        @click="updateBorrowStatus"
                    >
                        Lưu
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";

export default {
    props: {
        bookDetail: Array,
        optionStatus: Array,
    },
    data() {
        return {
            selected: "",
            borrowDetail: [],
        };
    },
    methods: {
        async updateBorrowStatus() {
            const response = await axios.post(
                `/borrow-histories/api/update-status`,
                {
                    id: this.bookDetail.book_item_id,
                    borrow_id: this.bookDetail.id,
                    status: this.selected,
                }
            );
            console.log(this.selected, "this.selected");

            toastr[response.data.status](response.data.msg);
            if (response.data.status == "success") {
                $("#update-borrow-status-modal").modal("hide");
                this.$emit("loadItems");
            }
        },
    },
    computed: {
        optionSelect() {
            return this.optionStatus[this.bookDetail.status];
        },
    },
    watch: {
        bookDetail() {
            this.borrowDetail = this.bookDetail;
            this.selected = this.bookDetail?.status;
        },
    },
};
</script>
<style lang=""></style>
