<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseContentModelField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_contents', function (Blueprint $table) {
            //
            $table->integer('course_id');
            $table->string('content_type');
            $table->integer('content_id');
            $table->timestamps();
        });
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->string('course_content_id');
        });
        Schema::table('lectures', function (Blueprint $table) {
            //
            $table->string('course_content_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_contents', function (Blueprint $table) {
            //
            $table->drop();
        });
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->dropColumn('course_content_id');
        });
        Schema::table('lectures', function (Blueprint $table) {
            //
            $table->dropColumn('course_content_id');
        });
    }
}
