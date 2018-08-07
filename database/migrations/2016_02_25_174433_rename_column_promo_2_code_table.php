<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameColumnPromo2CodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->renameColumn('discount_partner', 'discount_1');
            $table->renameColumn('discount_buyer', 'discount_2');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->renameColumn('discount_1', 'discount_partner');
            $table->renameColumn('discount_2', 'discount_buyer');
        });
    }
}
