@extends ('backend.layouts.master')

@section ('title', 'Report tài chính tài khoản doanh thu toàn hệ thống')

@section('after-scripts-end')
{{--{!! HTML::script('backend/js/searchindex.js') !!}--}}
@endsection

@section('page-header')
<h1>
    Danh sách hóa đơn
</h1>
@endsection

@section ('breadcrumbs')
<li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
<li class="active">Đơn hàng</li>
@stop

@section('content')
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Tìm kiếm</h3>
    </div>
    <div class="box-body">
        <div class="row">
            {!! Form::open(['method' => 'get']) !!}
            <div class="col-xs-6 col-sm-4 col-md-3">
                <input type="text" class="form-control" name="code"
                       placeholder="Mã đơn hàng"
                       value="{{\Request::query('code')}}"/>
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3">
                {!! Form::select('status',
                                [
                                    '' => 'Tất cả trạng thái',
                                    \App\Core\Money\Utils\Constant::PENDING_ORDER => 'Chờ xử lý',
                                    \App\Core\Money\Utils\Constant::APPROVED_ORDER => 'Đã xử lý',
                                    \App\Core\Money\Utils\Constant::REJECTED_ORDER => 'Đã bị hủy',
                                ],
                                \Request::query('status'),
                                ['class' => 'form-control']) !!}
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3">
                {!! Form::select('payment_item',
                                [
                                    '' => 'Tất cả loại giao dịch',
                                    'MobileCard' => 'Thẻ cào',
                                    'Course' => 'Khóa học',
                                    'BankPayment' => 'Ngân hàng'
                                ],
                                \Request::query('payment_item'),
                                ['class' => 'form-control']) !!}
            </div>
            <div class="col-xs-6 col-sm-4 col-md-3">
                <button class="btn btn-default">Tìm</button>
            </div>
            {!! Form::close() !!}
        </div>

    </div>
</div>
<div class="box">
    <div class="box-header with-border">
        <h3 class="box-title">Danh sách đơn hàng</h3>
    </div>
    <div class="box-body table-responsive no-padding">
        @include('backend.money.partial.order_table', ['orders' => $orders])
        <div class="row text-center">
            {!! $orders->appends(\Request::query())->render() !!}
        </div>
    </div>
</div>
@endsection