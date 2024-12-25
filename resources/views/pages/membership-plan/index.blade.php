@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách gói hội viên</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="#" data-toggle="modal" data-target="#add-plan-modal" type="button"
                            class="btn btn-primary">
                            Thêm gói hội viên
                        </a>

                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <div class="col-md-12">
            <membership-plan-list-table></membership-plan-list-table>
        </div>
    </div>
@endsection


@push('scripts')
@endpush
