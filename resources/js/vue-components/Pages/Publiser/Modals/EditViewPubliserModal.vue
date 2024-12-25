<template lang="">
    <div
        class="modal fade"
        id="edit-view-publiser-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Nhà xuất bản</h4>
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
                    <Form @submit="updatePubliser">
                        <div class="modal-body">
                            <div class="form-group">
                                <Field
                                    type="int"
                                    class="form-control"
                                    hidden
                                    :rules="validateName"
                                    v-model="form.id"
                                    name="id"
                                />

                                <label>Tên nhà xuất bản</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    placeholder="Tên nhà xuất bản ..."
                                    :rules="validateName"
                                    v-model="form.name"
                                    name="name"
                                />
                                <ErrorMessage class="text-danger" name="name" />
                            </div>

                            <div class="form-group">
                                <label>Số điện thoại</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    placeholder="Tên nhà xuất bản ..."
                                    :rules="validatePhone"
                                    v-model="form.phone"
                                    name="phone"
                                />
                                <ErrorMessage class="text-danger" name="name" />
                            </div>
                            <div class="form-group">
                                <label>Địa chỉ</label>
                                <Field
                                    v-slot="{ field, errors }"
                                    name="address"
                                    v-model="form.address"
                                    :rules="validateAdress"
                                >
                                    <textarea
                                        class="form-control"
                                        rows="3"
                                        placeholder="Mô tả ..."
                                        v-model="form.address"
                                    ></textarea>
                                </Field>
                                <ErrorMessage
                                    class="text-danger"
                                    name="address"
                                />
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
                            <button type="submit" class="btn btn-primary">
                                Lưu
                            </button>
                        </div>
                    </Form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import axios from "axios";
import { Field, Form, ErrorMessage, defineRule, useForm } from "vee-validate";
const { setFieldValue, setValues } = useForm();

export default {
    props: {
        loadItems: Function,
        isEdit: Boolean,
        publiser: Object,
    },
    components: {
        Field,
        Form,
        ErrorMessage,
    },
    data() {
        return {
            form: {},
        };
    },
    methods: {
        async updatePubliser(values, { resetForm }) {
            console.log(values, "values");
            const response = await axios.post(
                "/publisers/api/update-publiser",
                values
            );

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#edit-view-publiser-modal").modal("hide");
                this.$emit("loadItems");
                resetForm();
            }
        },
        validateName(value) {
            if (!value) {
                return "Trường tên không được bỏ trống";
            }
            return true;
        },
        validatePhone(value) {
            if (isNaN(value)) {
                return "Trường số điện thoại phải là số";
            }

            if (value.toString().length < 10) {
                return "Định dạng số điện thoại lớn hơn 10";
            }
            return true;
        },
        validateAdress(value) {
            if (!value) {
                return "Trường địa chỉ không được bỏ trống";
            }
            return true;
        },
    },
    watch: {
        publiser() {
            this.form.name = this.publiser.name;
            this.form.address = this.publiser.address;
            this.form.phone = this.publiser.phone;
            this.form.id = this.publiser.id;
        },
    },
};
</script>
<style lang=""></style>
