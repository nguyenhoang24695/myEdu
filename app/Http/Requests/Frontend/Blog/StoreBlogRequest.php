<?php

namespace App\Http\Requests\Frontend\Blog;

use App\Http\Requests\Request;

class StoreBlogRequest extends Request
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
            'blo_title'         => 'required|max:255',
            'blo_summary'       => 'required|max:500',
            'blo_path'          => 'required',
            'blo_cate'          => 'required'
        ];
    }
}
