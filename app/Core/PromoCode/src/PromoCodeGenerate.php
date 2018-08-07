<?php
namespace App\Core\PromoCode\src;
/**
 * Created by PhpStorm.
 * User: MyPC
 * Date: 22/02/2016
 * Time: 2:02 CH
 */
class PromoCodeGenerate
{

    //Mã code sẽ được sinh ra từ chuỗi này.
    protected $characters;

    /**
     * array chứa mã code được tạo;
    **/
    protected $codes = [];

    //Độ dài của mã code được tạo ra.
    protected $length = 6;

    public function __construct($length = "")
    {
        $this->characters = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                             'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',
                             'K', 'L', 'M', 'N', '0', 'P', 'Q', 'R', 'S', 'T',
                             'U', 'V', 'W', 'X', 'Y', 'Z'];
        if($length != "") {
            $this->length = $length;
        }

    }

    /**
     * Tạo ra 1 danh sách các mã duy nhất
     *
     * @param $amount
     * @param $length
     *
     * @return $collection
     *
     **/
    public function generate($amount = 1)
    {
        $collection = [];
        for ($i = 1; $i <= $amount; $i++) {
            $random = $this->randomize();
            while (!$this->validate($collection, $random)) {
                $random = $this->randomize();
            }
            $collection[] = $random;
        }
        return $collection;
    }

    /**
     * Tạo ra 1 mã code duy nhất
     *
     * @param $amount
     * @param $length
     *
     * @return $code
     *
     **/

    public function generateCode()
    {
        return $this->randomize();
    }

    /**
     * Xác nhận mã được tạo ra là duy nhất
     * phục vụ cho việc tạo ra nhiều mã một lúc
     * @param $collection
     * @param $new
     *
     * @return bool
     *
    **/

    public function validate($collection, $new)
    {
        if (count($collection) == 0 && count($this->codes) == 0) return true;
        $combined = array_merge($collection, $this->codes);
        return !in_array($new, $combined);
    }

    /**
     * Mã được tạo ra từ chuỗi được định nghĩa ở trên
     * @param $length, độ dài của chuỗi.
     *
     * @return $code
     *
     **/
    public function randomize()
    {
        $characters = $this->characters;
        $random     = [];
        $code       = '';

        for ($i = 1; $i <= $this->length; $i++) {
            $character = $characters[rand(0, count($characters) - 1)];
            $random[]  = $character;
        }

        shuffle($random);

        for ($i = 0; $i < count($random); $i++) {
            $code .= $characters[rand(0, count($characters) - 1)];
        }

        return $code;
    }
}