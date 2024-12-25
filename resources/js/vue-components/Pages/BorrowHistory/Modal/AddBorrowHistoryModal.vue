<template lang="">
    <div
        class="modal fade"
        id="add-borrow-modal"
        style="display: none1"
        aria-hidden="true"
    >
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <Form @submit="createBorrow" :validation-schema="schema">
                    <div class="modal-header d-flex justify-content-between">
                        <h4 class="modal-title">Tạo đơn mượn</h4>
                        <div
                            class="form-group ml-5 d-flex"
                            style="width: 400px"
                        >
                            <select
                                ref="selectMember"
                                v-model="member"
                                id="memberSelect"
                                class="select2"
                                style="width: 100%; height: 40px"
                            ></select>
                            <a
                                href="#"
                                class="btn btn-primary ml-3 text-nowrap"
                                @click="getUserById"
                                >Kiểm tra</a
                            >
                            <a
                                href="#"
                                class="btn btn-secondary ml-3 text-nowrap"
                                @click="resetMemberDetai"
                                >Reset</a
                            >
                        </div>
                        <button
                            type="button"
                            class="close"
                            data-dismiss="modal"
                            aria-label="Close"
                        >
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <span v-if="error['member']" class="text-danger"
                        >Bắt buộc kiểm tra hội viên</span
                    >
                    <span
                        v-if="memberDetail.books_can_borrow == 0"
                        class="text-danger"
                        >Số lượng sách có thể mượn đã đên giới hạn</span
                    >
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-2">
                                <div class="form-group">
                                    <label for="return_date"
                                        >Ảnh đại diện</label
                                    >
                                    <div class="col-5 text-center">
                                        <img
                                            :src="
                                                memberDetail?.user?.image ??
                                                'https://via.placeholder.com/150'
                                            "
                                            style="height: 140px; width: 110px"
                                            alt="user-avatar"
                                            class="Wimg-fluid"
                                        />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-10">
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label for="return_date"
                                            >Mã hội viên</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            disabled
                                            :value="memberDetail.member_code"
                                        />
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="return_date"
                                            >Họ và tên</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="return_date"
                                            disabled
                                            :value="memberDetail.user?.name"
                                        />
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="return_date">Email</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            disabled
                                            :value="memberDetail.user?.email"
                                        />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-4">
                                        <label for="return_date"
                                            >Số điện thoại</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            :value="memberDetail.user?.phone"
                                            disabled
                                        />
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="return_date">Địa chỉ</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            :value="memberDetail.user?.address"
                                            disabled
                                        />
                                    </div>
                                    <div class="form-group col-4">
                                        <label for="return_date"
                                            >Số lượng sách có thể mượn</label
                                        >
                                        <input
                                            type="text"
                                            class="form-control"
                                            :value="
                                                memberDetail.books_can_borrow
                                            "
                                            disabled
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row d-flex justify-content-center">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_date">Tên sách</label>
                                    <select
                                        ref="selectBook"
                                        v-model="book"
                                        id="bookSelect"
                                        class="select2"
                                        style="width: 100%; height: 40px"
                                        :disabled="
                                            memberDetail.length == 0 ||
                                            memberDetail.books_can_borrow == 0
                                        "
                                    ></select>
                                </div>
                                <span v-if="error['book']" class="text-danger"
                                    >Trường sách bắt buộc nhập</span
                                >
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_date">Mã sách</label>
                                    <select
                                        ref="selectBookItem"
                                        v-model="bookItem"
                                        id="selectBookItem"
                                        class="select2"
                                        style="width: 100%; height: 40px"
                                        :disabled="
                                            book == '' ||
                                            memberDetail.length == 0
                                        "
                                    ></select>
                                </div>
                                <span
                                    v-if="error['bookItem']"
                                    class="text-danger"
                                    >Trường sách bắt buộc nhập</span
                                >
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="borrow_date">Ngày mượn</label>
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
                                            name="borrow_date"
                                            :value="value"
                                            @input="
                                                handleChange(
                                                    $event.target.value
                                                )
                                            "
                                            @blur="handleBlur"
                                            :disabled="
                                                book == '' ||
                                                memberDetail.length == 0
                                            "
                                        />
                                        <ErrorMessage
                                            name="borrow_date"
                                            class="text-danger"
                                        />
                                    </Field>
                                </div>
                            </div>
                            <div class="col-md-6">
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
                                            :disabled="
                                                book == '' ||
                                                memberDetail.length == 0
                                            "
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
                        <button
                            type="submit"
                            class="btn btn-primary"
                            @click="createBorrow"
                            :disabled="
                                memberDetail.length == 0 ||
                                memberDetail.books_can_borrow == 0
                            "
                        >
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
            "Ngày mượn phải sau 2 ngày"
        )
        .nullable(),
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
        .required("Hãy chọn ngày trả")
        .nullable(),
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
            member: "",
            book: "",
            bookItem: "",
            memberDetail: [],
            bookDetail: [],
            bookName: "",
            error: [],
            borrowDate: {},
            returnDate: {},
        };
    },
    methods: {
        async createBorrow(values) {
            let check = this.checkErrorSubmit();
            console.log(this.borrowDate, this.returnDate, values, "values");

            if (check) {
                return false;
            }
            const response = await axios.post("/borrow-histories/borrow", {
                bookDetail: this.bookDetail,
                date: values,
                userId: this.memberDetail.user_id,
            });

            toastr[response.data.status](response.data.msg);

            if (response.data.status == "success") {
                $("#add-borrow-modal").modal("hide");
                this.$emit("loadItems");
            }
        },
        async getUserById() {
            let check = this.checkErrorMemberSubmit();
            if (check) {
                return false;
            }
            console.log(123, check);
            const response = await axios.get(
                "/members/api/get-member?id=" + this.member
            );
            console.log(response);
            this.memberDetail = response.data.data;
        },
        resetMemberDetai() {
            this.memberDetail = [];
            this.member = "";
        },
        initSelect2() {
            const vm = this;
            $("#memberSelect")
                .select2({
                    ajax: {
                        url: "/members/api/get-member-list",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                searchValue: params.term || "",
                                limit: 10,
                                offset: (params.page - 1) * 10 || 0,
                                orderBy: "DESC",
                                sortBy: "id",
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            // vm.memberDetail = data.data[0];
                            console.log(this.memberDetail, "detail");
                            return {
                                results: data.data.map((member) => ({
                                    id: member.member_code, // Sử dụng user_id làm id
                                    text: member.member_code, // Hiển thị tên người dùng
                                })),
                                pagination: {
                                    more: params.page * 10 < data.total,
                                },
                            };
                        },
                        cache: true,
                    },
                    placeholder: "Chọn mã hội viên",
                    templateResult: function (member) {
                        console.log(member, "params");

                        if (!member.id) {
                            return member.text;
                        }
                        return member.text;
                    },
                    templateSelection: function (member) {
                        return member.text || member.name;
                    },
                })
                .on("change", function () {
                    vm.member = $(this).val();
                });

            $("#bookSelect")
                .select2({
                    ajax: {
                        url: "/books/api/get-books",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                searchValue: params.term || "",
                                limit: 10,
                                offset: (params.page - 1) * 10 || 0,
                                orderBy: "DESC",
                                sortBy: "title",
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            console.log(data);
                            return {
                                results: data.data.map((book) => ({
                                    id: book.isbn_code, // Sử dụng user_id làm id
                                    text: book.title, // Hiển thị tên người dùng
                                })),
                                pagination: {
                                    more: params.page * 10 < data.total,
                                },
                            };
                        },
                        cache: true,
                    },
                    placeholder: "Chọn tên sách",
                    templateResult: function (book) {
                        return book.text;
                    },
                    templateSelection: function (book) {
                        return book.text;
                    },
                })
                .on("change", function () {
                    vm.book = $(this).val();
                });

            $("#selectBookItem")
                .select2({
                    ajax: {
                        url: "/book-item/api/get-by-isbn",
                        dataType: "json",
                        delay: 250,
                        data: function (params) {
                            return {
                                searchValue: params.term || "",
                                limit: 10,
                                offset: (params.page - 1) * 10 || 0,
                                orderBy: "DESC",
                                sortBy: "id",
                                isbn: vm.book,
                            };
                        },
                        processResults: function (data, params) {
                            params.page = params.page || 1;
                            console.log(data, "data123");
                            vm.bookName = data.data[0]?.book.title;
                            vm.bookDetail = data.data[0] ?? [];
                            return {
                                results: data.data.map((book) => ({
                                    id: book.book_code, // Sử dụng user_id làm id
                                    text: book.book_code, // Hiển thị tên người dùng
                                })),
                                pagination: {
                                    more: params.page * 10 < data.total,
                                },
                            };
                        },
                        cache: true,
                    },
                    placeholder: "Chọn mã sách",
                    templateResult: function (book) {
                        return book.text;
                    },
                    templateSelection: function (book) {
                        return book
                            ? vm.bookName + "( " + book.text + " )"
                            : book.text;
                    },
                })
                .on("change", function () {
                    vm.bookItem = $(this).val();
                });
        },
        checkErrorSubmit() {
            let check = false;
            if (this.book == "") {
                this.error["book"] = true;
                check = true;
            } else {
                this.error["book"] = false;
            }

            if (this.bookItem == "") {
                this.error["bookItem"] = true;
                check = true;
            } else {
                this.error["bookItem"] = false;
            }

            return check;
        },
        checkErrorMemberSubmit() {
            // let check = false;
            if (this.member == "") {
                this.error["member"] = true;
                return true;
            } else {
                this.error["member"] = false;
                return false;
            }
        },
    },
    computed: {
        schema() {
            return schema;
        },
    },
    watch: {
        member(newVal) {
            $(this.$refs.selectMember).val(newVal).trigger("change");
        },
    },
    mounted() {
        this.initSelect2();
    },
};
</script>
<style lang=""></style>
