<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTrackingLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tracking_links', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->comment('Mã code của partner');
            $table->integer('user_id')->comment('user tạo link');
            $table->integer('course_id');
            $table->integer('discount')->comment('Chiết khấu cho bạn bè');
            $table->integer('discount_max')->comment('% chiết khấu max partner được nhận');
            $table->integer('used_count')->comment('Số người truy cập link, tính cả f5');
            $table->integer('orders_success')->comment('Đơn hàng thành công');
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
        Schema::drop('tracking_links');
    }
}
