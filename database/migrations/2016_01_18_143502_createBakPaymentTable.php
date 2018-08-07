<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBakPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_payments', function (Blueprint $table) {
            $table->increments('id');

            $table->string('gate'); // bao_kim/manual
            $table->integer('bank_id')->nullable(); // id này tương ứng với gate
            $table->string('bank_name')->nullable();
            $table->string('bank_short_name')->nullable();
            $table->string('bank_account_name')->nullable(); // neu guide thi luu cac thong tin nay
            $table->string('bank_account_number')->nullable();

            $table->integer('price');// giá
            $table->string('payer_name')->nullable();
            $table->string('payer_email');
            $table->string('payer_phone_no')->nullable();
            $table->string('payer_address')->nullable();

            $table->integer('bank_payment_method');// direct/guide/...
            $table->string('bank_payment_link')->nullable();// direct/guide/...
            $table->string('transaction_id')->nullable();// ma giao dich, direct thi tra ve khi lay link, guide thi nhap khi co giao dich ngan hang
            $table->string('other_info')->nullable();// thong tin khac can luu khi dung guide

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
        Schema::drop('bank_payments');
    }
}
