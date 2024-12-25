@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="content-header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Thêm mới sách</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">
            <div class="col-md-12">
                <add-book-page :option-author="{{ $optionAuthor }}" :option-genres="{{ $optionGenres }}"
                    :option-publiser="{{ $optionPubliser }}"
                    :option-book-language="{{ $optionBookLanguage }}"></add-book-page>
            </div>
        </div>
    </div>
@endsection
@pushOnce('scripts')
@endPushOnce
