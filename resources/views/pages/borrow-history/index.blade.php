@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    @php
        use App\Models\BorrowHistory;
        use App\Models\Roles;

    @endphp

    @if (auth()->user()->role_id == 3)
        <borrow-history-list-page option-status="{{ json_encode(BorrowHistory::OPTION_STATUS) }}"
            list-status="{{ json_encode(BorrowHistory::STATUS_LIST) }}"></borrow-history-list-page>
    @else
        <borrow-history-manager-page option-status="{{ json_encode(BorrowHistory::OPTION_STATUS) }}"
            list-status="{{ json_encode(BorrowHistory::STATUS_LIST) }}"></borrow-history-manager-page>
    @endif
    {{-- </div> --}}
@endsection


@push('scripts')
    <script>
        $('#memberSelect').on('change', function() {
            $(this).val($(this).val()).trigger('change');
        });
    </script>
@endpush
