<div class="a_lecture_view opened ct-state-{{empty($course_content) ? "student" : $course_content->access_privacy}}" data-id="{{$content->id}}">
  <span class="lecture_name"> {{$content->lec_title}} </span> 
  <div class="pull-right">
    <span class="icon_buton">
      <i class="fa fa-edit" title="Sửa tiêu đề"></i>
    </span>
    <span class="icon_buton">
      <i class="fa fa-arrows move-handle" title="Sắp xếp"></i>
    </span>
    <span class="icon_buton btn_remove">
      <i class="fa fa-trash" title="Xóa"></i>
    </span>
    <span class="icon_buton btn_toggle_content">
      <i class="fa fa-th-list" title="Sửa nội dung"></i>
    </span>
  </div>
</div>