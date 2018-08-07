<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMediaInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            //
            $table->integer('duration');
            $table->string('thumbnail_disk');
            $table->string('thumbnail_path');
            $table->integer('file_size');
            $table->string('file_type');
        });
        Schema::table('audios', function (Blueprint $table) {
            //
            $table->integer('duration');
            $table->integer('file_size');
            $table->string('file_type');
        });
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->integer('pages');
            $table->integer('file_size');
            $table->string('file_type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            //
            $table->dropColumn(['duration', 'thumbnail_disk', 'thumbnail_path', 'file_size', 'file_type']);
        });
        Schema::table('audios', function (Blueprint $table) {
            //
            $table->dropColumn(['duration', 'file_size', 'file_type']);
        });
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn(['pages', 'file_size', 'file_type']);
        });
    }
}
