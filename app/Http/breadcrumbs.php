<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 11/28/15
 * Time: 08:49
 */
// Home
Breadcrumbs::register('home', function($breadcrumbs)
{
    $breadcrumbs->push('<i class="fa fa-home"></i>', route('home'), ['html_title' => true]);
});

// Home > [Category]
Breadcrumbs::register('course_category', function($breadcrumbs, $category)
{
    /** @var \App\Models\Category $category */
    if($category->parent){
        $breadcrumbs->parent('course_category', $category->parent);
    }else{
        $breadcrumbs->parent('home');
    }
    $breadcrumbs->push($category->cat_title, route('category.show', ['id' => $category->id, 'title' => str_slug($category->cat_title)]));
});

// Home > [Category] > Course
Breadcrumbs::register('course_detail', function($breadcrumbs, $course)
{
    /** @var \App\Models\Course $course */
    $breadcrumbs->parent('course_category', $course->category);
    $breadcrumbs->push($course->cou_title, $course->get_public_view_link());
});


// Home > [Category] > Course > CourseContent
Breadcrumbs::register('course_content', function($breadcrumbs, \App\Models\CourseContent $course_content)
{
    /** @var \App\Models\Course $course */
    $breadcrumbs->parent('course_detail', $course_content->course);
    $breadcrumbs->push($course_content->get_title(), null);
});

// Trang thanh toán khóa học
Breadcrumbs::register('pre_register_course', function($breadcrumbs){
    $breadcrumbs->parent('home');
    $breadcrumbs->push(trans('course.buy_course'), null);
});


//// Home > About
//Breadcrumbs::register('about', function($breadcrumbs)
//{
//    $breadcrumbs->parent('home');
//    $breadcrumbs->push('About', route('about'));
//});
//
//// Home > Blog
//Breadcrumbs::register('blog', function($breadcrumbs)
//{
//    $breadcrumbs->parent('home');
//    $breadcrumbs->push('Blog', route('blog'));
//});
//
//// Home > Blog > [Category]
//Breadcrumbs::register('category', function($breadcrumbs, $category)
//{
//    $breadcrumbs->parent('blog');
//    $breadcrumbs->push($category->title, route('category', $category->id));
//});
//
//// Home > Blog > [Category] > [Page]
//Breadcrumbs::register('page', function($breadcrumbs, $page)
//{
//    $breadcrumbs->parent('category', $page->category);
//    $breadcrumbs->push($page->title, route('page', $page->id));
//});