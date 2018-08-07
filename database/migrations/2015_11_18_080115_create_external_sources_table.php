<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExternalSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_sources', function (Blueprint $table) {
            //
            $table->increments('id');
            $table->integer('course_content_id');
            $table->integer('user_id');
            $table->string('title');
            $table->string('source_type');
            $table->string('content');
            $table->string('data_disk');
            $table->string('data_path');
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
        Schema::drop('external_sources');
    }
}
