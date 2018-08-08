<?php
/**
 * Khai báo các hàm liên quan đến search trong đây, khai báo thêm nếu cần,
 * chỉ sử dụng search của search engine qua giao diện này
 * User: hocvt
 * Date: 12/10/15
 * Time: 09:41
 */

namespace App\Core;


use App\Core\Contracts\SearchEngine;
use App\Models\Course;
use App\Models\EsTag;
use App\Models\User;

class MySeacher extends MySearchEngine
{
    public static function searchTag($keyword){
        $query = [
            'bool' => [
                'should' => [
                    ['match' => [
                        'name' => $keyword
                    ]],
                    ['match' => [
                        'slug' => $keyword
                    ]]
                ]
            ]
        ];
        $tags = EsTag::searchByQuery($query);
        $return['total'] = $tags->count();
        $return['tags'] = $tags;
        return $return;
    }

    /**
     * search Course.
     *
     * @return \Illuminate\Http\Response
     */
    public static function searchCourse($keyword, $activated = true)
    {

        $query = [
            'bool' => [
                'should' => [
                    ['match' => [
                        'cou_title' => [
                            "query" => $keyword,
                            'boost' => config('elasticquent.boost.course.title', 2),
                        ]
                    ]],
                    ['match' => [
                        'cou_sub_title' => [
                            "query" => $keyword,
                            'boost' => config('elasticquent.boost.course.description', 1),
                        ]
                    ]],
                    ['match' => [
                        'slug' => [
                            "query" => $keyword,
                            'boost' => config('elasticquent.boost.course.slug', 2),
                        ]
                    ]]
                ]
            ]
        ];

        if($activated){
            $query['bool']['should'][] = ['match' => [
                'cou_active' => 1,
            ]];
        }
//        dd($query);
        $courses = Course::searchByQuery($query);

        $return['total']    = $courses->count();
        $return['course']   = $courses;
        return $return;

    }

    /**
     * search User.
     *
     * @return \Illuminate\Http\Response
     */
    public static function searchUser($keyword)
    {
        $query = [
            'bool' => [
                'should' => [
                    ['match' => [
                        'name'      => $keyword
                    ]],
                    ['match' => [
                        'slug'      => $keyword
                    ]],
                    ['match' => [
                        'full_name' => $keyword
                    ]]
                ]
            ]
        ];
        $users = User::searchByQuery($query);
        $return['total']    = $users->count();
        $return['user']     = $users;
        return $return;
    }
}