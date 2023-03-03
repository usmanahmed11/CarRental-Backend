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
        Schema::create('candidate_info', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('growth_id');
            $table->string('name');
            $table->integer('experience');
            $table->text('skillSet');
            $table->string('jobTitle');
            $table->string('team');
            $table->string('location');
            $table->date('joiningDate');
            $table->string('status');
            $table->timestamps();
            $table->foreign('growth_id')->references('id')->on('growth')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('candidate_info');
    }
};
