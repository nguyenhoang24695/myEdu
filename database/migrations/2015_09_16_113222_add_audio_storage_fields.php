<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAudioStorageFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('audios', function (Blueprint $table) {
            //
            $table->integer('is_public', false, ['default' => 0]);
            $table->string('aud_disk');
            $table->string('aud_file_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('audios', function (Blueprint $table) {
            //
            $table->dropColumn(['is_public', 'aud_disk', 'aud_file_path']);
        });
    }
}
