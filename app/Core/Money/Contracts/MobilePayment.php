<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/9/16
 * Time: 11:14
 */

namespace App\Core\Money\Contracts;


use App\Models\MobileCard;

interface MobilePayment
{
    public function processCard(MobileCard $mobileCard);
}