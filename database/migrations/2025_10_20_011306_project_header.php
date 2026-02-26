<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_header', function (Blueprint $table) {
            $table->id();
            $table->string('project_code', 20)->nullable();
            $table->string('project_name', 255)->nullable();
            $table->dateTime('request_date')->nullable();

            $table->foreignId('requestor_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('status_id')
                ->constrained('statuses')
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->foreignId('priority_id')
                ->constrained('priorities') // ✅ FIX
                ->cascadeOnUpdate()
                ->restrictOnDelete();

            $table->unsignedTinyInteger('progress_percent')->default(0);
            $table->dateTime('progress_date')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();

            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->dateTime('actual_start_date')->nullable();
            $table->dateTime('actual_end_date')->nullable();

            $table->integer('total_pending_minutes')->default(0);
            $table->dateTime('effective_end_date')->nullable();
            $table->boolean('is_late')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        schema::dropIfExists('project_header');
    }
};
