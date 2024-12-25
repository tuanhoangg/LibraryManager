<template lang="">
    <div
        class="modal fade"
        id="add-book-item-modal"
        style="display: none"
        aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Thêm tác giả</h4>
                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal"
                        aria-label="Close"
                    >
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <Form @submit="addBookItem" :validation-schema="schema">
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Mã sách</label>
                                <Field
                                    type="number"
                                    name="book_code"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="book_code"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-6">
                                <div class="form-group">
                                    <label>Nhà xuất bản</label>
                                    <Field
                                        as="select"
                                        name="publiser_id"
                                        class="custom-select"
                                    >
                                        <option value="" disabled>
                                            Chọn nhà xuất bản
                                        </option>
                                        <option
                                            v-for="publiser in optionPubliser"
                                            :key="publiser.id"
                                            :value="publiser.id"
                                        >
                                            {{ publiser.name }}
                                        </option>
                                    </Field>
                                    <ErrorMessage
                                        name="publiser_id"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <div class="form-group">
                                    <label>Ngôn ngữ</label>
                                    <Field
                                        as="select"
                                        name="book_language_id"
                                        class="custom-select"
                                    >
                                        <option value="" disabled>
                                            Chọn ngôn ngữ
                                        </option>
                                        <option
                                            v-for="bookLanguage in optionBookLanguage"
                                            :key="bookLanguage.id"
                                            :value="bookLanguage.id"
                                        >
                                            {{ bookLanguage.language_name }}
                                        </option>
                                    </Field>
                                    <ErrorMessage
                                        name="book_language_id"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <label>Vị trí</label>
                                <Field
                                    type="text"
                                    name="location"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="location"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Phiên bản</label>
                                <Field
                                    type="text"
                                    name="edition"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="edition"
                                    class="text-danger"
                                />
                            </div>
                            <div class="form-group col-6">
                                <div class="form-group">
                                    <label>Trạng thái</label>
                                    <Field
                                        as="select"
                                        name="status"
                                        class="custom-select"
                                    >
                                        <option value="" disabled>
                                            Chọn trạng thái
                                        </option>
                                        <option value="1">Sẵn sàng</option>
                                        <option value="2">
                                            Không sẵn sàng
                                        </option>
                                        <option value="3">Mất</option>
                                    </Field>
                                    <ErrorMessage
                                        name="status"
                                        class="text-danger"
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-6">
                                <label>Giá sách</label>
                                <Field
                                    type="number"
                                    name="price"
                                    class="form-control"
                                />
                                <ErrorMessage
                                    name="price"
                                    class="text-danger"
                                />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                                <label>File xem trước</label>

                                <Field
                                    type="file"
                                    name="preview"
                                    v-slot="{ handleChange, handleBlur }"
                                    ref="preview"
                                >
                                    <input
                                        type="file"
                                        class="form-control"
                                        id="preview"
                                        name="preview"
                                        accept="pdf"
                                        @change="uploadFile"
                                    />
                                </Field>
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

import * as Yup from "yup";
const schema = Yup.object().shape({
    book_code: Yup.number()
        .typeError("Mã sách phải là số")
        .required("Vui lòng nhập mã sách"),
    book_language_id: Yup.string().required("Vui lòng chọn ngôn ngữ"),
    publiser_id: Yup.string().required("Vui lòng chọn nhà xuất bản"),
    location: Yup.string().required("Vui lòng nhập vị trí"),
    status: Yup.string().required("Vui lòng nhập trạng thái"),
    price: Yup.number().required("Vui lòng nhập giá tiền"),
});
export default {
    props: {
        loadItems: Function,
        optionPubliser: Array,
        optionBookLanguage: Array,
        bookItems: Array,
    },
    components: {
        Field,
        Form,
        ErrorMessage,
    },
    data() {
        return {
            filePreview: null,
        };
    },
    methods: {
        addBookItem(values, { resetForm }) {
            let checkDuplicate = 0;
            this.bookItems.forEach((item) => {
                if (item.book_code == values.book_code) {
                    checkDuplicate++;
                }
            });
            if (checkDuplicate > 0) {
                toastr.error("Trùng book code");
                return;
            }
            if (this.filePreview) {
                values.preview_url = this.filePreview;
                values.path_preview = URL.createObjectURL(this.filePreview);
            }
            this.$emit("addItem", values);

            this.filePreview = null;
            $("#add-book-item-modal").modal("hide");
            $("#preview").val("");
            resetForm();
        },
        uploadFile(e) {
            this.filePreview = e.target.files[0];
        },
    },
    computed: {
        schema() {
            return schema;
        },
    },
};
</script>
<style lang=""></style>
