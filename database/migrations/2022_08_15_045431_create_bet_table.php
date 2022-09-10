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
        Schema::create('bet', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('bet_purchase_id');
            $table->foreign('bet_purchase_id')->references('id')->on('bets_purchase')->onDelete('cascade');
            $table->string('bet', 70);
            $table->string('result_final', 70)->nullable();
            $table->string('type_bet', 70);
            $table->string('id_matche', 25);
            $table->string('bet_id', 150)->nullable();
            $table->boolean('win')->default(0);
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
        Schema::dropIfExists('bet');
    }
};
