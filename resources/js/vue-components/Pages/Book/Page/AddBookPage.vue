<template lang="">
   <div class="card">
        <Form @submit="addBook" :validation-schema="schema" >
            <div class="card-body" style="height: fit-content">
                <div class="row">
                    <div class="col-md-8">
                        <h3 class="mb-2">Ảnh đại diện</h3>
                        <hr></hr>
                        <div class="row">
                            <div class="form-group col-6">
                            <label>Mã ISBN</label>
                            <Field type="number" name="isbn" class="form-control" />
                            <ErrorMessage name="isbn" class="text-danger" />
                            </div>
                            <div class="form-group col-6">
                            <div class="form-group">
                                <label>Tác giả</label>
                                <Field as="select" name="author" class="custom-select">
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
                                <Field as="select" name="genre" class="custom-select">
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
                            <Field type="text" name="bookName" class="form-control" />
                            <ErrorMessage name="bookName" class="text-danger" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-12">
                            <label>Mô tả</label>
                            <Field as="textarea" name="description" class="form-control"></Field>
                            <ErrorMessage name="description" class="text-danger" />
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                                <h3 class="mb-2">Ảnh đại diện</h3>
                        <hr></hr>
    
                                <div class="form-group">
                                    <label for="">
                                        <img id="avatarPreview" class="img-circle elevation-2"
                                            src="https://via.placeholder.com/150" alt="Avatar Preview"
                                            style="height:150px; width: 150px;">
                              
                                            <Field type="file" name="avatar" v-slot="{ handleChange, handleBlur }" ref="avatar">
                                                <input type="file" class="form-control" id="avatar" name="avatar"
                                                accept="image/*"  @change="uploadImage">
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
                    <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#add-book-item-modal">Thêm mới</a>
                </div>
                <span v-if="isRequiredBookItem"  class="text-danger">Cần có ít nhất 1 phiên bản sách</span>
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
                                <td>
                                    <a v-if="book.preview_url" :href="book.path_preview ?? book.preview_url" target="_blank">Preview PDF</a>
                                </td>
                                <td class="text-wrap text-center">
                                    <a @click="deleteBookItem(book.book_code)" data-toggle="modal" data-target="#confirm-delete-modal"  class="btn btn-danger ml-2"
                                        ><i class="fas fa-trash"></i
                                    ></a>                            
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
</template>
<script>
import AddBookItemModal from '../Modal/AddBookItemModal.vue';
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
    // avatar: Yup.mixed()
    //     // .required('Vui lòng chọn ảnh đại diện')
    //     .test('fileSize', 'Vui lòng chọn ảnh đại diện', (value) => {
    //         return value instanceof File;
    //     }),
});

export default {
    props: ['optionGenres', 'optionAuthor', 'optionBookLanguage', 'optionPubliser'],
    components: {
        Field,
        Form,
        ErrorMessage,
        AddBookItemModal,
    },
    data() {
        return {
            author: this.optionAuthor,
            bookItems: [],
            isRequiredBookItem: false,
            avatar: {},
        }
    },
    methods: {
        async addBook(values) {
            if (this.bookItems == 0) {
                this.isRequiredBookItem = true;
                return
            }

            //          if (Object.keys(this.avatar).length == 0) {
            //            toastr.error("Vui lòng chọn ảnh");
            //          return
            //    }
            let formData = new FormData();
            formData.append('isbn', values.isbn);
            formData.append('author', values.author);
            formData.append('genre', values.genre);
            formData.append('bookName', values.bookName);
            formData.append('description', values.description ?? '');
            formData.append('avatar', this.avatar);

            this.bookItems.forEach((item, index) => {
                formData.append(`book_items[${index}][book_code]`, item.book_code);
                formData.append(`book_items[${index}][book_language_id]`, item.book_language_id);
                formData.append(`book_items[${index}][publiser_id]`, item.publiser_id);
                formData.append(`book_items[${index}][status]`, item.status);
                formData.append(`book_items[${index}][price]`, item.price);
                formData.append(`book_items[${index}][edition]`, item.edition);
                formData.append(`book_items[${index}][location]`, item.location);

                if (item.preview_url) {
                    formData.append(`book_items[${index}][preview_url]`, item.preview_url);
                }
            });
            ;

            const response = await axios.post('/books/api/add-book', formData, {
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

                $('#avatarPreview').attr('src', e.target.result);
                this.avatar = image;
            };
        },
        resetImage() {
            $('#avatarPreview').attr('src', 'https://via.placeholder.com/150');
            this.avatar = '';
            $('#avatar').val('');
        },
        addBookItem(book) {
            console.log('book', book);

            this.bookItems.push(book);
            this.isRequiredBookItem = false;
        },
        deleteBookItem(book_code) {
            this.bookItems = this.bookItems.filter(item => item.book_code != book_code);
        },
        getNamePubliser(id) {
            const publiser = this.optionPubliser.find(publiser => publiser.id == id);
            return publiser ? publiser.name : '';
        },
        getNameLanguage(id) {
            const language = this.optionBookLanguage.find(language => language.id == id);
            // console.log(this.optionBookLanguage);
            return language ? language.language_name : '';
        }
    },
    computed: {
        schema() {
            return schema;
        },
    },
    watch() {
    }
}
</script>
<style lang="">

</style>