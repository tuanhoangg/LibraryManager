@extends('layouts.app')
@push('styles')
@endPush
@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thông tin cá nhân</h1>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <form action="{{ route('update.profile') }}" method='POST' enctype="multipart/form-data">
        <div class="container-fluid mx-2 row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h3>Thông tin cá nhân</h3>
                    </div>
                    @csrf
                    <input type="text" name="id" hidden value="{{ auth()->user()->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Họ và tên</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Họ và tên"
                                name="name" value="{{ old('name', auth()->user()->name) }}">
                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for="exampleInputEmail1">Email</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" placeholder="Email"
                                name="email" value="{{ old('email', auth()->user()->email) }}">
                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for="exampleInputEmail1">Số điện thoại</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Số điện thoại"
                                name="phone" value="{{ old('phone', auth()->user()->phone) }}">
                        </div>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group">
                            <label for="exampleInputEmail1">Địa chỉ</label>
                            <input type="text" class="form-control" id="exampleInputEmail1" placeholder="Địa chỉ"
                                name="address" value="{{ old('address', auth()->user()->address) }}">
                        </div>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <input type="password" class="form-control" id="exampleInputEmail1" placeholder="Mật khẩu" hidden
                            name="password" value={{ auth()->user()->password }}>
                        <input type="password" class="form-control" id="exampleInputEmail1" placeholder="Mật khẩu" hidden
                            name="role_id" value={{ auth()->user()->role_id }}>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card ml-5">
                    <div class="card-header">
                        <h3>Ảnh đại diện</h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="form-group">
                            <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*"
                                value="{{ old('image') }}">
                        </div>
                        <div class="form-group">
                            <img id="avatarPreview" class="img-circle elevation-2"
                                src="{{ auth()->user()->image ?? 'https://via.placeholder.com/150' }}" alt="Avatar Preview"
                                style="height:150px; width: 150px;">
                        </div>

                    </div>
                    <div class="card-footer text-center">

                        <button type="button" class="btn btn-secondary" id="resetBtn">Reset</button>
                    </div>

                </div>
            </div>
        </div>
    </form>
@endsection

@pushOnce('scripts')
    <script>
        $(document).ready(function() {
            let initialAvatar = $('#avatarPreview').attr('src');

            $('#avatar').change(function() {
                let reader = new FileReader();
                reader.onload = function(e) {
                    $('#avatarPreview').attr('src', e.target.result);
                }
                reader.readAsDataURL(this.files[0]);
            });

            $('#resetBtn').click(function() {
                $('#avatarPreview').attr('src', initialAvatar);
                $('#avatar').val('');
            });
        });
    </script>
@endPushOnce
