<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/7/15
 * Time: 08:59
 */

namespace App\Http\Requests\Backend\Category;

use App\Http\Requests\Request;

class CreateCategoryRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'cat_title'	=>	'required'
        ];
    }
}