<?php
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 23/02/2016
 * Time: 11:49 SA
 */

namespace App\Core\PromoCode;


use App\Core\BaseRepository;
use App\Core\PromoCode\src\PromoCodeGenerate;
use App\Models\PromoCode;

class InnerPromoCode extends BaseRepository
{
    protected $model;

    public function __construct()
    {
        $this->model = new PromoCode();
    }

    public function getByCode($code)
    {
        return $this->model->where('code', $code)->first();
    }

    public function getCodeByUser($user_id){
        return $this->model->where('user_id', $user_id)->first();
    }

    public function checkCodeUnique($code)
    {
        return $this->model->where('code', $code)->count() > 0;
    }

    public function createCode($user_id)
    {
        $code       =   new PromoCodeGenerate(ConfigCode::PROMO_CODE_STRING_LENGHT);
        $gencode    =   $code->generateCode();

        //Kiểm tra xem code này đã có hay chưa;
        if($this->checkCodeUnique($gencode)){
            return response()->json('Mã code đã tồn tại',401);
        } else {
            return $this->create([
                'code'              => $gencode,
                'user_id'           => $user_id,
                'discount_1'        => ConfigCode::PROMO_CODE_DISCOUNT_1,
                'discount_2'        => ConfigCode::PROMO_CODE_DISCOUNT_2,
                'discount_max'      => ConfigCode::PROMO_CODE_DISCOUNT_MAX,
                'active'            => 1
            ]);
        }
    }

    //Kiểm tra xem thành viên đã có mã code hay chưa, chưa có thì tạo mới
    public function findOrcreate($user_id)
    {
        $check  =   $this->model->where('user_id', $user_id)->first();
        if($check){
            return $check;
        } else {
            return $this->createCode($user_id);
        }
    }
}