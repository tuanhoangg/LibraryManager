<template lang="">
<div class="modal fade" id="modal-change-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi mật khẩu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                <Form @submit="changePassword">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Mật khẩu hiện tại</label>
                            <Field type="password" class="form-control" :rules="{required: {name: 'mật khẩu'}}"
                                placeholder="Mật khẩu hiện tại" name="current_password"/>
                        <ErrorMessage class="text-danger" name="current_password"/>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputEmail1">Mật khẩu mới</label>
                            <Field type="password" class="form-control" :rules="{required: {name: 'mật khẩu mới'}}"
                                placeholder="Mật khẩu mới" name="new_password" v-model='newPass'/>
                            <ErrorMessage class="text-danger" name="new_password"/>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nhập lại mật khẩu mới</label>
                            <Field type="password" class="form-control"
                                placeholder="Nhập lại mật khẩu mới" name="retype_new_pass" :rules="{required: {name: 'mật khẩu nhập lại'}, confirmed  }" />
                            <ErrorMessage class="text-danger" name="retype_new_pass"/>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    </div>
                </Form>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

</template>
<script>
import axios from 'axios';
import { Field, Form, ErrorMessage, defineRule } from 'vee-validate';
defineRule('confirmed', (value, [target], ctx) => {
    return value === ctx.form.new_password ? true : 'Mật khẩu nhập lại không khớp';
});
defineRule('required', (value, field) => {
    if (!value) {
        return `Trường ${field.name} không được bỏ trống`;
    }
    return true;
});
export default {
    props: {
        // id: String
    },
    components: {
        Field,
        Form,
        ErrorMessage,
    },
    data() {
        return {
            newPass: ''
        }
    },
    methods: {
        changePassword(values, { resetForm }) {
            axios.post('/users/api/change-password', values).then(res => {
                console.log(res, 'res');
                toastr.options.timeOut = 4000;
                if (res.data.alertType == 'success') {
                    toastr.success(res.data.msg);
                    $('#modal-change-password').modal('hide');
                } else {
                    toastr.error(res.data.msg);
                }
                resetForm();
            });
        },
        validateNewPassword(value) {
            if (!value) {
                return 'Mật khẩu không được bỏ trống';
            }
            return true;
        },

        validateCurrentPassword(value) {
            if (!value) {
                return 'Mật khẩu không được bỏ trống';
            }
            return true;
        },
    }
}
</script>
<style lang="">

</style>