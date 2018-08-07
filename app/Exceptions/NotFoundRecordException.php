<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 10:54
 */

namespace App\Exceptions;


use Illuminate\Support\Facades\Lang;

class NotFoundRecordException extends \Exception
{

    /**
     * NotFoundRecordException constructor.
     */
    public function __construct($model)
    {
        $message = trans('exception.not_found_model_record', ['model' => $model]);
        parent::__construct($message);
    }
}