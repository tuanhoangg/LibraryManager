<template lang="">
    <div
        class="modal fade"
        id="borrow-book-modal"
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
                                <div class="form-group">
                                    <label for="return_date">Ngày mượn</label>
                                    <Field
                                        name="borrow_date"
                                        v-slot="{
                                            value,
                                            handleChange,
                                            handleBlur,
                                            errors,
                                        }"
                                    >
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="return_date"
                                            name="return_date"
                                            :value="value"
                                            @input="
                                                handleChange(
                                                    $event.target.value
                                                )
                                            "
                                            @blur="handleBlur"
                                        />
                                        <ErrorMessage
                                            name="borrow_date"
                                            class="text-danger"
                                        />
                                    </Field>
                                </div>
                            </div>
                            <div class="form-group col-6">
                                <div class="form-group">
                                    <label for="return_date">Ngày trả</label>
                                    <Field
                                        name="return_date"
                                        v-slot="{
                                            value,
                                            handleChange,
                                            handleBlur,
                                            errors,
                                        }"
                                    >
                                        <input
                                            type="date"
                                            class="form-control"
                                            id="return_date"
                                            name="return_date"
                                            :value="value"
                                            @input="
                                                handleChange(
                                                    $event.target.value
                                                )
                                            "
                                            @blur="handleBlur"
                                        />
                                        <ErrorMessage
                                            name="return_date"
                                            class="text-danger"
                                        />
                                    </Field>
                                </div>
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
    // borrow_date: Yup.date().nullable(),
    // return_date: Yup.date().nullable(),
    borrow_date: Yup.date()
        .required("Hãy chọn ngày mượn")
        .min(
            new Date(new Date().getTime() + 3 * 24 * 60 * 60 * 1000),
            "Ngày mượn phải sau 3 ngày"
        ),
    return_date: Yup.date()
        .when("borrow_date", (borrow_date, schema) => {
            if (borrow_date == "") return schema;
            return schema.min(
                new Date(
                    new Date(borrow_date).getTime() + 2 * 24 * 60 * 60 * 1000
                ),
                "Ngày trả phải sau ngày mượn ít nhất 2 ngày"
            );
        })
        .required("Hãy chọn ngày trả"),
});
export default {
    props: {
        loadItems: Function,
        optionPubliser: Array,
        optionBookLanguage: Array,
        book: Array,
        bookDetail: Array,
        userId: Number,
    },
    components: {
        Field,
        Form,
        ErrorMessage,
    },
    data() {
        return {
            borrowDate: {},
            returnDate: {},
        };
    },
    methods: {
        async addBookItem(values, { resetForm }) {
            // await this.$refs.form.validate();

            console.log(
                this.borrowDate,
                this.returnDate,
                values,
                "values",
                this.userId
            );
            const response = await axios.post("/borrow-histories/borrow", {
                bookDetail: this.bookDetail,
                date: values,
                userId: this.userId,
            });

            toastr[response.data.status](response.data.msg);
            if (response.data.status == "success") {
                $("#borrow-book-modal").modal("hide");
                this.$emit("loadItems");
            }
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
