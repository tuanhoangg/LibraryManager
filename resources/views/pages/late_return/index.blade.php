@extends('layouts.app')

@push('styles')
@endpush

@section('content')
@php
use App\Models\LateReturn;
use App\Models\Roles;
@endphp
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Danh sách trả muộn</h1>
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
    <late-return-page list-status="{{ json_encode(LateReturn::STATUS_LIST) }}"></late-return-page>
    @else
    <admin-late-return-page list-status="{{ json_encode(LateReturn::STATUS_LIST) }}"></admin-late-return-page>
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