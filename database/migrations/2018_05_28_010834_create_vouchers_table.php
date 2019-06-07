<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVouchersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('account_id');
            $table->string('card_number');
            $table->string('status')->default('unused');
            $table->date('date_issued');
            $table->date('valid_from');
            $table->date('valid_to');
            $table->string('remarks');
            $table->integer('destination_id');
            $table->date('date_redeemed')->nullable();
            $table->dateTime('check_in')->nullable();
            $table->dateTime('check_out')->nullable();
            $table->string('guest_first_name')->nullable();
            $table->string('guest_middle_name')->nullable();
            $table->string('guest_last_name')->nullable();
            $table->string('created_by');
            $table->string('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('vouchers');
    }
}
