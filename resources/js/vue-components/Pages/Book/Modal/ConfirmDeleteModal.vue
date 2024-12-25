<template lang="">
<div class="modal fade" id="confirm-delete-modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tên sách: {{book.title}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Trở lại</button>
                <button type="button" class="btn btn-danger" @click="deleteBook">Xóa</button>
            </div>
        </div>

    </div>

</div>

</template>
<script>
import axios from 'axios';
export default {
    props: {
        book: Array,
        loadItems: Function,
    },
    methods: {
        async deleteBook() {
            const response = await axios.delete('/books/api/delete-book/' + this.book.id);


            toastr[response.data.status](response.data.msg);

            if (response.data.status == 'success') {
                $('#confirm-delete-modal').modal('hide');
                this.$emit('loadItems');
            }
        }
    }
}
</script>
<style lang="">

</style>