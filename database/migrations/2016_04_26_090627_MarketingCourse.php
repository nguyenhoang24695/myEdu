<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MarketingCourse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('marketing_courses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('course_id');
            $table->string('course_url');
            $table->string('title');
            $table->string('description');
            $table->string('image');
            $table->string('image_disk');
            $table->text('exact_keyword');
            $table->text('similar_keyword');
            $table->integer('show_count');
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
        Schema::drop('marketing_courses');
    }
}
