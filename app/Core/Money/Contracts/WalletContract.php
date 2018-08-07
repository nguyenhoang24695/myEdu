<?php
/**
 * Created by PhpStorm.
 * User: hocvt
 * Date: 1/4/16
 * Time: 10:42
 */

namespace App\Core\Money\Contracts;


interface WalletContract
{
    public function walletType();
    public function primaryAmount($format = 'int', $append = '');
    public function primaryIncome($amount);
    public function primaryOutcome($amount);
    public function secondaryAmount($format = 'int', $append = '');
    public function secondaryIncome($amount);
    public function secondaryOutcome($amount);
}