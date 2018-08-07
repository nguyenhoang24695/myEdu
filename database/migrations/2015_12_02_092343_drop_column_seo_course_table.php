<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropColumnSeoCourseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['custom_title','keywords','cou_promo_video','cou_demo_video']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('custom_title');
            $table->text('keywords');
            $table->integer('cou_promo_video')->default(0);
            $table->integer('cou_demo_video')->default(0);
        });
    }
}
