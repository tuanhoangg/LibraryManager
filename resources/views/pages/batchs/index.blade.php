@extends('layouts.app')

@push('styles')
    <style>
        .loading-spinner {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 9999;
        }
    </style>
@endpush

@section('content')
    @php
        use App\Models\BorrowHistory;
        use App\Models\Roles;

    @endphp
    <div class="loading-spinner">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <div class="container-fluid">
        <div class="col-md-12">

            <button id="updateLateReturnBtn" class="btn btn-primary mt-3">Cập nhật trả muộn</button>
            <br>
            <button id="updateMemberBtn" class="btn btn-primary mt-3">Cập nhật người dùng</button>

        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            $('#updateLateReturnBtn').click(async function() {
                $('.loading-spinner').show();

                try {
                    const response = await axios.post('/update-late-return');
                    toastr.success('Cập nhật trả muộn thành công');
                } catch (error) {
                    console.error(error);
                    toastr.error('Có lỗi xảy ra');
                } finally {
                    $('.loading-spinner').hide();
                }
            });

            $('#updateMemberBtn').click(async function() {
                $('.loading-spinner').show();

                try {
                    const response = await axios.post('/update-member');
                    toastr.success('Cập nhật người dùng thành công');
                } catch (error) {
                    console.error(error);
                    toastr.error('Có lỗi xảy ra');
                } finally {
                    $('.loading-spinner').hide();
                }
            });
        });
    </script>
@endpush
