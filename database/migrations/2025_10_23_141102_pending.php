<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending', function (Blueprint $table) {
            $table->id();

            // Foreign key ke project_header
            $table->unsignedBigInteger('id_project_header');
            
            // Waktu mulai dan selesai pending
            $table->timestamp('pending_start')->nullable();
            $table->timestamp('pending_end')->nullable();

            // Alasan pending
            $table->string('reason');

            // Durasi pending (dalam menit)
            $table->integer('duration_minutes')->nullable();

            // Timestamps default Laravel
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_project_header')
                  ->references('id')->on('project_header')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending');
    }
};
