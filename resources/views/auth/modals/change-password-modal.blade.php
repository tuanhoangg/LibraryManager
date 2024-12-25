<div class="modal fade" id="modal-change-password">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Đổi mật khẩu</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('change.password') }}" method='POST'>
                    @csrf
                    <input type="text" name="id" hidden value="{{ auth()->user()->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Mật khẩu hiện tại</label>
                            <input type="current-password" class="form-control" id="exampleInputEmail1"
                                placeholder="Mật khẩu hiện tại" name="name">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Mật khẩu mới</label>
                            <input type="new-password" class="form-control" id="exampleInputEmail1"
                                placeholder="Mật khẩu mới" name="email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nhập lại mật khẩu mới</label>
                            <input type="retype-new-password" class="form-control" id="exampleInputEmail1"
                                placeholder="Nhập lại mật khẩu mới" name="email">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                <button type="button" class="btn btn-primary">Lưu thay đổi</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
