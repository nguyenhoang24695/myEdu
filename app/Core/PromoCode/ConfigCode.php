<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 23/02/2016
 * Time: 11:23 SA
 */

namespace App\Core\PromoCode;


class ConfigCode
{
    /**
     * 1 khóa học dc bán thành công sử dụng mã code giảm giá
     *
     * Tỷ lệ chiết khấu như sau
     *
     * 1. Giáo viên (seller) : hưởng 40%/giá bán
     * 2. Partner : Hưởng tối đa 30%/giá bán (khởi đầu 0 hoặc 10%)
     *      + 0%: khi đăng ký là partner chưa bán dc khóa học nào
     *      + 10%: khi mua khóa học dc tặng mã giảm giá
     * 3. Buyer: được hưởng chiết khấu theo partner hoặc mặc định 10%.
    **/

    //Chiết khấu mặc định
    const DISCOUNT_DEFAULT                  =   10;
    //Chiết khấu người bán (giáo viên) được nhận
    const DISCOUNT_OF_SELLER                =   40;

    /*********************
    **  ÁP DỤNG MÃ CODE **
    *********************/
    const PROMO_CODE_DISCOUNT_MAX   =  self::DISCOUNT_DEFAULT;
    const PROMO_CODE_DISCOUNT_1     =   0; //Sử dụng cho trường hợp P#GV
    const PROMO_CODE_DISCOUNT_2     =   0; //Sử dụng cho trường hợp p=GV

    /**
     * Tăng level của partner
    **/
    const LEVEL_STANDARD    =   0;
    const LEVEL_GOLD        =   10000000; //(10 triệu)
    const LEVEL_DIAMOND     =   15000000; //(15 triệu)
    const DISCOUNT_STANDARD =   10; //(10%)
    const DISCOUNT_GOLD     =   20; //(20%)
    const DISCOUNT_DIAMOND  =   30; //(30%)

    /**
     * Số lần sửa mã giảm giá
    **/

    const PROMO_CODE_TOTAL_EDIT    =   1;

    /**
     * Độ dài mã code giảm giá khởi tạo mặc định
    **/

    const PROMO_CODE_STRING_LENGHT =   6;

}