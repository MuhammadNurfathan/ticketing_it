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
            $table->string('ticket_code', 10)->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('support_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('restrict');
            $table->foreignId('problem_category_id')->nullable()->constrained('problem_categories')->nullOnDelete();
            $table->foreignId('assets_id')->nullable()->constrained('assets')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('status')->nullOnDelete();
            $table->foreignId('priority_id')->nullable()->constrained('priority')->nullOnDelete();
            $table->text('problem')->nullable();
            $table->string('image', 255)->nullable();
            $table->text('solution')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->integer('waiting_hour')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();
            $table->integer('time_spent')->nullable();
            $table->boolean('is_late')->default(false)->comment('false = On Time, true = Late');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
