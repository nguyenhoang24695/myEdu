@extends('frontend.layouts.default')

@section('content')
    <div class="block-active">
        <div class="color-overlay"></div>
        <div class="container-active ">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-6 col-md-offset-3 success-form text-center">
                        <p class="cropped">
                            <i class="fa fa-check-circle" aria-hidden="true"></i>
                        </p>
                        <p class="text-ok">Kích hoạt khóa học thành công</p>
                        <p class="redirect"><a href="{{ $course->get_default_studying_link() }}">Vào học ngay</a> hoặc quay lại <a href="{{ url('dashboard') }}">Trang cá nhân</a></p>
                        <p class="help-block">Hỗ trợ kích hoạt khóa học miễn phí 24/7. <span style="color: #1d72a3"><i class="fa fa-phone"></i> <b>04 6293 9998</b></span></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection