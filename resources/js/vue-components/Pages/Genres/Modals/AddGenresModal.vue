<template lang="">
    <div
        class="modal fade"
        id="add-genres-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm thể loại</h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <Form @submit="addGenres">
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Tên thể loại</label>
                            <Field
                                type="text"
                                class="form-control"
                                placeholder="Tên thể loại ..."
                                :rules="validateName"
                                name="name"
                            />
                            <ErrorMessage class="text-danger" name="name" />
                        </div>
                        <div class="form-group">
                            <label>Mô tả</label>
                            <Field
                                v-slot="{ field, errors }"
                                name="description"
                            >
                                <textarea
                                    v-bind="field"
                                    class="form-control"
                                    rows="3"
                                    placeholder="Mô tả ..."
                                    name="description"
                                ></textarea>
                            </Field>
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
        async addGenres(values, { resetForm }) {
            console.log(values, "values");

            const response = await axios.post("/genres/api/add-genres", values);

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#add-genres-modal").modal("hide");
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
};
</script>
<style lang=""></style>
