<div class="col-xs-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{$section->sec_title}}
      </h3>
    </div>
    <div class="panel-body">
      <div>
        <p>{{$section->sec_sub_title}}</p>
      </div>
      <div>Trong chương này : </div>
      <div class="list-group">
        <?php
        foreach($course_contents as $v){
        /** @var \App\Models\CourseContent $v */
        if($v->content_order > $viewing_content->content_order){
        if($v->get_type() == config('course.content_types.section')){
          break;
        }
        ?>
        <a class="list-group-item" href="<?php echo $course->get_default_studying_link($v->id) ?>"> <?php echo $v->get_title(); ?> </a>

        <?php
        }
        }?>
      </div>
    </div>
  </div>
</div>
