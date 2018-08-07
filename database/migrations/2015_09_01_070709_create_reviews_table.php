<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReviewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rev_title')->nullable();
            $table->longText('rev_content')->nullable();
            $table->integer('rev_cou_id')->default(0);
            $table->integer('rev_user_id')->default(0);
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
        Schema::drop('reviews');
    }
}
