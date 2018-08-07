<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentPublicField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->integer('is_public', false, ['default' => 0]);
            $table->string('doc_disk');
            $table->string('doc_file_path');
            $table->string('thumbnail_disk');
            $table->string('thumbnail_path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('documents', function (Blueprint $table) {
            //
            $table->dropColumn(['is_public', 'doc_disk', 'doc_file_path', 'thumbnail_disk', 'thumbnail_path']);
        });
    }
}
