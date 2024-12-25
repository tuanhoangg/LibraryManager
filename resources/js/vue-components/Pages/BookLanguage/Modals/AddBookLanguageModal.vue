<template lang="">
    <div
        class="modal fade"
        id="add-book-language-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm ngôn ngữ</h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <Form @submit="addBookLanguage">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Mã ngôn ngữ</label>
                            <Field
                                type="text"
                                class="form-control"
                                placeholder="Mã ngôn ngữ ..."
                                :rules="validateCode"
                                name="language_code"
                            />
                            <ErrorMessage
                                class="text-danger"
                                name="language_code"
                            />
                        </div>
                        <div class="form-group">
                            <label>Tên ngôn ngữ</label>
                            <Field
                                type="text"
                                class="form-control"
                                placeholder="Tên ngôn ngữ ..."
                                :rules="validateName"
                                name="language_name"
                            />
                            <ErrorMessage
                                class="text-danger"
                                name="language_name"
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
        async addBookLanguage(values, { resetForm }) {
            console.log(values, "values");

            const response = await axios.post(
                "/book-language/api/add-book-language",
                values
            );

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#add-book-language-modal").modal("hide");
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

        validateCode(value) {
            if (!value) {
                return "Trường mã không được bỏ trống";
            }
            return true;
        },
    },
};
</script>
<style lang=""></style>
