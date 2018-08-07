<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('notifi_type_id');
            $table->tinyInteger('enable_email');
            $table->tinyInteger('enable_profile');
            $table->timestamps();
        });

        Schema::create('notification_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->text('template');
            $table->text('description');
            $table->string('icon');
            $table->timestamps();
        });

        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('notifi_type_id');
            $table->integer('recipient_id');
            $table->integer('sender_id');
            $table->integer('body');
            $table->string('url');
            $table->tinyInteger('read');
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
        Schema::drop(['notification','notification_type','notification_setting']);
    }
}
