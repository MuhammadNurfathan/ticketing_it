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
          Schema::create('project_detail', function (Blueprint $table) {
        $table->id();
        $table->foreignId('project_header_id')
              ->constrained('project_header')
              ->onUpdate('cascade')
              ->onDelete('restrict');
        $table->dateTime('progress_date');
        $table->string('memo');
        $table->foreignId('status_id')
              ->constrained('status')
              ->onUpdate('cascade')
              ->onDelete('restrict');
        $table->unsignedTinyInteger('progress_percent')
              ->check('progress_percent <= 100');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        schema::dropIfExists('project_detail');
    }
};
