@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    @php
        use App\Models\Fines;
        use App\Models\Roles;
        use App\Models\BorrowHistory;
    @endphp
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Danh sách phạt</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <div class="float-sm-right">
                        <div class="btn-group dropleft">
                        </div>

                    </div>
                </div>
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>

    <div class="container-fluid">
        @if (auth()->user()->role_id == Roles::ROLE_USER)
            <fines-page list-status="{{ json_encode(Fines::STATUS_LIST) }}" filter-status = "{{ json_encode(Fines::FILTER_STATUS) }}"
                option-status="{{ json_encode(BorrowHistory::OPTION_STATUS) }}"></fines-page>
        @else
            <admin-fines-page list-status="{{ json_encode(Fines::STATUS_LIST) }}" filter-status = "{{ json_encode(Fines::FILTER_STATUS) }}"
                option-status="{{ json_encode(BorrowHistory::OPTION_STATUS) }}"></admin-fines-page>
        @endif
    </div>
@endsection


@push('scripts')
    <script>
        $('#memberSelect').on('change', function() {
            $(this).val($(this).val()).trigger('change');
        });
    </script>
@endpush
