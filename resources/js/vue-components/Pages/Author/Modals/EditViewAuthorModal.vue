<template lang="">
    <div
        class="modal fade"
        id="edit-view-author-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Tác giả</h4>
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
                    <Form @submit="updateAuthor">
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

                                <label>Tên tác giả</label>
                                <Field
                                    type="text"
                                    class="form-control"
                                    placeholder="Tên tác giả ..."
                                    :rules="validateName"
                                    v-model="form.name"
                                    name="name"
                                />
                                <ErrorMessage class="text-danger" name="name" />
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
        author: Object,
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
        async updateAuthor(values, { resetForm }) {
            console.log(values, "values");
            const response = await axios.post(
                "/author/api/update-author",
                values
            );

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#edit-view-author-modal").modal("hide");
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
    },
    watch: {
        author() {
            this.form.name = this.author.name;
            this.form.id = this.author.id;
        },
    },
};
</script>
<style lang=""></style>
