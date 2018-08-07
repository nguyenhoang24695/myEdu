<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cat_title')->nullable();
            $table->tinyInteger('cat_order')->default(0);
            $table->integer('cat_parent_id')->default(0);
            $table->tinyInteger('cat_has_child')->default(0);
            $table->tinyInteger('cat_active')->default(0);
            $table->tinyInteger('cat_delete')->default(0);
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
        Schema::drop('categories');
    }
}
