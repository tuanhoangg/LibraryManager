<template lang="">
<div class="modal fade" id="confirm-delete-modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa ngôn ngữ</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tên ngôn ngữ: {{bookLanguage.language_name}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Trở lại</button>
                <button type="button" class="btn btn-danger" @click="deleteBookLanguage">Xóa</button>
            </div>
        </div>

    </div>

</div>

</template>
<script>
import axios from 'axios';
export default {
    props: {
        bookLanguage: Array,
        loadItems: Function,
    },
    methods: {
        async deleteBookLanguage() {
            const response = await axios.delete('/book-language/api/delete-book-language/' + this.bookLanguage.id);


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