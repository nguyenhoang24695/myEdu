<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/4/16
 * Time: 10:43
 */

namespace App\Core\Money\Contracts;


interface SellableContract
{
    public function getOwner();
    public function getPrice();
}