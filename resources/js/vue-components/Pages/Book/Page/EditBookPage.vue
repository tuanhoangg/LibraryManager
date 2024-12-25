<template lang="">
<div class="card">
    <Form @submit="addBook" :validation-schema="schema">
        <div class="card-body" style="height: fit-content">
            <div class="row">
                <div class="col-md-8">
                    <h3 class="mb-2">Ảnh đại diện</h3>
                    <hr>
                    </hr>
                    <div class="row">
                        <div class="form-group col-6">
                            <label>Mã ISBN</label>
                            <Field type="number" name="isbn" class="form-control" v-model="book.isbn_code" />
                            <ErrorMessage name="isbn" class="text-danger" />
                        </div>
                        <div class="form-group col-6">
                            <div class="form-group">
                                <label>Tác giả</label>
                                <Field as="select" name="author" class="custom-select" v-model="book.author_id">
                                    <option value="" disabled>Chọn tác giả</option>
                                    <option v-for="author in optionAuthor" :key="author.id" :value="author.id">
                                        {{ author.name }}
                                    </option>
                                </Field>
                                <ErrorMessage name="author" class="text-danger" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-6">
                            <div class="form-group">
                                <label>Thể loại</label>
                                <Field as="select" name="genre" class="custom-select" v-model="book.genres_id">
                                    <option value="" disabled>Chọn thể loại</option>
                                    <option v-for="genre in optionGenres" :key="genre.id" :value="genre.id">
                                        {{ genre.name }}
                                    </option>
                                </Field>
                                <ErrorMessage name="genre" class="text-danger" />
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Tên sách</label>
                            <Field type="text" name="bookName" class="form-control" v-model="book.title" />
                            <ErrorMessage name="bookName" class="text-danger" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Mô tả</label>
                            <Field as="textarea" name="description" class="form-control" v-model="book.description"></Field>
                            <ErrorMessage name="description" class="text-danger" />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="text-center">
                        <h3 class="mb-2">Ảnh đại diện</h3>
                        <hr>
                        </hr>

                        <div class="form-group">
                            <label for="">
                                <img id="avatarPreview" class="img-circle elevation-2" :src="book.image" alt="Avatar Preview" style="height:150px; width: 150px;">

                                <Field type="file" name="avatar" v-slot="{ handleChange, handleBlur }">
                                    <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*" @change="uploadImage">
                                </Field>
                                <ErrorMessage name="avatar" class="text-danger" />

                            </label>
                        </div>
                        <button type="button" class="btn btn-secondary" @click="resetImage" id="resetBtn">Reset</button>

                    </div>
                </div>
            </div>
            <div class="row mx-2 mt-3 d-flex justify-content-between">
                <h4>Phiên bản sách</h4>
                <div>
                    <a class="btn btn-primary mr-3  " @click="exportPDF(book.isbn_code)">Xuất barcode</a>
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-book-item-modal">Thêm mới</a>

                </div>
            </div>
            <span v-if="isRequiredBookItem" class="text-danger">Cần có ít nhất 1 phiên bản sách</span>
            <div class="row mt-3 col-12">
                <table class="table text-nowrap table-bordered" name="bookItems">
                    <thead>
                        <th>
                            Edition
                        </th>
                        <th>
                            Mã sách
                        </th>
                        <th>
                            Ngôn ngữ
                        </th>
                        <th>
                            Nhà xuất bản
                        </th>
                        <th>
                            Trạng thái
                        </th>
                        <th>
                            Barcode
                        </th>
                        <th>
                            Xem trước
                        </th>
                        <th class="text-center">Thao tác</th>
                    </thead>
                    <tbody>
                        <tr v-for="book in bookItems">
                            <td>{{book.edition}}</td>
                            <td>{{book.book_code}}</td>
                            <td>{{getNameLanguage(book.book_language_id)}}</td>
                            <td>{{getNamePubliser(book.publiser_id)}}</td>
                            <td>{{getNameStatus(book.status)}}</td>
                            <td>
                                <img v-if="book.barcode" :src="book.barcode" alt="" style="height: 50px; width: 150px;">
                            </td>
                            <td>
                                <a v-if="book.preview_url" :href="book.path_preview ?? book.preview_url" target="_blank">Preview PDF</a>
                            </td>
                            <td class="text-wrap text-center">
                                <a @click="selectBookItem(book)" data-toggle="modal" data-target="#edit-book-item-modal" class="btn btn-success ml-2"><i class="fas fa-pen"></i></a>
                                <a @click="deleteBookItem(book.book_code)" data-toggle="modal" data-target="#confirm-delete-modal" class="btn btn-danger ml-2"><i class="fas fa-trash"></i></a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3 mx-2 float-right">
                <a class="btn btn-secondary" href="/books">Trở lại</a>
                <button class="btn btn-primary ml-2" type="submit">Lưu</button>

            </div>
        </div>
    </Form>
</div>
<preloader :loading="loading"></preloader>
<add-book-item-modal :option-publiser="optionPubliser" :option-book-language="optionBookLanguage" @add-item="addBookItem" :book-items="bookItems"></add-book-item-modal>
<edit-book-item-modal :option-publiser="optionPubliser" :option-book-language="optionBookLanguage" @update-item="updateBookItem" :book-items="bookItems" :bookItem="currentBookItem" :is-modal-open="openEditModal" @close-modal="openEditModal = false"></edit-book-item-modal>
</template>

