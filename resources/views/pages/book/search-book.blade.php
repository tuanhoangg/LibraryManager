@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Tìm kiếm sách</h1>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        <div class="col-md-12">
            <search-book-page :option-author="{{ $optionAuthor }}" :option-genres="{{ $optionGenres }}"
                :option-publiser="{{ $optionPubliser }}" :option-book-language="{{ $optionBookLanguage }}"
                :user-id="{{ auth()->user()->id }}">></search-book-page>
        </div>
    </div>
@endsection


@push('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#reservationdate').datetimepicker()
            $('#return_date').datetimepicker()
        });
        $('#authorSelect').on('change', function() {
            $(this).val($(this).val()).trigger('change');
        });
        $('#bookSelect').on('change', function() {
            $(this).val($(this).val()).trigger('change');
        });
    </script>
@endpush
