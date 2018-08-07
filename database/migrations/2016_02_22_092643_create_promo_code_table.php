<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_code', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',6)->nullable()->unique()->comment('Mã code phải duy nhất, chứa (4-6) ký tự');
            $table->integer('user_id');
            $table->integer('discount_1')->default(0);
            $table->integer('discount_2')->default(0);
            $table->integer('discount_max')->comment('Giới hạn % chiết khấu phụ thuộc vào total_money');
            $table->integer('used_count')->comment('Tổng số người sử dụng mã code');
            $table->bigInteger('total_money')->comment('Tổng số tiền sử dụng mã code');
            $table->tinyInteger('total_edit_code')->comment('Tổng số lần sửa mã code');
            $table->tinyInteger('active');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('promo_code');
    }
}
