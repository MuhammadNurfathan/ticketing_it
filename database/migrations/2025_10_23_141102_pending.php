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
            $table->unsignedBigInteger('project_header_id');
            $table->timestamp('pending_start')->nullable();
            $table->timestamp('pending_end')->nullable();
            $table->string('reason');
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
            $table->foreign('id_project_header')->references('id')->on('project_header')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending');
    }
};
