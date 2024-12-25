@extends('layouts.app')


@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Thêm người dùng</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <div class="col-md-12">
            <form action="{{ route('user.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body row" style="height: fit-content">
                        <div class="col-md-6">
                            <div class="form-group mb-0">
                                <label for="inputName">Họ và tên</label>
                                <input type="text" id="inputName" class="form-control" name="name"
                                    value="{{ old('name') }}">
                            </div>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            {{-- <div class="row"> --}}
                            <div class="form-group mb-0 mt-4">
                                <label for="inputProjectLeader">Mật khẩu</label>
                                <input type="password" id="inputProjectLeader" class="form-control" name="password"
                                    value="{{ old('password') }}">
                            </div>
                            @error('password')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="form-group mb-0 mt-4">
                                <label for="inputClientCompany">Nhập lại mật khẩu</label>
                                <input type="password" id="inputClientCompany" class="form-control"
                                    name="password_confirmation" value="{{ old('password_confirmation') }}">
                            </div>
                            @error('password_confirmation')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            {{-- </div> --}}
                            <div class="form-group mb-0 mt-4">
                                <label for="inputName">Email</label>
                                <input type="email" id="inputName" class="form-control" name="email"
                                    value="{{ old('email') }}">
                            </div>
                            @error('email')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            {{-- <div class="row"> --}}
                            <div class="form-group mb-0 mt-4">
                                <label for="inputProjectLeader">Số điện thoại</label>
                                <input type="text" id="inputProjectLeader" class="form-control" name="phone"
                                    value="{{ old('phone') }}">
                            </div>
                            @error('phone')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            <div class="form-group mb-0 mt-4">
                                <label for="inputClientCompany">Địa chỉ</label>
                                <input type="text" id="inputClientCompany" class="form-control" name="address"
                                    value="{{ old('address') }}">
                            </div>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            {{-- </div> --}}
                            <div class="form-group mb-0 mt-4">
                                <label for="inputStatus">Chức vụ</label>
                                <select id="inputStatus" class="form-control custom-select" name="role_id"
                                    value="{{ old('role_id') }}">
                                    <option value="">Chọn role</option>
                                    @foreach ($roles as $role)
                                        <option @selected(old('role_id') === $role->id) value="{{ $role->id }}">
                                            {{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror

                        </div>
                        <div class="col-md-6 text-center">
                            <h3 class="mb-4">Ảnh đại diện</h3>
                            <div class="form-group">
                                <label for="">
                                    <img id="avatarPreview" class="img-circle elevation-2"
                                        src="{{ 'https://via.placeholder.com/150' }}" alt="Avatar Preview"
                                        style="height:150px; width: 150px;">
                                    <input type="file" class="form-control" id="avatar" name="avatar"
                                        accept="image/*" value="{{ old('image') }}" hidden>
                                </label>
                            </div>
                            <button type="button" class="btn btn-secondary" id="resetBtn">Reset</button>

                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer clearfix float-right">
                        <button type="submit" class="btn btn-primary">Lưu</button>
                        <a class="btn btn-secondary ml-2" href="{{ route('user.list') }}">Thoát</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
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

            $("#avatarPreview").click(function() {
                $("input[id='avatar']").click();
            });

            $('#resetBtn').click(function() {
                $('#avatarPreview').attr('src', initialAvatar);
                $('#avatar').val('');
            });
        });
    </script>
@endPushOnce
