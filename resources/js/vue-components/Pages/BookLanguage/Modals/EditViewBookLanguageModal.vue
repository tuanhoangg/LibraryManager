<template lang="">
    <div
        class="modal fade"
        id="edit-view-book-language-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thể loại</h4>
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
                    <Form @submit="updateBookLanguage">
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

                                <label>Mã ngôn ngữ</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    placeholder="Mã ngôn ngữ"
                                    :rules="validateName"
                                    v-model="form.language_code"
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
                                    placeholder="Tên ngôn ngữ"
                                    :rules="validateCode"
                                    v-model="form.language_name"
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
        bookLanguage: Object,
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
        async updateBookLanguage(values, { resetForm }) {
            console.log(values, "values");
            const response = await axios.post(
                "/book-language/api/update-book-language",
                values
            );

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#edit-view-book-language-modal").modal("hide");
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
    watch: {
        bookLanguage() {
            this.form.language_code = this.bookLanguage.language_code;
            this.form.language_name = this.bookLanguage.language_name;
            this.form.id = this.bookLanguage.id;
        },
    },
};
</script>
<style lang=""></style>
