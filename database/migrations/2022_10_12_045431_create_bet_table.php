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
            $table->enum('type_event', ['prematche', 'live']);
            $table->string('type_bet', 70);
            $table->string('subtype_bet', 70);
            $table->string('customer_bet', 220);
            $table->string('bet_result_finish', 220)->nullable();
            $table->biginteger('apievents_sport_id');
            $table->biginteger('apievents_league_id');
            $table->biginteger('bet365_matche_id');
            $table->biginteger('odd_id');
            $table->double('odd');
            $table->boolean('is_active')->default(1);
            $table->boolean('win')->nullable();
            $table->uuid('bet_purchase_id');
            $table->foreign('bet_purchase_id')->references('id')->on('bets_purchase')->onDelete('cascade');
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
