<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('modelo_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->integer('tokens_count')->unsigned();
            $table->timestamps();

            $table->foreign('modelo_id')->references('id')->on('modelos');
            $table->foreign('service_id')->references('id')->on('services');
        });
        Schema::table('rates', function (Blueprint $table) {
            $table->integer('modelo_id')->unsigned()->change();
            $table->foreign('modelo_id')->references('id')->on('modelos')->change();
            $table->integer('service_id')->unsigned()->change();
            $table->foreign('service_id')->references('id')->on('services')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
