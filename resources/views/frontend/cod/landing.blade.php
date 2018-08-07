@extends('frontend.layouts.landing')

@section('after-scripts-end')
    <script>
        $(function(){
            $('.i-menu,.btn-bycourse').click(function(){
                $('html, body').animate({
                    scrollTop: $( $(this).attr('href') ).offset().top
                }, 500);
                return false;
            });

            $('#clock').countdown(count_down, function(event) {

                var day  = '<div class="timer">';
                        day += '<div class="timer-box">';
                            day += '<span class="timer-value">%D</span>';
                        day += '</div>';
                        day += '<div class="timer-label">';
                            day += '<span>Ngày</span>';
                        day += '</div>';
                    day += '</div>';

                var hours  = '<div class="timer">';
                        hours += '<div class="timer-box">';
                            hours += '<span class="timer-value">%H</span>';
                        hours += '</div>';
                        hours += '<div class="timer-label">';
                            hours += '<span>Giờ</span>';
                        hours += '</div>';
                    hours += '</div>';

                var minute  = '<div class="timer">';
                        minute += '<div class="timer-box">';
                            minute += '<span class="timer-value">%M</span>';
                        minute += '</div>';
                        minute += '<div class="timer-label">';
                            minute += '<span>Phút</span>';
                        minute += '</div>';
                    minute += '</div>';

                var second  = '<div class="timer">';
                        second += '<div class="timer-box">';
                            second += '<span class="timer-value">%S</span>';
                        second += '</div>';
                        second += '<div class="timer-label">';
                            second += '<span>Giây</span>';
                        second += '</div>';
                    second += '</div>';

                $(this).html(event.strftime(day+hours+minute+second));
            });

        });
    </script>
@endsection

@section('content')

<nav class="navbar navbar-default navbar-static-top navbar-landing">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">
                <img src="../frontend/img/myedu/landing/logo.png">
            </a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="#about" class="i-menu">Giới thiệu</a></li>
                <li><a href="#course_content" class="i-menu">Nội dung khóa học</a></li>
                <li><a href="#utilities" class="i-menu">Tiện ích</a></li>
                <li><a href="#faq" class="i-menu">FAQ</a></li>
                <li><a href="#cod-register" class="i-menu">Đăng ký</a></li>
            </ul>

            <ul class="nav navbar-nav navbar-right nav-hotline">
                <li class="call-line">
                    <a href="tel:0462939998" rel="nofollow"><span><i class="fa fa-phone"></i></span> 0168.234.4382</a>
                </li>
                <li>
                    <a href="#cod-register" title="Đăng ký mua khóa học" class="btn btn-primary btn-bycourse" >Đăng ký</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="box-candi-search">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="thumbnail-s">
                    <img src="../frontend/img/myedu/landing/head.png">
                </div>
            </div>
            <div class="col-md-5">
                <div class="form-search">
                    <h2>Tìm việc hiệu quả</h2>
                    <h1>Ứng tuyển thành công</h1>
                    <p>
                        <img style="max-width: 100%" src="../frontend/img/myedu/landing/head_2.png">
                    </p>
                    <a class="btn btn-primary btn-bycourse" href="#cod-register">Đăng ký</a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="box-what" id="about">
    <div class="tips-top"></div>
    <div class="tips-body">
        <div class="container" align="center">
            <iframe width="560" height="315" src="https://www.youtube.com/embed/keAarlFxE-s" frameborder="0" allowfullscreen></iframe>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="thumbnail-ball">
                        <div class="ball">
                            <p class="count">
                                <span>800.000</span>
                            </p>
                        </div>
                        <div class="caption">
                            <p>Sinh viên tốt nghiệp <br/> mỗi năm</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="thumbnail-ball">
                        <div class="ball">
                            <p class="count">
                                <span>37%</span>
                            </p>
                        </div>
                        <div class="caption">
                            <p>Sinh viên ra trường kiếm được việc</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="thumbnail-ball">
                        <div class="ball">
                            <p class="count">
                                <span>24,2%</span>
                            </p>
                        </div>
                        <div class="caption">
                            <p>Sinh viên tốt nghiệp <br/>tìm được việc sau 12 tháng</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="thumbnail-ball">
                        <div class="ball">
                            <p class="count">
                                <span>60%</span>
                            </p>
                        </div>
                        <div class="caption">
                            <p>Sinh viên ra trường <br/>làm trái ngành, trái nghề</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div style="float: left;width: 100%">
                        <div class="question">
                            <img src="../frontend/img/myedu/landing/head_5.png">
                        </div>
                        <div class="note">
                            <div class="note-left">
                                <img src="../frontend/img/myedu/landing/head_13.png">
                                <p>Nhà tuyển dụng loại ứng viên vì lý do gì</p>
                            </div>
                            <div class="note-space">
                                <img src="../frontend/img/myedu/landing/head_12.png">
                            </div>
                            <div class="note-right">
                                <img src="../frontend/img/myedu/landing/head_14.png">
                                <p>Sinh viên theo đuổi được công việc mơ ước, họ đã làm gì</p>
                            </div>
                        </div>
                    </div>
                    <h1 class="note-one" id="course_content">Một kế hoạch bài bản là giải pháp hữu hiệu nhất</h1>
                </div>
            </div>

            <div class="">
                <div class="row">
                    <div class="col-md-4">
                        <div class="thumbnail-tip">
                            <span class="count">1</span>
                            <p>
                                <span style="margin-top: 35px;">Tìm kiếm thế mạnh <br/>của bản thân</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail-tip">
                            <span class="count">2</span>
                            <p>
                                <span>Xác định nhu cầu <br/>nhà tuyển dụng</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail-tip">
                            <span class="count">3</span>
                            <p>
                                <span>Săn việc hiệu quả</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail-tip">
                            <span class="count">4</span>
                            <p>
                                <span>Kĩ thuật viết Hồ sơ xin việc (CV)</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail-tip">
                            <span class="count">5</span>
                            <p>
                                <span>Phỏng vấn thành công</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="thumbnail-tip">
                            <span class="count">6</span>
                            <p>
                                <span>Thực hành ứng tuyển</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <p class="text-center by-course"><a class="btn btn-primary btn-bycourse" href="#cod-register">Đăng ký</a></p>
                </div>
            </div>

        </div>
    </div>
    <div class="tips-bottom"></div>
