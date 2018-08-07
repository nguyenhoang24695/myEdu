<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->increments('id');
            $table->string('blo_title')->nullable();
            $table->string('blo_summary')->nullable();
            $table->integer('blo_user_id')->default(0);
            $table->integer('blo_views')->default(0);
            $table->integer('blo_blc_id')->default(0);
            $table->tinyInteger('blo_active')->default(0);
            $table->tinyInteger('blo_delete')->default(0);
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
        Schema::drop('blogs');
    }
}
