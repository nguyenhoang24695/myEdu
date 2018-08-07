<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEditPrivacyStatusCourseContent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_contents', function (Blueprint $table) {
            //
            $table->integer('edit_status')->default(0);
            $table->string('access_privacy')->default('student');
        });
        Schema::table('lectures', function (Blueprint $table) {
            if(in_array('lec_sec_id',$table->getColumns())){
                $table->dropColumn(['lec_sec_id',
                    'lec_type',
                    'lec_content_id',
                    'lec_active',
                    'item_order',
                    'lecture_data']);
            }

        });
        Schema::table('sections', function (Blueprint $table) {
            if(in_array('sec_cou_id',$table->getColumns())){
                $table->dropColumn(['sec_cou_id',
                    'sec_active',
                    'item_order']);
            }

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
            $table->dropColumn(['edit_status', 'access_privacy']);
        });
    }
}
