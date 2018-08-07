<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHdSdPathForVideo extends Migration
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
            $table->string('base_sub_path');
            $table->string('video_hd_path');
            $table->string('video_sd_path');
            $table->string('sub_languages')->default('vi');

            //convert_status 0: chưa convert, 1: convert ok, -1: cần convert lại, -2, -3,...: lỗi convert ...
            $table->integer('convert_status')->default(0);
        });
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->string('base_sub_path');
            $table->string('intro_video_hd_path');
            $table->string('intro_video_sd_path');
            $table->string('sub_languages')->default('vi');

            //convert_status
            // 0: chưa convert,
            // 1: convert ok,
            // -1: đang convert
            // -2, -3,...: cần convert lại,
            // 2,3,4,...: lỗi convert ...
            $table->integer('convert_status')->default(0);
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
            $table->dropColumn([
                'base_sub_path',
                'video_hd_path',
                'video_sd_path',
                'sub_languages',
                'convert_status',
            ]);
        });
        Schema::table('courses', function (Blueprint $table) {
            //
            $table->dropColumn([
                'base_sub_path',
                'intro_video_hd_path',
                'intro_video_sd_path',
                'sub_languages',
                'convert_status',
            ]);
        });
    }
}
