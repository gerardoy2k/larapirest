<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            //$table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name');
            $table->string('lastname');
            $table->date('birthdate');
            $table->string('gender');
            $table->unsignedInteger('country_id')->nullable();
            $table->string('state')->nullable();
            $table->unsignedInteger('city_id')->unsigned()->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('user_id')->unique();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('country_id')->references('id')->on('countries');
            $table->foreign('city_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}
