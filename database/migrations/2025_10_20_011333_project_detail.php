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
                  $table->foreignId('project_header_id')->constrained('project_header')->onUpdate('cascade')->onDelete('cascade');
                  $table->foreignId('dev_id')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
                  $table->datetime('progress_date')->nullable();
                  $table->foreignId('status_id')->constrained('status')->onUpdate('cascade')->onDelete('restrict');
                  $table->float('progress_percent')->default(0);
                  $table->string('memo', 255)->nullable();
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
