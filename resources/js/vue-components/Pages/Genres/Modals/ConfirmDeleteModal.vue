<template lang="">
<div class="modal fade" id="confirm-delete-modal" style="display: none;" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Xác nhận xóa thể loại</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Tên thể loại: {{genres.name}}</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Trở lại</button>
                <button type="button" class="btn btn-danger" @click="deleteGenres">Xóa</button>
            </div>
        </div>

    </div>

</div>

</template>
<script>
import axios from 'axios';
export default {
    props: {
        genres: Array,
        loadItems: Function,
    },
    methods: {
        async deleteGenres() {
            const response = await axios.delete('/genres/api/delete-genres/' + this.genres.id);


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