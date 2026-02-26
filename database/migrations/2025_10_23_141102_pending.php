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

            $table->foreignId('project_header_id')
                ->constrained('project_header')
                ->cascadeOnDelete();

            $table->timestamp('pending_start')->nullable();
            $table->timestamp('pending_end')->nullable();
            $table->string('reason');
            $table->integer('duration_minutes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending');
    }
};
