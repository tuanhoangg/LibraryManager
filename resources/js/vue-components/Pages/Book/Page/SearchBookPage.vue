<template lang="">
    <div class="card">
        <div class="card-body" style="height: fit-content; min-height: 50vh">
            <div
                class="row d-flex justify-content-center align-items-center mb-3"
            >
                <div class="form-check">
                    <input
                        class="form-check-input"
                        id="author"
                        type="radio"
                        value="author_id"
                        name="option"
                        v-model="currentSearch"
                    />
                    <label for="author" class="form-check-label">Tác giả</label>
                </div>
                <div class="form-check ml-3">
                    <input
                        class="form-check-input"
                        id="book"
                        type="radio"
                        value="id"
                        name="option"
                        v-model="currentSearch"
                        checked
                    />
                    <label for="book" class="form-check-label">Tiêu đề</label>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <div class="col-md-4" v-if="currentSearch == 'author_id'">
                    <div class="form-group">
                        <select
                            ref="select"
                            v-model="author"
                            id="authorSelect"
                            class="select2"
                            style="width: 100%; height: 40px"
                        ></select>
                    </div>
                </div>
                <div class="col-md-4" v-else>
                    <div class="form-group">
                        <select
                            ref="selectBook"
                            v-model="book"
                            id="bookSelect"
                            class="select2"
                            style="width: 100%; height: 40px"
                        ></select>
                    </div>
                </div>
            </div>
            <div class="row d-flex justify-content-center">
                <a href="#" class="btn btn-primary" @click="loadItems"
                    >Tìm kiếm</a
                >
                <a href="#" class="btn btn-secondary ml-3" @click="resetSearch"
                    >Reset</a
                >
            </div>
            <div class="card-body" style="height: fit-content">
                <table
                    class="table text-nowrap table-bordered"
                    name="bookItems"
                >
                    <thead>
                        <th>
                            Tên sách
                            <div
                                class="float-right"
                                v-if="sortKey.key == 'title'"
                            >
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th>
                            Mã sách
                            <div
                                class="float-right"
                                v-if="sortKey.key == 'book_code'"
                            >
                                <i
                                    v-if="sortKey.type === 'ASC'"
                                    class="fas fa-arrow-up"
                                ></i>
                                <i v-else class="fas fa-arrow-down"></i>
                            </div>
                        </th>
                        <th>Tác giả</th>
                        <th>Ngôn ngữ</th>
                        <th>Nhà xuất bản</th>
                        <th>Trạng thái</th>
                        <th>Xem trước</th>
                        <th class="text-center">Thao tác</th>
                    </thead>
                    <tbody>
                        <tr v-for="book in bookItems">
                            <td>{{ bookDetail.title }}</td>
                            <td>{{ book.book_code }}</td>
                            <td>{{ getNameAuthor(bookDetail.author_id) }}</td>
                            <td>
                                {{ getNameLanguage(book.book_language_id) }}
                            </td>
                            <td>{{ getNamePubliser(book.publiser_id) }}</td>
                            <td>{{ getNameStatus(book.status) }}</td>
                            <td>
                                <a
                                    v-if="book.preview_url"
                                    :href="book.preview_url"
                                    target="_blank"
                                    >Preview</a
                                >
                            </td>
                            <td class="text-wrap text-center">
                                <a
                                    v-if="
                                        book.status == constants.STATUS_AVAIABLE
                                    "
                                    class="btn btn-primary ml-2"
                                    data-toggle="modal"
                                    data-target="#borrow-book-modal"
                                    @click="currentBook = book"
                                >
                                    Mượn sách
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <b-pagination
                    class="float-right mt-3"
                    v-model="currentPage"
                    :total-rows="totalRows"
                    :per-page="perPage"
                ></b-pagination>
            </div>
        </div>
        <borrow-book-modal
            :bookDetail="currentBook"
            :book="bookDetail"
            @load-items="loadItems"
            :user-id="userId"
        ></borrow-book-modal>
    </div>
