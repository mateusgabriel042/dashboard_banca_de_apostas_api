<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->double('transaction_amount');
            $table->string('type_payment')->nullable();
            $table->string('colletion_id', 20)->nullable();
            $table->string('payer_email', 160)->nullable();
            $table->string('currency_id', 10)->nullable();
            $table->string('identification_type', 40)->nullable();
            $table->string('identification_number', 20)->nullable();
            $table->string('external_reference', 100)->nullable();
            $table->mediumText('qr_code')->nullable();
            $table->mediumText('qr_code_base64')->nullable();
            $table->string('status', 50);
            $table->string('transaction_id', 70)->nullable();
            $table->string('bank_transfer_id', 70)->nullable();
            $table->uuid('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamp('date_deposit')->useCurrent();
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
        Schema::dropIfExists('deposits');
    }
};
