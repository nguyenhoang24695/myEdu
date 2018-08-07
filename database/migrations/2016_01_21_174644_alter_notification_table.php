<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->renameColumn('notifi_type_id', 'type');
            $table->renameColumn('sender_id', 'user_id');
            $table->renameColumn('recipient_id', 'object_id');
            

            $table->string('subject');
            $table->string('object_type', 128);
            $table->dateTime('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notification', function (Blueprint $table) {
            $table->renameColumn('type', 'notifi_type_id');
            $table->renameColumn('user_id', 'sender_id');
            $table->renameColumn('object_id', 'recipient_id');
            

            $table->dropColumn(['subject','sent_at','object_type']);
        });
    }
}
