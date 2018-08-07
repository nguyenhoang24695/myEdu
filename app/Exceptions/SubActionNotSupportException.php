<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 9/29/15
 * Time: 13:50
 */

namespace App\Exceptions;


use App\Models\User;

class SubActionNotSupportException extends GeneralException
{
    public $user;

    /**
     * SubActionNotSupportException constructor.
     * @param User $user
     * @param int $sub_action
     */
    public function __construct(User $user, $sub_action)
    {
        $this->user = $user;
        $message = 'Không hỗ trợ sub_action ' . $sub_action;
        parent::__construct($message);
    }
}