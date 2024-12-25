<template lang="">
    <div
        class="modal fade"
        id="cancel-borrow-status-modal"
        style="display: none1"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-between">
                    <h4 class="modal-title">Hủy mượn</h4>
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
                    <p>Xác nhận hủy mượn?</p>
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
                        @click="cancelBorrow"
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
        statusBorrow: Array,
    },
    data() {
        return {
            borrowDetail: [],
        };
    },
    methods: {
        async cancelBorrow() {
            console.log(this.bookDetail, this.borrowDetail, "de");
            const response = await axios.post(
                `/borrow-histories/api/cancel-status`,
                {
                    isbnCode: this.bookDetail.isbn_code,
                    bookCode: this.bookDetail.book_code,
                    id: this.bookDetail.id,
                }
            );

            toastr[response.data.status](response.data.msg);
            if (response.data.status == "success") {
                $("#cancel-borrow-status-modal").modal("hide");
                this.$emit("loadItems");
            }
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
