<template lang="">
    <div
        class="modal fade"
        id="add-publiser-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm nhà xuất bản</h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <Form @submit="addPubliser">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên nhà xuất bản</label>
                            <Field
                                type="text"
                                class="form-control"
                                placeholder="Tên nhà xuất bản ..."
                                :rules="validateName"
                                name="name"
                            />
                            <ErrorMessage class="text-danger" name="name" />
                        </div>

                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <Field
                                type="text"
                                class="form-control"
                                placeholder="Số điện thoại ..."
                                :rules="validatePhone"
                                name="phone"
                            />
                            <ErrorMessage class="text-danger" name="phone" />
                        </div>

                        <div class="form-group">
                            <label>Địa chỉ</label>
                            <Field
                                v-slot="{ field, errors }"
                                name="address"
                                :rules="validateAdress"
                            >
                                <textarea
                                    v-bind="field"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Mô tả ..."
                                    name="address"
                                ></textarea>
                            </Field>
                            <ErrorMessage class="text-danger" name="address" />
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
</template>
<script>
import axios from "axios";
import { Field, Form, ErrorMessage, defineRule } from "vee-validate";

export default {
    props: {
        loadItems: Function,
    },
    components: {
        Field,
        Form,
        ErrorMessage,
    },
    data() {
        return {
            dataAdd: {
                name: "",
                description: "",
            },
        };
    },
    methods: {
        async addPubliser(values, { resetForm }) {
            console.log(values, "values");

            const response = await axios.post(
                "/publisers/api/add-publiser",
                values
            );

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#add-publiser-modal").modal("hide");
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
};
</script>
<style lang=""></style>
