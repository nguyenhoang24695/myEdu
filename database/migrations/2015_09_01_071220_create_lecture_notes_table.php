<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLectureNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lecture_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('len_title')->nullable();
            $table->integer('len_lec_id')->default(0);
            $table->integer('len_user_id')->default(0);
            $table->tinyInteger('len_time_video')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lecture_notes');
    }
}
