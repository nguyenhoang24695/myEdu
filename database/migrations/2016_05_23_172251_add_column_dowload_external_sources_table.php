<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnDowloadExternalSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_sources', function (Blueprint $table) {
            $table->tinyInteger('flag_dl');
            $table->tinyInteger('dl_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_sources', function (Blueprint $table) {
            $table->dropColumn(['flag_dl','dl_status']);
        });
    }
}
