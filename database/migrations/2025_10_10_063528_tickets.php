<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_code', 20)->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('support_id')
                ->nullable()
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('category_id')
                ->nullable()
                ->constrained('categories')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('asset_id')
                ->nullable()
                ->constrained('assets')
                ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->foreignId('status_id')
                ->nullable()
                ->constrained('statuses')
                ->restrictOnDelete()
                ->cascadeOnUpdate()
                ->default(1);

            $table->foreignId('priority_id')
                ->nullable()
                ->constrained('priorities')
               ->restrictOnDelete()
                ->cascadeOnUpdate();

            $table->text('problem')->nullable();
            $table->string('image', 255)->nullable();
            $table->text('solution')->nullable();
            $table->text('notes')->nullable();

            $table->dateTime('request_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->integer('waiting_minutes')->nullable();
            $table->integer('time_spent_minutes')->nullable();

            $table->boolean('is_late')->default(false)
                ->comment('false = On Time, true = Late');

            $table->timestamps();

            $table->index(['status_id', 'priority_id']);
            $table->index('user_id');
            $table->index('support_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
