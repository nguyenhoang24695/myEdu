<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseRate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->float('rating');
            $table->integer('review_count')->default(0);
            $table->integer('review_count_1')->default(0);
            $table->integer('review_count_2')->default(0);
            $table->integer('review_count_3')->default(0);
            $table->integer('review_count_4')->default(0);
            $table->integer('review_count_5')->default(0);
        });
        \App\Models\Course::all()->each(function(\App\Models\Course $course){
            $course->updateReview();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn([
                'review_count',
                'review_count_1',
                'review_count_2',
                'review_count_3',
                'review_count_4',
                'review_count_5',
                'rating']);
        });
    }
}
