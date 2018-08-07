<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddInfoUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable();
            $table->string('school_name')->nullable();
            $table->integer('grade')->default(0);
            $table->tinyInteger('is_student')->default(0);
            $table->tinyInteger('is_teacher')->default(0);
            $table->tinyInteger('gender')->default(0);
            $table->timestamp('birthday');
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
            $table->dropColumn(['avatar', 'school_name', 'grade','is_student','is_teacher','gender','birthday']);
        });
    }
}
