<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnTrackingLinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracking_links', function (Blueprint $table) {
            $table->dropColumn(['code','discount_max','orders_success']);
            $table->integer('used_count')->comment('Số người truy cập link')->change();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracking_links', function (Blueprint $table) {
            $table->string('code')->comment('Mã code của partner');
            $table->integer('discount_max')->comment('% chiết khấu max partner được nhận');
            $table->integer('used_count')->comment('Số người truy cập link, tính cả f5');
            $table->integer('orders_success')->comment('Đơn hàng thành công');
        });
    }
}
