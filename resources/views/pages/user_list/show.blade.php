@extends('layouts.app')


@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Chi tiết người dùng</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body row" style="height: fit-content">
                    <div class="col-md-6">
                        <div class="form-group mb-0">
                            <label for="inputName">Họ và tên</label>
                            <p class="font-weight-normal">{{ $user->name ?? '' }}</p>

                        </div>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group mb-0 mt-4">
                            <label for="inputName">Email</label>
                            <p class="font-weight-normal">{{ $user->email ?? '' }}</p>

                        </div>
                        @error('email')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group mb-0 mt-4">
                            <label for="inputProjectLeader">Số điện thoại</label>
                            <p class="font-weight-normal">{{ $user->phone ?? '' }}</p>

                        </div>
                        <div class="form-group mb-0 mt-4">
                            <label for="inputProjectLeader">Gói hội viên</label>
                            <p class="font-weight-normal">{{ $user['members']['membership_plan']['name'] ?? '' }}</p>

                        </div>
                        <div class="form-group mb-0 mt-4">
                            <label for="inputProjectLeader">Mã hội viên</label>
                            <p class="font-weight-normal">{{ $user['member_code'] ?? '' }}</p>

                        </div>
                        @error('phone')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group mb-0 mt-4">
                            <label for="inputClientCompany">Địa chỉ</label>
                            <p class="font-weight-normal">{{ $user->address ?? '' }}</p>

                        </div>
                        @error('address')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                        <div class="form-group mb-0 mt-4">
                            <label for="inputStatus">Chức vụ</label>
                            <p class="font-weight-normal">{{ $user->role->name ?? '' }}</p>

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
                                    src="{{ old('avatar', $user->image ?? Storage::url('/avatars/no_avatar.jpg')) }}"
                                    alt="Avatar Preview" style="height:350px; width: 350px;">
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*"
                                    value="{{ old('image') }}" hidden>
                            </label>
                        </div>

                    </div>
                </div>
                <div class="card-footer clearfix float-right">
                    <a class="btn btn-secondary ml-2" href="{{ route('user.list') }}">Thoát</a>
                </div>
            </div>
        </div>
    </div>
@endsection
@pushOnce('scripts')
@endPushOnce
