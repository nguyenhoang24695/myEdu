<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 12/8/15
 * Time: 11:41
 */

namespace App\Exceptions;


use Exception;

class TaggableNotSupported extends GeneralException
{
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        $message = empty($message) ? "Không hỗ trợ loại taggable này" : $message;
        parent::__construct($message, $code, $previous);
    }

}