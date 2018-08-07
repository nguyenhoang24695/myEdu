<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnSocialIdUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique();
            $table->string('github_id')->nullable()->unique();
            $table->string('twitter_id')->nullable()->unique();
            $table->string('linkedin_id')->nullable()->unique();
            $table->string('bitbucket_id')->nullable()->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user', function (Blueprint $table) {
            $table->dropColumn(['google_id','github_id','twitter_id','linkedin_id','bitbucket_id']);
        });
    }
}