</template>
<script>
import axios from "axios";
import constants from "../../../../constants";
import BorrowBookModal from "../Modal/BorrowBookModal.vue";
export default {
    components: {
        BorrowBookModal,
    },
    props: [
        "optionGenres",
        "optionAuthor",
        "optionBookLanguage",
        "optionPubliser",
        "userId",
    ],
    data() {
        return {
            author: "",
            book: "",
            bookItems: [],
            bookDetail: [],
            currentSearch: "id",
            pathBook: "/books/api/get-books",
            pathAuthor: "/author/api/get-author",
            currentPage: 1,
            totalRows: 0,
            perPage: 10,
            loading: true,
            searchKey: "name",
            searchValue: "",
            currentBook: [],
            sortKey: {
                key: "",
                type: "",
            },
            isEditAuthor: false,
            constants,
        };
    },
    methods: {
        async loadItems() {
            this.loading = true;
            const response = await axios.get(
                `/books/api/get-books?limit=${this.perPage}&offset=${
                    (this.currentPage - 1) * this.perPage
                }&searchKey=${this.currentSearch}&searchValue=${
                    this.currentSearch == "id" ? this.book : this.author
                }&sortBy=${this.sortKey.key}&orderBy=${this.sortKey.type}`
            );
            console.log(response, "res");
            if (response.data.data.length > 0) {
            }
            this.bookItems = response.data?.data[0]?.book_item ?? [];
            this.bookDetail = response.data.data[0] ?? [];
            this.totalRows = response.data.data[0]?.book_item.length ?? 0;

            this.loading = false;
        },
        getNamePubliser(id) {
            const publiser = this.optionPubliser.find(
                (publiser) => publiser.id == id
            );
            return publiser ? publiser.name : "";
        },
        getNameLanguage(id) {
            const language = this.optionBookLanguage.find(
                (language) => language.id == id
            );

            return language ? language.language_name : "";
        },
        getNameAuthor(id) {
            const author = this.optionAuthor.find((author) => author.id == id);

            return author ? author.name : "";
        },
        getNameStatus(status) {
            return constants.STATUS_BOOK_ITEM[status];
        },
        initSelect2() {
            const vm = this;
            $("#authorSelect")
                .select2({
                    ajax: {
                        url: "/author/api/get-author",
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

                            return {
                                results: data.data,
                                pagination: {
                                    more: params.page * 10 < data.total,
                                },
                            };
                        },
                        cache: true,
                    },
                    placeholder: "Chọn tác giả",
                    templateResult: function (author) {
                        return author.name;
                    },
                    templateSelection: function (author) {
                        return author.name || author.text;
                    },
                })
                .on("change", function () {
                    vm.author = $(this).val();
                });

            // Initialize bookSelect
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

                            return {
                                results: data.data,
                                pagination: {
                                    more: params.page * 10 < data.total,
                                },
                            };
                        },
                        cache: true,
                    },
                    placeholder: "Chọn tên sách",
                    templateResult: function (book) {
                        return book.title;
                    },
                    templateSelection: function (book) {
                        return book.title || book.text;
                    },
                })
                .on("change", function () {
                    vm.book = $(this).val();
                });
        },
        resetSearch() {
            this.book = "";
            this.author = "";
            this.currentSearch = "id";
            this.bookDetail = [];
            this.bookItems = [];
            this.totalRows = 0;
        },
        sortOrder(name) {
            if (!this.sortKey.type || this.sortKey.key != name) {
                this.sortKey.type = "ASC";
            } else if (this.sortKey.type == "ASC") {
                this.sortKey.type = "DESC";
            } else {
                this.sortKey.type = "ASC";
            }
            this.sortKey.key = name;
        },
    },
    beforeDestroy() {
        // Destroy select2 instances to avoid memory leaks
        if (this.$refs.select) {
            $(this.$refs.select).select2("destroy");
        }
        if (this.$refs.selectBook) {
            $(this.$refs.selectBook).select2("destroy");
        }
    },
    mounted() {
        const vm = this;
        $(this.$refs.select).select2();

        $(this.$refs.select).on("change", function () {
            vm.author = $(this).val();
        });
        $(this.$refs.selectBook).on("change", function () {
            vm.book = $(this).val();
        });
        this.initSelect2();
    },
    watch: {
        author(newVal) {
            $(this.$refs.select).val(newVal).trigger("change");
        },
        book(newVal) {
            $(this.$refs.selectBook).val(newVal).trigger("change");
        },
        currentSearch() {
            this.$nextTick(() => {
                this.initSelect2();
            });
        },
        sortKey: {
            handler() {
                this.currentPage = 1;
                return this.loadItems();
            },
            deep: true,
        },
    },
};
</script>
<style lang="css">
.select2-selection.select2-selection--single {
    height: 40px;
}

.select2-selection__arrow {
    padding-top: 35px;
}
</style>
