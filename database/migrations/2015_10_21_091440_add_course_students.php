<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_students', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->integer('course_id');
            $table->integer('user_id');
            $table->softDeletes();// baned study
            $table->timestamps();
        });

        Schema::table('courses', function (Blueprint $table) {
            //
            $table->integer('user_count');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('course_students');

        Schema::table('course', function (Blueprint $table){
            $table->dropColumn('user_count');
        });
    }
}
