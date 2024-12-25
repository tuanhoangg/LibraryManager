@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="content-header">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Chỉnh sửa sách</h1>
                    </div><!-- /.col -->

                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <div class="container-fluid">
            <div class="col-md-12">
                <edit-book-page :option-author="{{ $optionAuthor }}" :option-genres="{{ $optionGenres }}"
                    :id="{{ request()->id }}" :option-publiser="{{ $optionPubliser }}" :book-items="{{ $bookItems }}"
                    :book="{{ $book }}" :option-book-language="{{ $optionBookLanguage }}"></edit-book-page>
            </div>
        </div>
    </div>
@endsection
@pushOnce('scripts')
@endPushOnce
