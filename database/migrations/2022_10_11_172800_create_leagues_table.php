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
        Schema::create('leagues', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('sport_name', 150);
            $table->string('sport_label', 200)->nullable();
            $table->biginteger('apievents_sport_id');
            $table->biginteger('apievents_league_id');
            $table->string('name', 150);
            $table->string('label_name', 150);
            $table->string('type', 50);
            $table->string('label_type', 50);
            $table->string('logo', 250)->nullable();
            $table->boolean('is_active')->default(0);
            $table->uuid('sport_id');
            $table->foreign('sport_id')->references('id')->on('sports')->onDelete('cascade');
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
        Schema::dropIfExists('sports_leagues');
    }
};
