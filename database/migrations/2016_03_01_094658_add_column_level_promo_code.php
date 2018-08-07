<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnLevelPromoCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->tinyInteger('partner_level')->default(0)->after('total_money');
            $table->integer('discount_1')->comment('Chiết khấu khóa học người khác')->change();
            $table->integer('discount_2')->comment('Chiết khấu khóa học của chính partner')->change();
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
            $table->dropColumn('partner_level');
        });
    }
}
