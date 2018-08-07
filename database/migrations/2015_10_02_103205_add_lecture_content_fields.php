<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLectureContentFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lectures', function (Blueprint $table) {
            //
            $table->string('primary_data_type');
            $table->integer('primary_data_id');
            $table->string('secondary_data_type');
            $table->integer('secondary_data_id');
            $table->string('other_data');// other attachment data
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lectures', function (Blueprint $table) {
            //
            $table->dropColumn(['primary_data_type', 'primary_data_id', 'secondary_data_type', 'secondary_data_id', 'other_data']);
        });
    }
}
