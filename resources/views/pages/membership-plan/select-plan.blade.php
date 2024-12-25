@extends('layouts.app')

@push('styles')
@endpush
@php
    use App\Models\Payment;
@endphp
@section('content')
    @if (!auth()->user()->member_id)
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Danh sách gói hội viên</h1>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
    @endif

    <div class="container-fluid">
        <div class="col-md-12">
            <div class="row d-flex justify-content-center">
                @csrf
                @if (!auth()->user()->is_member && !auth()->user()->member_code)
                    @foreach ($plans as $plan)
                        <div class="col-md-4 mt-4">
                            <div class="card mb-4 shadow-sm d-flex flex-column h-100">
                                <div class="card-header">
                                    <h4 class="my-0 font-weight-normal text-center">{{ $plan->name }}</h4>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h1 class="card-title pricing-card-title text-center">{{ $plan->price }} VNĐ/tháng
                                    </h1>
                                    <ul class="list-unstyled mt-3 mb-4 text-center">
                                        <li>{{ $plan->description }}</li>
                                        <li>Số sách có thể mượn: {{ $plan->limit_book }}</li>
                                        <li>Giảm phạt khi trả sách muộn: {{ $plan->late_fee_discount }}%</li>
                                    </ul>
                                    <form action="{{ route('membership.plan.register') }}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="payment_method"
                                            value="{{ Payment::BANK_METHOD }}">
                                        <input type="text" hidden name="plan_id" value="{{ $plan->id }}">
                                        <input type="text" hidden name="amount" value="{{ $plan->price }}">
                                        <input type="text" hidden name="user_id" value="{{ auth()->user()->id }}">
                                        <button type="submit" class="btn btn-lg btn-block btn-primary mt-auto">Đăng ký
                                            ngay</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-4 mt-4">
                        {{-- @dd($member) --}}
                        <div class="card mb-4 shadow-sm d-flex flex-column h-100">
                            <div class="card-header">
                                <h3>Thông tin Hội viên
                                    @if (!auth()->user()->is_member)
                                        <span class="text-danger">(Hết hạn)</span>
                                    @endif
                                </h3>
                            </div>
                            <div class="card-body">
                                <p><strong>Tên: {{ auth()->user()->name }}</strong> </p>
                                <p><strong>Email: {{ auth()->user()->email }}</strong> </p>
                                <p><strong>Mã hội viên: {{ auth()->user()->member_code }}</strong> </p>
                                <p>
                                    <strong>Trạng thái:
                                        @if (auth()->user()->is_member)
                                            <span class="text-success">Hội viên</span>
                                        @else
                                            <span class="text-danger">Hết hạn</span>
                                        @endif
                                    </strong>
                                </p>
                                <p><strong>Ngày hết hạn: {{ $member->due_date }}</strong> </p>
                                <h4>Thông tin gói hội viên</h4>
                                <p><strong>Tên gói: {{ $member->membership_plan->name }}</strong> </p>
                                <p><strong>Số lượng sách tối đa có thể mượn:
                                        {{ $member->membership_plan->limit_book }}</strong> </p>
                                <p><strong>Số lượng sách có thể mượn: {{ $member->books_can_borrow }}</strong> </p>
                                @if (!auth()->user()->is_member && auth()->user()->member_code)
                                    <form action="{{ route('membership.plan.register') }}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="payment_method"
                                            value="{{ Payment::BANK_METHOD }}">
                                        <input type="text" hidden name="plan_id"
                                            value="{{ $member->membership_plan->id }}">
                                        <input type="text" hidden name="amount"
                                            value="{{ $member->membership_plan->price }}">
                                        <input type="text" hidden name="user_id" value="{{ auth()->user()->id }}">
                                        <button type="submit" class="btn btn-lg btn-block btn-primary mt-auto">Gia hạn gói
                                            hội viên</button>
                                        <a class="btn btn-lg btn-block  btn-primary mt-2" data-toggle="collapse"
                                            href="#collapseExample" role="button" aria-expanded="false"
                                            aria-controls="collapseExample">
                                            Các gói hội viên
                                        </a>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="collapse" id="collapseExample">
                <div class="row">
                    @foreach ($plans as $plan)
                        <div class="col-md-4 mt-4">
                            <div class="card mb-4 shadow-sm d-flex flex-column h-100">
                                <div class="card-header">
                                    <h4 class="my-0 font-weight-normal text-center">{{ $plan->name }}</h4>
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <h1 class="card-title pricing-card-title text-center">{{ $plan->price }} VNĐ/tháng
                                    </h1>
                                    <ul class="list-unstyled mt-3 mb-4 text-center">
                                        <li>{{ $plan->description }}</li>
                                        <li>Số sách có thể mượn: {{ $plan->limit_book }}</li>
                                        <li>Giảm phạt khi trả sách muộn: {{ $plan->late_fee_discount }}%</li>
                                    </ul>
                                    <form action="{{ route('membership.plan.register') }}" method="POST">
                                        @csrf
                                        <input type="text" hidden name="payment_method"
                                            value="{{ Payment::BANK_METHOD }}">
                                        <input type="text" hidden name="plan_id" value="{{ $plan->id }}">
                                        <input type="text" hidden name="amount" value="{{ $plan->price }}">
                                        <input type="text" hidden name="user_id" value="{{ auth()->user()->id }}">
                                        <button type="submit" class="btn btn-lg btn-block btn-primary mt-auto">Đăng ký
                                            ngay</button>
                                    </form>

                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
@endpush
