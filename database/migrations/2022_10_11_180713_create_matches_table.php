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
        Schema::create('matches', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->biginteger('bet365_matche_id');
            $table->biginteger('apievents_sport_id');
            $table->biginteger('apievents_league_id');
            $table->biginteger('team_home_id');
            $table->biginteger('team_away_id');
            $table->string('team_home_name', 250);
            $table->string('team_away_name', 250);
            $table->biginteger('team_home_image_id');
            $table->biginteger('team_away_image_id');
            $table->timestamp('date_matche')->nullable();
            $table->longText('object_odds_prematche')->nullable();
            $table->string('sport_name', 150);
            $table->string('sport_label', 200)->nullable();
            $table->uuid('league_id');
            $table->foreign('league_id')->references('id')->on('leagues')->onDelete('cascade');
            $table->uuid('country_id');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matches_league');
    }
};
