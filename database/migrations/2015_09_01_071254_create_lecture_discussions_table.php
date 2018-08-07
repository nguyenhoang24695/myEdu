<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectureDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture_discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('led_title')->nullable();
            $table->longText('led_content')->nullable();
            $table->integer('led_user_id')->default(0);
            $table->integer('led_lec_id')->default(0);
            $table->integer('led_parent_id')->default(0);
            $table->tinyInteger('led_active')->default(0);
            $table->tinyInteger('led_delete')->default(0);
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
        Schema::drop('lecture_discussions');
    }
}
