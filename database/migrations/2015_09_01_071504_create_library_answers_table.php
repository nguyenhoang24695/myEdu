<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLibraryAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('library_answers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('lia_title')->nullable();
            $table->integer('lia_liq_active')->default(0);
            $table->tinyInteger('lia_chose')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('library_answers');
    }
}
