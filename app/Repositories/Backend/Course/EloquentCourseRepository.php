<?php

namespace App\Repositories\Backend\Course;


use App\Core\BaseRepository;
use App\Models\Course;

class EloquentCourseRepository extends BaseRepository implements CourseContract
{
	/**
     * EloquentCourseRepository constructor.
     */
    protected $model;
    public function __construct(Course $course)
    {
      $this->model = $course;
    }
}