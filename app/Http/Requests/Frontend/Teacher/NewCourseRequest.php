<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/9/15
 * Time: 18:01
 */

namespace App\Http\Requests\Frontend\Teacher;


use App\Http\Requests\Request;

class NewCourseRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    /**
     * Khai báo rule
     * @return array
     */
    public function rules()
    {
        return [
            'cou_title' 		=> 'required|max:255',
            'cou_cate_id' 	=> 'cat_exist|min:1',
            'language' => 'required'
        ];
    }
}