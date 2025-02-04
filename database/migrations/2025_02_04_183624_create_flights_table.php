<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->dateTime('departure_date')->format('d/m/Y H:i') ;
            $table->dateTime('arrival_date')->format('d/m/Y H:i') ;
            $table->string('origin');
            $table->string('destination');
            $table->string('airplane_id'); 
            $table->boolean('available')->default(true);
            $table->timestamps();
            /* $table->foreign('airplane_id')->references('id')->on('airplanes')->onDelete('null'); */
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
