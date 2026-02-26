<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
      public function up(): void
      {
            Schema::create('project_details', function (Blueprint $table) {
                  $table->id();

                  $table->foreignId('project_header_id')
                        ->constrained('project_header')
                        ->cascadeOnUpdate()
                        ->cascadeOnDelete();

                  $table->foreignId('developer_id')
                        ->constrained('users') // ✅ FIX TYPO
                        ->cascadeOnUpdate()
                        ->cascadeOnDelete();

                  $table->dateTime('progress_date')->nullable();

                  $table->foreignId('status_id')
                        ->constrained('statuses')
                        ->cascadeOnUpdate()
                        ->restrictOnDelete();

                  $table->unsignedTinyInteger('progress_percent')->default(0); // ✅ konsisten
                  $table->text('description')->nullable();

                  $table->timestamps();
            });
      }

      public function down(): void
      {
            schema::dropIfExists('project_details');
      }
};
