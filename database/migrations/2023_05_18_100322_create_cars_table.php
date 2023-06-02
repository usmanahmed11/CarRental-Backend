<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('carTitle');
            $table->string('subTitle');
            $table->decimal('price', 8, 2);
            $table->integer('doors');
            $table->integer('passengers');
            $table->string('luggage');
            $table->string('transmission');
            $table->string('airConditioning');
            $table->string('picture')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('cars');
    }
}