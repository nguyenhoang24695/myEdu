<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddVideoOptions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('videos', function (Blueprint $table) {
            //
            $table->string('player')->default('default');
            $table->integer('convert_success_count')->default(0);
            $table->integer('convert_fail_count')->default(0);
            $table->boolean('sub_enabled')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('videos', function (Blueprint $table) {
            //
            $table->dropColumn([
                'player',
                'convert_success_count',
                'convert_fail_count',
                'sub_enabled'
            ]);
        });
    }
}
