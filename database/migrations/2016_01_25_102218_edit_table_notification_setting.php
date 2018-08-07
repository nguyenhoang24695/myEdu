<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditTableNotificationSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification_setting', function (Blueprint $table) {
            $table->renameColumn('notifi_type_id', 'notify_type');
        });
        Schema::table('notification_setting', function (Blueprint $table) {
            $table->text('notify_type')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification_setting', function (Blueprint $table) {
            $table->renameColumn('notify_type', 'notifi_type_id');
        });
        Schema::table('notification_setting', function (Blueprint $table) {
            $table->integer('notify_type')->change();
        });
    }
}
