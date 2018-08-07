<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPaymentMethodId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bank_payments', function (Blueprint $table) {
            //
            $table->integer('bank_payment_method_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bank_payments', function (Blueprint $table) {
            //
            $table->dropColumn('bank_payment_method_id');
        });
    }
}
