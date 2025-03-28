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
            $table->foreignId('plane_id')->constrained('planes')->cascadeOnDelete();
            $table->dateTime('departure_date')->format('d/m/Y H:i') ;
            $table->dateTime('arrival_date')->format('d/m/Y H:i') ;
            $table->string('origin');
            $table->string('destination');
            $table->boolean('available')->default(true);
            $table->timestamps();
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
