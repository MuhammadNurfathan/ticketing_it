<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained()->cascadeOnDelete();

            $table->tinyInteger('speed_rating')->nullable();     // kecepatan IT
            $table->tinyInteger('waiting_rating')->nullable();   // waktu tunggu
            $table->tinyInteger('solution_rating')->nullable();  // kualitas solusi

            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        schema::dropIfExists('feedback');
    }
};
