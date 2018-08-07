<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/9/15
 * Time: 15:28
 */

namespace App\Models;



interface CourseContentContract
{
    public function get_title();
    public function get_sub_title();
    public function get_type();
    public function get_content();

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course_content();
    public function delete_content();
    //public function update_content(CourseContentContract $content);

}