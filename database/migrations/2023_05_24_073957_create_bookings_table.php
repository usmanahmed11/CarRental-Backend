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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone');
            $table->string('email');
            $table->dateTime('departure_date');
            $table->dateTime('return_date');
            $table->string('car');
            $table->decimal('total_bill', 10, 2);
            $table->timestamps();
            $table->string('payment_status')->default('pending');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bookings');
    }
};
