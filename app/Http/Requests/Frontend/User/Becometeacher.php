<?php

namespace App\Http\Requests\Frontend\User;

use App\Http\Requests\Request;

class Becometeacher extends Request
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
            'name'	        => 'required',
            'unit_name'	    => 'required',
            'position'	    => 'required',
            'status_text'	=> 'required'
        ];
    }

    public function messages()
    {
        return [
            'name.required'         => 'Bạn chưa cập nhật tên hiển thị',
            'unit_name.required'    => 'Bạn chưa cập nhật tên đơn vị công tác',
            'position.required'     => 'Bạn chưa cập nhật vị trí công tác',
            'status_text.required'  => 'Bạn đã quên giới thiệu về bản thân'
        ];
    }
}
