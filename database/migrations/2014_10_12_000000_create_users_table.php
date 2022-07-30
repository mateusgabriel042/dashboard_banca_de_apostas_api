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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('first_name', 50);
            $table->string('last_name', 150);
            $table->date('birth_date');
            $table->string('sex', 20)->nullable();
            $table->string('cpf', 14)->unique();

            $table->double('money')->default(0);//saldo
            $table->string('email', 150)->unique();
            $table->string('mobile_phone', 30)->unique();
            $table->string('username', 100)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 200);

            $table->string('currency', 80)->nullable();//moeda
            $table->string('language', 100)->default('PT-br');
            $table->string('language_label', 100)->default('Português');
            $table->string('address_country', 150)->nullable();
            $table->string('timezone', 100)->default('GMT-3');//fuso horario

            $table->string('address_zipcode', 9)->nullable();
            $table->string('address_street')->nullable();
            $table->string('address_number', 6)->nullable();
            $table->string('address_neighborhood')->nullable();
            $table->string('address_complement')->nullable();
            $table->integer('address_city_id')->nullable();
            $table->string('address_city')->nullable();
            $table->integer('address_state_id')->nullable();
            $table->string('address_state')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
