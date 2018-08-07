<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('school_address')->nullable()->comment('Địa chỉ trường học');
            $table->string('class_room')->nullable()->comment('Tên lớp học');
            $table->string('full_name')->nullable()->comment('Họ tên đầy đủ');
            $table->string('address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['school_address', 'class_room', 'full_name','address']);
        });
    }
}
