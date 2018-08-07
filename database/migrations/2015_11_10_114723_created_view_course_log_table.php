<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatedViewCourseLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_content_view_logs', function (Blueprint $table) {
            //
            $table->dropColumn('viewed');



            $table->string('token');
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_content_view_logs', function(Blueprint $table){
            $table->boolean('viewed');
            $table->dropColumn(['token', 'status']);
        });
    }
}
