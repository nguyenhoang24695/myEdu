@section('styles-plugin')
  {!! HTML::style('frontend/plugin/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') !!}
@endsection
@section('scripts-plugin')
  {!! HTML::script('frontend/plugin/bootstrap-datetimepicker/moment/min/moment.min.js') !!}
  {!! HTML::script('frontend/plugin/bootstrap-datetimepicker/moment/locale/vi.js') !!}
  {!! HTML::script('frontend/plugin/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') !!}
@endsection
@section('before-scripts-end')
  <script type="text/javascript">
    $(function () {
        $('#birthday_user').datetimepicker({
          format: 'YYYY-MM-DD',
          locale: moment.locale('vi')
        });
    });
  </script>
@endsection


<section>
  <div class="panel unibee-box">
    <div class="panel-heading">
      <h3 class="panel-title">Thông tin cá nhân</h3>
    </div>
    <div class="panel-body no-padding">
    </div>
  </div>
  
  <div class="panel">
    <div class="panel-body ">
      <ul class="setting-use list-unstyled">
        <li>
          <h3>Ảnh đại diện <span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>
          <div class="show_info">
            @if(Auth::user()->avatar_path != "")
              <img src="{{ Auth::user()->showAvatar('ua_medium') }}" alt="" class="mini-pic show_avata">
            @else
              <img src="{{ Auth::user()->showDefaultAvatar() }}" alt="{{ Auth::user()->name }}"
                   class="mini-pic show_avata">
            @endif
          </div>

          <form enctype="multipart/form-data" action="{{ url('/dashboard/setting') }}" method="POST" class="update_info">
            <table>
              <tbody>
              <tr>
                <td><span>Chọn ảnh từ máy tính</span></td>
                <td>
                  <input type="file" name="avatar" class="form-control">
                  <input type="hidden" name="id" value="{{ Auth::user()->id  }}">
                </td>
              </tr>
              <tr>
                <td></td>
                <td>
                  <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                  <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                </td>
              </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>

        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Tên hiển thị <span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>

          <div class="show_info">
            @if(Auth::user()->name != "")
              <h2>{{ Auth::user()->name }}</h2>
            @else
              <h2>Đang cập nhật</h2>
            @endif
          </div>

          <form action="{{ url('/dashboard/setting') }}" method="POST" accept-charset="utf-8" class="update_info">
            <table>
              <tbody>
              <tr>
                <td class="first"><span>Tên hiển thị</span></td>
                <td class="last">
                  <input type="text" name="name" value="{{ Auth::user()->name }}" placeholder="Cập nhật tên hiển thị"
                         class="form-control">
                  <input type="hidden" name="id" value="{{ Auth::user()->id  }}">
                </td>
              </tr>
              <tr>
                <td class="first"></td>
                <td class="last">
                  <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                  <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                </td>
              </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>

        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Thông tin <span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>
          <table class="show_info">
            <tbody>
            <tr>
              <td class="first"><span>Họ tên</span></td>
              <td>{{ (Auth::user()->full_name != "") ? Auth::user()->full_name : "Đang cập nhật" }}</td>
            </tr>
            <tr>
              <td class="first"><span>Ngày sinh</span></td>
              <td>{{ (strtotime(Auth::user()->birthday) != NULL) ? \Carbon\Carbon::parse(Auth::user()->birthday)->format('d M Y') : "Đang cập nhật" }}</td>
            </tr>
            <tr>
              <td class="first"><span>Giới tính</span></td>
              <td>
                @if (Auth::user()->gender == 0)
                  {{ "Nam" }}
                @elseif (Auth::user()->gender == 1)
                  {{ "Nữ" }}
                @else
                  {{ "Đang cập nhật" }}
                @endif
              </td>
            </tr>
            <tr>
              <td class="first"><span>Địa chỉ</span></td>
              <td>{{ (Auth::user()->address != "") ? Auth::user()->address : "Đang cập nhật" }}</td>
            </tr>
            <tr>
              <td class="first"><span>Email</span></td>
              <td>{{ Auth::user()->email }}</td>
            </tr>
            <tr>
              <td class="first">
                <span>Kết nối thông tin</span>
                <span 
                class="fa fa-question-circle" 
                data-toggle="tooltip" 
                data-placement="bottom" 
                title="{{ config('app.name') }} sẽ đồng bộ thông tin với các tài khoản kết nối" 
                data-original-title=""></span>
              </td>
              <td>

                <a 
                class="btn btn-face btn-sm btn-default" 
                href="javascript:void(0);"
                data-toggle="modal" 
                data-target="#myModal_dev"
                style="min-width: 100px">
                  <span class="icom-facebook"></span> Facebook
                </a>

                <a 
                class="btn btn-google btn-sm btn-default" 
                href="javascript:void(0);"
                style="min-width: 100px"
                data-toggle="modal" 
                data-target="#myModal_dev">
                  <span class="icom-google-plus"></span> Google
                </a>

                @if (getenv('IDVG_CLIENT_ID') != "")
                  <a 
                  class="btn btn-idvg btn-sm" 
                  href="{{ route('idvg.setting',['uri'=>base64_encode(Request::url())]) }}"
                  style="min-width: 100px">
                    <i class="icom-idvg"></i> <strong>ID</strong> vatgia
                  </a>
                @endif
                
              </td>
            </tr>
            </tbody>
          </table>

          <form action="{{ url('/dashboard/setting') }}" method="POST" accept-charset="utf-8" class="update_info">
            <table>
              <tbody>
                <tr>
                  <td class="first"><span>Họ tên</span></td>
                  <td>
                    <input 
                    type="text" 
                    name="full_name" 
                    value="{{ Auth::user()->full_name }}"
                    placeholder="Cập nhật họ tên" 
                    class="form-control">
                  </td>
                </tr>
                <tr>
                  <td class="first"><span>Ngày sinh</span></td>
                  <td>
                    <input 
                    type="text" 
                    name="birthday" 
                    value="{{ (strtotime(Auth::user()->birthday) != NULL) ? \Carbon\Carbon::parse(Auth::user()->birthday)->format('Y-m-d') : "" }}"
                    class="form-control"
                    id="birthday_user">
                  </td>
                </tr>
                <tr>
                  <td class="first"><span>Giới tính</span></td>
                  <td>
                    <select class="form-control" name="gender" style="width: 50%">
                      <option value="2">Giới tính</option>
                      <option value="0" {{ (Auth::user()->gender == 0) ? "selected":"" }}>Nam</option>
                      <option value="1" {{ (Auth::user()->gender == 1) ? "selected":"" }}>Nữ</option>
                    </select>
                  </td>
                </tr>
                <tr>
                  <td class="first"><span>Địa chỉ</span></td>
                  <td>
                    <input 
                    type="text" 
                    name="address" 
                    value="{{ Auth::user()->address }}"
                    placeholder="Cập nhật địa chỉ" 
                    class="form-control">
                  </td>
                </tr>
                <tr>
                  <td class="first"></td>
                  <td>
                    <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                    <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                  </td>
                </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>

        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Công tác<span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>
          <table class="show_info">
            <tbody>
            <tr>
              <td class="first"><span>Đơn vị</span></td>
              <td>{{ (Auth::user()->unit_name != "") ? Auth::user()->unit_name : "Đang cập nhật" }}</td>
            </tr>
            <tr>
              <td class="first"><span>Vị trí</span></td>
              <td>{{ (Auth::user()->position != "") ? Auth::user()->position : "Đang cập nhật" }}</td>
            </tr>
            </tbody>
          </table>
          <form action="{{ url('/dashboard/setting') }}" method="POST" accept-charset="utf-8" class="update_info">
            <table>
              <tbody>
              <tr>
                <td class="first"><span>Đơn vị</span></td>
                <td>
                  <input type="text" name="unit_name" value="{{ Auth::user()->unit_name }}"
                         placeholder="Cập nhật tên đơn vị công tác" class="form-control">
                </td>
              </tr>
              <tr>
                <td class="first"><span>Vị trí</span></td>
                <td>
                  <input type="text" name="position" value="{{ Auth::user()->position  }}"
                         placeholder="Cập nhật vị trí công tác" class="form-control">
                </td>
              </tr>
              
              <tr>
                <td class="first"></td>
                <td>
                  <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                  <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                </td>
              </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>
        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Giới thiệu bản thân<span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>
          <table class="show_info">
            <tbody>
            <tr>
              <td class="first"><span>Giới thiệu bản thân</span></td>
              <td>{!! (Auth::user()->status_text != "") ? Auth::user()->status_text : "Đang cập nhật" !!}</td>
            </tr>
            </tbody>
          </table>
          <form action="{{ url('/dashboard/setting') }}" method="POST" accept-charset="utf-8" class="update_info">
            <table>
              <tbody>
              <tr>
                <td class="first"><span>Giới thiệu bản thân</span></td>
                <td>
                  <textarea name="status_text" class="summernote_editor">{{ Auth::user()->status_text }}</textarea>
                </td>
              </tr>
              <tr>
                <td class="first"></td>
                <td>
                  <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                  <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                </td>
              </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>
        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Thành tích đạt được<span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>
          <table class="show_info">
            <tbody>
            <tr>
              <td class="first"><span>Thành tích đạt được</span></td>
              <td>{!! (Auth::user()->achievement != "") ? Auth::user()->achievement : "Đang cập nhật" !!}</td>
            </tr>
            </tbody>
          </table>
          <form action="{{ url('/dashboard/setting') }}" method="POST" accept-charset="utf-8" class="update_info">
            <table>
              <tbody>
              <tr>
                <td class="first"><span>Thành tích đạt được</span></td>
                <td>
                  <textarea name="achievement" class="summernote_editor">{{ Auth::user()->achievement }}</textarea>
                </td>
              </tr>
              <tr>
                <td class="first"></td>
                <td>
                  <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                  <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                </td>
              </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>
        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Liên kết <span class="edit_info"><i class="fa fa-gear"></i> Sửa</span></h3>
          <table class="show_info">
            <tbody>
            <tr>
              <td class="first"><span>Facebook</span></td>
              <td>
                <a class="btn btn-face btn-sm btn-default" href="{{ (Auth::user()->social_facebook != "") ? Auth::user()->social_facebook : "javascript:void(0);" }}">
                  <span class="icom-facebook"></span> Facebook
                </a>
              </td>
            </tr>
            <tr>
              <td class="first"><span>Twitter</span></td>
              <td><a class="btn btn-twitter btn-sm btn-default" href="{{ (Auth::user()->social_twitter != "") ? Auth::user()->social_twitter : "javascript:void(0);" }}">
                  <span class="icom-twitter"></span> Twitter
                </a></td>
            </tr>
            <tr>
              <td class="first"><span>Gooogle+</span></td>
              <td><a class="btn btn-google btn-sm btn-default" href="{{ (Auth::user()->social_google != "") ? Auth::user()->social_google : "javascript:void(0);" }}">
                  <span class="icom-google-plus"></span> Gooogle+
                </a></td>
            </tr>
            <tr>
              <td class="first"><span>Linkedin</span></td>
              <td><a class="btn btn-linkin btn-sm btn-default" href="{{ (Auth::user()->social_linkedin != "") ? Auth::user()->social_linkedin : "javascript:void(0);" }}">
                  <span class="icom-linkedin"></span> Linkedin
                </a></td>
            </tr>
            </tbody>
          </table>
          <form action="{{ url('/dashboard/setting') }}" method="POST" accept-charset="utf-8" class="update_info">
            <table>
              <tbody>
              <tr>
                <td class="first"><span>Facebook</span></td>
                <td>
                  <input type="text" name="social_facebook" class="form-control" value="{{ Auth::user()->social_facebook }}" />
                </td>
              </tr>
              <tr>
                <td class="first"><span>Twitter</span></td>
                <td>
                  <input type="text" name="social_twitter" class="form-control" value="{{ Auth::user()->social_twitter }}" />
                </td>
              </tr>
              <tr>
                <td class="first"><span>Google+</span></td>
                <td>
                  <input type="text" name="social_google" class="form-control" value="{{ Auth::user()->social_google }}" />
                </td>
              </tr>
              <tr>
                <td class="first"><span>Linkedin</span></td>
                <td>
                  <input type="text" name="social_linkedin" class="form-control" value="{{ Auth::user()->social_linkedin }}" />
                </td>
              </tr>
              <tr>
                <td class="first"></td>
                <td>
                  <input type="hidden" name="id" value="{{ Auth::user()->id  }}">
                  <button type="submit" class="btn btn-primary btn-sm">Cập nhật</button>
                  <button type="button" class="btn btn-default btn-sm disabled_update ">Để sau</button>
                </td>
              </tr>
              </tbody>
            </table>
            {!! csrf_field() !!}
          </form>
        </li>
        <li>
          <hr>
        </li>
        <li>
          <h3>Mật khẩu <a class="edit_pass" href="{{route("password.change")}}"><i class="fa fa-gear"></i> Sửa </a></h3>
          <table>
            <tbody>
            <tr>
              <td class="first"><span>Mật khẩu hiện tại</span></td>
              <td>******************</td>
            </tr>
            </tbody>
          </table>
        </li>
      </ul>
    </div>
  </div>
</section>
@include('frontend.popup.update_info_user')