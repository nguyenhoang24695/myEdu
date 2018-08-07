<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartnerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('partner', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->string('marketing_mouth')->comment('Truyền miệng');
            $table->string('marketing_website')->comment('Website');
            $table->string('marketing_social')->comment('Mạng xã hội');
            $table->string('marketing_ads')->comment('Phương tiện quảng cáo');
            $table->string('marketing_other')->comment('Khác');
            $table->string('views_website')->comment('Lượt truy cập website');
            $table->string('access')->comment('Biết đến qua kênh nào');
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
        Schema::drop('partner');
    }
}
