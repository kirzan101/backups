<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('invoice_number')->default('0000000');
            $table->integer('account_id');
            $table->decimal('principal_amount', 10, 2);
            $table->decimal('downpayment', 10, 2)->default(0);
            $table->decimal('total_paid_amount', 10, 2)->default(0);
            $table->decimal('remaining_balance', 10, 2)->nullable();
            $table->string('status')->default('draft');
            $table->string('created_by');
            $table->softDeletes();
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
        Schema::dropIfExists('invoices');
    }
}
