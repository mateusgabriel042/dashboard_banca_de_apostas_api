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
        Schema::create('results_soccer', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('score', 7)->nullable();
            $table->integer('time_status')->nullable();
            $table->integer('home_goals_first_time')->nullable();
            $table->integer('home_goals_second_time')->nullable();
            $table->integer('away_goals_first_time')->nullable();
            $table->integer('away_goals_second_time')->nullable();
            $table->integer('home_attacks')->nullable();
            $table->integer('away_attacks')->nullable();
            $table->integer('home_ball_safe')->nullable();
            $table->integer('away_ball_safe')->nullable();
            $table->integer('home_corners')->nullable();
            $table->integer('away_corners')->nullable();
            $table->integer('home_corner_h')->nullable();
            $table->integer('away_corner_h')->nullable();
            $table->integer('home_dangerous_attacks')->nullable();
            $table->integer('away_dangerous_attacks')->nullable();
            $table->integer('home_fouls')->nullable();
            $table->integer('away_fouls')->nullable();
            $table->integer('home_freekicks')->nullable();
            $table->integer('away_freekicks')->nullable();
            $table->integer('home_goalattempts')->nullable();
            $table->integer('away_goalattempts')->nullable();
            $table->integer('home_goalkicks')->nullable();
            $table->integer('away_goalkicks')->nullable();
            $table->integer('home_goals')->nullable();
            $table->integer('away_goals')->nullable();
            $table->integer('home_injuries')->nullable();
            $table->integer('away_injuries')->nullable();
            $table->integer('home_offsides')->nullable();
            $table->integer('away_offsides')->nullable();
            $table->integer('home_off_target')->nullable();
            $table->integer('away_off_target')->nullable();
            $table->integer('home_on_target')->nullable();
            $table->integer('away_on_target')->nullable();
            $table->integer('home_penalties')->nullable();
            $table->integer('away_penalties')->nullable();
            $table->integer('home_possession_rt')->nullable();
            $table->integer('away_possession_rt')->nullable();
            $table->integer('home_redcards')->nullable();
            $table->integer('away_redcards')->nullable();
            $table->integer('home_saves')->nullable();
            $table->integer('away_saves')->nullable();
            $table->integer('home_shots_blocked')->nullable();
            $table->integer('away_shots_blocked')->nullable();
            $table->integer('home_substitutions')->nullable();
            $table->integer('away_substitutions')->nullable();
            $table->integer('home_throwins')->nullable();
            $table->integer('away_throwins')->nullable();
            $table->integer('home_yellowcards')->nullable();
            $table->integer('away_yellowcards')->nullable();
            $table->integer('home_yellowred_cards')->nullable();
            $table->integer('away_yellowred_cards')->nullable();

            $table->uuid('matche_id');
            $table->foreign('matche_id')->references('id')->on('matches')->onDelete('cascade');
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
        Schema::dropIfExists('results_soccer');
    }
};