<script>
import AddBookItemModal from '../Modal/AddBookItemModal.vue';
import EditBookItemModal from '../Modal/EditBookItemModal.vue';
import constants from '../../../../constants';
import { Field, Form, ErrorMessage, defineRule } from 'vee-validate';
import * as Yup from 'yup';
const schema = Yup.object().shape({
    isbn: Yup.number()
        .typeError('Mã ISBN phải là số')
        .required('Vui lòng nhập Mã ISBN'),
    author: Yup.string()
        .required('Vui lòng chọn tác giả'),
    genre: Yup.string()
        .required('Vui lòng chọn thể loại'),
    bookName: Yup.string()
        .required('Vui lòng nhập tên sách'),
    description: Yup.string()
        .required('Vui lòng nhập mô tả'),
});

export default {
    props: ['optionGenres', 'optionAuthor', 'optionBookLanguage', 'optionPubliser', 'id'],
    components: {
        Field,
        Form,
        ErrorMessage,
        AddBookItemModal,
        EditBookItemModal
    },
    data() {
        return {
            author: this.optionAuthor,
            // bookItems: [],
            isRequiredBookItem: false,
            avatar: '',
            preImage: '',
            loading: false,
            book: [],
            bookItems: [],
            currentBookItem: [],
            openEditModal: false,
            deletedBookItem: [],
        }
    },
    methods: {
        async loadItems() {
            this.loading = true;
            const response = await axios.get(`/books/api/get-book?id=${this.id}`);
            console.log(response, 'res');
            this.book = response.data.data.book;
            this.preImage = this.book.image;
            this.bookItems = response.data.data.bookItem;
            this.loading = false;
        },

        async exportPDF(isbnCode) {
            this.loading = true;
            const response = await axios.get(`/books/api/barcode-pdf/${isbnCode}`, {
                responseType: 'blob'
            });
            const blob = new Blob([response.data], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });

            const link = document.createElement('a');
            link.href = window.URL.createObjectURL(blob);
            link.download = 'Barcode.pdf';

            document.body.appendChild(link);
            link.click();

            console.log(response, 'response');
            this.loading = false;
        },
        async addBook(values) {
            if (this.bookItems == 0) {
                this.isRequiredBookItem = true;
                return
            }

            let formData = new FormData();
            formData.append('isbn', values.isbn);
            formData.append('author', values.author);
            formData.append('genre', values.genre);
            formData.append('bookName', values.bookName);
            formData.append('description', values.description);
            formData.append('id', this.id);
            formData.append('avatar', this.avatar); // Append avatar file
            // formData.append('bookItems', JSON.stringify(this.bookItems));
            formData.append('deletedItems', JSON.stringify(this.deletedBookItem));
            this.bookItems.forEach((item, index) => {
                formData.append(`book_items[${index}][book_code]`, item.book_code);
                formData.append(`book_items[${index}][book_language_id]`, item.book_language_id);
                formData.append(`book_items[${index}][publiser_id]`, item.publiser_id);
                formData.append(`book_items[${index}][status]`, item.status);
                formData.append(`book_items[${index}][price]`, item.price);
                formData.append(`book_items[${index}][edition]`, item.edition);
                formData.append(`book_items[${index}][location]`, item.location);
                formData.append(`book_items[${index}][id]`, item.id ?? '');

                formData.append(`book_items[${index}][preview_url]`, (item.id && !item.preview_url) ? '' : (item.preview_url ?? ''));
                if (item.preview_url) {
                }
            });
            const response = await axios.post('/books/api/update-book', formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });

            toastr[response.data.status](response.data.msg);
            console.log(response);
            if (response.data.status == 'success') {
                window.location.href = '/books'
            }
        },

        uploadImage(e) {
            const image = e.target.files[0];
            const reader = new FileReader();
            reader.readAsDataURL(image);
            reader.onload = e => {
                this.previewImage = e.target.result;
                console.log(this.previewImage);
                $('#avatarPreview').attr('src', e.target.result);
                this.avatar = image;
            };
        },
        resetImage() {
            $('#avatarPreview').attr('src', this.preImage);
            this.avatar = '';
            $('#avatar').val('');

        },
        addBookItem(book) {
            console.log('book', book);

            this.bookItems.push(book);
            this.isRequiredBookItem = false;
        },
        updateBookItem(bookItem, id) {
            console.log(bookItem, id, 'book item');

            this.bookItems.map(item => {
                if (item.id == id) {
                    Object.assign(item, bookItem);
                }
            })
        },
        deleteBookItem(book_code) {
            this.bookItems = this.bookItems.filter(item => item.book_code != book_code);
            this.deletedBookItem.push(book_code);
        },
        getNamePubliser(id) {
            const publiser = this.optionPubliser.find(publiser => publiser.id == id);
            return publiser ? publiser.name : '';
        },
        getNameLanguage(id) {
            const language = this.optionBookLanguage.find(language => language.id == id);

            return language ? language.language_name : '';
        },

        getNameStatus(status) {
            // const language = this.optionBookLanguage.find(language => language.id == id);
            // console.log(this.optionBookLanguage);
            return constants.STATUS_BOOK_ITEM[status];
        },
        selectBookItem(bookItem) {
            this.openEditModal = true;
            this.currentBookItem = bookItem;
        }
    },
    computed: {
        schema() {
            return schema;
        },
    },
    watch() { },
    created() {
        this.loadItems();
    },
}
</script>

<style lang="">

</style>
