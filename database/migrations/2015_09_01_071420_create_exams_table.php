<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('exa_title')->nullable();
            $table->string('exa_code')->nullable();
            $table->longText('exa_knowledge_is_checked')->nullable();
            $table->integer('exa_question_id')->default(0);
            $table->tinyInteger('exa_set_time')->default(0);
            $table->tinyInteger('exa_active')->default(0);
            $table->tinyInteger('exa_delete')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('exams');
    }
}
