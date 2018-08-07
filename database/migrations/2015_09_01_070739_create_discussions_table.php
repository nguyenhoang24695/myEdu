<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discussions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('dis_title')->nullable();
            $table->longText('dis_content')->nullable();
            $table->integer('dis_cou_id')->default(0);
            $table->integer('dis_user_id')->default(0);
            $table->integer('dis_parent_id')->default(0);
            $table->tinyInteger('rev_active')->default(0);
            $table->tinyInteger('rev_delete')->default(0);
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
        Schema::drop('discussions');
    }
}