</div>

<div class="combo">
    <div class="container">
        <h1>Tặng combo 6 khóa học định hướng <br> các ngành nghề có nhu cầu tuyển dụng cao nhất hiện nay</h1>
        <div class="row">

            <div class="col-md-4">
                <div class="thumbnail-combo">
                    <div class="icon">
                        <img src="../frontend/img/myedu/landing/icon_1.png">
                    </div>
                    <div class="caption">
                        <p>Xác định lộ trình <br/>nghề nghiệp của bản thân</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail-combo">
                    <div class="icon">
                        <img src="../frontend/img/myedu/landing/icon_2.png">
                    </div>
                    <div class="caption">
                        <p>Sẵn sàng với nghề <br/> kinh doanh - bán hàng</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail-combo">
                    <div class="icon">
                        <img src="../frontend/img/myedu/landing/icon_3.png">
                    </div>
                    <div class="caption">
                        <p>Thành thạo kỹ năng <br/>phân tích nghiệp vụ</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail-combo">
                    <div class="icon">
                        <img src="../frontend/img/myedu/landing/icon_4.png">
                    </div>
                    <div class="caption">
                        <p>Nền tảng kỹ năng tiếp thị trực tuyến - Marketing Online</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail-combo">
                    <div class="icon">
                        <img src="../frontend/img/myedu/landing/icon_5.png">
                    </div>
                    <div class="caption">
                        <p>Thiết kế trò chơi điện tử</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="thumbnail-combo">
                    <div class="icon">
                        <img src="../frontend/img/myedu/landing/icon_6.png">
                    </div>
                    <div class="caption">
                        <p>Kỹ năng phân tích <br/> trải nghiệm người dùng</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<div class="profile">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <p class="avatar">
                    <img style="max-width: 100%"  src="../frontend/img/myedu/landing/avatar.png">
                </p>
            </div>
            <div class="col-md-8">
                <h3>Giảng viên Th.S Phan Sơn</h3>
                <ul class="list-unstyled" style="margin-top:60px">
                    <li>
                        <span class="p-icon"><i class="fa fa-circle" aria-hidden="true"></i></span>
                        <span class="p-text">Giám đốc Tư vấn Tổ hợp phát triển HRD, Cố vấn Chủ tịch HĐQT Công ty cổ phần đầu tư NETREAL, giảng dạy tại Tổng công ty hàng không Việt Nam ACV, Tổng công ty Mobifone, Tập đoàn Viettel ,Công ty cổ phần bao bì APP, Tổng công ty điện tử Thành Long</span>
                    </li>
                    <li>
                        <span class="p-icon"><i class="fa fa-circle" aria-hidden="true"></i></span>
                        <span class="p-text">11 năm trải nghiệm về đào tạo và quản trị nguồn nhân lực, tư vấn tại các Doanh nghiệp có quy mô lớn</span>
                    </li>
                    <li>
                        <span class="p-icon"><i class="fa fa-circle" aria-hidden="true"></i></span>
                        <span class="p-text">Giám khảo của thi Ứng viên tài năng và là diễn giả của nhiều chương trình tập huấn dành cho sinh viên, cán bộ Đoàn</span>
                    </li>
                    <li>
                        <span class="p-icon"><i class="fa fa-circle" aria-hidden="true"></i></span>
                        <span class="p-text">Giảng viên cao cấp tại CDMS- Hội liên hiệp khoa học kỹ thuật Việt Nam, Eduviet, KNV Group, SeiKo,...</span>
                    </li>
                    <li>
                        <span class="p-icon"><i class="fa fa-circle" aria-hidden="true"></i></span>
                        <span class="p-text">Giảng viên thỉnh giảng Quản trị Nhân sự - Đại học KHXH và NV</span>
                    </li>
                    
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="commitment" id="utilities">
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <h3>Cam kết</h3>
                <ul class="list-unstyled">
                    <li>
                        <span class="icon"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                        <span class="text">Giới thiệu trực tiếp tới nhà tuyển dụng</span>
                    </li>
                    <li>
                        <span class="icon"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                        <span class="text">Tư vấn hoàn thiện hồ sơ xin việc sau 5 ngày hoàn thiện khóa học</span>
                    </li>
                    <li>
                        <span class="icon"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                        <span class="text">Tự tin tìm việc, tăng 300% cơ hội có việc làm sau 3 tuần</span>
                    </li>
                    <li>
                        <span class="icon"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                        <span class="text">Giáo trình luôn được cập nhập theo thị trường tuyển dụng</span>
                    </li>
                    <li>
                        <span class="icon"><i class="fa fa-check-square-o" aria-hidden="true"></i></span>
                        <span class="text">Trọn đời sở hữu học liệu, chủ động không gian – thời gian học</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <p class="text-center by-course"><a class="btn btn-primary btn-bycourse" href="#cod-register">Đăng ký</a></p>
            </div>
        </div>

    </div>
