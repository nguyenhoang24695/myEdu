@extends('frontend.layouts.default')

@section('content')
    <div class="block-active">
        <div class="color-overlay"></div>
        <div class="container-active ">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 active-form text-center ">
                        <h1>Kích hoạt khóa học của bạn</h1>
                        <h3>Nhập mã mà bạn nhận được vào bên dưới</h3>
                        <div class="line-horizontal" style="width: 100px; height: 10px; margin: 0 auto; border-bottom: 2px solid #ee3c33;"></div>
                        {!! Form::open(['class' => 'form-inline', 'role' => 'form', 'method' => 'POST', 'enctype'=>'multipart/form-data']) !!}
                            <div class="form-group cod-field">
                                <input name="code" type="text" required class="form-control" placeholder="Nhập mã kích hoạt...">
                                <button class="btn btn-primary" type="submit" data-toggle="modal" data-target="#processModal">Kích họat</button>
                            </div>
                        {!! Form::close() !!}
                        <p class="help-block">Hỗ trợ kích hoạt khóa học miễn phí 24/7. <span style="color: #1d72a3"><i class="fa fa-phone"></i> <b>04 6293 9998</b></span></p>
                    </div>

                    <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 success-form text-center hide">
                        <p class="cropped">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </p>
                        <p class="text-ok">Kích hoạt khóa học thành công</p>
                        <p class="redirect"><a href="#">Vào học ngay</a> hoặc quay lại <a href="#">Trang cá nhân</a></p>
                        <p class="help-block">Hỗ trợ kích hoạt khóa học miễn phí 24/7. <span style="color: #1d72a3"><i class="fa fa-phone"></i> <b>04 6293 9998</b></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="processModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Đang xử lý yêu cầu</h4>
                </div>
                <div class="modal-body">
                    <p class="text-center loading-booking">
                        <i class="fa fa-spinner fa-pulse fa-3x fa-fw margin-bottom"></i>
                    </p>
                    <p class="text-center loading-tit">Mã kích hoạt của quý khách đang được xử lý. Vui lòng đợi trong giây lát!</p>
                </div>
            </div>
        </div>
    </div>

@endsection