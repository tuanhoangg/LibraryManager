@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách ngôn ngữ</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <a href="#" data-toggle="modal" data-target="#add-book-language-modal" type="button"
                            class="btn btn-primary">
                            Thêm ngôn ngữ
                        </a>

                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <div class="col-md-12">
            <book-language-list-table></book-language-list-table>
        </div>
    </div>
@endsection


@push('scripts')
@endpush
