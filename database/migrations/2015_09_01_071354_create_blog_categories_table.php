<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlogCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('blc_title')->nullable();
            $table->tinyInteger('blc_order')->default(0);
            $table->integer('blc_parent_id')->default(0);
            $table->tinyInteger('blc_has_child')->default(0);
            $table->tinyInteger('blc_active')->default(0);
            $table->tinyInteger('blc_delete')->default(0);
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
        Schema::drop('blog_categories');
    }
}
