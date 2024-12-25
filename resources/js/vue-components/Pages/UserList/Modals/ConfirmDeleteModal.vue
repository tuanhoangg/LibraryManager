<template lang="">
    <div
        class="modal fade"
        id="confirm-delete-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Xác nhận xóa người dùng</h4>
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
                    <p>Họ và tên: {{ user.name }}</p>
                    <p>Email: {{ user.email }}</p>
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
                        type="button"
                        class="btn btn-danger"
                        @click="deleteUser"
                    >
                        Xóa
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
        user: Array,
        loadItems: Function,
    },
    methods: {
        async deleteUser() {
            const res = await axios.delete("/users/delete/" + this.user.id);
            toastr.options.timeOut = 4000;
            if (res.data.alertType == "success") {
                toastr.success(res.data.msg);
                $("#confirm-delete-modal").modal("hide");
                this.$emit("loadItems");
            } else {
                toastr.error(res.data.msg);
            }
        },
    },
};
</script>
<style lang=""></style>
