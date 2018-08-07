<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnnouncementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ann_title')->nullable();
            $table->longText('ann_content')->nullable();
            $table->integer('ann_cou_id')->default(0);
            $table->integer('ann_user_id')->default(0);
            $table->integer('ann_parent_id')->default(0);
            $table->tinyInteger('ann_active')->default(0);
            $table->tinyInteger('ann_delete')->default(0);
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
        Schema::drop('announcements');
    }
}
