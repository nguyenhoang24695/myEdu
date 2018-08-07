@extends('backend.layouts.master')

@section ('title', 'Toàn bộ tài khoản')

@section('page-header')
  <h1>
    Tài khoản tiền
  </h1>
@endsection

@section ('breadcrumbs')
  <li><a href="{!!route('backend.dashboard')!!}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
  <li><a href="{!!route('backend.wallet.index')!!}"><i class="fa fa-dashboard"></i> Quản lý tài khoản</a></li>
  <li class="active">Check giao dịch cho tài khoản</li>
@stop

@section('content')

  <div class="box">
    <div class="box-header">
      <h4>Danh sách transaction với người dùng: {{$user->name or $user->full_name}}</h4>
    </div>
    <div class="box-body">
      {!! Form::open(['name' => 'wallet_filter_form', 'method' => "get"]) !!}
      <div class="col-xs-6 col-md-3">
        <input class="form-control" name="start_date" value="{{\Request::query('start_date')}}" placeholder="Ngày bắt đầu" />
      </div>
      <div class="col-xs-6 col-md-3">
        <input class="form-control" name="end_date" value="{{\Request::query('end_date')}}" placeholder="Ngày kết thúc" />
      </div>
      <div class="col-xs-6 col-md-3">
        <input class="btn btn-primary" type="submit" value="Kiểm tra" />
      </div>
      {!! Form::close() !!}

      <table class="table table-bordered" style="margin-top: 10px;">
       <thead>
       <tr>
         <th>
           Date
         </th>
         <th>
           Before By Count
         </th>
         <th>
           Before By Previous
         </th>
         <th>
           Amount
         </th>
         <th>
           After
         </th>
       </tr>
       </thead>
      @if($transactions)
        <?php $before_by_pre = 0; ?>
        <?php $after = 0; ?>
        @foreach($transactions as $transaction)
          <?php
            if($transaction->from_acc == $user->id){
              $before_by_count = $transaction->from_acc_remain + $transaction->amount;
            }else{
              $before_by_count = $transaction->to_acc_remain - $transaction->amount;
            }
            if($before_by_pre == 0){
              $before_by_pre = $before_by_count;
            }else{
              $before_by_pre = $after;
            }

            if($transaction->from_acc == $user->id){
              $after = $transaction->from_acc_remain;
              $amount = -$transaction->amount;
            }else{
              $after = $transaction->to_acc_remain;
              $amount = $transaction->amount;
            }

            $class = $before_by_pre == $before_by_count ? "text-info" : "text-danger";

            ?>
          <tr class="{{$class}}">
            <td>
              {{$transaction->created_at}}
            </td>
            <td>
              {{$before_by_count}}
            </td>
            <td>
              {{$before_by_pre}}
            </td>
            <td>
              {{$amount}}
            </td>
            <td>
              {{$after}}
            </td>
          </tr>
          @endforeach

        @endif
      </table>
    </div>
  </div>
@endsection