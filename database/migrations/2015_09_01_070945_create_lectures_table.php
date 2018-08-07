<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLecturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lectures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lec_title')->nullable();
            $table->string('lec_sub_title')->nullable();
            $table->integer('lec_sec_id')->default(0);
            $table->integer('lec_content_id')->default(0);
            $table->string('lec_type');
            $table->tinyInteger('lec_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lectures');
    }
}
