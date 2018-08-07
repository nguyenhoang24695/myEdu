<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterQuizzesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('qui_sub_title')->nullable();
            $table->tinyInteger('require')->default(0)->comment('Đánh dấu yêu cầu học viên phải làm mới được học tiếp');
            $table->timestamps();
            $table->dropColumn(['qui_lec_id', 'qui_active', 'qui_answer']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['qui_sub_title', 'require', 'qui_answer']);
            $table->integer('qui_lec_id')->default(0);
            $table->tinyInteger('qui_active')->default(0);
            $table->longText('qui_answer')->nullable();
        });
    }
}
