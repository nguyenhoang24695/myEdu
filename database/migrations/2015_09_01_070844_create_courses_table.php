<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cou_title')->nullable();
            $table->string('cou_sub_title')->nullable();
            $table->longText('cou_summary')->nullable();
            $table->integer('cou_cate_id')->default(0);
            $table->integer('cou_user_id')->default(0);
            $table->integer('cou_price')->default(0);
            $table->string('cou_cover')->nullable();
            $table->integer('cou_promo_video')->default(0);
            $table->integer('cou_demo_video')->default(0);
            $table->longText('cou_goals')->nullable();
            $table->longText('cou_requirements')->nullable();
            $table->longText('cou_audience')->nullable();
            $table->longText('cou_knowledge_goals')->nullable();
            $table->integer('cou_skill_level')->default(0);
            $table->tinyInteger('cou_active')->default(0);
            $table->tinyInteger('cou_delete')->default(0);
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
        Schema::drop('courses');
    }
}
