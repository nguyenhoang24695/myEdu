<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/4/15
 * Time: 10:24
 */

namespace App\Exceptions;

class HaveRelativeDataException extends \Exception{

    /**
     * @param string $model
     * @param array $relative_models
     */
    public function __construct($model, $relative_models = array()){
        $message = trans("exception.model_have_relative_data", ['model' => $model, 'relatives' => implode(',', $relative_models)]);
        parent::__construct($message);
    }
}