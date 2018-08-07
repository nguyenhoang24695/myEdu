<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAutoSaveFieldForMedia extends Migration
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
            $table->boolean('is_auto_save')->default(false);
        });
        Schema::table('audios', function (Blueprint $table) {
            //
            $table->boolean('is_auto_save')->default(false);
        });
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->boolean('is_auto_save')->default(false);
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
            $table->dropColumn('is_auto_save');
        });
        Schema::table('audios', function (Blueprint $table) {
            //
            $table->dropColumn('is_auto_save');
        });
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn('is_auto_save');
        });
    }
}
