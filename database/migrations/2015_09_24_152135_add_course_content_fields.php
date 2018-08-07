<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourseContentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->integer('item_order');
            $table->timestamps();
        });
        Schema::table('lectures', function (Blueprint $table) {
            //
            $table->integer('item_order');
            $table->string('lecture_data');
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
        Schema::table('sections', function (Blueprint $table) {
            //
            $table->dropColumn(['item_order']);
        });
        Schema::table('lectures', function (Blueprint $table) {
            //
            $table->dropColumn(['item_order', 'lecture_data']);
        });
    }
}