</div>

<div class="faq" id="faq">
    <div class="container">
        <div class="row row-faq">
            <div class="col-md-4">
                <div class="title">
                    <p>faqs</p>
                </div>
            </div>
            <div class="col-md-8">
                <ul class="list-unstyled">
                    <li>
                        <p class="box">MyEdu là gì? <span class="arrow-down pull-right" aria-hidden="true"></span></p>
                        <p class="text">MyEdu là nền tảng cung cấp khóa học và chứng chỉ trực tuyến hàng đầu Việt Nam. Các khóa học online và offline của MyEdu hướng đến sự hoàn thiện kỹ năng và nền tảng tri thức với của 8.000.000 sinh viên Việt Nam. 500 trường đại học - cao đẳng trên cả nước, Microsoft Việt Nam, Ngoại ngữ 24h, VMCG, và Alphabooks đang kế kết để trở thành đối tác chiến lược của MyEdu. Hiện tại 50.000 sinh viên đã tin tưởng và theo học ở MyEdu.</p>
                    </li>
                    <li>
                        <p class="box">Sinh viên có nên theo học chương trình này? <span class="arrow-down pull-right" aria-hidden="true"></span></p>
                        <p class="text">Các bài học có được thiết kế theo lộ trình bắt đầu đi tìm việc và các ví dụ minh họa thực tế nên ai cũng có tham gia học mà không ngại gì kể cả chưa có kinh nghiệm làm việc.</p>
                    </li>
                    <li>
                        <p class="box">Đã đi làm được một thời gian, tôi có nên học? <span class="arrow-down pull-right" aria-hidden="true"></span></p>
                        <p>Với những bài học phân tích, đánh giá kỹ năng và cách thức áp dụng kỹ năng đã có vào công việc bạn đang theo đuổi, thực sự bạn còn rất nhiều thứ cần khám phá. Liệu bạn có tự tin mình có đầy đủ hoặc biết cách khai thác những kỹ năng này? Hoặc nếu bạn có dự định nhảy việc, khóa học này là cần thiết để bạn biết cách chuyển sang một công việc mới hiệu quả và lương thượng được mức lương mong muốn.</p>
                    </li>
                    <li>
                        <p class="box">Học xong tôi có xin được việc không? <span class="arrow-down pull-right" aria-hidden="true"></span></p>
                        <p>Bạn sẽ không thể tìm được ở bất cứ đâu một giáo trình hoàn chỉnh và chất lượng dịch vụ tốt như ở đây. Sau khi học xong, bạn sẽ được MyEdu giới thiệu trực tiếp hồ sơ của bạn đến nhà tuyển dụng. Hay nếu bạn vẫn không thể tìm được công việc, đội ngũ tư vấn chuyên nghiệp của MyEdu sẽ tiếp tục đồng hành cùng bạn tới khi bạn tìm được công việc mong muốn.</p>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="cod-register" id="cod-register">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <p class="pic-gui">
                    <img src="../frontend/img/myedu/landing/head_9.png">
                </p>
                <div class="apply">
                    <h2><span>Chỉ áp dụng cho</span><strong> 50 người đăng ký đầu tiên</strong></h2>
                    <h4>đến hết ngày 25/01/2017</h4>
                    <div class="seeding">
                        <div class="seeding-body">
                            <p>Đăng ký khóa học ngay và tiết kiệm 50%</p>
                        </div>
                    </div>
                </div>
                <div class="box-time">
                    <div id="clock"></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-cod">
                    <h2>Đăng ký tư vấn dịch vụ</h2>
                    {!! Form::open(['class' => '', 'role' => 'form', 'method' => 'POST', 'enctype'=>'multipart/form-data']) !!}
                        <div class="form-group">
                            <input type="text" name="contact_name" required="" placeholder="Họ và tên" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="email" name="contact_email" required="" placeholder="Email" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="number" name="contact_phone" required="" placeholder="Số điện thoại" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="contact_district" required="" placeholder="Quận/Huyện" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="contact_city" required="" placeholder="Thành phố" value="" class="form-control">
                        </div>
                        <div class="form-group">
                            <input type="text" name="contact_address" required="" placeholder="Địa chỉ" value="" class="form-control">
                        </div>
                        <input type="hidden" name="course_id" value="{{ $course_id }}">
                        <button type="submit" class="btn btn-primary btn-bycourse btn-block">Đăng ký ngay</button>
                    {!! Form::close() !!}
                </div>

                <div class="price-cod">
                    <p class="old-price">Giá cũ: <span>599.000 VNĐ</span></p>
                    <p class="new-price">Chỉ còn: 399.000 VNĐ</p>
                </div>

                <div class="count-num">
                    <div class="input-group">
                        <p class="form-control">Số người đăng ký</p>
                        <span class="input-group-addon" >{{ $num_count }}</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script type='text/javascript'>window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",57529]);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);</script> 

<div class="cod-footer">
    <div class="container">
        <p>MyEdu - Nền tảng cung cấp khóa học và chứng chỉ trực tuyến hàng đầu Việt Nam</p>
    </div>
</div>

@endsection