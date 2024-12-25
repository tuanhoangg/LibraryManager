@extends('layouts.app')

@push('styles')
@endpush

@section('content')
    <div class="container-fluid">
        <div class="col-md-12">
            <book-list-table></book-list-table>
        </div>
    </div>
@endsection


@push('scripts')
@endpush
