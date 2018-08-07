<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterDiscussionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->renameColumn('dis_title', 'title');
            $table->renameColumn('dis_content', 'content');
            $table->renameColumn('dis_cou_id', 'cou_id');
            $table->renameColumn('dis_user_id', 'user_id');
            $table->renameColumn('dis_parent_id', 'parent_id');
            $table->renameColumn('rev_active', 'active');
            $table->integer('lec_id')->default(0);
            $table->dropColumn(['rev_delete']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('discussions', function (Blueprint $table) {
            $table->renameColumn('title', 'dis_title');
            $table->renameColumn('content', 'dis_content');
            $table->renameColumn('cou_id', 'dis_cou_id');
            $table->renameColumn('user_id', 'dis_user_id');
            $table->renameColumn('parent_id', 'dis_parent_id');
            $table->renameColumn('active', 'rev_active');
            $table->dropColumn(['lec_id']);
            $table->tinyInteger('rev_delete')->default(0);
        });
    }
}
