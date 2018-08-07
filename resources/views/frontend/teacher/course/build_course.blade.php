@extends('frontend.layouts.default')

@section('after-styles-end')
  {!! HTML::style('frontend/css/lecture.css') !!}
  @endsection

@section('content')
<div class="container mr_top_3">
  <div class="row">
    <div class="col-sm-3 col-md-3 aside">
      <aside>
        <div class="thumbnail">
          <img src="https://dujk9xa5fr1wz.cloudfront.net/course/480x270/244336_6f63_2.jpg" alt="">
        </div>
        <div class="use_info">
          <a class="u-avatar pull-left" href="#">
            <img src="https://dujk9xa5fr1wz.cloudfront.net/user/50x50/15359278_9908.jpg" alt="" class="img-circle">
          </a>
          <p class="u-name">
            <a href="#">Thạo Nguyễn Văn</a>
            <span class="nn">Giảng viên Đại Học bách khoa Hà nội</span>
          </p>
        </div>
        <div class="wrap_list">
          <h3><span>Nội dung giảng dạy</span> <hr></h3>
          <ul>
            <li><a href="#"><i class="fa fa-user"></i> Đối tượng của lớp học</a></li>
            <li><a href="#"><i class="fa fa-columns"></i> Nội dung giảng dạy</a></li>
          </ul>
        </div>
        <div class="wrap_list">
          <h3><span>Thông tin lớp học</span><hr></h3>
          <ul>
            <li><a href="#"><i class="fa fa-list"></i> Thông tin tóm tắt</a></li>
            <li><a href="#"><i class="fa fa-flag"></i> Giới thiệu về lớp học</a></li>
            <li><a href="#"><i class="fa fa-image"></i> Ảnh đại diện</a></li>
            <li><a href="#"><i class="fa fa-film"></i> Video giới thiệu</a></li>
          </ul>
        </div>
        <div class="wrap_list">
          <h3><span>Thiết lập lớp học</span><hr></h3>
          <ul>
            <li><a href="#"><i class="fa fa-lock"></i> Chế độ riêng tư</a></li>
            <li><a href="#"><i class="fa fa-money"></i> Giá</a></li>
            <li><a href="#"><i class="fa fa-info-circle"></i> Thông tin cơ bản</a></li>
          </ul>
        </div>
      </aside>
    </div>
    <div class="col-sm-9 col-md-9 main main_lecture">
      <div class="wrap_main">

        <section>
          <div class="panel panel-default">
            <div class="panel-body">
              <div class="media">
                <div class="media-left">
                  <a href="#">
                    <img class="media-object" src="https://dujk9xa5fr1wz.cloudfront.net/course/240x135/609810_5ca4.jpg" alt="...">
                  </a>
                </div>
                <div class="media-body">
                  <h4 class="media-heading course_title">Đào tạo tiến sĩ tương lai cho đất nước</h4>
                  <p class="tech_name">Giáo viên : <a href="#">Thạo nguyễn</a></p>
                  <p class="course_stt">Khóa học do bạn tạo chưa được đăng</p>
                  <p class="course_btn_view"><a href="#" class="cv_demo">Xem trước</a><a href="#" class="cv_demo">Đăng lên</a></p>
                </div>
              </div>
            </div>
          </div>

          <div class="line_pattern"></div>

          <div class="panel wiget_box">
            <div class="panel-heading">
              <h3 class="panel-title">Đối tượng của lớp học</h3>
            </div>
            <div class="panel-body ">
              <form class="obj_course">


                <div class="form-group">
                  <label for="">Học sinh nào nên học khóa học này</label>
                  <p class="help-block">Để nâng cao chất lượng đào tạo bạn hay cho biết đối tượng nào phù hợp với khóa học này.</p>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                    <input type="text" class="form-control" >
								      <span class="input-group-btn">
								        <button class="btn" type="button">Thêm</button>
								      </span>
                  </div><!-- /input-group -->
                </div>

                <div class="form-group">
                  <label for="">Học sinh nào nên học khóa học này</label>
                  <p class="help-block">Để nâng cao chất lượng đào tạo bạn hay cho biết đối tượng nào phù hợp với khóa học này.</p>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                    <input type="text" class="form-control" >
								      <span class="input-group-btn">
								        <button class="btn" type="button">Thêm</button>
								      </span>
                  </div><!-- /input-group -->
                </div>

                <div class="form-group">
                  <label for="">Học sinh nào nên học khóa học này</label>
                  <p class="help-block">Để nâng cao chất lượng đào tạo bạn hay cho biết đối tượng nào phù hợp với khóa học này.</p>
                  <ul class="obj_list">

                    <li>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                        <input type="text" class="form-control" readonly="readonly" >
											      <span class="form-control-feedback">
											      	<i class="fa fa-list-ul"></i>
											      	<i class="fa fa-trash"></i>
											      </span>
                      </div><!-- /input-group -->
                    </li>

                    <li>
                      <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                        <input type="text" class="form-control" readonly="readonly" >
											      <span class="form-control-feedback">
											      	<i class="fa fa-list-ul"></i>
											      	<i class="fa fa-trash"></i>
											      </span>
                      </div><!-- /input-group -->
                    </li>

                  </ul>
                  <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-circle"></i></span>
                    <input type="text" class="form-control" >
								      <span class="input-group-btn">
								        <button class="btn" type="button">Thêm</button>
								      </span>
                  </div><!-- /input-group -->
                </div>

                <div class="form-group">
                  <label for="">Học lực phù hợp</label>
                  <p class="help-block">Để nâng cao chất lượng đào tạo bạn hay cho biết đối tượng nào phù hợp với khóa học này.</p>
                  <div class="input-group">
                    <label class="radio-inline">
                      <input type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1"> Học mới
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2"> khá giỏi
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Trung bình
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Yếu kém
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3"> Tất cả
                    </label>
                  </div>
                </div>

                <button type="submit" class="btn btn_save reset_button">Lưu</button>
              </form>
            </div>
          </div>

        </section> <!-- /section -->

        <section>

          <div class="panel panel-default">
            <div class="panel-body">
              <div class="media">
                <div class="media-left">
                  <a href="#">
                    <img class="media-object" src="https://dujk9xa5fr1wz.cloudfront.net/course/240x135/609810_5ca4.jpg" alt="...">
                  </a>
                </div>
                <div class="media-body">
                  <h4 class="media-heading course_title">Đào tạo tiến sĩ tương lai cho đất nước</h4>
                  <p class="tech_name">Giáo viên : <a href="#">Thạo nguyễn</a></p>
                  <p class="course_stt">Khóa học do bạn tạo chưa được đăng</p>
                  <p class="course_btn_view"><a href="#" class="cv_demo">Xem trước</a><a href="#" class="cv_demo">Đăng lên</a></p>
                </div>
              </div>
            </div>
          </div>

          <div class="line_pattern"></div>

          <div class="panel wiget_box">
            <div class="panel-heading">
              <h3 class="panel-title">Nội dung giảng dạy</h3>
            </div>
            <div class="panel-body upload_lecture">
              <ul class="s_section">

                <li class="item_list">
                  <a class="show_section" href="#">Chương 1 tên chương 1 <i class="fa fa-edit"></i><i class="fa fa-move pull-right"></i></a>
                  <ul class="s_lecture">
                    <li class="show_lecture">
                      <a href="#">
                        Tiết 1 , chương khởi đầu
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-move pull-right"></i>
                        <i class="add_content pull-right">+ Thêm nội dung</i>
                      </a>
                    </li>
                    <li class="add_lecture">
                      <a href="#" class="btn_add_lec">+ Thêm tiết học</a>
                      <a href="#" class="btn_add_lec">+ Thêm bài tập cho tiết 1</a>
                    </li>
                  </ul>
                </li>

                <li class="item_list">
                  <a class="show_section" href="#">Chương 1 tên chương 1 <i class="fa fa-edit"></i><i class="fa fa-move pull-right"></i></a>
                  <ul class="s_lecture">
                    <li class="show_lecture">
                      <a href="#">
                        Tiết 2 , chương khởi đầu
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-move pull-right"></i>
                        <i class="add_content pull-right">+ Thêm nội dung</i>
                      </a>
                    </li>
                    <li class="add_lecture">
                      <a href="#" class="btn_add_lec">+ Thêm tiết học</a>
                      <a href="#" class="btn_add_lec">+ Thêm bài tập cho tiết 2</a>
                    </li>
                  </ul>
                </li>

                <li class="item_list">
                  <a class="show_section close" href="#">Chương 1 tên chương 1 <i class="fa fa-edit"></i><i class="fa fa-move pull-right"></i></a>
                  <div class="frm_add form-horizontal">

                    <div class="form-group">
                      <label class="col-sm-2 control-label">Chương 1</label>
                      <div class="col-sm-10 input-group">
                        <input type="text" class="form-control reset_input"  placeholder="Tên chương">
                        <div class="input-group-addon"><span>0</span>/70</div>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="col-sm-2 control-label"></label>
                      <div class="col-sm-10 input-group">
                        <input type="text" class="form-control reset_input"  placeholder="Mô tả ngắn">
                        <div class="input-group-addon"><span>0</span>/70</div>
                      </div>
                    </div>

                    <div class="form-group">
                      <div class="col-sm-offset-2 col-sm-10 col_save">
                        <button type="button" class="btn reset_button save">Lưu</button>
                        <button type="button" class="btn reset_button reset">Hủy</button>
                        <button type="button" class="btn reset_button delete pull-right"><i class="fa fa-trash"></i></button>
                      </div>
                    </div>

                  </div>
                  <ul class="s_lecture close">
                    <li class="show_lecture">
                      <a href="#">
                        Tiết 2 , chương khởi đầu
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-move pull-right"></i>
                        <i class="add_content pull-right">+ Thêm nội dung</i>
                      </a>
                    </li>
                    <li class="add_lecture">
                      <a href="#" class="btn_add_lec">+ Thêm tiết học</a>
                      <a href="#" class="btn_add_lec">+ Thêm bài tập cho tiết 2</a>
                    </li>
                  </ul>
                </li>

                <li class="item_list">
                  <a class="show_section" href="#">Chương 1 tên chương 1 <i class="fa fa-edit"></i><i class="fa fa-move pull-right"></i></a>
                  <ul class="s_lecture">
                    <li class="show_lecture">
                      <a href="#">
                        Tiết 2 , chương khởi đầu
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-move pull-right"></i>
                        <i class="add_content pull-right">+ Thêm nội dung</i>
                      </a>

                      <div class="tab_header pull-right">
                        <a href="#">+ Thêm nội dung <i class="fa fa-remove-sign"></i></a>

                      </div>

                      <ul class="lb_content">
                        <li>
                          <a href="#">
                            <i class="fa fa-facetime-video"></i>
                            <span class="lb_name">Video</span>
                          </a>
                        </li>
                        <li>
                          <a href="#">
                            <i class="fa fa-volume-up"></i>
                            <span class="lb_name">Audio</span>
                          </a>
                        </li>
                        <li>
                          <a href="#">
                            <i class="fa fa-copy"></i>
                            <span class="lb_name">slide</span>
                          </a>
                        </li>
                        <li>
                          <a href="#">
                            <i class="fa fa-file-alt"></i>
                            <span class="lb_name">Văn bản</span>
                          </a>
                        </li>
                        <li>
                          <a href="#">
                            <i class="fa fa-bookmark-empty"></i>
                            <span class="lb_name">Gõ chữ</span>
                          </a>
                        </li>
                        <li>
                          <a href="#">
                            <i class="fa fa-desktop"></i>
                            <span class="lb_name">Tất cả</span>
                          </a>
                        </li>
                      </ul>
                    </li>

                    <li class="add_lecture">
                      <a href="#" class="btn_add_lec">+ Thêm tiết học</a>
                      <a href="#" class="btn_add_lec">+ Thêm bài tập cho tiết 2</a>
                    </li>
                  </ul>
                </li>

                <li class="item_list">
                  <a class="show_section" href="#">Chương 1 tên chương 1 <i class="fa fa-edit"></i><i class="fa fa-move pull-right"></i></a>
                  <ul class="s_lecture">
                    <li class="show_lecture">
                      <a href="#">
                        Tiết 2 , chương khởi đầu
                        <i class="fa fa-edit"></i>
                        <i class="fa fa-move pull-right"></i>
                        <i class="add_content pull-right visible">+ Thêm nội dung</i>
                      </a>

                      <div class="tab_header pull-right">
                        <a href="#">+ Thêm audio <i class="fa fa-remove-sign"></i></a>
                      </div>

                      <ul class="lb_content">
                        <li class="progressbar">

                          <div class="input-group">
                            <input type="text" class="form-control" readonly="readonly" value="Đăng các loại file có dung lượng <= 3MB">
													      <span class="input-group-btn">
													        <button class="btn reset_button" type="button">Tải lên</button>
													      </span>
                          </div>

                          <div class="input-group mt_15">
                            <div class="progress">
                              <div class="progress-bar progress-bar-info progress-bar-striped active" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: 20%">
                                <span class="sr-only">20% Complete</span>
                              </div>
                            </div>
															<span class="input-group-btn">
													        <button class="btn del_button" type="button">hủy</button>
													      </span>
                          </div>

                        </li>
                      </ul>
                    </li>

                    <li class="add_lecture">
                      <a href="#" class="btn_add_lec">+ Thêm tiết học</a>
                      <a href="#" class="btn_add_lec">+ Thêm bài tập cho tiết 2</a>
                    </li>
                  </ul>
                </li>

                <li class="item_list add_section">
                  <a href="#">+ Thêm chương mới</a>
                </li>

              </ul>
            </div>
          </div>

        </section><!-- /section -->
      </div>
    </div>
  </div>
</div> <!-- /container -->
@endsection