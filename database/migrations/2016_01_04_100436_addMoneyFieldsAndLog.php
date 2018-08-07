<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoneyFieldsAndLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        //
        Schema::create('orders', function(Blueprint $table){
            $table->increments('id');
            // them thong tin gui nhan voi cong thanh toan
            $table->string('payment_method'); // Loại hình thức thanh toán <=> cong thanh toan
            $table->integer('payment_transaction_id'); // id của giao dịch thông qua phương thức thanh toán tuong ung
            $table->integer('type');// buy, exchange, recharge,
            $table->integer('seller');// người bán ko nhất thiết là người tạo khóa học
            $table->integer('buyer');// người thực hiện mua
            $table->integer('created_by');
            $table->integer('item_type');// loại hàng hóa, hiện tại là khóa học, thẻ cào, Thẻ ngân hàng, Tk bảo kim khác
            $table->integer('item_id');
            $table->integer('item_price');// giá tại thời điểm mua của hàng hóa
            $table->string('promote_code');// mã khuyến mãi
            $table->integer('status');// default 0: pending, 1: approved, 2: reverted, -1: rejected, ...
            $table->integer('approved_by'); //0: by system
            $table->integer('reverted_by'); //0: by system
            $table->timestamp('reverted_at')->default(null); // thời điểm thu hồi lại giao dịch
            $table->timestamps();
        });
        Schema::create('transactions', function(Blueprint $table){
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('type');// cache loại đơn hàng
            $table->integer('order_status');// status của đơn hàng sau khi thực hiện giao dịch tiền(approved/reverted) -> approving/reverting
            $table->integer('from_acc');
            $table->integer('to_acc');// 0: system , trường hợp giảm quyền mua khóa học của người dùng
            $table->integer('amount');// số tiền thực tế chuyển giao, có thể ko phải là giá của đơn hàng, âm hoặc dương
            $table->integer('created_by');// người approve order
            $table->integer('from_acc_remain');// số tiền tk gửi sau khi thực hiện giao dịch
            $table->integer('to_acc_remain');// số tiền tk nhận sau khi thực hiện giao dịch
            $table->string('acc_type'); // Loại tài khoản thực hiện giao dịch : primary|secondary
            $table->timestamps();
        });

        Schema::create('transaction_notifications', function(Blueprint $table){
            $table->increments('id');
            $table->integer('transaction_id');
            $table->integer('receiver');
            $table->string('preview');// cached preview of notification
            $table->timestamps();
        });

        Schema::table("users", function(Blueprint $table){
            $table->integer('primary_wallet');// tk chinh, rut, chuyen khoan, chuyen sang secondary accout
            $table->integer('secondary_wallet');// tk phu, chi dung de mua khoa hoc, khi cho tang cung chi co gia tri mua ban khoa hoc
            $table->string("wallet_type");//admin, user, seller, revenue
            $table->string("wallet_payment");//Bảo Kim, ...
        });

        Schema::create('mobile_cards', function(Blueprint $table){
            $table->increments('id');
            $table->string('transaction_id');// mã giao dịch với cổng thanh toán
            $table->string('pin');// mã thẻ
            $table->string('serial');// serial thẻ
            $table->string('provider');// nhà mạng
            $table->integer('price');// mệnh giá thẻ
            $table->integer('user_id');// mệnh giá thẻ
            $table->string('gate');// cong thanh toan khi xu ly the
            $table->integer('discount');// Triet khau cho cong thanh toan, vi du 23 la 23%
            $table->integer('status');// 0: mới nhập, 1: nạp thành công, -1: Lỗi
            $table->integer('real_price');// số tiền thực tế nhận được khi nạp
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop("orders");
        Schema::drop("transactions");
        Schema::drop("transaction_notifications");
        Schema::drop("mobile_cards");
        Schema::table("users", function(Blueprint $table){
            $table->dropColumn(["primary_wallet", "secondary_wallet", "wallet_type", "wallet_payment"]);
        });
    }
}
